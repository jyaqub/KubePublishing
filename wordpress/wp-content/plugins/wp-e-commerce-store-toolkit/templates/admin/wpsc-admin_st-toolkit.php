<script type="text/javascript">
	function showProgress() {
		window.scrollTo(0,0);
		document.getElementById('progress').style.display = 'block';
		document.getElementById('content').style.display = 'none';
		document.getElementById('support-donate_rate').style.display = 'none';
	}
</script>

<div id="content">

	<h2 class="nav-tab-wrapper">
		<a data-tab-id="overview" class="nav-tab<?php wpsc_st_admin_active_tab( 'overview' ); ?>" href="edit.php?post_type=wpsc-product&amp;page=wpsc_st-toolkit"><?php _e( 'Overview', 'wpsc_st' ); ?></a>
		<a data-tab-id="nuke" class="nav-tab<?php wpsc_st_admin_active_tab( 'nuke' ); ?>" href="edit.php?post_type=wpsc-product&amp;page=wpsc_st-toolkit&amp;tab=nuke"><?php _e( 'Nuke', 'wpsc_st' ); ?></a>
		<a data-tab-id="tools" class="nav-tab<?php wpsc_st_admin_active_tab( 'tools' ); ?>" href="edit.php?post_type=wpsc-product&amp;page=wpsc_st-toolkit&amp;tab=tools"><?php _e( 'Tools', 'wpsc_st' ); ?></a>
		<a data-tab-id="demo" class="nav-tab<?php wpsc_st_admin_active_tab( 'demo' ); ?>" href="edit.php?post_type=wpsc-product&amp;page=wpsc_st-toolkit&amp;tab=demo"><?php _e( 'Demo Mode', 'wpsc_st' ); ?></a>
	</h2>
	<?php wpsc_st_tab_template( $tab ); ?>

</div>
<!-- #content -->

<div id="progress" style="display:none;">
	<p><?php _e( 'Chosen WP e-Commerce details are being nuked, this process can take awhile. Time for a beer?', 'wpsc_st' ); ?></p>
	<img src="<?php echo plugins_url( '/templates/admin/images/progress.gif', $wpsc_st['relpath'] ); ?>" alt="" />
</div>
<!-- #progress -->