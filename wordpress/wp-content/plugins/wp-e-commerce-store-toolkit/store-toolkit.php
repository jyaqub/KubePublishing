<?php
/*
Plugin Name: WP e-Commerce - Store Toolkit
Plugin URI: http://www.visser.com.au/wp-ecommerce/plugins/store-toolkit/
Description: Permanently remove all store-generated details of your WP e-Commerce store.
Version: 1.8.8
Author: Visser Labs
Author URI: http://www.visser.com.au/about/
License: GPL2
*/

load_plugin_textdomain( 'wpsc_st', null, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

include_once( 'includes/functions.php' );

include_once( 'includes/common.php' );

switch( wpsc_get_major_version() ) {

	case '3.7':
		include_once( 'includes/release-3_7.php' );
		break;

	case '3.8':
		include_once( 'includes/release-3_8.php' );
		break;

}

$wpsc_st = array(
	'filename' => basename( __FILE__ ),
	'dirname' => basename( dirname( __FILE__ ) ),
	'abspath' => dirname( __FILE__ ),
	'relpath' => basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ )
);

$wpsc_st['prefix'] = 'wpsc_st';
$wpsc_st['name'] = __( 'Store Toolkit for WP e-Commerce', 'wpsc_st' );
$wpsc_st['menu'] = __( 'Store Toolkit', 'wpsc_st' );

