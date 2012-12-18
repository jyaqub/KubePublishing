<?php
if( is_admin() ) {

	/* Start of: WordPress Administration */

	function wpsc_st_template_header( $title = null, $icon = 'tools' ) {

		global $wpsc_st;

		if( $title )
			$output = $title;
		else
			$output = $wpsc_st['menu'];
		$icon = wpsc_is_admin_icon_valid( $icon ); ?>
<div class="wrap">
	<div id="icon-<?php echo $icon; ?>" class="icon32"><br /></div>
	<h2><?php echo $output; ?></h2>
<?php
	}

	function wpsc_st_template_footer() { ?>
</div>
<?php
	}

	function wpsc_st_support_donate() {

		global $wpsc_st;

		$output = '';
		$show = true;
		if( function_exists( 'wpsc_vl_we_love_your_plugins' ) ) {
			if( in_array( $wpsc_st['dirname'], wpsc_vl_we_love_your_plugins() ) )
				$show = false;
		}
		if( $show ) {
			$donate_url = 'http://www.visser.com.au/#donations';
			$rate_url = 'http://wordpress.org/support/view/plugin-reviews/' . $wpsc_st['dirname'];
			$output = '
	<div id="support-donate_rate" class="support-donate_rate">
		<p>' . sprintf( __( '<strong>Like this Plugin?</strong> %s and %s.', 'wpsc_st' ), '<a href="' . $donate_url . '" target="_blank">' . __( 'Donate to support this Plugin', 'wpsc_st' ) . '</a>', '<a href="' . add_query_arg( array( 'rate' => '5' ), $rate_url ) . '#postform" target="_blank">rate / review us on WordPress.org</a>' ) . '</p>
	</div>
';
		}
		echo $output;

	}

	function wpsc_st_empty_dir( $dir ) {

		if( strpos( php_uname(), 'Windows' ) !== FALSE )
			$dir = str_replace( '/', '\\', $dir );
		
		$handle = opendir( $dir );
		if( $handle ) {
			while( ( $file = readdir( $handle ) ) !== false ) {
				if( $file <> '.htaccess' )
					@unlink( $dir . '/' . $file );
			}
		}
		closedir( $handle );

	}

	if( !function_exists( 'wpsc_st_remove_filename_extension' ) ) {

		function wpsc_st_remove_filename_extension( $filename ) {

			$extension = strrchr( $filename, '.' );
			$filename = substr( $filename, 0, -strlen( $extension ) );

			return $filename;

		}

	}

	function wpsc_st_post_statuses() {

		$output = array(
			'publish',
			'pending',
			'draft',
			'auto-draft',
			'future',
			'private',
			'inherit',
			'trash'
		);
		return $output;

	}

	function wpsc_st_pd_options_addons( $options ) {

		$options[] = array( 'aioseop_keywords', __( 'All in One SEO - Keywords', 'wpsc_pd' ) );
		$options[] = array( 'aioseop_description', __( 'All in One SEO - Description', 'wpsc_pd' ) );
		$options[] = array( 'aioseop_title', __( 'All in One SEO - Title', 'wpsc_pd' ) );
		$options[] = array( 'aioseop_titleatr', __( 'All in One SEO - Title Attributes', 'wpsc_pd' ) );
		$options[] = array( 'aioseop_menulabel', __( 'All in One SEO - Menu Label', 'wpsc_pd' ) );
		return $options;

	}
	add_filter( 'wpsc_pd_options_addons', 'wpsc_st_pd_options_addons', null, 1 );

	function wpsc_st_pd_import_addons( $import, $csv_data ) {

		if( function_exists( 'aioseop_get_version' ) ) {
			if( isset( $csv_data['aioseop_keywords'] ) ) {
				$import->csv_aioseop_keywords = array_filter( $csv_data['aioseop_keywords'] );
				$import->log .= "<br />>>> " . __( 'All in One SEO Pack - Keywords has been detected and grouped', 'wpsc_pd' );
			}
			if( isset( $csv_data['aioseop_description'] ) ) {
				$import->csv_aioseop_description = array_filter( $csv_data['aioseop_description'] );
				$import->log .= "<br />>>> " . __( 'All in One SEO Pack - Description has been detected and grouped', 'wpsc_pd' );
			}
			if( isset( $csv_data['aioseop_title'] ) ) {
				$import->csv_aioseop_title = array_filter( $csv_data['aioseop_title'] );
				$import->log .= "<br />>>> " . __( 'All in One SEO Pack - Title has been detected and grouped', 'wpsc_pd' );
			}
			if( isset( $csv_data['aioseop_titleatr'] ) ) {
				$import->csv_aioseop_titleatr = array_filter( $csv_data['aioseop_titleatr'] );
				$import->log .= "<br />>>> " . __( 'All in One SEO Pack - Title Attributes has been detected and grouped', 'wpsc_pd' );
			}
			if( isset( $csv_data['aioseop_menulabel'] ) ) {
				$import->csv_aioseop_menulabel = array_filter( $csv_data['aioseop_menulabel'] );
				$import->log .= "<br />>>> " . __( 'All in One SEO Pack - Menu Label has been detected and grouped', 'wpsc_pd' );
			}
		} else if( $import->advanced_log ) {
			$import->log .= "<br />>>> " . __( 'All in One SEO Pack was not detected, skipping Product meta tags', 'wpsc_pd' );
		}
		return $import;

	}
	add_filter( 'wpsc_pd_import_addons', 'wpsc_st_pd_import_addons', null, 2 );

	function wpsc_st_pd_product_addons( $product, $import, $count ) {

		if( isset( $import->csv_aioseop_keywords[$count] ) )
			$product->aioseop_keywords = $import->csv_aioseop_keywords[$count];
		if( isset( $import->csv_aioseop_description[$count] ) )
			$product->aioseop_description = $import->csv_aioseop_description[$count];
		if( isset( $import->csv_aioseop_title[$count] ) )
			$product->aioseop_title = $import->csv_aioseop_title[$count];
		if( isset( $import->csv_aioseop_titleatr[$count] ) )
			$product->aioseop_titleatr = $import->csv_aioseop_titleatr[$count];
		if( isset( $import->csv_aioseop_menulabel[$count] ) )
			$product->aioseop_menulabel = $import->csv_aioseop_menulabel[$count];
		return $product;

	}
	add_filter( 'wpsc_pd_product_addons', 'wpsc_st_pd_product_addons', null, 3 );

	function wpsc_st_pd_create_product_log_addons( $import, $product ) {

		if( isset( $product->aioseop_keywords ) || isset( $product->aioseop_description ) || isset( $product->aioseop_title ) || isset( $product->aioseop_titleatr ) || isset( $product->aioseop_menulabel ) )
			$import->log .= "<br />>>>>>> " . __( 'Linking All in One SEO Pack meta details', 'wpsc_pd' );
		return $import;

	}
	add_filter( 'wpsc_pd_create_product_log_addons', 'wpsc_st_pd_create_product_log_addons', null, 2 );

	function wpsc_st_pd_merge_product_data_addons( $product_data, $product, $import ) {

		if( $product->ID ) {
			$product_data->aioseop_keywords = get_post_meta( $product->ID, '_aioseop_keywords', true );
			$product_data->aioseop_description = get_post_meta( $product->ID, '_aioseop_description', true );
			$product_data->aioseop_title = get_post_meta( $product->ID, '_aioseop_title', true );
			$product_data->aioseop_titleatr = get_post_meta( $product->ID, '_aioseop_titleatr', true );
			$product_data->aioseop_menulabel = get_post_meta( $product->ID, '_aioseop_menulabel', true );
		}
		return $product_data;

	}
	add_filter( 'wpsc_pd_merge_product_data_addons', 'wpsc_st_pd_merge_product_data_addons', null, 3 );

	function wpsc_st_pd_merge_product_log_addons( $import, $product, $product_data ) {

		if( isset( $product->aioseop_keywords ) && $product->aioseop_keywords || isset( $product->aioseop_description ) && $product->aioseop_description || isset( $product->aioseop_title ) && $product->aioseop_title || isset( $product->aioseop_titleatr ) && $product->aioseop_titleatr || isset( $product->aioseop_menulabel ) && $product->aioseop_menulabel ) {
			if( isset( $product->aioseop_keywords ) ) {
				if( $product_data->aioseop_keywords <> $product->aioseop_keywords )
					$import->log .= "<br />>>>>>> " . __( 'Updating AIOSEO Pack Keywords', 'wpsc_pd' );
			}
			if( isset( $product->aioseop_description ) ) {
				if( $product_data->aioseop_description <> $product->aioseop_description )
					$import->log .= "<br />>>>>>> " . __( 'Updating AIOSEO Pack Description', 'wpsc_pd' );
			}
			if( isset( $product->aioseop_title ) ) {
				if( $product_data->aioseop_title <> $product->aioseop_title )
					$import->log .= "<br />>>>>>> " . __( 'Updating AIOSEO Pack Title', 'wpsc_pd' );
			}
			if( isset( $product->aioseop_titleatr ) ) {
				if( $product_data->aioseop_titleatr <> $product->aioseop_titleatr )
					$import->log .= "<br />>>>>>> " . __( 'Updating AIOSEO Pack Title Atr', 'wpsc_pd' );
			}
			if( isset( $product->aioseop_menulabel ) ) {
				if( $product_data->aioseop_menulabel <> $product->aioseop_menulabel )
					$import->log .= "<br />>>>>>> " . __( 'Updating AIOSEO Pack Menu Label', 'wpsc_pd' );
			}
		}
		return $import;

	}
	add_filter( 'wpsc_pd_merge_product_log_addons', 'wpsc_st_pd_merge_product_log_addons', null, 3 );

	function wpsc_st_format_post_mime_type_filter( $post_mime_type = '', $action = 'shrink' ) {

		$output = '';
		if( $post_mime_type ) {
			switch( $action ) {

				case 'shrink':
					$output = wpsc_st_format_post_mime_ext( str_replace( '/', '', strstr( $post_mime_type, '/' ) ) );
					break;

				case 'expand':
					$mime_types = get_allowed_mime_types();
					foreach( $mime_types as $key => $mime_type ) {
						$pieces = explode( '|', $key );
						$size = count( $pieces );
						for( $i = 0; $i < $size; $i++ ) {
							if( $pieces[$i] == $post_mime_type )
								$output = $mime_type;
						}
					}
					break;

			}
		}
		return $output;

	}

	function wpsc_st_format_post_mime_type( $post_mime_type = '', $show_mime_ext = true ) {

		$output = '';
		if( $post_mime_type ) {
			$mime_type_name = ucfirst( strstr( $post_mime_type, '/', true ) );
			$mime_type_ext = false;
			if( $show_mime_ext )
				$mime_type_ext = wpsc_st_format_post_mime_ext( str_replace( '/', '', strstr( $post_mime_type, '/' ) ) );
			if( $mime_type_name && $mime_type_ext )
				$output = sprintf( '%s: *.%s', $mime_type_name, $mime_type_ext );
			else if( $mime_type_name )
				$output = $mime_type_name;
		}
		return $output;

	}

	function wpsc_st_format_post_mime_ext( $post_mime_ext = '' ) {

		$output = '';
		if( $post_mime_ext ) {
			switch( $post_mime_ext ) {

				case 'plain':
					$output = 'txt';
					break;

				case 'jpeg':
					$output = 'jpg';
					break;

				case 'mpeg':
					$output = 'mp3';
					break;

				default:
					$output = $post_mime_ext;
					break;

			}
		}
		return $output;

	}

	function wpsc_st_admin_active_tab( $tab_name = null, $tab = null ) {

		if( isset( $_GET['tab'] ) && !$tab )
			$tab = $_GET['tab'];
		else
			$tab = 'overview';

		$output = '';
		if( isset( $tab_name ) && $tab_name ) {
			if( $tab_name == $tab ) {
				$output = ' nav-tab-active';
			}
		}
		echo $output;

	}

	function wpsc_st_tab_template( $tab ) {

		global $wpsc_st;

		switch( $tab ) {

			case 'overview':
			case 'nuke':
			case 'tools':
			case 'demo':
				break;

			default:
				$tab = 'overview';
				break;

		}
		if( $tab )
			include_once( $wpsc_st['abspath'] . '/templates/admin/wpsc-admin_st-toolkit_' . $tab . '.php' );

	}

	function wpsc_st_add_colours_to_sale_statuses( $sale_statuses = null ) {

		if( isset( $sale_statuses ) && $sale_statuses ) {
			foreach( $sale_statuses as $key => $status ) {
				$background = '';
				$border = '';
				switch( $status['internalname'] ) {

					case 'incomplete_sale':
						$background = '464646';
						$border = '3d3d3d';
						break;

					case 'order_received':
						$background = '298cba';
						$border = '2786b3';
						break;

					case 'accepted_payment':
						$background = '6fbf4d';
						$border = '6eb84f';
						break;

					case 'job_dispatched':
						$background = '58993e';
						$border = '579140';
						break;

					case 'closed_order':
						$background = 'cc0003';
						$border = '910002';
						break;

					case 'declined_payment':
						$background = 'cc0003';
						$border = '910002';
						break;

				}
				if( $background || $border ) {
					$sale_statuses[$key]['default_background'] = $background;
					$sale_statuses[$key]['default_border'] = $border;
				}
			}
		}
		return $sale_statuses;

	}

	function wpsc_st_get_options() {

		$options = array(
			'addtocart_label' => wpsc_st_get_option( 'addtocart_label', __( 'Add To Cart', 'wpsc_st' ) )
		);
		return $options;

	}

	/* End of: WordPress Administration */

} else {

	/* Start of: Storefront */

	function wpsc_st_capture_ip_address() {

		$ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
		if( !$ip_address )
			$ip_address = $_SERVER['REMOTE_ADDR'];
		$ip_address_array = explode( ',', $ip_address ); // Get first IP address, discard the rest.
		return $ip_address_array[0];

	}

	/* End of: Storefront */

}

/* Start of: Common */

function wpsc_st_get_option( $option = null, $default = false ) {

	global $wpsc_st;

	$output = '';
	if( isset( $option ) ) {
		$separator = '_';
		$output = get_option( $wpsc_st['prefix'] . $separator . $option, $default );
	}
	return $output;

}

function wpsc_st_update_option( $option = null, $value = null ) {

	global $wpsc_st;

	$output = false;
	if( isset( $option ) && isset( $value ) ) {
		$separator = '_';
		$output = update_option( $wpsc_st['prefix'] . $separator . $option, $value );
	}
	return $output;

}

/* End of: Common */
?>