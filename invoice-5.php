<?php
require_once( plugin_dir_path( __FILE__ ) . '/dompdf/lib/html5lib/Parser.php');
require_once( plugin_dir_path( __FILE__ ) . '/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php');
require_once( plugin_dir_path( __FILE__ ) . '/dompdf/lib/php-svg-lib/src/autoload.php');
require_once( plugin_dir_path( __FILE__ ) . '/dompdf/src/Autoloader.php');
Dompdf\Autoloader::register();
// reference the Dompdf namespace
use Dompdf\Dompdf;


$dompdf = new Dompdf();

$htmla = ' <p style="font-family: Arial, sans-serif;"> €  &#0128;  &euro; || &#2547; || \U+09F3	 || 0xE0 0xA7 0xB3 (e0a7b3) || &11100000:10100111:10110011 || &#x9f3; || &#x9f3; ||  &#8364; lol haha ě š čřžýáíé ৳ </p>' ;

$html = iconv('UTF-8','Windows-1250',$htmla);

$html_bangla = "বিশ্বের সবচেয়ে জনবহুল দেশগুলোর তালিকায় বাংলাদেশের অবস্থান" ;

$curen_unicode = '<p> Lek ؋	$ 	ƒ  $ ₼ 	$ Br 	BZ$ ៛  	¥  kn ₱  	Kč 	£ 	€ kr ﷼
₪  ₩ 	ден 	₨  лв 	$U    hmm->	&#x2655;</p>' ;

$curen_Code2000 = '<p style="font-family: Sylfaen"> Lek ؋  	$ 	ƒ ₼ Br BZ$ $b лв  R$	$  ៛ 	¥ ₡ kn 	₱ 	Kč 	kr RD$ 	£ 	¢ ﷼  ₪ лв ₩ ден ₨ 	₮ ₦
₨ ฿ </p>' ;

$cart_body='<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<style>
@font-face {
  font-family: latha;
  font-style: normal;
  font-weight: 400;
  src: url(http://eclecticgeek.com/dompdf/fonts/latha.ttf) format(\'true-type\');
}
</style>

</head>
<body>
    <p style="font-family: latha">தமிழ்</p>
</body>
</html>';

$test_2 = '<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
    <p style="font-family: firefly, DejaVu Sans, sans-serif;">献给母亲的爱</p>
</body>
</html>' ;

// def("DOMPDF_UNICODE_ENABLED", true);
define("DOMPDF_ENABLE_REMOTE", true);
define("DOMPDF_UNICODE_ENABLED", true);

$dompdf->load_html($test_2);

// (Optional) Setup the paper size and orientation
$dompdf->set_paper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream('document.pdf');



?>