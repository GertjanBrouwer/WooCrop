<?php
	function woocrop_woocommerce_add_section( $sections ) {
		$sections['woocrop'] = 'WooCrop';
		return $sections;
	}

	function woocrop_add_settings( $settings, $current_section ) {
		if ( $current_section == 'woocrop' ) {
			$settings_slider = array();

			$settings_slider[] = array( 
				'name' => 'WC Slider Settings',
				'type' => 'title', 
				'desc' => 'The following options are used to configure WC Slider',
				'id' => 'woocrop' 
			);
			return $settings_slider;
		} else {
			return $settings;
		}
	}
	
	add_filter( 'woocommerce_get_sections_products', 'woocrop_woocommerce_add_section' );
	add_filter( 'woocommerce_get_settings_products', 'woocrop_add_settings', 10, 2 );
?>