<?php
$products = wpsc_st_return_count( 'products' );
$variations = wpsc_st_return_count( 'variations' );
$images = wpsc_st_return_count( 'images' );
$files = wpsc_st_return_count( 'files' );
$tags = wpsc_st_return_count( 'tags' );
$categories = wpsc_st_return_count( 'categories' );
if( $categories ) {
	$term_taxonomy = 'wpsc_product_category';
	$args = array(
		'hide_empty' => 0
	);
	$categories_data = get_terms( $term_taxonomy, $args );
}
$orders = wpsc_st_return_count( 'orders' );
$coupons = wpsc_st_return_count( 'coupons' );

$wishlist = wpsc_st_return_count( 'wishlist' );
$enquiries = wpsc_st_return_count( 'enquiries' );
$credit_cards = wpsc_st_return_count( 'credit-cards' );
$attributes = wpsc_st_return_count( 'custom-fields' );

$posts = wpsc_st_return_count( 'posts' );
$post_categories = wpsc_st_return_count( 'post_categories' );
$post_tags = wpsc_st_return_count( 'post_tags' );
$links = wpsc_st_return_count( 'links' );
$comments = wpsc_st_return_count( 'comments' );

if( $products || $variations || $images || $files || $tags || $categories || $orders || $wishlist || $enquiries || $credit_cards || $attributes )
	$show_table = true;
else
	$show_table = false;
?>
<ul class="subsubsub">
	<li><a href="#empty-wpecommerce-tables"><?php _e( 'Empty WP e-Commerce Tables', 'wpsc_st' ); ?></a> |</li>
	<li><a href="#empty-3rdparty-plugins"><?php _e( 'Empty 3rd Party Plugins', 'wpsc_st' ); ?></a> |</li>
	<li><a href="#empty-product-by-category"><?php _e( 'Empty Products by Product Category', 'wpsc_st' ); ?></a> |</li>
	<li><a href="#empty-wordpress-tables"><?php _e( 'WordPress Tables', 'wpsc_st' ); ?></a></li>
