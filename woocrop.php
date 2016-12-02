<?php
	/*
	 * Plugin Name: WooCrop
	 * Plugin URI: https://github.com/GertjanBrouwer/WooCrop
	 * Description: Allows
	 * Version: 1.0.0
	 * Author: WooCommerce
	 * Author URI: http://woocommerce.com/
	 * Developer: Gertjan Brouwer
	 * Developer URI: https://github.com/GertjanBrouwer/
	 * Text Domain: woocrop
	 * Domain Path: /languages
	 *
	 * Copyright: © 2009-2015 WooCommerce.
	 * License: GNU General Public License v3.0
	 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
	 */

	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	    
	    // function save_name_on_tshirt_field( $cart_item_data, $product_id ) {
	    // 	global $woocommerce;
	    //     if( isset( $_COOKIE['prijs_cookie'] ) && $product_id == 222) {
	    //         $cart_item_data['prijs_voor_product'] = $_COOKIE['prijs_opslag'];
	    //          below statement make sure every add to cart action as unique line item 
	    //         $cart_item_data['unique_key'] = md5( microtime().rand() );
	    //     }
	    //     return $cart_item_data;
	    // }
	    // add_action( 'woocommerce_add_cart_item_data', 'save_name_on_tshirt_field', 10, 2 );

		// function woo_add_prijs() {
		//     global $woocommerce;

		//     foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
		//         if($cart_item['data']->id == 222 && ! empty($_COOKIE['prijs_cookie'])){
		//             $cart_item['data']->set_price($_COOKIE['prijs_cookie']);
		//         }
		//     }
		// }

		// add_action( 'woocommerce_before_calculate_totals', 'woo_add_prijs', 10, 0);

	    function save_gift_wrap_fee( $cart_item_data, $product_id ) {
	         
	        if( isset( $_COOKIE['prijs_cookie'] ) && $product_id == 222 ) {
	        	$prijs_voor_productVar = $_COOKIE['prijs_cookie'];
	        	$afbeelding_url_productVar = $_COOKIE['afbeelding_url'];
	            $cart_item_data[ "prijs_voor_product" ] = $prijs_voor_productVar;  
	            $cart_item_data[ "afbeelding_url_product" ] =  $afbeelding_url_productVar; 
	        }
	        return $cart_item_data;
	         
	    }
	    add_filter( 'woocommerce_add_cart_item_data', 'save_gift_wrap_fee', 99, 2 );

	    function calculate_prijs($cart_object) {
	    	global $woocommerce;  
	        if( !WC()->session->__isset( "reload_checkout" )) {
	            //$additionalPrice = $_COOKIE['prijs_cookie'];
	            //echo($_COOKIE['prijs_cookie']);
	            foreach ( $cart_object->cart_contents as $key => $value ) {
	                if( isset( $value["prijs_voor_product"] ) ) {
	                	$orgPrice = $value["prijs_voor_product"];
	                    $value['data']->price = ( $orgPrice );
	                    //echo($additionalPrice);
	                }
	                else{
	                	//echo("geen cart");
	                }
	            }   
	        }   
	    }
	    add_action( 'woocommerce_before_calculate_totals', 'calculate_prijs', 99, 1);

	    function render_meta_on_cart_and_checkout( $cart_data, $cart_item = null ) {
	        $custom_items = array();
	        /* Woo 2.4.2 updates */
	        if( !empty( $cart_data ) ) {
	            $custom_items = $cart_data;
	        }
	        if( isset( $cart_item['afbeelding_url_product'] ) ) {
	            $custom_items[] = array( "name" => 'Afbeelding', "value" => $cart_item['afbeelding_url_product'] );
	        }
	        return $custom_items;
	    }
	    add_filter( 'woocommerce_get_item_data', 'render_meta_on_cart_and_checkout', 10, 2 );
	}
?>