# 🧠 TODO List

## 📦 Bugs

- [ ] fix resetting filter
- [ ] fix stimulus document filter it doesn't send a request via ajax when it is submitted

## 📦 New features

- [ ] default texts (email, invoice description, ...) management
- [ ] Add a notification system (new payment, not assigned payment, messages from the system ...)
- [ ] Add payment date to the document to show the user when it was paid

## 🔧 Refactoring

- [ ] update payment of all odl documents transfer them to table payment
- [ ] chat sql can nou take the price fom document table
- [ ] QR payment has to be refactored do to all countries have their own standard
- [ ] improve handling with customer in the document when the customer was changed during editing the document
- [ ] refactor the document customer ⇒ remove all customer columns and create customers in the customer table with
  relations
- [ ] refactor the document bank account ⇒ remove all bank account columns and create a bank account in the bank account
  table with relations
- [ ] remove transfer_tax from the database it will be replaced by vat_mode ``VatMode::DOMESTIC_REVERSE_CHARGE``
- [ ] make the document filter responsible

## ✅ Done

- [x] make migration for Vat values
- [x] remove a private invoice type and related things
- [x] document numbers management
- [x] Fixing the problem with document prices (low vat, high vat, no vat). Probably the best solution will be to make a
  separate table for that