</ul>
<br class="clear" />
<h3><?php _e( 'Nuke WP e-Commerce', 'wpsc_st' ); ?></h3>
<p><?php _e( 'Select the WP e-Commerce tables you wish to empty then click Remove to permanently remove WP e-Commerce generated details from your WordPress database.', 'wpsc_st' ); ?></p>
<form method="post" onsubmit="showProgress()">
	<div id="poststuff">

		<div class="postbox id="#empty-wpecommerce-tables">
			<h3 class="hndle"><?php _e( 'Empty WP e-Commerce Tables', 'wpsc_st' ); ?></h3>
			<div class="inside">
				<p class="description"><?php _e( 'Permanently remove WP e-Commerce details.', 'wpsc_st' ); ?></p>
				<table class="form-table">

					<tr>
						<th>
							<label for="products"><?php _e( 'Products', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="products" name="wpsc_st_products"<?php if( $products == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $products; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="product_variations"><?php _e( 'Product Variations', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="product_variations" name="wpsc_st_product_variations"<?php if( $variations == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $variations; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="product_images"><?php _e( 'Product Images', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="product_images" name="wpsc_st_product_images"<?php if( $images == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $images; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="product_files"><?php _e( 'Product Files', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="product_files" name="wpsc_st_product_files"<?php if( $files == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $files; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="product_tags"><?php _e( 'Product Tags', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="product_tags" name="wpsc_st_product_tags"<?php if( $tags == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $tags; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="product_categories"><?php _e( 'Product Categories', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="product_categories" name="wpsc_st_product_categories"<?php if( $categories == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $categories; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="sales_orders"><?php _e( 'Sales', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="sales_orders" name="wpsc_st_sales_orders"<?php if( $orders == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $orders; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="coupons"><?php _e( 'Coupons', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="coupons" name="wpsc_st_coupons"<?php if( $coupons == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $coupons; ?>)
						</td>
					</tr>

				</table>
				<p class="submit">
					<input type="submit" value="<?php _e( 'Remove', 'wpsc_st' ); ?>" class="button-primary" />
				</p>
			</div>
		</div>
		<!-- .postbox -->

		<div class="postbox">
			<h3 class="hndle" id="empty-3rdparty-plugins"><?php _e( 'Empty 3rd Party Plugins', 'wpsc_st' ); ?></h3>
			<div class="inside">
				<p class="description"><?php _e( 'Permanently remove details created by other WP e-Commerce Plugins.', 'wpsc_st' ); ?></p>
				<table class="form-table">

<?php if( $wishlist ) { ?>
					<tr>
						<th>
							<label for="wishlist"><?php _e( 'Wishlist', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="wishlist" name="wpsc_st_wishlist"<?php if( $wishlist == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $wishlist; ?>)
							<span class="description"><?php _e( 'via Add to Wishlist', 'wpsc_st' ); ?></span>
						</td>
					</tr>

<?php } ?>
<?php if( $enquiries ) { ?>
					<tr>
						<th>
							<label for="enquiries"><?php _e( 'Enquiries', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="enquiries" name="wpsc_st_enquiries"<?php if( $enquiries == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $enquiries; ?>)
							<span class="description"><?php _e( 'via Product Enquiry', 'wpsc_st' ); ?></span>
						</td>
					</tr>

<?php } ?>
<?php if( $credit_cards ) { ?>
					<tr>
						<th>
							<label for="creditcards"><?php _e( 'Credit Cards', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="creditcards" name="wpsc_st_creditcards"<?php if( $credit_cards == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $credit_cards; ?>)
							<span class="description"><?php _e( 'via Offline Credit Card Processing', 'wpsc_st' ); ?></span>
						</td>
					</tr>

<?php } ?>
<?php if( $attributes ) { ?>
					<tr>
						<th>
							<label for="customfields"><?php _e( 'Attributes', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="customfields" name="wpsc_st_customfields"<?php if( $attributes == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $attributes; ?>)
							<span class="description"><?php _e( 'via Custom Fields', 'wpsc_st' ); ?></span>
						</td>
					</tr>

<?php } ?>
				</table>
				<p class="submit">
					<input type="submit" value="<?php _e( 'Remove', 'wpsc_st' ); ?>" class="button-primary" />
				</p>
			</div>
		</div>
		<!-- .postbox -->

		<div class="postbox">
			<h3 class="hndle" id="empty-product-by-category"><?php _e( 'Empty Products by Product Category', 'wpsc_st' ); ?></h3>
			<div class="inside">
<?php if( $categories ) { ?>
				<p><?php _e( 'Remove Products from specific Product Categories by selecting the Product Categories below, then click Remove to permanently remove those Products.', 'wpsc_st' ); ?></p>
				<ul>
	<?php foreach( $categories_data as $category_single ) { ?>
					<li>
						<label>
							<input type="checkbox" name="wpsc_st_categories[<?php echo $category_single->term_id; ?>]" value="<?php echo $category_single->term_id; ?>"<?php if( $category_single->count == 0 ) { ?> disabled="disabled"<?php } ?> />
							<?php echo $category_single->name; ?> (<?php echo $category_single->count; ?>)
						</label>
					</li>
	<?php } ?>
				</ul>
				<p class="submit">
					<input type="submit" value="<?php _e( 'Remove', 'wpsc_st' ); ?>" class="button-primary" />
				</p>
<?php } else { ?>
				<p><?php _e( 'No Categories have been created.', 'wpsc_st' ); ?></p>
<?php } ?>
			</div>
		</div>
		<!-- .postbox -->

		<div class="postbox">
			<h3 class="hndle" id="empty-wordpress-tables"><?php _e( 'Empty WordPress Tables', 'wpsc_st' ); ?></h3>
			<div class="inside">
				<p class="description"><?php _e( 'Permanently remove WordPress details.', 'wpsc_st' ); ?></p>
				<table class="form-table">

					<tr>
						<th>
							<label for="posts"><?php _e( 'Posts', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="posts" name="wpsc_st_posts"<?php if( $posts == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $posts; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="post_categories"><?php _e( 'Post Categories', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="post_categories" name="wpsc_st_post_categories"<?php if( $post_categories == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $post_categories; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="post_tags"><?php _e( 'Post Tags', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="post_tags" name="wpsc_st_post_tags"<?php if( $post_tags == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $post_tags; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="links"><?php _e( 'Links', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="links" name="wpsc_st_links"<?php if( $links == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $links; ?>)
						</td>
					</tr>

					<tr>
						<th>
							<label for="comments"><?php _e( 'Comments', 'wpsc_st' ); ?></label>
						</th>
						<td>
							<input type="checkbox" id="links" name="wpsc_st_comments"<?php if( $comments == 0 ) { ?> disabled="disabled"<?php } ?> /> (<?php echo $comments; ?>)
						</td>
					</tr>

				</table>
				<p class="submit">
					<input type="submit" value="<?php _e( 'Remove', 'wpsc_st' ); ?>" class="button-primary" />
				</p>
			</div>
		</div>
		<!-- .postbox -->

	</div>
	<input type="hidden" name="action" value="nuke" />
</form>