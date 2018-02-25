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
// Woocommerce Query Ends 

$html = '
		<style>
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
			width:150px; 
		}

		#total{
			width:130px;
		}

		#subtotal_col {

		}


		</style>' ;
		// rowspan="2"
		$header = '
			<p><img src="http://via.placeholder.com/350x150"></p>
		';

		$table = '  
			<table style="width:100%">
			  <tr>
			    <td> 
			    	<p><img src="http://via.placeholder.com/350x150"></p> 
			    </td>
			    <td>
			    	<p>  
			    		<b>leetech</b><br>
			    		124 / Kha , Majar co-opperativ Market
			    		<br>
			    		Dhaka – 1216
			    		<br>
			    		Bangladesh
			    	</p>
			    </td>
			  </tr>
			</table>
		';

		$table1='<table id="customers">
			  <tr>
			    <th> Billing Address </th>
			    <th> Shipping Address  </th>
			    <th> Order details </th>
			  </tr>

			  <tr>

			  	<td>TTC Company</td>
			  	<td> TTC Bagabond </td>
			    <td  > Invoice Number: 420 </td>
			  </tr>

			  <tr>
			  	<td>Kristina R Maxwell</td>
			  	<td> Edith J Conkling </td>
			  	<td> Invoice Date:	November 21, 2017 </td>
			  </tr>

			  <tr>
			  	<td>2907 Caynor Circle</td>
			  	<td> 46 Stratford Court </td>
			    <td>Order Number: 142 </td>
			  </tr>

			  <tr>
			  	<td>Branchburg</td>
			  	<td> Raleigh </td>
			    <td> Order Date:	November 08, 2017 </td>
			  </tr>

			  <tr>
			  	<td>New Jersey 08817 </td>
			  	<td> North Carolina 27601 </td>
			    <td> Payment Method:Check payments </td>
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
		  </tr>
		  <tr>
		  	<td>1</td>
		  	<td> Apple </td>
		    <td> 2 </td>
		    <td>80</td>
		    <td> 160 </td>
		  </tr>
		  <tr>
		  	<td>1</td>
		  	<td> Apple </td>
		    <td> 2 </td>
		    <td>80</td>
		    <td> 160 </td>
		  </tr>
		  <tr>
		  	<td>1</td>
		  	<td> Apple </td>
		    <td> 2 </td>
		    <td>80</td>
		    <td> 160 </td>
		  </tr>
		  <tr>
		  	<td>1</td>
		  	<td> Apple </td>
		    <td> 2 </td>
		    <td>80</td>
		    <td> 160 </td>
		  </tr>
		  <tr>
		  	<td>1</td>
		  	<td> Apple </td>
		    <td> 2 </td>
		    <td>80</td>
		    <td> 160 </td>
		  </tr>
		  <tr>
		  	<td>1</td>
		  	<td> Apple </td>
		    <td> 2 </td>
		    <td>80</td>
		    <td> 160 </td>
		  </tr>
		  <tr>
		  	<td>1</td>
		  	<td> Apple </td>
		    <td> 2 </td>
		    <td>80</td>
		    <td> 160 </td>
		  </tr>
		  <tr>
		  	<td>1</td>
		  	<td> Apple </td>
		    <td> 2 </td>
		    <td>80</td>
		    <td> 160 </td>
		  </tr>
		  <tr>
		  	<td>1</td>
		  	<td> Apple </td>
		    <td> 2 </td>
		    <td>80</td>
		    <td> 160 </td>
		  </tr>
		  <tr>
		  	<td>1</td>
		  	<td> Apple </td>
		    <td> 2 </td>
		    <td>80</td>
		    <td> 160 </td>
		  </tr>';

	$table2 .='
		<tr>
			<td colspan="2" > </td>
			<td colspan="2" id="subtotal_col"  > Sub total </td>
		  	<td> 160 </td>
		</tr>
		<tr>
			<td colspan="2" > </td>
			<td colspan="2" id="subtotal_col" > Shipping </td>
		  	<td> 160 </td>
		</tr>
		<tr>
			<td colspan="2" > </td>
			<td colspan="2" id="subtotal_col"  > Total Tax </td>
		  	<td> 160 </td>
		</tr>

		<tr>
			<td colspan="2" > </td>
			<td colspan="2" id="subtotal_col"  > discount total </td>
		  	<td> 160 </td>
		</tr>

		<tr>
			<td colspan="2"> </td>
			<td colspan="2" id="subtotal_col"  > Invoice total </td>
		  	<td> 160 </td>
		</tr>
	';


	$table2 .='</table>';



// $dompdf->loadHtml('hello world');
$dompdf->loadHtml($table.$html.$table1.$table2);

// Enable Image  Bisoncode
$dompdf->set_option('isRemoteEnabled', TRUE);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();



?>