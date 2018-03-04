<?php
require_once( plugin_dir_path( __FILE__ ) . '/dompdf/lib/html5lib/Parser.php');
require_once( plugin_dir_path( __FILE__ ) . '/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php');
require_once( plugin_dir_path( __FILE__ ) . '/dompdf/lib/php-svg-lib/src/autoload.php');
require_once( plugin_dir_path( __FILE__ ) . '/dompdf/src/Autoloader.php');
Dompdf\Autoloader::register();
// reference the Dompdf namespace
use Dompdf\Dompdf;


ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Document</title>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <!-- <link href="https://fonts.googleapis.com/css?family=Hind+Siliguri" rel="stylesheet"> -->


    <!-- <link href="https://fonts.maateen.me/adorsho-lipi/font.css" rel="stylesheet"> -->

    <!-- <link href='https://fonts.googleapis.com/css?family=Baloo+Da' rel='stylesheet'> -->
    <style>
    .droid {
        font-family: droidsansfallback, sans-serif;
    }
    </style>
</head>
<body>
    <!-- <span class="droid">中文</span> / English -->
     <!-- <p style="font-family: symbol , firefly, DejaVu Sans, sans-serif;"> ৳ ₹ Lek ؋	$ 	ƒ  $ ₼ 	$ Br 	BZ$ ៛  	¥  kn ₱  	Kč 	£ 	€ kr ﷼ ₪  ₩ 	ден 	₨  лв 	$U    hmm->	&#x2655;</p> -->

     <p> ৳ ₹ Lek ؋	$ 	ƒ  $ ₼ 	$ Br 	BZ$ ៛  	¥  kn ₱  	Kč 	£ 	€ kr ﷼ ₪  ₩ 	ден 	₨  лв 	$U    hmm->	&#x2655; hhm </p>
     
    <p style="font-family: DejaVu Sans, firefly, sans-serif;"> ৳ ₹ Lek ؋	$ 	ƒ  $ ₼ 	$ Br 	BZ$ ៛  	¥  kn ₱  	Kč 	£ 	€ kr ﷼ ₪  ₩ 	ден 	₨  лв 	$U    hmm->	&#x2655; lol dokumentiert und vermittelt die deutsche Sprache und Literatur in ihren historischen und gegenwärtigen Formen.</p>


    <p style="font-family: firefly,  DejaVu Sans, sans-serif;"> ৳ ₹ &#8377; &#8360;  Lek ؋	$ 	ƒ  $ ₼ 	$ Br 	BZ$ ៛  	¥  kn ₱  	Kč 	£ 	€ kr ﷼ ₪  ₩ 	ден 	₨  лв 	$U    hmm->	&#x2655;  lol</p>

    <p style="font-family: firefly,  DejaVu Sans, sans-serif;"> ৳ ₹ &#8377; &#8360;  Lek ؋	$ 	ƒ  $ ₼ 	$ Br 	BZ$ ៛  	¥  kn ₱  	Kč 	£ 	€ kr ﷼ ₪  ₩ 	ден 	₨  лв 	$U    hmm->	&#x2655;  lol</p>

     <!-- <p style="font-family: Arial  ,'Baloo Da', cursive, ' DejaVu Sans', sans-serif;"> ৳  &#2547; &#x9f3; || ₹ &#8377;   Lek ؋	$ 	ƒ  $ ₼ 	$ Br 	BZ$ ៛  	¥  kn ₱  	Kč 	£ 	€ kr ﷼ ₪  ₩ 	ден 	₨  лв 	$U    hmm->	&#x2655;  lol पश्चिम पाकिस्तान की तत्कालीन सरकार के अन्याय के विरुद्ध </p> -->

     <!-- <p><span style="font-family: FontAwesome" class="fa fa-envelope"></span></p> -->
     <!-- <p><span  class="fa fa-envelope"></span></p> -->

     <!-- <p style="font-family: AdorshoLipi, Arial, sans-serif  ,  "> ৳  &#2547; &#x9f3;  पश्चिम पाकिस्तान की तत्कालीन सरकार के अन्याय के विरुद्ध  aita kaj kore </p> -->

     <!-- <p style="font-family: Arial  ,'Baloo Da', cursive, ' DejaVu Sans', sans-serif;"> ৳  &#2547; &#x9f3;  पश्चिम पाकिस्तान की तत्कालीन सरकार के अन्याय के विरुद्ध  aita kaj kore </p> -->

</body>
</html>
<?php
$content = ob_get_clean();

$dompdf = new \Dompdf\Dompdf(); // new Dompdf();
$dompdf->loadHtml($content);
$dompdf->render();

header('Content-Type: application/pdf; charset=utf-8');
header('Content-disposition: inline; filename="' .  $no . '.pdf"', false);

echo $dompdf->output();
// echo $content ;

?>