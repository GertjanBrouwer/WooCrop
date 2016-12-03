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

	//Don't allow direct calls to woocrop.php file
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly.
	}

	//Checks if woocommerce is active
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	    //Puts the crop area in the right location
	    add_action('woocommerce_after_single_product', 'woocrop_add_crop_area');
	    function woocrop_add_crop_area(){
	    	//Product page(s) where crop area needs to be displayed
	    	if(is_product('8'))	{
	    		?>
	    		<div id="woocrop_value_area">
		    		<!-- These inputs hold values for cropping -->
		    		<!-- <input type="hidden" id="price" name="price" /> -->
				    <input type="hidden" id="woocrop_min_width" name="min_width" value="" />
				    <input type="hidden" id="woocrop_min_height" name="min_height" value="" />
				    <input type="hidden" id="woocrop_thumbnail_width" name="thumbnail_width" value="" />
				    <input type="hidden" id="woocrop_thumbnail_height" name="thumbnail_height" value="" />
				    <input type="hidden" id="woocrop_orig_width" name="orig_width" value="" />
				    <input type="hidden" id="woocrop_orig_height" name="orig_height" value="" />
				    <input type="hidden" id="woocrop_achtergrond_link" name="achtergrond_link" value="" />

				    <!-- These inputs hold the location of the crop section for calculating the thumbnail see function showPreview-->
				    <input type="hidden" id="woocrop_x1" name="woocrop_x1" />
			        <input type="hidden" id="woocrop_y1" name="woocrop_y1" />
			        <input type="hidden" id="woocrop_x2" name="woocrop_x2" />
			        <input type="hidden" id="woocrop_y2" name="woocrop_y2" />
			        <input type="hidden" id="woocrop_w" name="woocrop_width" />
			        <input type="hidden" id="woocrop_h" name="woocrop_height" />
			    </div>
			    <div id="woocrop_crop_area">
			    	<input type="file" name="image_file" id="upload-image" accept="image/*" />
			        <input type="button" value="Kies afbeelding" id="image_event" onclick="document.getElementById('upload-image').click();" />
			    </div>
		        <?php
	    	}
	    }
	}
?>