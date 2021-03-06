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

    ##
    ## Important Help Text 
    ## https://stackoverflow.com/questions/468881/print-div-id-printarea-div-only/7532581#7532581
    ##


#  cheack to Make Sure Woocommerce Is active And Running
    if ( in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))){
    	# run only if  there is no other class with this name 
    	if (! class_exists('Wcip')) {
    		# Extension Main Code Starts form heare 
    		
    		class Wcip {

    		
    			public function __construct(){
    				
    				# INIT woocommerce action  Hook 
    				add_action( 'woocommerce_admin_order_actions_end', array($this,'Addfirst') , 99 , 2 );

                    // Woocommerce Setting Page Tab Array settings 
                    add_filter( 'woocommerce_settings_tabs_array', array($this, 'add_settings_tab'), 50 );


    				// Handel post request Starts
    				add_action( 'admin_post_nopriv_viewinps', array($this , 'viewinps'));
    				add_action( 'admin_post_viewinps', array($this , 'viewinps'));
    				// Handel post request Ends

    				
                    // Showing Settings Fields
                    add_action( 'woocommerce_settings_invoice', array($this, 'invoice_setting_tab_admin_page'));
                    // Save settings data 
                    add_action( 'woocommerce_update_options_invoice', array($this, 'invoice_update_settings'));

                    ############################ Paking List ###########################################
                    // Showing Settings Fields for paking List
                    add_action( 'woocommerce_settings_paking_list', array($this, 'paking_list_setting_tab_admin_page'));
                    // Save settings data for Paking List
                    add_action( 'woocommerce_update_options_paking_list', array($this, 'paking_list_update_settings'));

                    #################################### Adding Javascript On Footer ####################
                    add_action('admin_footer', array($this , 'my_admin_footer_function'), 100);

                    #####################################  AJAX STARTS ####################################
                    add_action('wp_ajax_my_action',array( $this, 'my_action' ));
                    
                    add_action('wp_ajax_nopriv_my_action', array( $this, 'my_action' ));

                    // Adding Jquery to Plugin Starts 
                    // add_action( 'wp_enqueue_scripts', array($this ,'jquery_add'));
                    // Adding Jquery to Plugin Ends
    			}

                function jquery_add() {
                    wp_enqueue_script( 'jquery_in_plugin', plugins_url('/jquery-3.3.1.min.js', __FILE__) , array( 'jquery' ), '1.0.0', true );
                }

                // ############################# AJAX STARTS #######################
                    public function my_action() {
                
                       $user_id = get_current_user_id();
                
                       echo "user ID Is :".$user_id  ;
                       wp_die(); // this is required to terminate immediately and return a proper response
                    }
                // ############################# AJAX ENDS #######################

    			// ########################## Another Way ###########################

    			public function Addfirst($parm){

                     echo "<a class='button wc-action-button wc-action-button-view parcial view parcial'  target='_blank' 
                      href='". wp_nonce_url( admin_url( "admin-post.php?action=viewinps&status=invoice&id={$parm->id}")) ."'>Δ
                         </a>";

                    echo "<a class='button wc-action-button wc-action-button-view dom view dom'   target='_blank' 
                     href='". wp_nonce_url( admin_url( "admin-post.php?action=viewinps&status=packinglist&id={$parm->id}")) ."'> #
                         </a>";
    				
    				//  echo "<a class='button wc-action-button wc-action-button-view parcial view parcial' onclick='sayHello()' target='_blank' 
    				//   href='". wp_nonce_url( admin_url( "admin-post.php?action=viewinps&status=invoice&id={$parm->id}")) ."'>Δ
    				//   	</a>";

    				// echo "<a class='button wc-action-button wc-action-button-view dom view dom' onclick='sayHello()'  target='_blank' 
    				//  href='". wp_nonce_url( admin_url( "admin-post.php?action=viewinps&status=packinglist&id={$parm->id}")) ."'> #
    				//  	</a>";

                    // Starts Query String 

                    $order = wc_get_order( $parm->id );
                    $currency_symbol = get_woocommerce_currency_symbol( $order->currency );
                    $country_name = WC()->countries->countries[ $order->billing_country];
                    $dataarray = array(


                                    'order_number'   =>     $parm->id,
                                    'order_date'     =>     $order->order_date ,
                                    'payment_method' =>     $order->payment_method_title,
                                    'currency_symbol'=>     $currency_symbol,
                                    'country_name'   =>     $country_name,

                                    'shipping_charge'=>     $order->shipping_total ,
                                    'total_tax'      =>     $order->total_tax ,
                                    'discount_total' =>     $order->discount_total ,
                                    'invoice_total'  =>     $order->total ,

                                    'billing_company'   =>  $order->billing_company ,
                                    'billing_first_name'=>  $order->billing_first_name ,
                                    'billing_last_name' =>  $order->billing_last_name ,
                                    'billing_address_1' =>  $order->billing_address_1 ,
                                    'billing_address_2' =>  $order->billing_address_2 ,
                                    'billing_city'      =>  $order->billing_city ,
                                    'billing_postcode'  =>  $order->billing_postcode ,

                                    'shipping_company'    =>    $order->shipping_company ,
                                    'shipping_first_name' =>    $order->shipping_first_name ,
                                    'shipping_last_name'  =>    $order->shipping_last_name ,
                                    'shipping_address_1'  =>    $order->shipping_address_1 ,
                                    'shipping_address_2'  =>    $order->shipping_address_2 ,
                                    'shipping_city'       =>    $order->shipping_city ,
                                    'shipping_postcode'   =>    $order->shipping_postcode 

                                    // 'shopping_products'   =>    $order->get_items() 
                                );

                    foreach($order->get_items() as $item_id => $item_values){
                        $items = array();

                        // $product_id = $item_values['product_id'];

                        // $dataarray['shopping_products'] [$product_id ]['product_id'] =     $item_values['product_id'];
                        // $dataarray['shopping_products'] [$product_id ]['name']       =     $item_values['name'];
                        // $dataarray['shopping_products'] [$product_id ]['quantity']   =     $item_values['quantity'];
                        // $dataarray['shopping_products'] [$product_id ]['subtotal']   =     $item_values['subtotal'];
                        // $dataarray['shopping_products'] [$product_id ]['total']      =     $item_values['total'];

                        $items['product_id'] = $item_values['product_id'];
                        $items['name'] =       $item_values['name'];
                        $items['quantity'] =   $item_values['quantity'];
                        $items['subtotal'] =   $item_values['subtotal'];
                        $items['total'] =      $item_values['total'];

                        $dataarray['shopping_products'][]  =    $items;
                    }


                    // Ends Query String 


                    // echo "<a  data-order-id='". json_encode($dataarray) ."' class='invoice_link button wc-action-button wc-action-button-view parcial view parcial'  >Δ
                    //     </a>";

                    // echo "<a  data-order-id='".$parm->id."' class='invoice_link button wc-action-button wc-action-button-view dom view dom'   > # </a>";
    			}

                public static function add_settings_tab( $settings_tabs ) {
                    $settings_tabs['invoice'] = __( 'Invoice','woocommerce-settings-tab-demo' );
                    $settings_tabs['paking_list'] = __( 'Paking List','woocommerce-settings-tab-demo' );
                        return $settings_tabs;
                }

                public function invoice_setting_tab_admin_page($value=''){
                   // echo "Hello From javed , How are you doing Guyes ";
                    // woocommerce_admin_fields(self::get_settings()); 
                    
                    woocommerce_admin_fields(
                        array(
                            'section_title' => array(
                                'name'     => __( 'Section Title', 'woocommerce-invoice' ),
                                'type'     => 'title',
                                'desc'     => '',
                                
                                'id'       => 'section_title'
                            ),

                             # Add invoice To Email  Attachment 
                            'invoice_attached_to' => array(
                                'name' => __( ' Attachment to New Order admin Mail', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                // 'desc' => __( 'if Cheacked invoice copy Will Atteched With  Admin Order Mail', 'woocommerce-invoice' ),
                                // 'desc_tip' => 'Hmm is Working',
                                'id'   => 'invoice_attached_to'
                            ),




                            # invoice_output_process
                            'invoice_output_process' => array(
                                'name'    => __( 'Invoice output Process ', 'woocommerce-invoice' ),
                                'label'   => __( 'A Dropdown', 'wedevs' ),
                                // 'desc'    => __( 'Dropdown description', 'wedevs' ),
                                'type'    => 'radio',
                                'id'      =>'invoice_output_process' ,
                                'default' => 'default',
                                'options' => array(
                                    'default' => 'Default',
                                    'pdf'  => 'PDF',
                                )
                            ),

                            #Select theme 
                            'select_theme ' => array(
                                'name'    => __( 'select Theme ', 'woocommerce-invoice' ),
                                'label'   => __( 'A Dropdown', 'wedevs' ),
                                // 'desc'    => __( 'Dropdown description', 'wedevs' ),
                                'type'    => 'select',
                                'id'      =>'select_theme' ,
                                'desc_tip' => ' Select a theme For Yur Invoice ',
                                'default' => 'no',
                                'options' => array(
                                    'default' => 'default',
                                    'modern'  => 'modern'
                                )
                            ),

                            #Shop Name  
                            'shop_name' => array(
                                'name' => __( 'Shop Name ', 'woocommerce-settings-tab-demo' ),
                                'type' => 'text',
                                // 'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'shop_name'
                            ),

                            #shop Address
                            'shop_address' => array(
                                'name' => __( 'Shop Address ', 'woocommerce-invoice' ),
                                'type' => 'textarea',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'shop_address'
                            ),

                            # Select Date Formet 
                            'invoice_time_format' => array(
                                'name'    => __( 'Date format', 'woocommerce-invoice' ),
                                'label'   => __( 'A Dropdown', 'wedevs' ),
                                // 'desc'    => __( 'Dropdown description', 'wedevs' ),
                                'type'    => 'radio',
                                'id'      =>'invoice_time_format' ,
                                'desc_tip' => 'Choose Your Invoice Date formate ',
                                'default' => 'no',
                                'options' => array(
                                    'default' => 'March 10, 2018',
                                    'hh'  => '02-03-2018',
                                    'modern'  => 'dd-mm-yy'
                                )
                            ),

                            # default Color
                            'invoice_prime_color ' => array(
                                 'name'    => __( 'Invoice Prime Color', 'woocommerce-invoice' ),
                                 'label'   => __( 'Advanced Editor', 'woocommerce-invoice' ),
                                 'desc'    => __( 'WP_Editor description', 'wedevs' ),
                                 'desc_tip' => 'Invoice Main theme  colour Will be Your Selected Coloure',
                                 'type'    => 'color',
                                 'id'      =>'invoice_prime_color' ,
                                 'default' => '#ffa'
                            ),

                            # Logo URL 
                            'logo_url' => array(
                                'name' => __( ' Invoice Logo Url', 'woocommerce-settings-tab-demo' ),
                                'type' => 'text',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Past Invoice Image or Logo ',
                                'id'   => 'logo_url'
                            ),


                            # PDF Page SIze 
                            'pdf_page_size ' => array(
                                'name'    => __( 'Invoice PDF Page Size', 'woocommerce-invoice' ),
                                'label'   => __( 'A Dropdown', 'wedevs' ),
                                'desc'    => __( 'Dropdown description', 'wedevs' ),
                                'type'    => 'select',
                                'id'      =>'pdf_page_size' ,
                                'default' => 'no',
                                'options' => array(
                                    'a4'  => 'A 4',
                                    'a5'  => 'A 5'
                                )
                            ),

                            # Button Click event 
                            'button_click_action ' => array(
                                'name'    => __( 'Invoice Icon Click Action', 'woocommerce-invoice' ),
                                'label'   => __( 'A Dropdown', 'wedevs' ),
                                'desc'    => __( 'Dropdown description', 'wedevs' ),
                                'type'    => 'radio',
                                'id'      =>'button_click_action' ,
                                'default' => 'no',
                                'options' => array(
                                    'same_tab' => 'Open  in Current Browser tab ',
                                    'new_tab' => 'Open invoice in New Browser tab ',
                                )
                            ),

                            # Show Shipping Address 
                            'show_shipping_address' => array(
                                'name' => __( ' Show Shipping Address ', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'show_shipping_address'
                            ),

                            # Show Phone Number
                            'show_phone_number' => array(
                                'name' => __( ' Show Phone Number ', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'show_phone_number'
                            ),

                            # Show Email address
                            'show_email_address' => array(
                                'name' => __( ' Show Email Address', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'show_email_address'
                            ),

                            # Show invoice date
                            'show_invoice_date' => array(
                                'name' => __( ' Show Invoice Date', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'show_invoice_date'
                            ),

                            # Show product thumnail on Invoice 
                            'show_product_thumbnail' => array(
                                'name' => __( ' Show Product thumbnail ', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'show_product_thumbnail'
                            ),
                            # Show Order Note On Invoice 
                            'show_order_note' => array(
                                'name' => __( ' Show Order note ', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'show_order_note'
                            ),
                            # Show Vat On Invoice 
                            'show_vat' => array(
                                'name' => __( ' Show Vat on invoice ', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'show_vat'
                            ),
                            # Show Qty 
                            'show_qty' => array(
                                'name' => __( ' Show quantity on invoice ', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'show_vat'
                            ),
                            # Show Discount 
                            'show_discount' => array(
                                'name' => __( ' Show Discount on invoice ', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'show_discount'
                            ),
                            #Footer Note 
                            'invoice_footer_not_1' => array(
                                'name' => __( 'Invoice footer not 1 ', 'woocommerce-invoice' ),
                                'type' => 'textarea',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'invoice_footer_not_1'
                            ),

                            #thankyou Note 
                            'thankyou_note' => array(
                                'name' => __( 'Thankyou Note  ', 'woocommerce-invoice' ),
                                'type' => 'textarea',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'thankyou_note'
                            ),


                            'section_end' => array(
                                 'type' => 'sectionend',
                                 'id' => 'section_end'
                            )    
                        )
                    ); 

                    echo "Hello Woarld";
                }

                public function invoice_update_settings($value=''){
                    woocommerce_update_options(
                        array(
                            'section_title' => array(
                                'name'     => __( 'Section Title', 'woocommerce-invoice' ),
                                'type'     => 'title',
                                'desc'     => '',
                                
                                'id'       => 'section_title'
                            ),

                             # Add invoice To Email  Attachment 
                            'invoice_attached_to' => array(
                                'name' => __( ' Attachment to New Order admin Mail', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'invoice_attached_to'
                            ),




                            # invoice_output_process
                            'invoice_output_process' => array(
                                'name'    => __( 'Invoice output Process ', 'woocommerce-invoice' ),
                                'label'   => __( 'A Dropdown', 'wedevs' ),
                                'desc'    => __( 'Dropdown description', 'wedevs' ),
                                'type'    => 'radio',
                                'id'      =>'invoice_output_process' ,
                                'default' => 'default',
                                'options' => array(
                                    'default' => 'Default',
                                    'pdf'  => 'PDF',
                                )
                            ),

                            #Select theme 
                            'select_theme ' => array(
                                'name'    => __( 'select Theme ', 'woocommerce-invoice' ),
                                'label'   => __( 'A Dropdown', 'wedevs' ),
                                'desc'    => __( 'Dropdown description', 'wedevs' ),
                                'type'    => 'select',
                                'id'      =>'select_theme' ,
                                'default' => 'no',
                                'options' => array(
                                    'default' => 'default',
                                    'modern'  => 'modern'
                                )
                            ),

                            #Shop Name  
                            'shop_name' => array(
                                'name' => __( 'Shop Name ', 'woocommerce-settings-tab-demo' ),
                                'type' => 'text',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'shop_name'
                            ),

                            #shop Address
                            'shop_address' => array(
                                'name' => __( 'Shop Address ', 'woocommerce-invoice' ),
                                'type' => 'textarea',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'shop_address'
                            ),

                            # Select Date Formet 
                            'invoice_time_format' => array(
                                'name'    => __( 'Time format', 'woocommerce-invoice' ),
                                'label'   => __( 'A Dropdown', 'wedevs' ),
                                'desc'    => __( 'Dropdown description', 'wedevs' ),
                                'type'    => 'radio',
                                'id'      =>'invoice_time_format' ,
                                'default' => 'no',
                                'options' => array(
                                    'default' => 'March 10, 2018',
                                    'hh'  => '02-03-2018',
                                    'modern'  => 'dd-mm-yy'
                                )
                            ),

                            # default Color
                            'invoice_prime_color ' => array(
                                 'name'    => __( 'Invoice Prime Color', 'woocommerce-invoice' ),
                                 'label'   => __( 'Advanced Editor', 'woocommerce-invoice' ),
                                 'desc'    => __( 'WP_Editor description', 'wedevs' ),
                                 'desc_tip' => 'Hmm is Working',
                                 'type'    => 'color',
                                 'id'      =>'invoice_prime_color' ,
                                 'default' => '#ffa'
                            ),

                            # Logo URL 
                            'logo_url' => array(
                                'name' => __( ' Invoice Logo Url', 'woocommerce-settings-tab-demo' ),
                                'type' => 'text',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'logo_url'
                            ),


                            # PDF Page SIze 
                            'pdf_page_size ' => array(
                                'name'    => __( 'Invoice PDF Page Size', 'woocommerce-invoice' ),
                                'label'   => __( 'A Dropdown', 'wedevs' ),
                                'desc'    => __( 'Dropdown description', 'wedevs' ),
                                'type'    => 'select',
                                'id'      =>'pdf_page_size' ,
                                'default' => 'no',
                                'options' => array(
                                    'a4'  => 'A 4',
                                    'a5'  => 'A 5'
                                )
                            ),

                            # Button Click event 
                            'button_click_action ' => array(
                                'name'    => __( 'Invoice icon Click Action', 'woocommerce-invoice' ),
                                'label'   => __( 'A Dropdown', 'wedevs' ),
                                'desc'    => __( 'Dropdown description', 'wedevs' ),
                                'type'    => 'radio',
                                'id'      =>'button_click_action' ,
                                'default' => 'no',
                                'options' => array(
                                    'open_in_new_table' => 'Open invoice in New table ',
                                    'downloard'  => 'Downloard invoice '
                                )
                            ),

                            # Show Shipping Address 
                            'show_shipping_address' => array(
                                'name' => __( ' Show Shipping Address ', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'show_shipping_address'
                            ),

                            # Show Phone Number
                            'show_phone_number' => array(
                                'name' => __( ' Show Phone Number ', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'show_phone_number'
                            ),

                            # Show Email address
                            'show_email_address' => array(
                                'name' => __( ' Show Email Address', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'show_email_address'
                            ),

                            # Show invoice date
                            'show_invoice_date' => array(
                                'name' => __( ' Show Invoice Date', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'show_invoice_date'
                            ),

                            # Show product thumnail on Invoice 
                            'show_product_thumbnail' => array(
                                'name' => __( ' Show Product thumbnail ', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'show_product_thumbnail'
                            ),
                            # Show Order Note On Invoice 
                            'show_order_note' => array(
                                'name' => __( ' Show Order note ', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'show_order_note'
                            ),
                            # Show Vat On Invoice 
                            'show_vat' => array(
                                'name' => __( ' Show Vat on invoice ', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'show_vat'
                            ),
                            # Show Qty 
                            'show_qty' => array(
                                'name' => __( ' Show quantity on invoice ', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'show_vat'
                            ),
                            # Show Discount 
                            'show_discount' => array(
                                'name' => __( ' Show Discount on invoice ', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'show_discount'
                            ),
                            #Footer Note 
                            'invoice_footer_not_1' => array(
                                'name' => __( 'Invoice footer not 1 ', 'woocommerce-invoice' ),
                                'type' => 'textarea',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'invoice_footer_not_1'
                            ),

                            #thankyou Note 
                            'thankyou_note' => array(
                                'name' => __( 'Thankyou Note  ', 'woocommerce-invoice' ),
                                'type' => 'textarea',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'thankyou_note'
                            ),


                            'section_end' => array(
                                 'type' => 'sectionend',
                                 'id' => 'section_end'
                            )    
                        )
                    );
                }

                ####################### paking Sip ###############################

                public function paking_list_setting_tab_admin_page($value=''){
                    woocommerce_admin_fields(
                        array(
                            # Starts
                            'paking_slip_section_title' => array(
                            'name'     => __( 'Section Title', 'woocommerce-invoice' ),
                            'type'     => 'title',
                            'desc'     => '',
                                
                            'id'       => 'section_title'
                            ),



                            # select Pakingslip Template 

                            'paking_list_theme' => array(
                                'name'    => __( 'select Paking List Theme ', 'woocommerce-invoice' ),
                                'label'   => __( 'A Dropdown', 'wedevs' ),
                                'desc'    => __( 'Dropdown description', 'wedevs' ),
                                'type'    => 'select',
                                'id'      =>'paking_list_theme' ,
                                'default' => 'no',
                                'options' => array(
                                    'default_list' => 'default Paking List ',
                                    'modern_list'  => 'modern paking List'
                                )
                            ),

                            # Show Shipping Address 
                            'show_billing_address_in_paking_list' => array(
                                'name' => __( ' Show Billing Address in Paking list ', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'billing_address_in_paking_list'
                            ),

                            # Show Shipping Address 
                            'show_shipping_address_in_paking_list' => array(
                                'name' => __( ' Show Shipping Address ', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'shipping_address_in_paking_list'
                            ),

                            # Show Shipping Address 
                            'show_thumbnail_in_paking_list' => array(
                                'name' => __( ' Show Shipping Address ', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'thumbnail_in_paking_list'
                            ),

                            # Show Shipping Address 
                            'show_order_note_in_paking_list' => array(
                                'name' => __( ' Show Shipping Address ', 'woocommerce-invoice' ),
                                'type' => 'checkbox',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'order_note_in_paking_list'
                            ),



                            #Ends
                            'paking_slip_section_end' => array(
                                 'type' => 'sectionend',
                                 'id' => 'section_end'
                            ) 
                        )
                   );
                }


                public function paking_list_update_settings($value=''){
                    woocommerce_update_options(
                       array(
                           # Starts
                           'paking_slip_section_title' => array(
                           'name'     => __( 'Section Title', 'woocommerce-invoice' ),
                           'type'     => 'title',
                           'desc'     => '',
                               
                           'id'       => 'section_title'
                           ),

                           # select Pakingslip Template 

                           'paking_list_theme' => array(
                               'name'    => __( 'select Paking List Theme ', 'woocommerce-invoice' ),
                               'label'   => __( 'A Dropdown', 'wedevs' ),
                               'desc'    => __( 'Dropdown description', 'wedevs' ),
                               'type'    => 'select',
                               'id'      =>'paking_list_theme' ,
                               'default' => 'no',
                               'options' => array(
                                   'default_list' => 'default Paking List ',
                                   'modern_list'  => 'modern paking List'
                               )
                           ),

                           # Show Shipping Address 
                           'show_billing_address_in_paking_list' => array(
                               'name' => __( ' Show Billing Address in Paking list ', 'woocommerce-invoice' ),
                               'type' => 'checkbox',
                               'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                               'desc_tip' => 'Hmm is Working',
                               'id'   => 'billing_address_in_paking_list'
                           ),

                           # Show Shipping Address 
                           'show_shipping_address_in_paking_list' => array(
                               'name' => __( ' Show Shipping Address ', 'woocommerce-invoice' ),
                               'type' => 'checkbox',
                               'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                               'desc_tip' => 'Hmm is Working',
                               'id'   => 'shipping_address_in_paking_list'
                           ),

                           # Show Shipping Address 
                           'show_thumbnail_in_paking_list' => array(
                               'name' => __( ' Show Shipping Address ', 'woocommerce-invoice' ),
                               'type' => 'checkbox',
                               'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                               'desc_tip' => 'Hmm is Working',
                               'id'   => 'thumbnail_in_paking_list'
                           ),

                           # Show Shipping Address 
                           'show_order_note_in_paking_list' => array(
                               'name' => __( ' Show Shipping Address ', 'woocommerce-invoice' ),
                               'type' => 'checkbox',
                               'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                               'desc_tip' => 'Hmm is Working',
                               'id'   => 'order_note_in_paking_list'
                           ),



                           #Ends
                           'paking_slip_section_end' => array(
                                'type' => 'sectionend',
                                'id' => 'section_end'
                           ) 
                       )
                    );
                }

    			
    			# Adding External PDF Layout Link Starts
    			public function viewinps($value=''){
    				require_once( plugin_dir_path( __FILE__ ) . '/hmm.php');	
    				
    			}
    			# Adding External PDF Layout Link Ends


                // Adding Javascrip to Admin Footer Starts 
                
                function my_admin_footer_function() {
                   ?>
                  
                    <div id="invoice"  >
                           
                            
                        <table style="width:100%">
                         <tr>
                           <td style="width:65%"> 
                            <p>
                                <img src="http://via.placeholder.com/150x150">
                                <img src="http://api.qrserver.com/v1/create-qr-code/?color=000000&amp;bgcolor=FFFFFF&amp;data=http%3A%2F%2Flocalhost%2Fcomponentsource%2Fmy-account%2F&amp;qzone=1&amp;margin=0&amp;size=150x150&amp;ecc=L" alt="qr code" />
                            </p> 
                           </td>
                           <td style="width:35%">
                            <h2 style="color:#999999; "> INVOICE {{message}} </h2>
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

                        <table id="customers">
                                  <tr>
                                    <th style="width:33%" > Billing Address </th>
                                    <th style="width:33%" > Shipping Address  </th>
                                    <th style="width:33%" > Order details </th>
                                  </tr>

                                  <tr>

                                    <td><?php $order->billing_company         ?></td>
                                    <td><?php $order->shipping_company        ?></td>
                                    <td> Invoice Number: <i> <b> 1++ </b> </i> </td>
                                  </tr>

                                  <tr>
                                    <td><?php $order->billing_first_name . " " .  $order->shipping_last_name ?> </td>
                                    <td><?php $order->shipping_first_name . " " .  $order->shipping_last_name ?></td>
                                    <td> Invoice Date: <i>  November 21, 2017 </i> </td>
                                  </tr>

                                  <tr>
                                    <td><?php $order->billing_address_1    ?></td>
                                    <td><?php $order->shipping_address_1  ?></td>
                                    <td> Order Number: <i><b><?php $order->id ?></b></i> </td>
                                  </tr>

                                  <tr>
                                    <td><?php $order->billing_address_2    ?></td>
                                    <td><?php $order->shipping_address_2   ?> </td>
                                    <td> Order Date: <i>    <?php $order->order_date ?> </i> </td>
                                  </tr>

                                  <tr>
                                    <td><?php $order->billing_city . $order->billing_postcode  ?></td>
                                    <td><?php $order->shipping_city . $order->shipping_postcode    ?></td>
                                    <td> Payment Method: <i> <?php $order->payment_method_title ?> </i> </td>
                                  </tr>        
                        </table>
                        <br>
                        <br>

                        <span id="referance"></span>

                       <!--  <table id="customers">
                            <tr id="divafter" >
                                <th id="number" > # </th>
                                <th>Product Name</th>
                                <th id="qty" >Qty</th>
                                <th id="unit_price">Unit Price</th>
                                <th id="total">Total</th>
                            </tr> -->

                            

                           <!--  <tr>
                                <td>1</td>
                                <td> Apple </td>
                                <td> 2 </td>
                                <td>80</td>
                                <td> 160 </td>
                            </tr> -->

                            

                            <!-- <tr>
                                <td colspan='2' > </td>
                                <td colspan='2' id='subtotal_col' > Shipping </td>
                                    <td id='shipping_total_amount'> </td>
                            </tr>
                          
                            <tr>
                                <td colspan='2' > </td>
                                <td colspan='2' id='subtotal_col'  > Total Tax </td>
                                <td id='total_tax_amount'>   </td>
                            </tr>

                            <tr>
                                <td colspan='2' > </td>
                                <td colspan='2' id='subtotal_col'  > discount total </td>
                                <td id='discount_total_amount'> </td>
                            </tr>
                            <tr>
                                <td colspan='2'> </td>
                                <td colspan='2' id='subtotal_col'  > Invoice total </td>
                                <td id='total_amount'>  </td>
                            </tr> -->

                        </table>

                        <style type="text/css">

                           @media print {
                              /* #invoice{
                                 visibility: visible;
                               }*/

                               html, body {
                                  height: 99%;    
                                  margin: 0 !important ;
                                  padding:0 !important ;
                                  background:  white ;
                               }

                               /*Table Style start*/

                               /*table {
                                   font-family: arial, sans-serif;
                                   border-collapse: collapse;
                                   width: 100%;
                                   margin-top: 15px ;
                               }

                               td, th {
                                   border: 1px solid #dddddd;
                                   text-align: left;
                                   padding: 8px;
                               }

                               tr:nth-child(even) {
                                   background-color: #dddddd;
                               } */

                               table{
                                   width: 100% ;
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

                               /*Table Style Ends*/
                           }


                           #invoice{
                               visibility: hidden;
                               position: absolute;
                               top: 0 ;
                               left:0;
                               right: 0 ;
                               width: 100%;
                           }
                        </style>


                        <script type="text/javascript">

                            jQuery(document).ready(function(){

                               jQuery('body').on('click','a.invoice_link',  function(evt){
                                    // alert( $(this).data('order-id') );

                                    // console.log( JSON.parse( $(this).data('order-id')));


                                    console.log( jQuery(this).data('order-id'));

                                    info  = jQuery(this).data('order-id') ;

                                     $html_table1 = `<table id="customers" class="yep">
                                                <tr id="divafter" >
                                                    <th id="number" > # </th>
                                                    <th>Product Name</th>
                                                    <th id="qty" >Qty</th>
                                                    <th id="unit_price">Unit Price</th>
                                                    <th id="total">Total</th>
                                                </tr>"`;

                                    $( "#referance" ).append( $html_table1 );
 
                                    // $.each( info.shopping_products , function(i, item) {
                                    //     // console.log( info.shopping_products[i].product_id +" |" + info.shopping_products[i].name);
                                    //     jQuery('#divafter').append('<tr> <td> '+i+' </td> <td> '+info.shopping_products[i].name+' </td> <td> '+info.shopping_products[i].quantity+' </td> <td>'+info.shopping_products[i].subtotal+'</td><td> '+info.shopping_products[i].total+' </td>  </tr>');
                                    //     // console.log(i);
                                    // })
                                    priceandproduct ='' ;
                                    $.each( info.shopping_products , function(i, item) {
                                        // console.log( info.shopping_products[i].product_id +" |" + info.shopping_products[i].name);
                                        priceandproduct +='<tr> <td> '+i+' </td> <td> '+info.shopping_products[i].name+' </td> <td> '+info.shopping_products[i].quantity+' </td> <td>'+info.shopping_products[i].subtotal+'</td><td> '+info.shopping_products[i].total+' </td>  </tr>';
                                        // console.log(i);
                                    })


                                    // Html table Ends 

                                    $html_table_end =`<tr>
                                            <td colspan='2' > </td>
                                            <td colspan='2' id='subtotal_col' > Shipping </td>
                                                <td id='shipping_total_amount'> </td>
                                        </tr>
                                      
                                        <tr>
                                            <td colspan='2' > </td>
                                            <td colspan='2' id='subtotal_col'  > Total Tax </td>
                                            <td id='total_tax_amount'>   </td>
                                        </tr>

                                        <tr>
                                            <td colspan='2' > </td>
                                            <td colspan='2' id='subtotal_col'  > discount total </td>
                                            <td id='discount_total_amount'> </td>
                                        </tr>
                                        <tr>
                                            <td colspan='2'> </td>
                                            <td colspan='2' id='subtotal_col'  > Invoice total </td>
                                            <td id='total_amount'>  </td>
                                        </tr>

                                    </table>`;

                                    $( ".yep" ).append( priceandproduct+$html_table_end );







                                    // $('#referance').after($html_table) ;

                                    // Shipping
                                    $( "#shipping_total_amount" ).html( info.currency_symbol+' '+  info.shipping_charge);
                                    // Total Tax
                                    $( "#total_tax_amount" ).html(  info.currency_symbol+' '+  info.total_tax);
                                    // discount total
                                    $( "#discount_total_amount" ).html( info.currency_symbol+' '+  info.discount_total);
                                    // Invoice total
                                    $( "#total_amount" ).html( info.currency_symbol+' '+ info.invoice_total);


                                    
                                    // Starts
                                        var printContents = document.getElementById('invoice').innerHTML;
                                        var originalContents = document.body.innerHTML;

                                        document.getElementById("invoice").style.visibility = "visible";
                                        document.body.innerHTML =  printContents
                                                                      
                                        window.print();
                                        document.body.innerHTML = originalContents  ;
                                    // Ends
                                    

                                   
                                });

                            });


                            

                            // function sayHello($id) {
                            //     var printContents = document.getElementById('invoice').innerHTML;
                            //     var originalContents = document.body.innerHTML;

                            //     document.getElementById("invoice").style.visibility = "visible";
                            //     document.body.innerHTML =  printContents
                               
                            //     window.print();
                            //     document.body.innerHTML = originalContents;
                            // }


                        </script>


                    </div>




                  

                   <?php
                }
                //Adding Javascrip to Admin Footer  Ends 
    		}

    		$GLOBALS['wc_example'] = new Wcip() ;




    		# Extension main Code  Eends Here 
    	}

    }






