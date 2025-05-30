<?php

namespace App\Command;

use App\BankAccount\EmailNotificationDispatcher;
use App\BankAccount\ParsedNotificationDto;
use App\Entity\BankAccount;
use App\Entity\BankAccountBalance;
use App\Entity\Company;
use App\Entity\Document;
use App\Entity\Payment;
use App\Enum\NotificationType;
use App\Enum\PaymentType;
use App\Repository\BankAccountRepository;
use App\Repository\CompanyRepository;
use App\Repository\DocumentRepository;
use App\Repository\PaymentRepository;
use App\Service\InboxEmailParser;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use eXorus\PhpMimeMailParser\Parser;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Mime\Address;

#[AsCommand(
    name: 'app:process-email-payment-notification',
    description: 'Process payment or bank account balance from email ',
)]
class ProcessEmailPaymentNotificationCommand extends Command
{
    private const array TRUSTED_EMAILS = ['automat@fio.cz', 'info@airbank.cz'];//

    public function __construct(
        private readonly InboxEmailParser $emailAddressParser,
        private readonly LoggerInterface $logger,
        private readonly EmailNotificationDispatcher $emailParserDispatcher,
        private readonly BankAccountRepository $accountRepository,
        private readonly CompanyRepository $companyRepository,
        private readonly DocumentRepository $documentRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly PaymentRepository $paymentRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('source', InputArgument::REQUIRED, 'Path to e-mail file (or "stdin")');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        try {
            $rawEmail = $this->loadEmail($input);
            list($from, $to, $subject, $body) = $this->parseEmail($rawEmail);
            $identifier = $this->emailAddressParser->parseIdentifier($to);
            if (!$identifier) {
                //TODO: Make a notification to the Administrator that the identifier doesnt exist
                throw new Exception('identifier not found : '.$identifier);
            }
            $company = $this->companyRepository->findOneByIdentifier($identifier);
            if (!$company) {
                throw new Exception('Company not found for identifier: '.$identifier);
            }
            $parsedNotification = $this->emailParserDispatcher->dispatch($from, $subject, $body);
//            dump($parsedNotification);
            $bankAccount = $this->accountRepository->findActiveByAccountNumberAndInboxIdentifier(
                $parsedNotification->account,
                $identifier
            );
            if (!$bankAccount instanceof BankAccount) {
                //TODO: make a notification to the company that the bank account doesnt exist
                throw new Exception('Bank account doesnt exist');
            }
            if ($parsedNotification->type->isTransaction()) {
                $this->processTransaction($parsedNotification, $company, $bankAccount);
            } else {
                $this->processBalance($parsedNotification, $bankAccount);
            }
            $io->success('Email was successfully processed.');

            return self::SUCCESS;
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
            $io->error($exception->getMessage());

            return self::FAILURE;
        }
    }

    /**
     * @throws Exception
     */
    public function loadEmail(InputInterface $input): string
    {
        $source = $input->getArgument('source');
        $rawEmail = $source === 'stdin'
            ? stream_get_contents(STDIN)
            : file_get_contents($source);
        if (!$rawEmail) {
            throw new Exception("E-mail loading error");
        }

        return $rawEmail;
    }

    /**
     * @return string[]
     * @throws Exception
     */
    public function parseEmail(string $rawEmail): array
    {
        $parser = new Parser();
        $parser->setText($rawEmail);
        $fromHeader = $parser->getHeader('from');
        $from = strtolower(Address::create($fromHeader)->getAddress());
        if (!in_array($from, self::TRUSTED_EMAILS, true)) {
            //TODO: save the email for manual check
            throw new Exception("Unrecognized email notification from: $from");
        }
        $to = $parser->getHeader('to');
        $subject = $parser->getHeader('subject');
        $body = $parser->getMessageBody();

        return array($from, $to, $subject, $body);
    }

    public function processTransaction(
        ParsedNotificationDto $parsedNotification,
        Company $company,
        BankAccount $bankAccount
    ): void {
        $price = $parsedNotification->amount;
        $paymentType = $parsedNotification->type === NotificationType::TRANSACTION_INCOMING ? PaymentType::INCOME : PaymentType::EXPENSE;
        $document = $this->documentRepository->findByCompanyAndVariableSymbolAndSpecificSymbol(
            $company,
            $parsedNotification->vs,
        );
        $payment = $this->paymentRepository->findMatchingPayment(
            $company,
            $paymentType,
            $price,
            $parsedNotification->date,
            $document,
            $bankAccount,
            $parsedNotification->vs,
            $parsedNotification->ks,
            $parsedNotification->ss,
            $parsedNotification->counterparty,
            $parsedNotification->message,
            $parsedNotification->transactionId);
        $documentToPay = (count($payment) > 0) ? null : $document;
        $payment = new Payment($company, $paymentType, $price, $parsedNotification->date, $documentToPay, $bankAccount,
            $parsedNotification->vs, $parsedNotification->ks, $parsedNotification->ss,
            $parsedNotification->counterparty, $parsedNotification->message, $parsedNotification->transactionId
        );
        $this->entityManager->persist($payment);
        $this->entityManager->flush();
    }

    public function processBalance(
        ParsedNotificationDto $parsedNotification,
        BankAccount $bankAccount
    ): void {
        $balance = $parsedNotification->balance;
        $date = $parsedNotification->date;
        $bankAccountBalance = new BankAccountBalance($bankAccount, (string)$balance, $date);
        $this->entityManager->persist($bankAccountBalance);
        $this->entityManager->flush();
    }
}
