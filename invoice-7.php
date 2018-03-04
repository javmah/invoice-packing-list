<?php
//Include the main DomPDF library (search for installation path).
require_once( plugin_dir_path( __FILE__ ) . '/dompdf/lib/html5lib/Parser.php');
require_once( plugin_dir_path( __FILE__ ) . '/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php');
require_once( plugin_dir_path( __FILE__ ) . '/dompdf/lib/php-svg-lib/src/autoload.php');
require_once( plugin_dir_path( __FILE__ ) . '/dompdf/src/Autoloader.php');
Dompdf\Autoloader::register();
// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

// Woocommerce Query Starts 
	if (is_user_logged_in()) {
		// echo " Hmm you are Logged in : Good Job <br>";
	}
	$status = (isset($_GET['status']) ? $_GET['status'] : false); // status
	$id =  (isset($_GET['id']) ? $_GET['id'] : false); // id
	$order = wc_get_order( $id  );
	$currency_symbol = get_woocommerce_currency_symbol( $order->currency );
	// help Text :: https://stackoverflow.com/questions/25528454/getting-country-name-from-country-code-in-woocommerce/25533953
	$country_name = WC()->countries->countries[ $order->billing_country];

// Woocommerce Query Ends 
    // <meta http-equiv="Content-Type" content="text/html" charset=UTF-8" />

