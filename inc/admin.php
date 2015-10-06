<?php
/**
 * Custom admin theme pages
 *
 * @package Memberlite
 */

/**
 * Adds Theme Support submenu page to "Appearance" menu.
 *
 */

function memberlite_theme_menu() {
	add_theme_page('Memberlite Documentation and Support', 'Memberlite', 'edit_theme_options', 'memberlite-support', 'memberlite_support');
}
add_action('admin_menu', 'memberlite_theme_menu');

function memberlite_support() {
	//only let admins get here
	if(!function_exists("current_user_can") || (!current_user_can("edit_theme_options") && !current_user_can("member_lite_options")))
	{
		die(__("You do not have permissions to perform this action.", "pmpro"));
	}
	
	//updating license?
	if(!empty($_REQUEST['pmpro-verify-submit']))
	{
		$key = preg_replace("/[^a-zA-Z0-9]/", "", $_REQUEST['pmpro-license-key']);
					
		//check key
		$valid = pmpro_license_isValid($key, 'plus', true);
		
		if($valid)
		{
		?>
		<div id="message" class="updated fade">
			<p><?php _e('Your license key has been validated.', 'memberlite');?></p>
		</div>
		<?php
		}
		else
		{
			global $pmpro_license_error;
		?>
		<div id="message" class="error">
			<p><?php echo $pmpro_license_error;?></p>
		</div>
		<?php
		}
		
		//update key
		update_option('pmpro_license_key', $key);
	}
	
	//get saved license
	$key = get_option("pmpro_license_key", "");
	$pmpro_license_check = get_option("pmpro_license_check", array("license"=>false, "enddate"=>0));
	
	if(isset($_REQUEST['tab']))
		$view = $_REQUEST['tab'];
	else
		$view = "";
	?>
	<div id="wpbody-content" aria-label="Main content" tabindex="0">	
		<div class="wrap"><div class="metabox-holder">
			<h2><?php _e('Memberlite Theme Documentation and Support', 'memberlite');?></h2>
			<?php if(empty($key)) { ?>
			<div class="error">
				<p><?php _e("Uh Oh - You haven't entered your Paid Memberships Pro license key.", "memberlite");?></p>
			</div>
			<?php } ?>
			<h2 class="nav-tab-wrapper">
				<a href="admin.php?page=memberlite-support&tab=overview" class="nav-tab<?php if( ($view == 'overview') || (empty($view)) ) { ?> nav-tab-active<?php } ?>"><?php _e('License', 'memberlite');?></a>
				<a href="admin.php?page=memberlite-support&tab=docs" class="nav-tab<?php if($view == 'docs') { ?> nav-tab-active<?php } ?>"><?php _e('Documentation', 'memberlite');?></a>
			</h2>
			<br class="clear" />
			<!-- /manage-menus -->
			<?php if( ($view == 'overview') || (empty($view)) ) { ?>
				<div id="memberlite-overview">
					<div class="postbox">
						<h3 class="hndle"><?php _e('License Key', 'memberlite');?></h3>
						<div class="inside">
							
							<?php if(empty($key)) { ?>
								<div class="notice notice-error inline"><p><strong><?php _e('It appears that you have not entered and verified your Paid Memberships Pro license key.</strong> Your license key can be found in your membership email receipt or in your <a href="http://www.paidmembershipspro.com/login/?redirect_to=/membership-account/" target="_blank">Membership Account</a>', 'memberlite');?></p></div>
							<?php } elseif(!pmpro_license_isValid()) { ?>
								<div class="notice notice-error inline"><p><?php _e('<strong>Your license is invalid or expired.</strong> Visit the PMPro <a href="http://www.paidmembershipspro.com/login/?redirect_to=/membership-account/" target="_blank">Membership Account</a> page to confirm that your account is active and to find your license key.', 'memberlite');?></p></div>
							<?php } elseif(!pmpro_license_isValid(NULL, 'plus')) { ?>
								<div class="notice notice-error inline"><p><?php _e('<strong>The Memberlite Theme requires a PMPro Plus license. <a href="http://www.paidmembershipspro.com/login/?redirect_to=/membership-account/membership-checkout/?level=20" target="_blank">Please upgrade your PMPro license</a> to receive automatic updates.', 'memberlite');?></p></div>
							<?php } else { ?>													
								<div class="notice inline"><p><?php printf(__('<strong>Thank you!</strong> A valid <strong>%s</strong> license key has been used to activate your support license on this site.', 'memberlite'), ucwords($pmpro_license_check['license']));?></p></div>
							<?php } ?>
							
							<table class="form-table">
								<tbody>
									<tr id="pmpro-settings-key-box">
										<th scope="row">
											<label for="pmpro-settings-key"><?php _e( 'PMPro License Key', 'pmpro' ); ?></label>
										</th>
										<td>
											<form id="pmpro-settings-verify-key" method="post">
												<input type="password" name="pmpro-license-key" id="pmpro-settings-key" value="<?php echo esc_attr($key);?>" placeholder="Enter PMPro license key here..." size="50" />
												<?php wp_nonce_field( 'pmpro-key-nonce', 'pmpro-key-nonce' ); ?>
												<?php submit_button( __( 'Verify Key', 'pmpro' ), 'primary', 'pmpro-verify-submit', false ); ?>										
											</form>
										</td>
									</tr>
								</tbody>
							</table>
						</div> <!-- end inside -->
					</div> <!-- end post-box -->
				</div> <!-- end memberlite-overview-->
			<?php } ?>
			<?php if($view == 'docs') { ?>
				<div id="memberlite-shortcodes">
					<h2>Adding Your Logo</h2>
					<p>Use the Appearance Header Screen to add a Custom Header logo (formatted for retina display) and to toggle the display of header text and text color.</p>
					<p><?php 
						if ( current_theme_supports( 'custom-header' ) && current_user_can( 'customize') ) 
						{
							?>
							<a href="<?php echo admin_url( 'themes.php?page=custom-header'); ?>">Edit Your Custom Header &raquo</a> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
							<?php
						}
					?>
					<a href="http://www.paidmembershipspro.com/themes/memberlite/adding-your-logo/" target="_blank">Explore Documentation on Custom Headers in Memberlite &raquo;</a></p>
					<hr />
					<h2>Customize the Theme</h2>
					<p>Use the Customize Screen to modify theme layout, logo, fonts, colors, copyright message and more.</p>
					<p><a href="<?php echo wp_customize_url (); ?>">Customize Your Theme &raquo;</a> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; <a href="http://www.paidmembershipspro.com/themes/memberlite/customize-the-theme/" target="_blank">Explore Documentation on Customizing Memberlite &raquo;</a></p>
					<hr />
					<h2>Using Child Themes</h2>
					<p>If you need to customize the theme beyond the settings in "Customize", use a child theme.</p>
					<p><a href="https://github.com/strangerstudios/memberlite-child" target="_blank">Download a Blank Child Theme &raquo;</a> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; <a href="http://codex.wordpress.org/Child_Themes" target="_blank">About Child Themes (WordPress Codex)  &raquo; </a></p>
					<hr />
					<h2>Shortcodes</h2>
					<p>Memberlite shortcodes enhance the appearance of your site content and can be used to  customize the display of Paid Memberships Pro-generated pages. Shortcodes are included for:</p>
					<ul class="ul-disc">
						<li>[row] and [column] for formatting text in responsive columns. <a href="http://www.paidmembershipspro.com/themes/memberlite/shortcodes/column-shortcodes/" target="_blank">docs</a></li>
						<li>[fa] for Font Awesome icons. <a href="http://www.paidmembershipspro.com/themes/memberlite/shortcodes/font-awesome-icons/" target="_blank">docs</a></li>
						<li>[memberlite_btn] for formatted buttons. <a href="http://www.paidmembershipspro.com/themes/memberlite/shortcodes/buttons/" target="_blank">docs</a></li>
						<li>[memberlite_levels] to display a block with details and a registration link for the specified membership levels. <a href="http://www.paidmembershipspro.com/themes/memberlite/shortcodes/membership-levels-display/" target="_blank">docs</a></li>
						<li>[memberlite_msg] for contextual message blocks. <a href="http://www.paidmembershipspro.com/themes/memberlite/shortcodes/contextual-messages/" target="_blank">docs</a></li>
						<li>[memberlite_recent_posts] designed to be used on the homepage and outputs the newest posts overall or in a defined category. <a href="http://www.paidmembershipspro.com/themes/memberlite/shortcodes/recent-posts/" target="_blank">docs</a></li>
						<li>[memberlite_signup] to display a block with signup fields for a specific membership level. <a href="http://www.paidmembershipspro.com/themes/memberlite/shortcodes/membership-signup-block/" target="_blank">docs</a></li>
						<li>[memberlite_subpagelist] to show a list of a given pages' subpages in the order you define. <a href="http://www.paidmembershipspro.com/themes/memberlite/shortcodes/subpagelist/" target="_blank">docs</a></li>
						<li>[memberlite_tabs] and [memberlite_tab] for tabbed content blocks. <a href="http://www.paidmembershipspro.com/themes/memberlite/shortcodes/tabs/" target="_blank">docs</a></li>
					</ul>
					<p><a href="http://www.paidmembershipspro.com/themes/memberlite/shortcodes/" target="_blank">View Shortcode Documentation &raquo;</a></p>
					<hr />
					<h2>Integrated Plugins</h2>
					<p>Memberlite includes formatting for use with:</p>
					<ul class="ul-disc">
						<li><strong><a href="http://www.paidmembershipspro.com" target="_blank">Paid Memberships Pro</a></strong><br /><a href="<?php echo admin_url( 'plugin-install.php?tab=search&s=paid+memberships+pro'); ?>">Install Plugin &raquo;</a></li>
						<li><strong><a href="http://www.woothemes.com/woocommerce/" target="_blank">WooCommerce</a></strong><br /><a href="<?php echo admin_url( 'plugin-install.php?tab=search&s=woocommerce'); ?>">Install Plugin &raquo;</a> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; <a href="<?php echo admin_url( 'plugin-install.php?tab=search&type=term&s=Paid+Memberships+Pro+-+WooCommerce+Add+On'); ?>">Install PMPro WooCommerce Addon &raquo;</a></li>						
						<li><strong><a href="http://www.bbpress.org" target="_blank">bbPress</a></strong><br /><a href="<?php echo admin_url( 'plugin-install.php?tab=search&s=bbpress'); ?>">Install Plugin</a> &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp; <a href="<?php echo admin_url( 'plugin-install.php?tab=search&type=term&s=Paid+Memberships+Pro+-+bbPress+Add+On'); ?>">Install PMPro bbPress Addon &raquo;</a></li>
						<li><strong><a href="http://wp-events-plugin.com" target="_blank">Events Manager</a></strong><br /><a href="<?php echo admin_url( 'plugin-install.php?tab=search&s=events+manager'); ?>">Install Plugin &raquo;</a></li>						
				</div>		
			<?php } ?>
		</div></div><!-- /.wrap-->
	<div class="clear"></div></div>
	<?php	
}

/*
	Add a Banner Image as a secondary thumbnail
*/
function memberlite_banner_image_setup()
{
	//$memberlite_post_types = get_post_types( array('public' => true), 'names' );
	if (class_exists('MultiPostThumbnails')) {
	    $screens = get_post_types( array('public' => true), 'names' );
		foreach ($screens as $screen) 
		{
			if(in_array($screen, array('reply','topic')))
				continue;
			else
			{
				new MultiPostThumbnails(
					array(
						'label' => 'Banner Image',
						'id' => 'memberlite_banner_image' . $screen,
						'post_type' => $screen,
					)
				);
			}
		}
	}
}
add_action('wp_loaded', 'memberlite_banner_image_setup');