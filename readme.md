# Fakturace

## Todo
- [x] migration for Vat values
- [ ] document numbers management
- [ ] default texts (email, invoice description, ...) management
- [ ] delete all customer data from document
- [ ] remove private invoice type and related things
- [ ] Fix the problem with document prices (low vat, high vat, no vat) probably the best solution will be to make separate table for that 


adding czech font is necessary copy them from ``fonts`` to ``vendor/technickom/tcpdf/fonts`` directory. Or use the TCPDF utility ``tools/tcpdf_addfont.php`` Specify the path to the font 

``php tcpdf_addfont.php -b -t OpenSans -f 32 -i /open_sans/static/OpenSans-Regular.ttf,/open_sans/static/OpenSans-Bold.ttf``