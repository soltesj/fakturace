# Fakturace

## Todo
- [x] make migration for Vat values
- [x] remove a private invoice type and related things
- [x] document numbers management
- [ ] default texts (email, invoice description, ...) management
- [ ] Fix the problem with document prices (low vat, high vat, no vat) probably the best solution will be to make a
  separate table for that
- [ ] refactor the document customer ⇒ remove all customer columns and create customers in the customer table with
  relations
- [ ] improve handling with customer in the document when the customer was changed during editing the document
- [ ] refactor the document bank account ⇒ remove all bank account columns and create bank account in the bank account
  table with relations

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

## important

git commit 625085c312a909ef2efd12f175eb0f6470de6205 is fixing migration be careful  