# Fakturace

## Todo
- [x] make migration for Vat values
- [ ] document numbers management
- [ ] default texts (email, invoice description, ...) management
- [ ] delete all customer data from document
- [ ] remove private invoice type and related things
- [ ] Fix the problem with document prices (low vat, high vat, no vat) probably the best solution will be to make separate table for that
- [ ] refactor the document customer => remove all customer column and create customers in the customer table with relations
- [ ] refactor the document bank account => remove all bank account column and create bank account in the bank account table with relations

## Installation
adding czech font is necessary copy them from ``fonts`` to ``vendor/technickom/tcpdf/fonts`` directory. Or use the TCPDF utility ``tools/tcpdf_addfont.php`` Specify the path to the font 

``php tcpdf_addfont.php -b -t OpenSans -f 32 -i /open_sans/static/OpenSans-Regular.ttf,/open_sans/static/OpenSans-Bold.ttf``

##important
git commit 625085c312a909ef2efd12f175eb0f6470de6205 is fixing migration be careful  