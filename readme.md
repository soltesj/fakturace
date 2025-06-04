# Fakturace

## Installation
adding czech font is necessary copy them from ``fonts`` to ``vendor/tecnickcom/tcpdf/fonts`` directory. Or use the TCPDF utility ``tools/tcpdf_addfont.php`` Specify the path to the font

>``make fonts``
> 
> or
> 
>``cp fonts/* vendor/tecnickcom/tcpdf/fonts``
> 
> or
> 
>``php tcpdf_addfont.php -b -t OpenSans -f 32 -i /open_sans/static/OpenSans-Regular.ttf,/open_sans/static/OpenSans-Bold.ttf``

Javascript compile
> ``npm run watch``
>
> ``npm run build``
## important

git commit 625085c312a909ef2efd12f175eb0f6470de6205 is fixing migration be careful

Features
sen email notification of payment to php bin/console app:p email/income.automat@fio.cz.eml

## Load tests

in dev there prepare a separate vhost for url k6.fakturace.local with env APP_ENV=k6, for example, apache
`SetEnv APP_ENV k6`
prepare a database with fixture
> ``make test-k6``

then
> ``k6 run tests/k6/[script-name].js``