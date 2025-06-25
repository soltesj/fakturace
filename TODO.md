# 🧠 TODO List

## 𓆣 Bugs

- [ ] Fix the problem with generation document number in the load test there ware many attempts with same document
  number
- [ ] if the price is updated, the vat_amount is not recalculated
- [ ] if the document is edited, the first value off the vat amount is set it is probably caused by changing document
  vat mode
- [ ] set vatMode for existing documents
- [ ] set currency for existing documents

## 📦 New features

- [ ] default texts (email, invoice description, ...) management
- [ ] Add a notification system (new payment, not assigned payment, messages from the system ...)
- [ ] when a new company is created, add an email template
- [ ] vat summary on the dashboard now counts only vat from income expense should be counted too

## 🔧 Refactoring

- [ ] consider making a separate database for K6 tests
- [ ] update payment of all odl documents transfer them to table payment
- [ ] chart sql can nou take the price fom document table
- [ ] QR payment has to be refactored do to all countries have their own standard
- [ ] improve handling with customer in the document when the customer was changed during editing the document

## ✅ Done

- [x] make migration for Vat values
- [x] remove a private invoice type and related things
- [x] document numbers management
- [x] Fixing the problem with document prices (low vat, high vat, no vat). Probably the best solution will be to make a
  separate table for that
- [x] make the document filter responsible
- [x] remove transfer_tax from the database it will be replaced by vat_mode ``VatMode::DOMESTIC_REVERSE_CHARGE``
- [x] fix resetting filter
- [x] Add payment date to the document to show the user when it was paid
- [x] refactor the document bank account ⇒ remove all bank account columns and create a bank account in the bank account
  table with relations
- [x] refactor the document customer ⇒ remove all customer columns and create customers in the customer table with
  relations
- [x] management for default template for sending invoice by email
- [x] set default email template for existing company
- [x] set roles for exiting users "ROLE_USER","ROLE_COMPANY_EDITOR", "ROLE_COMPANY_ADMIN"

## REMOVED

- [ ] fix stimulus document filter it doesn't send a request via ajax when it is submitted