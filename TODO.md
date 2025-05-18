# 🧠 TODO List

## 📦 New features

- [ ] default texts (email, invoice description, ...) management
- [ ] refactor the document customer ⇒ remove all customer columns and create customers in the customer table with
  relations
- [ ] improve handling with customer in the document when the customer was changed during editing the document
- [ ] refactor the document bank account ⇒ remove all bank account columns and create a bank account in the bank account
  table with relations
- [ ] remove transfer_tax from database it will be replaced by vat_mode ``VatMode::DOMESTIC_REVERSE_CHARGE``
- [ ] fix stimulus document filter is doesnt send request via ajax when is submitted

## 🔧 Refactoring

## ✅ Done

- [x] make migration for Vat values
- [x] remove a private invoice type and related things
- [x] document numbers management
- [x] Fix the problem with document prices (low vat, high vat, no vat) probably the best solution will be to make a
  separate table for that