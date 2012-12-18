<?php
/*
Plugin Name: Woocommerce Dropdown Cart Widget.
Plugin URI: http://www.chromeorange.co.uk/
Description: Subtly modifies the Woocommerce Cart Widget and makes it dropdown - nice in the header see :) If you need any support with this plugin (but not CSS support) please email plugins@chromeorange.co.uk (NOTE : this plugin requires WooCommerce to work!)
Version: 1.1
Author: Andrew Benbow
Author URI: http://www.chromeorange.co.uk

	Copyright: © 2009-2011 Andrew Benbow.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
 	
	Copy the CSS and 'open.png' file to your theme directory to modify the layout
	eg wp-content/themes/YOUR_THEME/woocommerce-dropdown-cart/widget-flyout-cart-style.css
	and wp-content/themes/YOUR_THEME/woocommerce-dropdown-cart/open.png
*/
class WooCommerce_Widget_DropdownCart extends WP_Widget {
	
	/** Variables to setup the widget. */
	var $woo_widget_cssclass;
	var $woo_widget_description;
	var $woo_widget_idbase;
	var $woo_widget_name;

	/** constructor */
	function WooCommerce_Widget_DropdownCart() {
	
		/* Widget variable settings. */
		$this->woo_widget_cssclass 		= 'widget_dropdown_cart';
		$this->woo_widget_description 	= __( 'Display the users Shopping Cart in the header/sidebar.', 'woothemes' );
		$this->woo_widget_idbase 		= 'widget_dropdown_cart';
		$this->woo_widget_name 			= __('WooCommerce Drop Down Shopping Cart', 'woothemes' );
		
		/* Widget settings. */
		$widget_ops = array( 'classname' => $this->woo_widget_cssclass, 'description' => $this->woo_widget_description );
		
		/* Create the widget. */
		$this->WP_Widget('dropdown_shopping_cart', $this->woo_widget_name, $widget_ops);
	}

	/** @see WP_Widget */
	function widget( $args, $instance ) {
		global $woocommerce;
	
		if (is_cart()) return;
		
		extract($args);
		$title = $instance['title'];
		$title = apply_filters('widget_title', $title, $instance, $this->id_base);

		echo $before_widget;
		if ( $title ) echo $before_title . $title . $after_title;

		
		echo '<div id="dropdowncart">' . "\r\n";
		echo '<div class="dropdowntriggerarea">' . "\r\n";
		echo '<span>' . "\r\n";
				 	
		echo sprintf( _n('%d item &ndash; ', 
						 '%d items &ndash; ',  
						 sizeof($woocommerce->cart->cart_contents), 'woocommerce'),  
						 sizeof($woocommerce->cart->cart_contents) 
					);						
		echo $woocommerce->cart->get_cart_total();

		echo '</span> <a href="/cart/" class="dropdowncarttrigger" title="open"><span class="open"></span></a>' . "\r\n";
		echo '</div>' . "\r\n";
		echo '<div class="dropdowncartcontents">' . "\r\n";
		
						echo '<ul class="cart_list">';

	if (sizeof($woocommerce->cart->cart_contents)>0) : 
		$i = 0;					
		foreach ($woocommerce->cart->cart_contents as $cart_item_key => $cart_item) :
			
			$i++;
			if ( $i == 1 ) :				
				$rowclass = ' class="cart_oddrow"';			
			else :
				$rowclass = ' class="cart_evenrow"';
				$i = 0;
			endif;
	
			$_product = $cart_item['data'];
			
			if ($_product->exists() && $cart_item['quantity']>0) :
				echo '<li'.$rowclass.'>';
				
				echo '<div class="dropdowncartimage">';
				echo '<a href="'.get_permalink($cart_item['product_id']).'">';				
				if (has_post_thumbnail($cart_item['product_id'])) :					
					echo get_the_post_thumbnail($cart_item['product_id'], 'shop_thumbnail'); 
				else :					 
					echo '<img src="'.$woocommerce->plugin_url(). '/assets/images/placeholder.png" alt="Placeholder" width="'.$woocommerce->get_image_size('shop_thumbnail_image_width').'" height="'.$woocommerce->get_image_size('shop_thumbnail_image_height').'" />'; 				
				endif;				
				echo '</a>';
				echo '</div>';
				
				echo '<div class="dropdowncartproduct">';
				echo '<a href="'.get_permalink($cart_item['product_id']).'">';				
				echo apply_filters('woocommerce_cart_widget_product_title', $_product->get_title(), $_product).'</a>';				
				if ($_product instanceof woocommerce_product_variation && is_array($cart_item['variation'])) :
        			echo woocommerce_get_formatted_variation( $cart_item['variation'] );
   				endif;
				echo '</a>';
				echo '</div>';
				
				echo '<div class="dropdowncartquantity">';				
				echo '<span class="quantity">' .$cart_item['quantity'].' &times; '.woocommerce_price($_product->get_price()).'</span>';
				echo '</div>';
				echo '<div class="clear"></div>';
				
				echo '</li>';
				
			endif;
		endforeach; 
	else: 
		echo '<li class="empty">'.__('No products in the cart.', 'woothemes').'</li>'; 
	endif;
		
	echo '</ul>';
		
	if (sizeof($woocommerce->cart->cart_contents)>0) :
		
		echo '<p class="total"><strong>';
			
		if (get_option('js_prices_include_tax')=='yes') :
			_e('Total', 'woothemes');
		else :
			_e('Subtotal', 'woothemes');
		endif;
	
		echo ':</strong> '.$woocommerce->cart->get_cart_total();
			
		echo '</p>';
			
		do_action( 'woocommerce_widget_shopping_cart_before_buttons' );
			
		echo '<p class="buttons">
			  <a href="'.$woocommerce->cart->get_cart_url().'" class="dropdownbutton">'.__('View Cart &rarr;', 'woothemes').'</a> 
			  <a href="'.$woocommerce->cart->get_checkout_url().'" class="dropdownbutton checkout">'.__('Checkout &rarr;', 'woothemes').'</a>
			  </p>';
	endif;
	
    	echo '</div>' . "\r\n";
		echo '</div>' . "\r\n";

		echo $after_widget;
	}

