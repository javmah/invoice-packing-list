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

// //Include the main DomPDF library (search for installation path).
// require_once( plugin_dir_path( __FILE__ ) . '/dompdf/lib/html5lib/Parser.php');
// require_once( plugin_dir_path( __FILE__ ) . '/dompdf/lib/php-font-lib/src/FontLib/Autoloader.php');
// require_once( plugin_dir_path( __FILE__ ) . '/dompdf/lib/php-svg-lib/src/autoload.php');
// require_once( plugin_dir_path( __FILE__ ) . '/dompdf/src/Autoloader.php');
// Dompdf\Autoloader::register();

// // reference the Dompdf namespace
// use Dompdf\Dompdf;

################################ Adding external library Starts ####################################


#  cheack to Make Sure Woocommerce Is active And Running
    if ( in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){
    	# run only if  there is no other class with this name 
    	if (! class_exists('Wcip')) {
    		# Extension Main Code Starts form heare 
    		
    		class Wcip {

    			

    			
    			public function __construct(){
    				
    				# INIT woocommerce action  Hook 
    				add_action( 'woocommerce_admin_order_actions_end', array($this,'Addfirst') , 99 , 2 );
    				// Handel post request Starts
    				add_action( 'admin_post_nopriv_viewinps', array($this , 'viewinps'));
    				add_action( 'admin_post_viewinps', array($this , 'viewinps'));
    				// Handel post request Ends

    				# Adding Manu Page Under Woocommerce 
    				add_action('admin_menu', array($this ,  'invoice_paking_init') ,10 );

    				// Add options By Settings API 
    				add_action( 'admin_init', array($this ,'register_my_cool_plugin_settings' ) ) ;
    				add_action('admin_menu', array($this ,'my_cool_plugin_create_menu') );
    			}

    			// ########################## Another Way ###########################

    			public function Addfirst($parm){
    				
    				 echo "<a class='button wc-action-button wc-action-button-view parcial view parcial' target='_blank' 
    				  href='". wp_nonce_url( admin_url( "admin-post.php?action=viewinps&status=invoice&id={$parm->id}")) ."'>Δ
    				  	</a>";

    				echo "<a class='button wc-action-button wc-action-button-view dom view dom' target='_blank' 
    				 href='". wp_nonce_url( admin_url( "admin-post.php?action=viewinps&status=packinglist&id={$parm->id}")) ."'> #
    				 	</a>";
    			}



    			

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

    			
    			# Adding External PDF Layout Link Starts
    			public function viewinps($value=''){
    				require_once( plugin_dir_path( __FILE__ ) . '/invoice-1.php');	
    			}
    			# Adding External PDF Layout Link Ends



    			# Adding Settings Page And Menu item Starts

    			function invoice_paking_init() {
    				// add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
    			    add_submenu_page( 'woocommerce', 'Invoice And Paking List ', 'Invoice & paking list', 'manage_options', 'Invoice_Paking', array($this, 'invoice_paking_callback' ) ); 
    			}

    			function invoice_paking_callback() {
    			   require_once( plugin_dir_path( __FILE__ ) . '/admin_page_layout-1.php');
    			  	// echo "Hello World How Are You ";
    			}
    			

    			# Adding Settings Page And Menu item Ends
    			

    		}

    		$GLOBALS['wc_example'] = new Wcip() ;




    		# Extension main Code  Eends Here 
    	}

    }






