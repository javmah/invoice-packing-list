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


    			function viewinps() {
    			  // form processing code here
    				if (is_user_logged_in()) {
    					echo " Hmm you are Logged in : Good Job";
    				}

    				echo "<br>";
    				// status
    				$status = (isset($_GET['status']) ? $_GET['status'] : false);
    				// id
    				$id =  (isset($_GET['id']) ? $_GET['id'] : false);

    				echo "status is : " . $status ." AND id is : " . $id  ; 
    				echo "<br>";
    				echo "status is : " . $_GET['status'] ." AND id is : " . $_GET['id'] ; 

    				echo "<hr>";

    				// $orders = wc_get_orders( array('order_key' =>  $id ) );
    				$order = wc_get_order( $id  );

    				echo "<pre>";
    				// print_r($order) ; 
    				echo "</pre>";

    				$order->id; // order ID
    				$order->post_title; // order Title
    				$order->post_status; // order Status
    				// getting order items
    				echo "<table>
    				      <tr>
    				        <th>id</th>
    				        <th>Product Name </th>
    				        <th>Quantity</th>
    				        <th>Price</th>
    				      </tr>";
    				      $i = 1 ; 
    				foreach($order->get_items() as $item_id => $item_values){
    				    // Getting the product ID
    				    
    				    $product_id = $item_values['product_id'];
    				    $product_name = $item_values['name'];
    				    $product_quantity = $item_values['quantity'];
    				    $product_price = $item_values['total'];
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
    				        <td><?php echo $product_price ; ?></td>
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
    				    <td> </td>
    				    <td> </td>
    				    <td><b> {$order->total} </b></td>
    				</tr>";
    				echo "</table>";

    				echo "<hr>";
    				echo "<pre>";
    				print_r($order) ; 
    				echo "</pre>";

    				

    			}


    			// ############################# Handelling GET Request Ends ###############################



    		}

    		$GLOBALS['wc_example'] = new Wcip() ;




    		# Extension main Code  Eends Here 
    	}

    }






