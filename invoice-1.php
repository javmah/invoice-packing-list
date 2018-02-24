<?php 
	// create new PDF document
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	// set document information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Nicola Asuni');
	$pdf->SetTitle('TCPDF Example 006');
	$pdf->SetSubject('TCPDF Tutorial');
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

	// remove default header/footer
	$pdf->setPrintHeader(false);
	$pdf->setPrintFooter(false);

	// set default monospaced font
	$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

	// set margins
	// $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetMargins(PDF_MARGIN_LEFT, 5 , PDF_MARGIN_RIGHT);

	// set auto page breaks
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

	// set image scale factor
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	// set some language-dependent strings (optional)
	if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	    require_once(dirname(__FILE__).'/lang/eng.php');
	    $pdf->setLanguageArray($l);
	}

	// ---------------------------------------------------------


	// set font
	$pdf->SetFont('dejavusans', '', 10);

	// add a page
	$pdf->AddPage();

	// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
	// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

	// New Starts
	$pdf->SetPrintHeader(false);
	$pdf->SetPrintFooter(false);
	// New Ends


	// Woocommerce Query Starts 
	$status = (isset($_GET['status']) ? $_GET['status'] : false); // status
	$id =  (isset($_GET['id']) ? $_GET['id'] : false); // id
	$order = wc_get_order( $id  );

	// echo "<pre>" ;
	// print_r( $order->order_date) ;
	// echo "<pre/>" ;

	// Woocommerce Query Ends

	$header ='<img src="http://localhost/componentsource/wp-content/uploads/2017/11/fabicon-3.png" height="42" width="42">';

	// create some HTML content
	$html1 = '
			<br>
			<table cellpadding="3" style="width:99% ; margin-bottom: 50px ;">

				<tr style="background-color:#7f7f7f; color:#e5e5e5;" >
				    <th>Billing Address :</th>
				    <th>Shipping Address :  </th> 
				    <th>Order details </th>
				</tr>

			  	
				<tr>
				  	<td align="center" >
				  		<b> Billing Address : </b>
				  		<br>
				  		Name : '. $order->billing_first_name .'
				  		<br> Company : '. $order->billing_company .' 
				  		<br> Address 1 : '. $order->billing_address_1 .'
				  		<br> Address 2: '. $order->billing_address_2 .'
				  		<br> Post Code : '. $order->billing_postcode .'
				  		<br> billing Country : '. $order->billing_country . '
				  	</td>

				    <td align="center" >
				    	<b> Shipping Address : </b>
				    	<br>
				    	Name : '. $order->shipping_last_name .'
				    	<br> Company : '. $order->shipping_company .' 
				    	<br> Address 1 : '. $order->shipping_address_1 .'
				    	<br> Address 2: '. $order->shipping_address_2 .'
				    	<br> city : '. $order->shipping_city 	.'
				    	<br> state : '. $order->shipping_state 	.'
				    	<br> Post Code : '. $order->billing_postcode .'
				    	<br> billing Country : '. $order->shipping_country . '
				    </td>


				    <td align="center"  >
				    	<span > Order Number: '. $order->id.'</span><br/>
				    	<span > Order Date: '. $order->order_date .' </span><br/>
				    	<span > Payment Method: '. $order->payment_method_title.'</span><br/>
					</td>
			    </tr>
			</table>


			<p> 
			</p>
			' ;

		$table1 = '<table cellpadding="5" cellspacing="0"  width="100%">
			  
			  <tr style="background-color:#7f7f7f;color:#e5e5e5;">
			   <td width="35" align="center"><b>#</b></td>
			   <td width="370" align="center"><b>Product Name</b></td>
			   <td width="55" align="center"><b>Qty</b></td>
			   <td width="95" align="center"> <b>Unit Price</b></td>
			   <td width="77" align="center"><b>Total</b></td>
			  </tr>

			  ' ;

		// Loop Starts
		
		$i = 1 ; 
		foreach($order->get_items() as $item_id => $item_values){
		    // Getting the product ID
		    
		    $product_id = $item_values['product_id'];
		    $product_name = $item_values['name'];
		    $product_quantity = $item_values['quantity'];
		    $product_subtotal_price = $item_values['subtotal'];
		    $product_total_price = $item_values['total'];
		    // ..../...
		    $table2 .= "  <tr>
		        <td width='35' align='center' > $i </td>
		        <td width='370' align='center' >$product_name </td>
		        <td width='55' align='center' > $product_quantity </td>

		        <td width='95' align='center' >" ; 
		        	
		        		// echo $product_subtotal_price / $product_quantity  ;
		        		if($product_subtotal_price == $product_total_price){
		        			$table2 .= $product_subtotal_price / $product_quantity  ;
		        		}else{
		        			$table2 .= "<strike>". $product_subtotal_price / $product_quantity . "</strike>" . $product_total_price / $product_quantity   ;
		        		}

		    $table2 .="</td>

		        <td width='77' align='center' > $product_total_price </td>
		      </tr>" ;
		    $i++ ; 
		} 
		// Loop  Ends 

		
		$table3 = "	<tr>
    				    <td> </td> 
    				    <td> Shipping total  </td>
    				    <td> </td>
    				    <td>  </td>
    				    <td><b> {$order->shipping_total} </b></td>
    				</tr>

    				<tr>
    				    <td> </td> 
    				    <td> discount total  </td>
    				    <td> </td>
    				    <td>  </td>
    				    <td><b> {$order->discount_total} </b></td>
    				</tr>


    				<tr>
    				    <td> </td> 
    				    <td> Total Tax </td>
    				    <td> </td>
    				    <td>  </td>
    				    <td><b> {$order->total_tax} </b></td>
    				</tr>

    				<tr>
    				    <td> </td> 
    				    <td> Total  </td>
    				    <td> </td>
    				    <td> </td>
    				    <td><b> {$order->total} </b></td>
    				</tr>




    				";

		$table4 ='</table>'; 

		$style = '<style type="text/css">

					table, th, td {
					    border: 1px solid black;
					}

					

				</style>' ; 


	// $html = $header.$html1 . "<br/>" . $table1 . $table2 . $table3 .$style  ; 
	$html = $header.$html1 . "<br/>" . $table1 . $table2 . $table3 .$table4 .$style  ; 

	// output the HTML content
	$pdf->writeHTML($html, true, false, true, false, '');

	// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

	// reset pointer to the last page
	$pdf->lastPage();

	// ---------------------------------------------------------

	//Close and output PDF document
	$pdf->Output( 'invoice-'.$order->id.'-'.$order->billing_first_name.'.pdf', 'I');

	//============================================================+
	// END OF FILE
	//============================================================+



 ?>

