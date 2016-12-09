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
	 * Copyright: © 2009-2015 WooCommerce.
	 * License: GNU General Public License v3.0
	 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
	 */

	
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if woocrop is accessed directly.
	}

	define( 'WOOCROP_PATH', plugin_dir_path(__FILE__) );
    
    //necesary activate, deactivate and uninstall functions.
	function woocrop_activation(){
		require_once (WOOCROP_PATH . 'includes/class_woocrop_activator.php');
		WooCrop_Activator::activate();
	}

	function woocrop_deactivation(){
		require_once (WOOCROP_PATH . 'includes/class_woocrop_deactivator.php');
		WooCrop_Deactivator::deactivate();
	}

	function woocrop_uninstall(){
		require_once (WOOCROP_PATH . 'includes/class_woocrop_uninstall.php');
		WooCrop_Uninstall::uninstall();
	}

	register_activation_hook( __FILE__, 'woocrop_activation' );
    register_deactivation_hook( __FILE__, 'woocrop_deactivation' );
    register_uninstall_hook(__FILE__, 'woocrop_uninstall' );

    //Create woocommerce settings tab.
    require_once(WOOCROP_PATH. 'includes/class_woocrop_woocommerce_settings_tab.php');
	
    //Error logging
    function log_me($message) {
        if (WP_DEBUG === true) {
            if (is_array($message) || is_object($message)) {
                error_log(print_r($message, true), 3, WOOCROP_PATH . '/errors.log');
            } else {
                error_log($message, 3, WOOCROP_PATH . '/error.log');
            }
        }
    }

	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	    add_action('woocommerce_after_single_product', 'woocrop_add_crop_area');
	    function woocrop_add_crop_area(){
	    	if(is_product('8'))	{
	    		?>
	    		
		        <?php
	    	}
	    }
	}
?>