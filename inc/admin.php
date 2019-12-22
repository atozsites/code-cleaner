<?php 
//if no tab is set, default to first/options tab
if(empty($_GET['tab'])) {
	$_GET['tab'] = 'default';
} 
?>
<div class="wrap atoz_codecleaner-admin">

	<!-- Plugin Admin Page Title -->
	<h2><?php _e('Code Cleaner Settings', 'atoz_codecleaner'); ?></h2>

    <!-- Tab Navigation -->
	<h2 class="nav-tab-wrapper">
		<a href="?page=atoz_codecleaner&tab=default" class="nav-tab <?php echo $_GET['tab'] == 'default' || '' ? 'nav-tab-active' : ''; ?>"><?php _e('Default', 'atoz_codecleaner'); ?></a>
		<a href="?page=atoz_codecleaner&tab=woocommerce" class="nav-tab <?php echo $_GET['tab'] == 'woocommerce' || '' ? 'nav-tab-active' : ''; ?>"><?php _e('WooCommerce', 'atoz_codecleaner'); ?></a>
		<a href="?page=atoz_codecleaner&tab=ga" class="nav-tab <?php echo $_GET['tab'] == 'ga' ? 'nav-tab-active' : ''; ?>"><?php _e('Google Analytics', 'atoz_codecleaner'); ?></a>
		<a href="?page=atoz_codecleaner&tab=more" class="nav-tab <?php echo $_GET['tab'] == 'more' ? 'nav-tab-active' : ''; ?>"><?php _e('More', 'atoz_codecleaner'); ?></a>
		<a href="?page=atoz_codecleaner&tab=support" class="nav-tab <?php echo $_GET['tab'] == 'support' ? 'nav-tab-active' : ''; ?>"><?php _e('Support', 'atoz_codecleaner'); ?></a>
	</h2>

	<!-- Plugin Options Form -->
	<form method="post" action="options.php">

		<!-- Main Options Tab -->
		<?php if($_GET['tab'] == 'default') { ?>

		    <?php settings_fields('atoz_codecleaner_options'); ?>
		    <?php do_settings_sections('atoz_codecleaner_options'); ?>
			<?php submit_button(); ?>
            
		<!-- Main WooCommerce Tab -->
		<?php } elseif($_GET['tab'] == 'woocommerce') { ?>

		    <?php settings_fields('atoz_codecleaner_woocommerce'); ?>
		    <?php do_settings_sections('atoz_codecleaner_woocommerce'); ?>
			<?php submit_button(); ?>
            
		<!-- Google Analytics Tab -->
		<?php } elseif($_GET['tab'] == 'ga') { ?>

			<?php settings_fields('atoz_codecleaner_ga'); ?>
		    <?php do_settings_sections('atoz_codecleaner_ga'); ?>
			<?php submit_button(); ?>

		<!-- Extras Tab -->
		<?php } elseif($_GET['tab'] == 'more') { ?>

			<?php settings_fields('atoz_codecleaner_extras'); ?>
		    <?php do_settings_sections('atoz_codecleaner_extras'); ?>
			<?php submit_button(); ?>

		<!-- Support Tab -->
		<?php } elseif($_GET['tab'] == 'support') { ?>

			<h2><?php _e('Support', 'atoz_codecleaner'); ?></h2>
			<p><?php _e("For plugin support and documentation, please visit <a href='https://atozsites.com/cleaner/' title='atoz_codecleaner' target='_blank'>atozsites.com</a>.", 'atoz_codecleaner'); ?></p>

		<?php } ?>
	</form>

	<?php if($_GET['tab'] != 'support' && $_GET['tab'] != 'license') { ?>

		<div id="atoz_codecleaner-legend">
			<div id="atoz_codecleaner-tooltip-legend">
				<span>?</span><?php _e('Click on tooltip icons to view full documentation.', 'atoz_codecleaner'); ?>
			</div>
		</div>

	<?php } ?>
    
</div>


<script src="<?php echo plugins_url('js/jquery-1.11.1.min.js', dirname(__FILE__))?>"></script>

  <script>
      (function ($) {
          $(".atoz_codecleaner-tooltip").hover(function(){
              $(this).closest("tr").find(".atoz_codecleaner-tooltip-text-container").show();
          },function(){
              $(this).closest("tr").find(".atoz_codecleaner-tooltip-text-container").hide();
          });
      }(jQuery));
  </script>

<script>
window.onload = $("#disable_heartbeat");
	if ($("#disable_heartbeat").val() == 'disable_everywhere') {
     $('#heartbeat_frequency').prop('disabled', true);
  } else {
     $('#heartbeat_frequency').prop('disabled', false);  
  }
</script>
<script>
$('#disable_heartbeat').change(function(){
  if (this.selectedIndex == 1) {
     $('#heartbeat_frequency').prop('disabled', true);
  } else {
     $('#heartbeat_frequency').prop('disabled', false);  
  }
});
</script>
<script>
$('#disable_post_revisions').change(function(){
  if ($("#disable_post_revisions").is(':checked'))
  {
	  document.getElementById( 'limit_post_revisions').style.display = 'block';
	  document.getElementById( 'label_limit_post_revisions').style.display = 'block';
  }
  else
  {
	  document.getElementById( 'limit_post_revisions').style.display = 'none'
	  document.getElementById( 'label_limit_post_revisions').style.display = 'none'
  }
});
</script>
<script>
window.onload = $("#disable_post_revisions");
  if ($("#disable_post_revisions").is(':checked'))
  {
	  document.getElementById( 'limit_post_revisions').style.display = 'block';
	  document.getElementById( 'label_limit_post_revisions').style.display = 'block';
  }
  else
  {
	  document.getElementById( 'limit_post_revisions').style.display = 'none'
	  document.getElementById( 'label_limit_post_revisions').style.display = 'none'
  }
</script>
