<?php

/*
Plugin Name: Woocommerce invoice and packing Slip
Description: Print Order in PDF Formet With Ease
Version: 1.01.01
Plugin URI: https://fb.com/javmah
Author URI: https://fb.com/javmah
Author: javmah
Text Domain: wcip
*/

/*  Copyright 2010  Formidable Forms

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.
*/


############################# Project To Do List ############################################

// USE PDF to Complite The Thing , Do it Tonight 
// Use Option To Make It More Interactive 
// Use Template Engine To Do Some Worke  

################################ Adding external library Starts ####################################

//Include the main TCPDF library (search for installation path).
// require_once('tcpdf_include.php');
require_once( plugin_dir_path( __FILE__ ) . '/tcpdf/tcpdf.php');


################################ Adding external library Starts ####################################


#  cheack to Make Sure Woocommerce Is active And Running
    if ( in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){
    	# run only if  there is no other class with this name 
    	if (! class_exists('Wcip')) {
    		# Extension Main Code Starts form heare 
    		
    		class Wcip {

    			

    			
    			public function __construct(){
    				// ########################## Another Way ###########################
    				// add_action( 'woocommerce_admin_order_actions_start', array($this,'Addfirst') , 99 , 2 );
    				add_action( 'woocommerce_admin_order_actions_end', array($this,'Addfirst') , 99 , 2 );


    				// ########################## Another Way Ends ######################

    				// Printing A Admin Notice 
    				add_action(  'admin_notices' , array($this , "say_hello") , 10);

    				//  Adding Icons For PDF 
    				// add_filter( 'manage_shop_order_posts_columns', array($this,'set_custom_edit_post_columns') , 99 , 1 );
    				add_action( 'manage_shop_order_posts_custom_column', array($this,'custom_cpost_column') , 99 , 2 );

    				// Testing Custom Action Hook 
    				add_action( 'say_hello_action_hook', array($this,'say_hello_hook') , 99 , 2 );
    				//  Testing  custom Filter hooks
    				add_filter('say_hello_filter_hook', 'say_hello_filter');


    				// ##########################  $$$ ####################################
    				# { woocommerce_admin_order_actions } =>   is a filter  To See filter Coide Please Visit     { Class-wc-admin-list-table-orders.php } 

    				add_filter( 'woocommerce_admin_order_actions', array($this ,'add_custom_order_status_actions_button'), 100, 2 );
    				add_action( 'admin_head', array($this , 'add_custom_order_status_actions_button_css') );


    				// ##########################  $$$ ####################################

    				// Handel post request Starts
    				add_action( 'admin_post_nopriv_viewinps', array($this , 'viewinps'));
    				add_action( 'admin_post_viewinps', array($this , 'viewinps'));

    				// Handel post request Ends

    				


    			}

    			public function say_hello(){
    				?>
    				    <div class="notice notice-success is-dismissible">
    				        <p><?php _e( 'This is a Sample Notice javed', 'wcip' ); ?></p>
    				       
    				    </div>
    				   
    				<?php
    				// Custom Action Hook  Test
    				do_action('say_hello_action_hook');
    				//  Custom Filter Hook Test 
    				apply_filters('say_hello_filter_hook');
    				    
    			}

    			// Custom Action Hook  test Function 
    			public function say_hello_hook( ){
    				?>

    				<div class="notice notice-success is-dismissible">
    				    <p><?php _e( ' This is Another Success !! if It Worke As Aspected  Good Lyck Boy @ action Hook Is Working  ', 'wcip' ); ?></p>
    				</div>

    				<?php
    			}

    			// custom Filter Hook  Function 

    			public function say_hello_filter($value=''){
    				return "Is it Working "  ;
    			}






    			# Help : https://stackoverflow.com/questions/45414924/what-is-the-hook-for-woocommerce-backend-orders-table

    			// add_filter( 'manage_shop_order_posts_columns', 'set_custom_edit_post_columns',99,1 );

    			function set_custom_edit_post_columns($columns) {
    			    $columns['custom-columns'] = __( 'invoice / packing slip', 'your_text_domain' );
    			    return $columns;
    			}

    			// add_action( 'manage_shop_order_posts_custom_column' , 'custom_cpost_column', 99, 2 );

    			function custom_cpost_column( $column, $post_id ) {
    			    switch ( $column ) {

    			        // case 'custom-columns'://new-title=your column slug :
    			        case 'wc_actions'://new-title=your column slug :
    			            // echo 'custom columns value' ;
    			        	// print_r($post_id);
    			        	$actions['icons'] = "Hmm";
    			        	?>
    			        	
    			        	<!-- <span class="dashicons dashicons-screenoptions"></span> -->
    			        	<!-- <a class="button wc-action-button wc-action-button-processing processing" href="http://localhost/componentsource/wp-admin/admin-ajax.php?action=woocommerce_mark_order_status&amp;status=processing&amp;order_id=188&amp;_wpnonce=a283d650d4">pdf</a> -->

    			        	<!-- <span  class="dashicons-media-text"> PDF</span>
    			        	<span class="dashicons-media-default"> Slip</span> -->

    			        	<?php
    			            break;
    			    }
    			} 


    			// ############################ $$ ##########################
    			# help url : https://stackoverflow.com/questions/45516819/add-a-custom-action-button-in-woocommerce-admin-order-list
    			// Add your custom order status action button (for orders with "processing" status)
    			// add_filter( 'woocommerce_admin_order_actions', 'add_custom_order_status_actions_button', 100, 2 );
    			function add_custom_order_status_actions_button( $actions, $order ) {
    			    // Display the button for all orders that have a 'processing' status
    			    if ( $order->has_status( array( 'processing' ) ) ) {

    			        // Get Order ID (compatibility all WC versions)
    			        $order_id = method_exists( $order, 'get_id' ) ? $order->get_id() : $order->id;
    			        // Set the action button
    			       
    			        // $actions['parcial'] = array(
    			        //     'url'       => wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_status&status=parcial&order_id=' . $order_id ), 'woocommerce-mark-order-status' ),
    			        //     'name'      => __( 'Envio parcial', 'woocommerce' ),
    			        //     'action'    => "view parcial", // keep "view" class for a clean button CSS
    			        // );

    			        $actions['parcial'] = array(
    			            'url'       => wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_status&status=parcial&order_id=' . $order_id ), 'woocommerce-mark-order-status' ),
    			            'name'      => __( 'Envio parcial', 'woocommerce' ),
    			            'action'    => "view parcial", // keep "view" class for a clean button CSS
    			            'hello_javed' => 'this is Test ' ,
    			        );
    			    }
    			    return $actions;
    			}

    			// Set Here the WooCommerce icon for your action button
    			// add_action( 'admin_head', 'add_custom_order_status_actions_button_css' );
    			function add_custom_order_status_actions_button_css() {
    			    echo '<style>.view.parcial::after { font-family: woocommerce; content: "Δ" !important; }</style>';
    			}
    			// ######################################################


    			// ########################## Another Way ###########################

    			public function Addfirst($parm){
    				// echo "<a class='button wc-action-button wc-action-button-view parcial view parcial' target='blank'  href='http://localhost/componentsource/wp-admin date='".$hmm->id."''>Δ</a>";
    				// echo "<a class='button wc-action-button wc-action-button-view parcial view parcial' target='blank'  href='".esc_url( admin_url( 'admin-post.php' ) )."?action=processform&a=420'>Δ</a>";

    				// echo "<a class='button wc-action-button wc-action-button-view parcial view parcial' target='blank' 
    				//  href=\'". wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_status&status=parcial&order_id=420')) ."\'>Δ
    				//  	</a>";

    				 echo "<a class='button wc-action-button wc-action-button-view parcial view parcial' target='_blank' 
    				  href='". wp_nonce_url( admin_url( "admin-post.php?action=viewinps&status=invoice&id={$parm->id}")) ."'>Δ
    				  	</a>";

    				echo "<a class='button wc-action-button wc-action-button-view dom view dom' target='_blank' 
    				 href='". wp_nonce_url( admin_url( "admin-post.php?action=viewinps&status=packinglist&id={$parm->id}")) ."'> #
    				 	</a>";



    				// echo  wp_nonce_url( admin_url( 'admin-ajax.php?action=woocommerce_mark_order_status&status=parcial&order_id=420'));
    				// $this->importantData = $hmm ;
    			}



    			// ######################## Another Way Ends #########################

    			// ############################# Handelling GET Request Starts #############################


    			function viewinps_working_copy() {
    			  // form processing code here
    				if (is_user_logged_in()) {
    					echo " Hmm you are Logged in : Good Job <br>";
    				}
    				$status = (isset($_GET['status']) ? $_GET['status'] : false); // status
    				$id =  (isset($_GET['id']) ? $_GET['id'] : false); // id

    				// echo "status is : " . $status ." AND id is : " . $id  ; 
    				// echo "<br>";
    				// echo "status is : " . $_GET['status'] ." AND id is : " . $_GET['id'] ; 

    				echo "<hr>";
    				// $orders = wc_get_orders( array('order_key' =>  $id ) );
    				$order = wc_get_order( $id  );

    				echo "<pre>";
    				// print_r($order) ; 
    				echo "</pre>";

    				

    				// Header section One Starts
    				// Header section One  Ends 


    				// Header section two Starts
    				// Header section two Ends 

    				// Order information Starts  
    				echo "Order Number:		" . $order->id ."<br>" ;
    				echo "Order Date  :		" . $order->order_date ."<br>" ;
    				echo "Payment Method:	" . $order->payment_method_title ;
    				// Order information Ends 

    				echo "<hr>";

    				// Shipping Address Starts 
    				echo "Shipping Address:<br>";
    				echo " name 	:" . $order->shipping_first_name . " " .  $order->shipping_last_name	. "<br>";
    				// echo "last name 	:" . $order->shipping_last_name 	. "<br>";
    				echo "company 		:" . $order->shipping_company 		. "<br>";
    				echo "address 1 	:" . $order->shipping_address_1 	. "<br>";
    				echo "address 2 	:" . $order->shipping_address_2 	. "<br>";
    				echo "city 			:" . $order->shipping_city 			. "<br>";
    				echo "state 		:" . $order->shipping_state			. "<br>";
    				echo "postcode 		:" . $order->shipping_postcode 		. "<br>";
    				echo "country 		:" . $order->shipping_country		. "<br>";
    				
    				// Shipping Address Ends 
    				echo "<hr>";
    				// Billing Address Starts
    				echo "Billing Address: <br>";
    				echo " name 	:" . $order->billing_first_name . " " .  $order->shipping_last_name	. "<br>";
    				// echo "last name 	:" . $order->shipping_last_name 	. "<br>";
    				echo "company 		:" . $order->billing_company 		. "<br>";
    				echo "address 1 	:" . $order->billing_address_1 	. "<br>";
    				echo "address 2 	:" . $order->billing_address_2 	. "<br>";
    				echo "city 			:" . $order->billing_city 			. "<br>";
    				echo "state 		:" . $order->billing_state			. "<br>";
    				echo "postcode 		:" . $order->billing_postcode 		. "<br>";
    				echo "country 		:" . $order->billing_country		. "<br>";
    				// Billing Address Ends 
    				echo "<hr>";
    				// getting order items
    				echo "<table border='1' >
    				      <tr >
    				        <th>id</th>
    				        <th>Product Name </th>
    				        <th>Quantity</th>
    				        <th>Unit Price</th>
    				        <th>Total </th>
    				      </tr>";
    				      $i = 1 ; 
    				foreach($order->get_items() as $item_id => $item_values){
    				    // Getting the product ID
    				    
    				    $product_id = $item_values['product_id'];
    				    $product_name = $item_values['name'];
    				    $product_quantity = $item_values['quantity'];
    				    $product_subtotal_price = $item_values['subtotal'];
    				    $product_total_price = $item_values['total'];
    				    // ..../...
    				    ?>

    				    <!-- <table>
    				      <tr>
    				        <th>Product</th>
    				        <th>Quantity</th>
    				        <th>Price</th>
    				      </tr> -->
    				      <tr>
    				        <td><?php echo $i ; ?></td>
    				        <td><?php echo $product_name ; ?></td>
    				        <td><?php echo $product_quantity ; ?></td>

    				        <td>
    				        	<?php 
    				        		// echo $product_subtotal_price / $product_quantity  ;
    				        		if($product_subtotal_price == $product_total_price){
    				        			echo $product_subtotal_price / $product_quantity  ;
    				        		}else{
    				        			echo "<strike>". $product_subtotal_price / $product_quantity . "</strike>" . $product_total_price / $product_quantity   ;
    				        		}


    				         	?>
    				        </td>

    				        <td><?php echo $product_total_price ; ?></td>
    				      </tr>
    				      
    				    <!-- </table> -->


    				    <?php
    				    $i++ ;
    				    // .../...
    				    // echo $product_id ."<br>";
    				    // echo $product_name ."<br>";
    				    // echo $product_quantity ."<br>";
    				}
    				echo "<tr>
    				    <td> </td> 
    				    <td> Shipping total  </td>
    				    <td> </td>
    				    <td>  </td>
    				    <td><b> {$order->shipping_total} </b></td>
    				</tr>";

    				echo "<tr>
    				    <td> </td> 
    				    <td> discount total  </td>
    				    <td> </td>
    				    <td>  </td>
    				    <td><b> {$order->discount_total} </b></td>
    				</tr>";

    				echo "<tr>
    				    <td> </td> 
    				    <td> Total Tax </td>
    				    <td> </td>
    				    <td>  </td>
    				    <td><b> {$order->total_tax} </b></td>
    				</tr>";

    				echo "<tr>
    				    <td> </td> 
    				    <td> Total  </td>
    				    <td> </td>
    				    <td> </td>
    				    <td><b> {$order->total} </b></td>
    				</tr>";
    				echo "</table>";

    				echo "<hr>";
    				echo "<pre>";
    				// print_r($order) ; 
    				echo "</pre>";



    				// Fvooter section One  Starts 

    				// Fvooter section One  Ends



    				// Footer section two Starts 

    				// Footer section two Ends 
    			} 
    			// Function Ends Heare 


    			// ############################# Handelling GET Request Ends #################################

    			// ######################################  PDF  Testing Starts ################################

    			public function viewinps_PDF_working($value=''){
    				// create new PDF document
    				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    				// set document information
    				$pdf->SetCreator(PDF_CREATOR);
    				$pdf->SetAuthor('Nicola Asuni');
    				$pdf->SetTitle('TCPDF Example 023');
    				$pdf->SetSubject('TCPDF Tutorial');
    				$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

    				// set default header data
    				$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 023', PDF_HEADER_STRING);

    				// set header and footer fonts
    				$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    				$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    				// set default monospaced font
    				$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    				// set margins
    				$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    				$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    				$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

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
    				$pdf->SetFont('times', 'BI', 14);

    				// Start First Page Group
    				$pdf->startPageGroup();

    				// add a page
    				$pdf->AddPage();

    				// set some text to print
    				$txt ="
    				Example of page groups.
    				Check the page numbers on the page footer.

    				This is the first page of group 1." ; 
    				

    				// print a block of text using Write()
    				$pdf->Write(0, $txt, '', 0, 'L', true, 0, false, false, 0);

    				// add second page
    				$pdf->AddPage();
    				$pdf->Cell(0, 10, 'This is the second page of group 1', 0, 1, 'L');

    				// Start Second Page Group
    				$pdf->startPageGroup();

    				// add some pages
    				$pdf->AddPage();
    				$pdf->Cell(0, 10, 'This is the first page of group 2', 0, 1, 'L');
    				$pdf->AddPage();
    				$pdf->Cell(0, 10, 'This is the second page of group 2', 0, 1, 'L');
    				$pdf->AddPage();
    				$pdf->Cell(0, 10, 'This is the third page of group 2', 0, 1, 'L');
    				$pdf->AddPage();
    				$pdf->Cell(0, 10, 'This is the fourth page of group 2', 0, 1, 'L');

    				// ---------------------------------------------------------

    				//Close and output PDF document
    				$pdf->Output('goodjob.pdf', 'I');
    			}
    			


    			// ######################################  PDF  Testing Ends   ################################

    			// ############################################ PDF Layout Genarating Starts ####################################

    			public function viewinps_working_x($value=''){
    				// create new PDF document
    				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    				// set document information
    				$pdf->SetCreator(PDF_CREATOR);
    				$pdf->SetAuthor('Nicola Asuni');
    				$pdf->SetTitle('TCPDF Example 006');
    				$pdf->SetSubject('TCPDF Tutorial');
    				$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

    				// set default header data
    				$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

    				// set header and footer fonts
    				$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    				$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    				// set default monospaced font
    				$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    				// set margins
    				$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    				$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    				$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

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

    				// Woocommerce Query Starts 
    				$status = (isset($_GET['status']) ? $_GET['status'] : false); // status
    				$id =  (isset($_GET['id']) ? $_GET['id'] : false); // id
    				$order = wc_get_order( $id  );

    				// echo "<pre>" ;
    				// print_r( $order->order_date) ;
    				// echo "<pre/>" ;

    				// Woocommerce Query Ends

    				// create some HTML content
    				$html1 = '
    					

    						
    						<table cellpadding="10" style="width:100% ; margin-bottom: 50px ;">
    						  <tr>
    						    <td style="padding-left: 10px; " >
    						    	Name : '. $order->billing_first_name .'
    						    	<br> Company : '. $order->billing_company .' 
    						    	<br> Address 1 : '. $order->billing_address_1 .'
    						    	<br> Address 2: '. $order->billing_address_2 .'
    						    	<br> Post Code : '. $order->billing_postcode .'
    						    	<br> billing Country : '. $order->billing_country . '
    						    </td>


    						    <td>
    						    	Order Number: '. $order->id.'
    						    	<br>
    						    	Order Date: '. $order->order_date .'
    						    	<br>
    						    	Payment Method: '. $order->payment_method_title.'
    							</td>
    						  </tr>
    						</table>' ;


    					$html2 = '
    						<p>
    							order contrey: '. $order->billing_country . '
    						</p>
    					'; 
    						
    					
    					// cellpadding="5"

    					$table1 = '<table  style="width: 100% ;  ">
    						  <tr style="background-color:#7f7f7f;" >
    						    <th>#</th>
    						    <th>Product Name</th>
    						    <th> Quantity </th>
    						    <th>Unit Price</th>
    						    <th>Total</th>
    						  </tr>' ;

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
    					        <td> $i </td>
    					        <td>$product_name </td>
    					        <td> $product_quantity </td>

    					        <td>" ; 
    					        	
    					        		// echo $product_subtotal_price / $product_quantity  ;
    					        		if($product_subtotal_price == $product_total_price){
    					        			$table2 .= $product_subtotal_price / $product_quantity  ;
    					        		}else{
    					        			$table2 .= "<strike>". $product_subtotal_price / $product_quantity . "</strike>" . $product_total_price / $product_quantity   ;
    					        		}

    					    $table2 .="</td>

    					        <td> $product_total_price </td>
    					      </tr>" ;
    					    $i++ ; 
    					} 
    					// Loop  Ends 

    					


    					$table3 ='	</table>

    						<!-- Style Starts -->
    						<style type="text/css">
    							

    							table, th, td {
    							    border: 1px solid black;
    							}

    							table{
    								margin-top: 170px ;
    							}

    							td {
    								padding: 5px 10px 5px 5px; 
    							}

    							

    							/*#inbody{
    								width: 100%;
    							}*/
    						</style>
    						<!-- Style Ends -->
    					

    				'; 

    				$html = $html1 . "<br/>" . $html2 . "<br/>" . $table1 . $table2 . $table3 ; 

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

    				

    			}

    			// ############################################ PDF Layout Genarating Ends ######################################

    			//  ############# Test Starts ##################
    			public function viewinps_working_fine($value=''){
    				// create new PDF document
    				$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    				// set document information
    				$pdf->SetCreator(PDF_CREATOR);
    				$pdf->SetAuthor('Nicola Asuni');
    				$pdf->SetTitle('TCPDF Example 006');
    				$pdf->SetSubject('TCPDF Tutorial');
    				$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

    				// set default header data
    				$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

    				// set header and footer fonts
    				$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    				$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

    				// set default monospaced font
    				$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    				// set margins
    				$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    				$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
    				$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

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

    				// Woocommerce Query Starts 
    				$status = (isset($_GET['status']) ? $_GET['status'] : false); // status
    				$id =  (isset($_GET['id']) ? $_GET['id'] : false); // id
    				$order = wc_get_order( $id  );

    				// echo "<pre>" ;
    				// print_r( $order->order_date) ;
    				// echo "<pre/>" ;

    				// Woocommerce Query Ends

    				// create some HTML content
    				$html1 = '
    					
    						<table cellpadding="3" style="width:99% ; margin-bottom: 50px ;">
    						  <tr>
    						    <td style="padding-left: 10px; " >
    						    	<b> Billing Address : </b>
    						    	<br>
    						    	Name : '. $order->billing_first_name .'
    						    	<br> Company : '. $order->billing_company .' 
    						    	<br> Address 1 : '. $order->billing_address_1 .'
    						    	<br> Address 2: '. $order->billing_address_2 .'
    						    	<br> Post Code : '. $order->billing_postcode .'
    						    	<br> billing Country : '. $order->billing_country . '
    						    </td>


    						    <td>
    						    	Order Number: '. $order->id.'
    						    	<br>
    						    	Order Date: '. $order->order_date .'
    						    	<br>
    						    	Payment Method: '. $order->payment_method_title.'
    							</td>
    						  </tr>
    						</table>' ;


    					
    						
    					//  table Attributes
    					// cellpadding="5"
    					// border="1"
    					// cellpadding="2" cellspacing="2"

    					$table1 = '<table cellpadding="5" cellspacing="0"  width="100%">
    						  
    						  <tr style="background-color:#7f7f7f;color:#0000FF;">
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

    					


    					$table3 ='	</table>'; 

    					$style = '<style type="text/css">

    								table, th, td {
    								    border: 1px solid black;
    								}

    								

    							</style>' ; 


    				$html = $html1 . "<br/>" . $table1 . $table2 . $table3 .$style  ; 

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

    				

    			}
    			//  ############# Test Ends ##################

    			// ################# Real worke Starts ###############

    			public function viewinps($value=''){
    				require_once( plugin_dir_path( __FILE__ ) . '/invoice-1.php');	
    			}
    			// ################# Real worke Ends ###############



    		}

    		$GLOBALS['wc_example'] = new Wcip() ;




    		# Extension main Code  Eends Here 
    	}

    }






