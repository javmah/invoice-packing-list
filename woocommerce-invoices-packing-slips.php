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

    				add_action("admin_init",array($this , "display_options") ) ;

                    // Woocommerce Setting Page Tab Array settings 
                    // add_filter( $tag, $function_to_add, $priority = 10, $accepted_args = 1 )
                    add_filter( 'woocommerce_settings_tabs_array', array($this, 'add_settings_tab'), 50 );
                    // Showing Settings Fields
                    add_action( 'woocommerce_settings_invoice', array($this, 'invoice_setting_tab_admin_page'));
                    // Save settings data 
                    add_action( 'woocommerce_update_options_invoice', array($this, 'invoice_update_settings'));

                    ############################ Paking List ###########################################
                    // Showing Settings Fields for paking List
                    add_action( 'woocommerce_settings_paking_list', array($this, 'paking_list_setting_tab_admin_page'));
                    // Save settings data for Paking List
                    add_action( 'woocommerce_update_options_paking_list', array($this, 'paking_list_update_settings'));


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
                                'name'    => __( 'Invoice Icon Click Action', 'woocommerce-invoice' ),
                                'label'   => __( 'A Dropdown', 'wedevs' ),
                                'desc'    => __( 'Dropdown description', 'wedevs' ),
                                'type'    => 'select',
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

                            'invoice_output_process' => array(
                                'name'    => __( 'Time format', 'woocommerce-invoice' ),
                                'label'   => __( 'A Dropdown', 'wedevs' ),
                                'desc'    => __( 'Dropdown description', 'wedevs' ),
                                'type'    => 'radio',
                                'id'      =>'invoice_output_process' ,
                                'default' => 'no',
                                'options' => array(
                                    'default' => 'Default',
                                    'pdf'  => 'PDF',
                                )
                            ),

                            #Select theme dd
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

                            #Select theme dd
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
                                'name'    => __( 'Invoice Icon Click Action', 'woocommerce-invoice' ),
                                'label'   => __( 'A Dropdown', 'wedevs' ),
                                'desc'    => __( 'Dropdown description', 'wedevs' ),
                                'type'    => 'select',
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
                                'id'   => 'show_qty'
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
                                'name' => __( 'Thankyou Note ', 'woocommerce-invoice' ),
                                'type' => 'textarea',
                                'desc' => __( 'This is some helper text', 'woocommerce-invoice' ),
                                'desc_tip' => 'Hmm is Working',
                                'id'   => 'thankyou_note'
                            ),


                            'section_end' => array(
                                 'type' => 'sectionend',
                                 'id' => 'wc_settings_tab_demo_section_end'
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
                                 'name' => __( 'Show Billing Address in Paking List', 'woocommerce-invoice' ),
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


    			

    			// Function Ends Heare 

    			
    			# Adding External PDF Layout Link Starts
    			public function viewinps($value=''){
    				require_once( plugin_dir_path( __FILE__ ) . '/invoice-8.php');	
    				
    			}
    			# Adding External PDF Layout Link Ends



    			# Adding Settings Page And Menu item Starts

    			function invoice_paking_init() {
    				// add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
    			    add_submenu_page( 'woocommerce', 'Invoice And Paking List ', 'Invoice & paking list', 'manage_options', 'Invoice_Paking', array($this, 'admin_page_layout' ) ); 
    			}

                public function admin_page_layout($value=''){
                    ?>
                        <div class="wrap">
                            <div id="icon-options-general" class="icon32"></div>
                            <h1>Theme Options</h1>
                            <form method="post" action="options.php">
                                <?php
                                
                                    //add_settings_section callback is displayed here. For every new section we need to call settings_fields.
                                    settings_fields("header_section");
                                    
                                    // all the add_settings_field callbacks is displayed here
                                    do_settings_sections("theme-options");
                                
                                    // Add the submit button to serialize the options
                                    submit_button(); 
                                    
                                ?>          
                            </form>
                        </div>
                    <?php
                }

    			

    			# Adding Settings Page And Menu item Ends
    			//this action callback is triggered when wordpress is ready to add new items to menu.

    			    // add_action("admin_menu", "add_new_menu_items");


    			    /*WordPress Settings API Demo*/
                    // Help text 
                    // https://code.tutsplus.com/series/the-complete-guide-to-the-wordpress-settings-api--cms-624
                    // http://wpsettingsapi.jeroensormani.com/
                    // http://qnimate.com/wordpress-settings-api-a-comprehensive-developers-guide/ 

    			    function display_options()
    			    {
    			        //section name, display name, callback to print description of section, page to which section is attached.
    			        add_settings_section("header_section", "Header Options", array($this,"display_header_options_content"), "theme-options");

    			        //setting name, display name, callback to print form element, page in which field is displayed, section to which it belongs.
    			        //last field section is optional.
                        #add_settings_field( $id,       $title,         $callback,                           $page,              $section,      $args );
    			        // add_settings_field("header_logo", "Logo Url",array($this, "display_logo_form_element"), "theme-options", "header_section");
                        // add_settings_field("advertising_code", "Ads Code", array($this,"display_ads_form_element"), "theme-options", "header_section");
                        // My Personal Fields 
                        add_settings_field("wcip_themeselect", "Select theme ", array($this,"wcip_themeselect_dropdown_form_element"), "theme-options", "header_section");
                        add_settings_field("wcip_theme_colour", "Invoice Primari colour", array($this,"wcip_theme_colour_form_element"), "theme-options", "header_section");

                        add_settings_field("wcip_invoice_logo", "Logo URL ", array($this,"wcip_invoice_logo_form_element"), "theme-options", "header_section");
                        add_settings_field("wcip_paper_size", " PDF Paper Size ", array($this,"wcip_paper_size_form_element"), "theme-options", "header_section");
                        add_settings_field("wcip_click", " Button Click Action ", array($this,"wcip_click_downloard_show_form_element"), "theme-options", "header_section");
                        add_settings_field("wcip_show_vat", "Show Vat On Invoice ", array($this,"wcip_show_vat_form_element"), "theme-options", "header_section");
                        add_settings_field("wcip_show_shipping_add", " Show Shipping Address  ", array($this,"wcip_show_shipping_address_form_element"),"theme-options", "header_section");
                        add_settings_field("wcip_show_thumbnail", "Show Thumbnail ", array($this,"wcip_show_thumbnail_form_element"), "theme-options", "header_section");
                        add_settings_field("wcip_show_qty", "show Quntaty ", array($this,"wcip_show_qty_form_element"), "theme-options", "header_section");
                        add_settings_field("wcip_show_discout", "Show Discout ", array($this,"wcip_show_discout_form_element"), "theme-options", "header_section");
                        add_settings_field("wcip_show_order_note","Show Order Note",array($this,"wcip_show_order_note_form_element"),"theme-options","header_section");
    			        add_settings_field("wcip_thankyou_note","Thankyou Note",array($this,"wcip_thankyou_note_form_element"),"theme-options","header_section");


    			        //section name, form element name, callback for sanitization

    			        // register_setting("header_section", "header_logo");
               //          register_setting("header_section", "advertising_code");

                        // My Personal Fields 
                        register_setting("header_section", "wcip_themeselect");
                        register_setting("header_section", "wcip_theme_colour");
                        register_setting("header_section", "wcip_invoice_logo");
                        register_setting("header_section", "wcip_paper_size");
                        register_setting("header_section", "wcip_click");
                        register_setting("header_section", "wcip_show_vat");
                        register_setting("header_section", "wcip_show_shipping_add");
                        register_setting("header_section", "wcip_show_thumbnail");
                        register_setting("header_section", "wcip_show_qty");
                        register_setting("header_section", "wcip_show_discout");
                        register_setting("header_section", "wcip_show_order_note");
    			        register_setting("header_section", "wcip_thankyou_note");
    			    }

    			    function display_header_options_content(){
    			    	echo "The header of the theme";
    			    }

    			    function display_logo_form_element()
    			    {
    			        //id and name of form element should be same as the setting name.
    			        ?>
    			            <input type="text" name="header_logo" id="header_logo" value="<?php echo get_option('header_logo'); ?>" />
    			        <?php
    			    }


    			    function display_ads_form_element()
    			    {
    			        //id and name of form element should be same as the setting name.
    			        ?>
    			            <input type="text" name="advertising_code" id="advertising_code" value="<?php echo get_option('advertising_code'); ?>" />
    			        <?php
    			    }

                    // My Fields  $$$$$$$$$$$$$$$$$$$$$

                    function wcip_themeselect_dropdown_form_element(){
                        //id and name of form element should be same as the setting name.

                        // $selected_option =   get_option('wcip_themeselect') ?

                        // $selected_option =   (isset(get_option('wcip_themeselect')) ? get_option('wcip_themeselect') : false);
                        $selected_option =   (!is_null(get_option('wcip_themeselect')) ? get_option('wcip_themeselect') : false);
                        $theme = array('1'=> 'Basic' ,'2'=> 'Modern' ,'3'=> 'hmm' ,'4'=> 'Lates' ,'5'=> 'javmah' );

                        if ($selected_option) {
                           echo '<select name="wcip_themeselect">';
                           foreach ($theme as $key => $value) {
                                if ($key == $selected_option) {
                                    echo "<option value='".$key."' selected >" . $value. " </option>" ;
                                }else{
                                 echo "<option value='".$key."'>" . $value. " </option>" ;
                                }
                           }
                           echo '</select>';

                        }else{
                            echo '<select name="wcip_themeselect">';
                            foreach ($theme as $key => $value) {
                               echo "<option value='".$key."'>" . $value. " </option>" ;
                            }
                            echo '</select>';
                        }  
                    }

                    function wcip_theme_colour_form_element()
                    {
                        //id and name of form element should be same as the setting name.
                        ?>
                            <input type="text" name="wcip_theme_colour" id="advertising_code" value="<?php echo get_option('advertising_code'); ?>" />
                        <?php
                    }

                    # Logo Text fields 
                    function wcip_invoice_logo_form_element(){
                        //id and name of form element should be same as the setting name.
                        ?>
                            <input type="text" name="wcip_invoice_logo" id="advertising_code" value="<?php echo get_option('advertising_code'); ?>" />
                        <?php
                    }

                    # PDF Paper Size
                    function wcip_paper_size_form_element(){
                        //id and name of form element should be same as the setting name.
                        ?>
                            <select name="wcip_paper_size">
                              <option value="a4">A4</option>
                              <option value="a5">A5</option>
                            </select>
                        <?php
                    }

                    function wcip_click_downloard_show_form_element(){
                        //id and name of form element should be same as the setting name.
                        ?>
                            
                            <select name="wcip_click">
                              <option value="a4">Show in New Window </option>
                              <option value="a5">Downloard</option>
                            </select>
                        <?php
                    }

                    function wcip_show_vat_form_element(){
                        //id and name of form element should be same as the setting name.
                        ?>
                            <select>
                              <option value="volvo">Volvo</option>
                              <option value="saab">Saab</option>
                              <option value="mercedes">Mercedes</option>
                              <option value="audi">Audi</option>
                            </select>
                        <?php
                    }

                    function wcip_show_shipping_address_form_element(){
                        //id and name of form element should be same as the setting name.
                        ?>
                            
                            <input type="checkbox" name="vehicle" value="Bike">
                        <?php
                    }


                    function wcip_show_thumbnail_form_element(){
                        //id and name of form element should be same as the setting name.
                        ?>
                            <input type="checkbox" name="vehicle" value="Bike"> 
                        <?php
                    }

                    function wcip_show_qty_form_element(){
                        //id and name of form element should be same as the setting name.
                        ?>
                            <input type="checkbox" name="vehicle" value="Bike">
                        <?php
                    }

                    function wcip_show_discout_form_element(){
                        //id and name of form element should be same as the setting name.
                        ?>
                           <input type="checkbox" name="vehicle" value="Bike">
                        <?php
                    }

                   function wcip_show_order_note_form_element(){
                       //id and name of form element should be same as the setting name.
                       ?>
                          <input type="checkbox" name="vehicle" value="Bike">
                       <?php
                   }

                   function wcip_thankyou_note_form_element(){
                       //id and name of form element should be same as the setting name.
                       ?>
                          <input type="text" name="wcip_thankyou_note" value="THANK YOU FOR YOUR BUSINESS">
                       <?php
                   }


    			   
    			

    		}

    		$GLOBALS['wc_example'] = new Wcip() ;




    		# Extension main Code  Eends Here 
    	}

    }