	/** @see WP_Widget->update */
	function update( $new_instance, $old_instance ) {
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		return $instance;
	}

	/** @see WP_Widget->form */
	function form( $instance ) {
	?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'woothemes') ?></label>
	<input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php if (isset ( $instance['title'])) {echo esc_attr( $instance['title'] );} ?>" /></p>
	<?php
	}

} // class WooCommerce_Widget_DropdownCart

// REGISTER THE WIDGET
	function woocomm_dropdowncart_load_widgets() {
		
  		register_widget('WooCommerce_Widget_DropdownCart');
				
	}
	add_action('widgets_init', 'woocomm_dropdowncart_load_widgets');
	
	// Load the CSS
	add_action('wp_print_styles', 'dropdowncart_stylesheet',0);
	// Load the jScript
	add_action('wp_enqueue_scripts', 'dropdowncart_scripts',0);
	/**
	 * Load the CSS
	 **/

	function dropdowncart_stylesheet() {
		
		// Respects SSL, Style.css is relative to the current file
	       $dropdowncart_stylesheet_url  = plugins_url('widget-flyout-cart-style.css', __FILE__); 		   
		   $theme_stylesheet_file		 = get_stylesheet_directory() . '/woocommerce-drop-down-cart-widget/widget-flyout-cart-style.css';
		   $theme_stylesheet_url		 = get_stylesheet_directory_uri() . '/woocommerce-drop-down-cart-widget/widget-flyout-cart-style.css'; 
		   
		   
		   $css = file_exists($theme_stylesheet_file) ? $theme_stylesheet_url : $dropdowncart_stylesheet_url;				
		   wp_register_style('dropdowncart_stylesheets', $css);
	       wp_enqueue_style( 'dropdowncart_stylesheets');
		   

		
	} // END dropdowncart_stylesheet

	/**
	 * Load the jscript
	 **/	
	function dropdowncart_scripts() {
		
		// Respects SSL, Style.css is relative to the current file
	       $dropdowncart_jscript_url  = plugins_url('widget-flyout-cart-script.js', __FILE__); 
	       $dropdowncart_jscript_file = WP_PLUGIN_DIR . '/woocommerce-drop-down-cart-widget/widget-flyout-cart-script.js';
	
		   if ( file_exists( $dropdowncart_jscript_file ) ) :
			
		   // register your script location, dependencies and version
		   wp_register_script('dropdown_cart',
		    					$dropdowncart_jscript_url,
		  						array('jquery'),
		    					'',
								'' );
		  // enqueue the script
		  wp_enqueue_script('dropdown_cart');
			
		  endif;
					
	} // END dropdowncart_scripts