if( is_admin() ) {

	/* Start of: WordPress Administration */

	function wpsc_st_add_settings_link( $links, $file ) {

		static $this_plugin;
		if( !$this_plugin ) $this_plugin = plugin_basename( __FILE__ );
		if( $file == $this_plugin ) {
			/* Manage */
			$manage_link = '<a href="' . add_query_arg( 'page', 'wpsc_st', 'options-general.php' ) . '">' . __( 'Settings', 'wpsc_st' ) . '</a>';
			array_unshift( $links, $manage_link );
			/* Settings */
			$settings_link = '<a href="' . add_query_arg( array( 'post_type' => 'wpsc-product', 'page' => 'wpsc_st-toolkit' ), 'edit.php' ) . '">' . __( 'Manage', 'wpsc_st' ) . '</a>';
			array_unshift( $links, $settings_link );
		}
		return $links;

	}
	add_filter( 'plugin_action_links', 'wpsc_st_add_settings_link', 10, 2 );

	function wpsc_st_init() {

		global $wpdb, $wpsc_st;

		$action = wpsc_get_action();
		switch( $action ) {

			case 'relink-pages':
				$productpage_sql = "SELECT `ID` FROM `" . $wpdb->posts . "` WHERE `post_content` = '[productspage]' AND `post_status` = 'publish' AND `post_type` = 'page' LIMIT 1";
				$productpage = $wpdb->get_var( $productpage_sql );
				$checkout_sql = "SELECT `ID` FROM `" . $wpdb->posts . "` WHERE `post_content` = '[shoppingcart]' AND `post_status` = 'publish' AND `post_type` = 'page' LIMIT 1";
				$checkout = $wpdb->get_var( $checkout_sql );
				$transactionresults_sql = "SELECT `ID` FROM `" . $wpdb->posts . "` WHERE `post_content` = '[transactionresults]' AND `post_status` = 'publish' AND `post_type` = 'page' LIMIT 1";
				$transactionresults = $wpdb->get_var( $transactionresults_sql );
				$account_sql = "SELECT `ID` FROM `" . $wpdb->posts . "` WHERE `post_content` = '[userlog]' AND `post_status` = 'publish' AND `post_type` = 'page' LIMIT 1";
				$account = $wpdb->get_var ( $account_sql );
				if( $productpage )
					update_option( 'product_list_url', get_bloginfo( 'url' ) . "/?page_id=" . $productpage );
				if( $checkout )
					update_option( 'shopping_cart_url', get_bloginfo( 'url' ) . "/?page_id=" . $checkout );
				if( $checkout )
					update_option( 'checkout_url', get_bloginfo( 'url' ) . "/?page_id=" . $checkout );
				if( $transactionresults )
					update_option( 'transact_url', get_bloginfo( 'url' ) . "/?page_id=" . $transactionresults );
				if( $account )
					update_option( 'user_account_url', get_bloginfo( 'url' ) . "/?page_id=" . $account );
				break;

			case 'relink-existing-preregistered-sales':
				$sales_sql = "SELECT `id` as ID FROM `" . $wpdb->prefix . "wpsc_purchase_logs` WHERE `user_ID` = 0";
				$sales = $wpdb->get_results( $sales_sql );
				$total_sales = $wpdb->num_rows;
				if( $sales ) {
					$adjusted_sales = 0;
					foreach( $sales as $sale ) {
						$sale_email_sql = "SELECT wpsc_submited_form_data.`value` 
						FROM `" . $wpdb->prefix . "wpsc_checkout_forms` as wpsc_checkout_forms, `" . $wpdb->prefix . "wpsc_submited_form_data` as wpsc_submited_form_data WHERE wpsc_checkout_forms.`id` = wpsc_submited_form_data.`form_id` AND wpsc_checkout_forms.`checkout_set` = '0' AND wpsc_checkout_forms.`type` = 'email' AND wpsc_submited_form_data.`log_id` = " . $sale->ID . " LIMIT 1";
						$sale_email = $wpdb->get_var( $sale_email_sql );
						if( $sale_email ) {
							$sale_user_sql = "SELECT `ID` FROM `" . $wpdb->users . "` WHERE `user_email` = '" . $sale_email . "' LIMIT 1";
							$sale_user = $wpdb->get_var( $sale_user_sql );
							if( $sale_user ) {
								$wpdb->update( $wpdb->prefix . 'wpsc_purchase_logs', array(
									'user_ID' => $sale_user
								), array( 'id' => $sale->ID ) );
							}
							$adjusted_sales++;
						}
					}
				}
				if( $adjusted_sales > 0 )
					$message = '<strong>' . $adjusted_sales . '</strong>' . __( ' of ', 'wpsc_st' ) . '<strong>' . $total_sales . '</strong>' . __( ' unlinked Sale\'s from pre-registered Users have been re-linked.', 'wpsc_st' );
				else
					$message = __( 'No existing Sales from pre-registered Users have been re-linked.', 'wpsc_st' );
				$output = '<div class="updated settings-error"><p>' . $message . '</p></div>';
				echo $output;
				break;

			case 'tools':
				$options = array();
				$options['demo_store'] = $_POST['demo_store'];
				$options['demo_store_text'] = $_POST['demo_store_text'];
				foreach( $options as $key => $option )
					wpsc_st_update_option( $key, $option );

				break;

			case 'nuke':
				if( !ini_get( 'safe_mode' ) )
					set_time_limit( 0 );

				if( isset( $_POST['wpsc_st_products'] ) )
					wpsc_st_clear_dataset( 'products' );
				if( isset( $_POST['wpsc_st_product_variations'] ) )
					wpsc_st_clear_dataset( 'variations' );
				if( isset( $_POST['wpsc_st_product_tags'] ) )
					wpsc_st_clear_dataset( 'tags' );
				if( isset( $_POST['wpsc_st_product_categories'] ) )
					wpsc_st_clear_dataset( 'categories' );
				if( isset( $_POST['wpsc_st_product_images'] ) )
					wpsc_st_clear_dataset( 'images' );
				if( isset( $_POST['wpsc_st_product_files'] ) )
					wpsc_st_clear_dataset( 'files' );
				if( isset( $_POST['wpsc_st_sales_orders'] ) )
					wpsc_st_clear_dataset( 'orders' );

				if( isset( $_POST['wpsc_st_coupons'] ) )
					wpsc_st_clear_dataset( 'coupons' );
				if( isset( $_POST['wpsc_st_wishlist'] ) )
					wpsc_st_clear_dataset( 'wishlist' );
				if( isset( $_POST['wpsc_st_enquiries'] ) )
					wpsc_st_clear_dataset( 'enquiries' );
				if( isset( $_POST['wpsc_st_creditcards'] ) )
					wpsc_st_clear_dataset( 'credit-cards' );
				if( isset( $_POST['wpsc_st_customfields'] ) )
					wpsc_st_clear_dataset( 'custom-fields' );

				if( isset( $_POST['wpsc_st_categories'] ) ) {
					$categores = $_POST['wpsc_st_categories'];
					wpsc_st_clear_dataset( 'categories', $categores );
				}

				if( isset( $_POST['wpsc_st_posts'] ) )
					wpsc_st_clear_dataset( 'posts' );
				if( isset( $_POST['wpsc_st_post_categories'] ) )
					wpsc_st_clear_dataset( 'post_categories' );
				if( isset( $_POST['wpsc_st_post_tags'] ) )
					wpsc_st_clear_dataset( 'post_tags' );
				if( isset( $_POST['wpsc_st_links'] ) )
					wpsc_st_clear_dataset( 'links' );
				if( isset( $_POST['wpsc_st_comments'] ) )
					wpsc_st_clear_dataset( 'comments' );

				break;

		}

	}
	add_action( 'admin_init', 'wpsc_st_init' );

	function wpsc_st_enqueue_scripts( $hook ) {

		/* Settings */
		$page = 'settings_page_wpsc_st';
		if( $page == $hook ) {
			/* Color Picker */
			wp_register_script( 'colorpicker', plugins_url( '/js/colorpicker.js', __FILE__ ), array( 'jquery' ) );
			wp_enqueue_script( 'colorpicker' );
			wp_enqueue_style( 'colorpicker', plugins_url( '/templates/admin/colorpicker.css', __FILE__ ) );

			/* Common */
			wp_enqueue_script( 'wpsc_st_scripts', plugins_url( '/templates/admin/wpsc-admin_st-settings.js', __FILE__ ), array( 'jquery' ) );
		}

		/* Manage Sales */
		$pages = array( 'dashboard_page_wpsc-purchase-logs', 'wpsc-product_page_wpsc_st-toolkit' );
		if( in_array( $hook, $pages ) ) {
			wp_enqueue_style( 'wpsc_st_styles', plugins_url( '/templates/admin/wpsc-admin_st-toolkit.css', __FILE__ ) );
		}

	}
	add_action( 'admin_enqueue_scripts', 'wpsc_st_enqueue_scripts' );

	function wpsc_st_store_admin_menu() {

		add_submenu_page( 'wpsc_sm', __( 'Store Toolkit', 'wpsc_st' ), __( 'Store Toolkit', 'wpsc_st' ), 'manage_options', 'wpsc_st', 'wpsc_st_html_toolkit' );
		remove_filter( 'wpsc_additional_pages', 'wpsc_st_add_modules_manage_pages', 10 );
		remove_action( 'admin_menu', 'wpsc_st_admin_menu', 10 );

	}
	add_action( 'wpsc_sm_store_admin_subpages', 'wpsc_st_store_admin_menu' );

	function wpsc_st_default_file_downloads_html_page() {

		global $wpsc_st, $wpdb;

		$post_type = 'wpsc-product-file';

		$mime_types_all = new stdClass;
		$mime_types_all->post_mime_type = __( 'All', 'wpsc_st' );
		$mime_types_count = wp_count_posts( $post_type );
		$mime_types_all->count = $mime_types_count->inherit;
		if( $mime_types_all->count ) {

			$current_post_mime = false;
			if( isset( $_GET['post_mime_type'] ) ) {
				$current_post_mime = $_GET['post_mime_type'];
				if( $current_post_mime == 'all' )
					$current_post_mime = false;
			}

			if( $current_post_mime ) {
				$args = array(
					'post_type' => $post_type,
					'post_mime_type' => wpsc_st_format_post_mime_type_filter( $current_post_mime, 'expand' ),
					'post_status' => 'inherit',
					'numberposts' => -1
				);
			} else {
				$args = array(
					'post_type' => $post_type,
					'post_status' => 'inherit',
					'numberposts' => -1
				);
			}

			$files = get_posts( $args );

			$mime_types_sql = "SELECT `post_mime_type`, COUNT(DISTINCT `post_mime_type`) as count FROM `" . $wpdb->posts . "` WHERE `post_type` = '" . $post_type . "' GROUP BY `post_mime_type`";
			$mime_types = $wpdb->get_results( $mime_types_sql );

			$show_mime_ext = true;
			if( $mime_types ) {
				$i = 1;
				array_unshift( $mime_types, $mime_types_all );
				$size = count( $mime_types );
				foreach( $mime_types as $key => $mime_type ) {
					$mime_types[$key]->current = false;
					if( empty( $mime_type->post_mime_type ) ) {
						$mime_types[$key]->filter = 'other';
						$mime_types[$key]->post_mime_type = __( 'N/A', 'wpsc_st' );
					} else if( $mime_type->post_mime_type == __( 'All', 'wpsc_st' ) ) {
						if( !$current_post_mime )
							$mime_types[$key]->current = true;
						$mime_types[$key]->filter = 'all';
					} else {
						$mime_types[$key]->filter = wpsc_st_format_post_mime_type_filter( $mime_type->post_mime_type );
						$mime_types[$key]->post_mime_type = wpsc_st_format_post_mime_type( $mime_type->post_mime_type, $show_mime_ext );
					}
					if( $mime_type->filter == 'all' )
						$mime_type->filter = false;
					if( $mime_type->filter )
						$mime_types[$key]->filter_url = add_query_arg( 'post_mime_type', $mime_type->filter );
					else
						$mime_types[$key]->filter_url = add_query_arg( array( 'post_type' => 'wpsc-product', 'page' => 'wpsc_st-file_downloads' ), 'edit.php' );
					if( $current_post_mime && ( $mime_type->filter == $current_post_mime ) )
						$mime_types[$key]->current = true;
					$mime_types[$key]->i = $i;
					$i++;
				}
			}
			if( $files ) {
				foreach( $files as $key => $file ) {
					$files[$key]->post_mime_type = get_post_mime_type( $file->ID );
					if( !$file->post_mime_type )
						$files[$key]->post_mime_type = __( 'N/A', 'wpsc_st' );
					$files[$key]->media_icon = wp_get_attachment_image( $file->ID, array( 80, 60 ), true );
					$author_name = get_user_by( 'id', $file->post_author );
					$parent_post = get_post( $file->post_parent );
					if( $parent_post ) {
						$files[$key]->post_parent_title = $parent_post->post_title;
					} else {
						$files[$key]->post_parent = '';
						$files[$key]->post_parent_title = __( 'Unassigned', 'wpsc_st' );
					}
					$files[$key]->post_author_name = $author_name->display_name;
					$t_time = strtotime( $file->post_date, current_time( 'timestamp' ) );
					$time = get_post_time( 'G', true, $file->ID, false );
					if( ( abs( $t_diff = time() - $time ) ) < 86400 )
						$files[$key]->post_date = sprintf( __( '%s ago' ), human_time_diff( $time ) );
					else
						$files[$key]->post_date = mysql2date( __( 'Y/m/d' ), $file->post_date );
				}
			}
		}

		include_once( 'templates/admin/wpsc-admin_st-file_downloads.php' );

	}

	function wpsc_st_default_toolkit_html_page() {

		global $wpdb, $wpsc_st;

		$tab = false;
		if( isset( $_GET['tab'] ) )
			$tab = $_GET['tab'];

		include_once( 'templates/admin/wpsc-admin_st-toolkit.php' );

	}

	function wpsc_st_html_file_downloads() {

		wpsc_st_template_header( __( 'File Downloads', 'wpsc_st' ), 'upload' );
		$action = wpsc_get_action();
		switch( $action ) {

			default:
				wpsc_st_default_file_downloads_html_page();
				break;

		}
		wpsc_st_template_footer();

	}

	function wpsc_st_html_settings() {

		global $wpsc_st;

		$action = wpsc_get_action();
		wpsc_st_template_header();
		switch( $action ) {

			case 'update':
				$options = array();
				if( isset( $_POST['options'] ) )
					$options = $_POST['options'];
				$options['sale_status_background'] = $_POST['sale_status_background'];
				$options['sale_status_border'] = $_POST['sale_status_border'];
				foreach( $options as $key => $option )
					wpsc_st_update_option( $key, $option );

				$message = __( 'Settings saved.', 'wpsc_st' );
				$output = '<div class="updated settings-error"><p>' . $message . '</p></div>';
				echo $output;

				wpsc_st_options_form();
				break;

			default:
				wpsc_st_options_form();
				break;

		}
		wpsc_st_template_footer();

	}

	function wpsc_st_options_form() {

		global $wpsc_purchlog_statuses;

		$sale_statuses = wpsc_st_add_colours_to_sale_statuses( $wpsc_purchlog_statuses );
		if( !$sale_status_background = get_option( 'wpsc_st_sale_status_background' ) ) {
			$sale_status_background = array();
			foreach( $sale_statuses as $sale_status )
				$sale_status_background[$sale_status['internalname']] = $sale_status['default_background'];
		}
		if( !$sale_status_border = get_option( 'wpsc_st_sale_status_border' ) ) {
			$sale_status_border = array();
			foreach( $sale_statuses as $sale_status )
				$sale_status_border[$sale_status['internalname']] = $sale_status['default_border'];
		}
		$options = wpsc_st_get_options();

		include( 'templates/admin/wpsc-admin_st-settings.php' );

	}

	function wpsc_st_html_toolkit() {

		global $wpdb;

		wpsc_st_template_header();
		wpsc_st_support_donate();
		$action = wpsc_get_action();
		switch( $action ) {

			case 'nuke':
				$message = __( 'Chosen WP e-Commerce details have been permanently erased from your store.', 'wpsc_st' );
				$output = '<div class="updated settings-error"><p>' . $message . '</p></div>';
				echo $output;

				wpsc_st_default_toolkit_html_page();
				break;

			case 'relink-existing-preregistered-sales':
				wpsc_st_default_toolkit_html_page();
				break;

			case 'relink-pages':
				$message = __( 'Default WP e-Commerce Pages have been restored.', 'wpsc_st' );
				$output = '<div class="updated settings-error"><p>' . $message . '</p></div>';
				echo $output;

				wpsc_st_default_toolkit_html_page();
				break;

			case 'fix-wpsc_version':
				if( ( wpsc_get_major_version() == '3.8' ) && ( WPSC_VERSION == '3.7' ) ) {

					update_option( 'wpsc_version', '3.7' );
					$message = __( 'WordPress option \'wpsc_version\' has been repaired.', 'wpsc_st' );
					$output = '<div class="updated settings-error"><p>' . $message . '</p></div>';

				} else {

					$message = __( 'WordPress option \'wpsc_version\' did not require attention.', 'wpsc_st' );
					$output = '<div class="error settings-error"><p>' . $message . '</p></div>';

				}
				echo $output;

				wpsc_st_default_toolkit_html_page();
				break;

			case 'tools':
				$message = __( 'Settings saved.', 'wpsc_st' );
				$output = '<div class="updated settings-error"><p><strong>' . $message . '</strong></p></div>';
				echo $output;

				wpsc_st_default_toolkit_html_page();
				break;

			case 'clear-claimed_stock':

				$wpdb->query( "TRUNCATE TABLE `" . $wpdb->prefix . "wpsc_claimed_stock`" );
				$message = __( 'The \'claimed stock\' table has been emptied.', 'wpsc_st' );
				$output = '<div class="updated settings-error"><p>' . $message . '</p></div>';
				echo $output;

				wpsc_st_default_toolkit_html_page();
				break;

			default:
				wpsc_st_default_toolkit_html_page();
				break;

		}
		wpsc_st_template_footer();

	}

	/* End of: WordPress Administration */

} else {

	/* Start of: Storefront */

	function wpsc_st_print_scripts() {

		global $wpsc_st;

		$output = '';
		$addtocart = get_option( $wpsc_st['prefix'] . '_addtocart_label', __( 'Add To Cart', 'wpsc' ) );
		if( $addtocart <> __( 'Add To Cart', 'wpsc' ) ) {
			$output = '
<!-- Store Toolkit: Add To Cart -->
<script type="text/javascript">
var $j = jQuery.noConflict();

$j(function(){

	$j(\'input.wpsc_buy_button\').val( \'' . $addtocart . '\' );

});
</script>
';
		}
		echo $output;

	}
	add_action( 'wp_print_footer_scripts' , 'wpsc_st_print_scripts' );

	include_once( 'includes/template.php' );

	/* End of: Storefront */

}
?>