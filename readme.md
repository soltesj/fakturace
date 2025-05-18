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

## important

git commit 625085c312a909ef2efd12f175eb0f6470de6205 is fixing migration be careful  