$html = '
        <!DOCTYPE html>
        <html>
		<head>
		    <meta charset="UTF-8">
            <meta http-equiv="Content-Type" content="text/html" charset=UTF-8" />
            

            <meta name="description" content="Free Web tutorials">
            <meta name="keywords" content="HTML,CSS,XML,JavaScript">
            <meta name="author" content="John Doe">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
		</head>

		<body>
		<style>

        body{
           font-family: DejaVu Sans ,firefly, sans-serif; 
        }

		#customers {
		    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
		    border-collapse: collapse;
		    width: 100%;
		}

		#customers td, #customers th {
		    border: 1px solid #ddd;
		    padding: 8px;
		}

		#customers tr:nth-child(even){background-color: #f2f2f2;}

		#customers tr:hover {background-color: #ddd;}

		#customers th {
		    padding-top: 12px;
		    padding-bottom: 12px;
		    text-align: left;
		    background-color: #4CAF50;
		    color: white;
		}

		#number{
			width:40px; 
		}

		#qty{
			width:50px; 
		}

		#unit_price{
			width:130px; 
		}

		#total{
			width:130px;
		}

		#subtotal_col {

		}


		</style>' ;
		// rowspan="2"
		$header = ' ';

		$table = '  
			<table style="width:100%">
			  <tr>
			    <td style="width:65%"> 
			    	<p>
			    		<img src="http://via.placeholder.com/150x150">
			    	</p> 
			    </td>
			    <td style="width:35%">
			    	<h2 style="color:#999999; "> INVOICE </h2>
			    	<p>  
			    		<b>leetech</b><br>
			    		124 / Kha , Majar co-opperativ Market
			    		<br>
			    		Dhaka – 1216 , 
			    		Bangladesh
			    		<br>
			    		tel: +01775-787641  ,
			    		email : leetech.info@gmail.com 
			    	</p>
			    </td>
			  </tr>
			</table>
		';

		$table1='<table id="customers">
			  <tr>
			    <th style="width:33%" > Billing Address </th>
			    <th style="width:33%" > Shipping Address  </th>
			    <th style="width:33%" > Order details </th>
			  </tr>

			  <tr>

			  	<td>' . $order->billing_company 		.'</td>
			  	<td>' . $order->shipping_company 		.'</td>
			    <td  > Invoice Number: <i> <b> 1++ </b> </i> </td>
			  </tr>

			  <tr>
			  	<td>'.$order->billing_first_name . " " .  $order->shipping_last_name .'</td>
			  	<td> '.$order->shipping_first_name . " " .  $order->shipping_last_name .'</td>
			  	<td> Invoice Date: <i>	November 21, 2017 </i> </td>
			  </tr>

			  <tr>
			  	<td>'. $order->billing_address_1 	. '</td>
			  	<td> '. $order->shipping_address_1 	. ' </td>
			    <td> Order Number: <i><b>'. $order->id .'</b></i> </td>
			  </tr>

			  <tr>
			  	<td>'. $order->billing_address_2 	. '</td>
			  	<td> '. $order->shipping_address_2 	. ' </td>
			    <td> Order Date: <i>	'. $order->order_date .' </i> </td>
			  </tr>

			  <tr>
			  	<td>'. $order->billing_city . $order->billing_postcode	.'</td>
			  	<td>'. $order->shipping_city . $order->shipping_postcode	.'</td>
			    <td> Payment Method: <i> '. $order->payment_method_title .' </i> </td>
			  </tr>
			  
			</table>
			<br>
			<br>
	';


		
	$table2 ='<table id="customers">
		  <tr>
		  	<th id="number" > # </th>
		    <th>Product Name</th>
		    <th id="qty" >Qty</th>
		    <th id="unit_price">Unit Price</th>
		    <th id="total">Total</th>
		  </tr>';

	$table2_aa .='<tr>
		  	<td>1</td>
		  	<td> Apple </td>
		    <td> 2 </td>
		    <td>80</td>
		    <td> 160 </td>
		  </tr>';

		$i = 1 ;
		foreach($order->get_items() as $item_id => $item_values){
		    // Getting the product ID
		    
		    $product_id = $item_values['product_id'];
		    $product_name = $item_values['name'];
		    $product_quantity = $item_values['quantity'];
		    $product_subtotal_price = $item_values['subtotal'];
		    $product_total_price = $item_values['total'];
		    // ..../...
		    
			$table2 .="<tr>
		        <td>{$i}</td>
		        <td>{$product_name}</td>
		        <td>{$product_quantity}</td>

		        <td>";
		        	
		        		// echo $product_subtotal_price / $product_quantity  ;
		        		if($product_subtotal_price == $product_total_price){
		        			$table2 .= "<span style=' font-family: DejaVu Sans ,firefly, sans-serif; '>{$currency_symbol} </span> ". $product_subtotal_price / $product_quantity  ;
		        		}else{
		        			$table2 .=  "<span style=' font-family: DejaVu Sans ,firefly, sans-serif; '> {$currency_symbol} </span> <strike>". $product_subtotal_price / $product_quantity . "</strike> " . $product_total_price / $product_quantity   ;
		        		}


		         	
			$table2 .= "</td>

		        <td><span style=' font-family: DejaVu Sans ,firefly, sans-serif; '>{$currency_symbol}</span>  {$product_total_price} </td>
		      </tr>";
		      
		    


		    
		    $i++ ;
		}

	$table2 .="
			<tr>
				<td colspan='2' > </td>
				<td colspan='2' id='subtotal_col' > Shipping </td>
			  	<td><span style=' font-family: DejaVu Sans ,firefly, sans-serif; '>{$currency_symbol}</span>  {$order->shipping_total} </td>
			</tr>
			<tr>
				<td colspan='2' > </td>
				<td colspan='2' id='subtotal_col'  > Total Tax </td>
			  	<td><span style=' font-family: DejaVu Sans ,firefly, sans-serif; '>{$currency_symbol}</span>  {$order->total_tax}  </td>
			</tr>
			<tr>
				<td colspan='2' > </td>
				<td colspan='2' id='subtotal_col'  > discount total </td>
			  	<td><span style=' font-family: DejaVu Sans ,firefly, sans-serif; '>{$currency_symbol}</span> {$order->discount_total}</td>
			</tr>
			<tr>
				<td colspan='2'> </td>
				<td colspan='2' id='subtotal_col'  > Invoice total </td>
			  	<td><span style=' font-family: DejaVu Sans ,firefly, sans-serif; '>{$currency_symbol}</span>  {$order->total}  </td>
			</tr>
		";



	$table2 .='</table>';

	$order_note = "
		<br>
		<table id='customers'>
		  <tr>
		    <th> Order Note : </th>
		  </tr>
		  <tr>
		    <td>
		    	<p style ='padding-left: 10px ; padding-right:10px' >
		    		{$order->customer_note} 
		   		</p>
		    </td>
		  </tr>
		</table>
	";

	$footer_note = "<p style='text-align: center;color:#4c4c4c ;  ' > 
		Make all cheacks payable  to City Corp
	</p>
	<h3 style='text-align: center;color:#999999 ; ' > THANK YOU FOR YOUR BUSINESS  </h3>
	" ;

	$page_end = "</body>";

// print_r($table.$html.$table1.$table2 .$order_note. $footer_note) ;

	// For Spacial Caharecters 
	// mb_internal_encoding('UTF-8');
	// def("DOMPDF_UNICODE_ENABLED", true);




// For remote Image Link 
$dompdf->set_option('isRemoteEnabled', TRUE);
// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');
$dompdf->loadHtml($table.$html.$table1.$table2 .$order_note. $footer_note . $page_end);
// Render the HTML as PDF
$dompdf->render();


// Output the generated PDF to Browser
// $dompdf->stream('xyzfile.pdf',array('Attachment' => 0));
// $dompdf->stream("dompdf_out.pdf", array("Attachment" => false));
// exit(0);

$fileName ="Hmm.pdf";

$dompdf->stream(
  $fileName ,
  array(
    'Attachment' => 0
  )
);

?>