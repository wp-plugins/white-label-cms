<?php
/*
Plugin Name: White Label CMS
Plugin URI: http://www.videousermanuals/white-label-cms/
Description:  A plugin that allows you to brand wordpress CMS as your own
Version: 1.0.5
Author: www.videousermanuals.com
Author URI: http://www.videousermanuals.com
*/


// set admin screen
function wlcms_add_menu() {

	$wlcms_admin = add_options_page('White Label CMS','White Label CMS','manage_options','wlcms-plugin.php','wlcms_admin');

	$wlcms_help = "
	<style>
	ul.help_list {
		margin-top:10px;}
		
	ul.help_list li {
		list-style-type:disc;
		margin-left:20px;}
	</style>

		
	<p>The purpose of this plugin is to help developers hand over a white label version of Wordpress to their clients, which looks more professional, and removes some of the unnecessary and confusing clutter from the dashboard.</p>
	
	<p>We tried to make this as simple as possible so the options should be self explanatory.  There are really only 2 things that need explanation.</p>

	<p style=\"font-size:9px; margin-bottom:10px;\">Icons: <a href=\"http://www.woothemes.com/2009/09/woofunction/\">WooFunction</a></p>
	
	<h4>Where To Upload The Images</h4>
	<p>We made the decision that the custom logo images. The header logo, footer logo and login logo should all go into the images directory of your current theme.   We did this because we realised that web developers usually customise the theme themselves for their clients, so for each project adding in a couple of logo's to the theme is a very simple thing to do.</p>
	
	<h4>The Custom Dashboard Panel</h4>
	<p>We usually add a personalised introduction for each client, and add out contact details, and a link to our help desk. We feel that this gives a more professional handover to our clients. You can use it any way you want, but please remember to use HTML code.</p>

	<h4>Troubleshooting</h4>
	<p><strong>I installed the plugin and the logos disappear?:</strong> You need to upload your logos to you current themes images directory.</p>
	
	<p>This plugin is sponsored by: <a href=\"http://www.videousermanuals.com?ref=whitelabelcmsplugin\" target=\"_blank\">Video User Manuals</a></p>

	";

	add_contextual_help($wlcms_admin, $wlcms_help);

}

add_action('admin_menu', 'wlcms_add_menu');

function wlcms_remove_dashboard_widgets() {
   global $wp_meta_boxes;
   unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins']);
   unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
   unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary']);
   unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments']);
   unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links']);
   unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']);
   unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts']);
}

function wlcms_remove_right_now() {
   global $wp_meta_boxes;
	unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now']);
}

// custom logo in header
function wlcms_custom_logo() {
   echo '
	  <style type="text/css">
		 #header-logo { background-image: url('.get_bloginfo('template_directory').'/images/' . get_option('wlcms_o_header_custom_logo') . ') !important; }
	  </style>
   ';
}

// add footer logo
function wlcms_remove_footer_admin() {
    echo '<div id="wlcms-footer-container">';
    if (get_option('wlcms_o_developer_url')) {
		echo '<a target="_blank" href="' . get_option('wlcms_o_developer_url') . '"><img src="'.get_bloginfo('template_directory').'/images/' . get_option('wlcms_o_footer_custom_logo') . '" id="wlcms-footer-logo"> ' . get_option('wlcms_o_developer_name') . '</a>';
	} else {
		echo '<img src="'.get_bloginfo('template_directory').'/images/' . get_option('wlcms_o_footer_custom_logo') . '" id="wlcms-footer-logo"> ' . get_option('wlcms_o_developer_name');
	}
	echo '</div><p id="safari-fix"';
}


// custom logo login
function wlcms_custom_login_logo() {
	echo '<style type="text/css">
	h1 a { background-image:url('.get_bloginfo('template_directory').'/images/' . get_option('wlcms_o_login_custom_logo') . ') !important; margin-bottom: 10px; }
	#login{ background: ' . get_option('wlcms_o_login_bg') . '; padding: 20px;-moz-border-radius:11px;-khtml-border-radius:11px;-webkit-border-radius:11px;border-radius:5px; }
	</style>';
}

/* add dashboard help widget */
function wlcms_custom_dashboard_help() {
	echo stripslashes(get_option('wlcms_o_welcome_text'));
}

if (get_option('wlcms_o_show_welcome') == 1) {
	function wlcms_add_dashboard_widget() {
		wp_add_dashboard_widget('custom_help_widget', get_option('wlcms_o_welcome_title'), 'wlcms_custom_dashboard_help');
	}
}

// actions
// remove dashboard widgets
if (get_option('wlcms_o_dashboard_remove_widgets') == 1) {
	add_action('wp_dashboard_setup', 'wlcms_remove_dashboard_widgets');
}

// remove nag update
if (get_option('wlcms_o_dashboard_remove_nag_update') == 1) {
	add_action( 'admin_init', create_function('', 'remove_action( \'admin_notices\', \'update_nag\', 3 );') );
}

// remove right now
if (get_option('wlcms_o_dashboard_remove_right_now') == 1) {
	add_action('wp_dashboard_setup', 'wlcms_remove_right_now');
}

// add dashboard widget
if (get_option('wlcms_o_show_welcome') == 1) {
	add_action('wp_dashboard_setup', 'wlcms_add_dashboard_widget' );
}

// custom logos
add_action('admin_head', 'wlcms_custom_logo');
add_action('login_head', 'wlcms_custom_login_logo');

// filter
add_filter('admin_footer_text', 'wlcms_remove_footer_admin');


// Contextual Help For Plugin
function my_contextual_help($text) {
	$screen = $_GET['page'];
	if ($screen == 'White Label CMS') {
		$text = "<h5>Need help with this plugin?</h5>";
		$text .= "<p>Check out the documentation and support forums for help with this plugin.</p>";
		$text .= "<a href=\"http://example.org/docs\">Documentation</a><br /><a href=\"http://example.org/support\">Support forums</a>";
	}
	return $text;
}
add_action('contextual_help', 'my_contextual_help');


/**********************************/
/*  Admin Page */
/**********************************/

$wlcmsThemeName = "White Label CMS";
$wlcmsShortName = "wlcms_o";

$wlcmsOptions = array (
 
array( "name" => $wlcmsThemeName." Options",
	"type" => "title"),
 

array( "name" => "Customization",
	"type" => "section"),
array( "type" => "open"),

array( "name" => "Hide 'Right Now'",
	"desc" => "This will hide the Right Now panel from the dashboard",
	"id" => $wlcmsShortName."_dashboard_remove_right_now",
	"type" => "radio",
	"options" => array("1", "0"),
	"std" => 0),
	
array( "name" => "Hide Other Dashboard Panels",
	"desc" => "This will hide all standard dashboard panels except the Right Now panel",
	"id" => $wlcmsShortName."_dashboard_remove_widgets",
	"type" => "radio",
	"options" => array("1", "0"),
	"std" => 1),
	
array( "name" => "Hide Nag Update",
	"desc" => "This will hide the Nag Update for out of date versions of wordpress",
	"id" => $wlcmsShortName."_dashboard_remove_nag_update",
	"type" => "radio",
	"options" => array("1", "0"),
	"std" => 1),

array( "name" => "Custom Header Logo",
	"desc" => "This is the logo that will appear in the header.  It should be a transparent .gif or.png and about 30px by 30px. You should upload the logo to the current theme /images/ directory",
	"id" => $wlcmsShortName."_header_custom_logo",
	"type" => "text",
	"std" => 'custom-logo.gif'),

array( "name" => "Custom Footer Logo",
	"desc" => "This is the logo that will appear in the footer.  It should be a transparent .gif or.png and about 30px by 30px. You should upload the logo to the current theme /images/ directory",
	"id" => $wlcmsShortName."_footer_custom_logo",
	"type" => "text",
	"std" => 'custom-logo.gif'),
	
array( "name" => "Developer Website URL",
	"desc" => "There will be a link to your website in the footer.  Leave it as blank if you don't want the otherwise please include http://",
	"id" => $wlcmsShortName."_developer_url",
	"type" => "text",
	"std" => ''),	
	
array( "name" => "Developer Website Name",
	"desc" => "The developer's name will appear in the footer",
	"id" => $wlcmsShortName."_developer_name",
	"type" => "text",
	"std" => ''),		

array( "name" => "Custom Login Logo",
	"desc" => "This logo will appear on the login page. It should be about 300px by 80px.",
	"id" => $wlcmsShortName."_login_custom_logo",
	"type" => "text",
	"std" => 'custom_login_logo.gif'),		

array( "name" => "Login Background Color",
	"desc" => "This is the color of the background which will contain your logo.",
	"id" => $wlcmsShortName."_login_bg",
	"type" => "text",
	"std" => '#FFFFFF'),			
	
array( "type" => "close"),
array( "name" => "Add Your Own Dashboard Panel",
	"type" => "section"),
array( "type" => "open"),

array( "name" => "Add You Own Welcome Panel?",
	"desc" => "This will appear on the dashboard.  We recommend providing your contact details and links to the help files you have made for your client.",
	"id" => $wlcmsShortName."_show_welcome",
	"type" => "radio",
	"options" => array("1", "0"),
	"std" => 0 ),	

array( "name" => "Video Variables",
	"type" => "subsectionvars"),

array( "name" => "Title",
	"desc" => "The title of your dashboard panel.",
	"id" => $wlcmsShortName."_welcome_title",
	"type" => "textlocalvideo",
	"std" => 'Welcome To Your New Website'),	

array( "name" => "Description",
	"desc" => "Please add the text in html format here.",
	"id" => $wlcmsShortName."_welcome_text",
	"type" => "textarea",
	"std" => ''),		
		
		
array( "type" => "close")
);


function wlcms_add_admin() {
 
global $wlcmsThemeName, $wlcmsShortName, $wlcmsOptions;


// setup options for first time
foreach ($wlcmsOptions as $value) {
		if ($value['id'] != '') {
			add_option( $value['id'], $value['std']  );
		}
}
 
 
if ( $_GET['page'] == 'wlcms-plugin.php') {
 
	if ( 'save' == $_REQUEST['action'] ) {
 
		foreach ($wlcmsOptions as $value) {
		update_option( $value['id'], $_REQUEST[ $value['id'] ] ); }
 
foreach ($wlcmsOptions as $value) {
	if( isset( $_REQUEST[ $value['id'] ] ) ) { update_option( $value['id'], $_REQUEST[ $value['id'] ]  ); } else { delete_option( $value['id'] ); } }
 
	header("Location: admin.php?page=wlcms-plugin.php&saved=true");
die;
 
} 
else if( 'reset' == $_REQUEST['action'] ) {
 
	foreach ($wlcmsOptions as $value) {
		delete_option( $value['id'] ); }
 
	header("Location: admin.php?page=wlcms-plugin.php&reset=true");
die;
 
}
}

}

function wlcms_add_init() {

wp_enqueue_style('white-label-cms', plugins_url('white-label-cms/css/wlcms_style.css'), false, '1.0', 'all');
wp_enqueue_script("white-label-cms", plugins_url('white-label-cms/scripts/wlcms_script.js'), false, "1.0");

}
function wlcms_admin() {
 
global $wlcmsThemeName, $wlcmsShortName, $wlcmsOptions;
$i=0;
 
if ( $_REQUEST['saved'] ) echo '<div id="message" class="updated fade"><p><strong>'.$wlcmsThemeName.' settings saved.</strong></p></div>';
if ( $_REQUEST['reset'] ) echo '<div id="message" class="updated fade"><p><strong>'.$wlcmsThemeName.' settings reset.</strong></p></div>';
 
?>
<div class="wrap wlcms_wrap">
<h2><?php echo $wlcmsThemeName; ?> Settings</h2>
 
<div class="wlcms_opts">
<form method="post">
<?php foreach ($wlcmsOptions as $value) {
switch ( $value['type'] ) {
 
case "open":
?>
 
<?php break;
 
case "close":
?>
 
</div>
</div>
<br />

 
<?php break;
 
case "title":
?>
<p><strong>For a detailed explanation of the plugin please refer to the help tab.</strong></p>

<p><em>Please Note:</em> Custom logo images should be uploaded to the current theme /images/ directory.</p> 
 
<?php break;
 
case 'text':
?>

<div class="wlcms_input wlcms_text">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
 	<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" value="<?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id'])  ); } else { echo $value['std']; } ?>" />
 <small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
 
 </div>
 
 <?php break;
 case 'textlocalvideo':
?>

<div class="wlcms_input_local_video wlcms_text">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
 	<input name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" type="text" value="<?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id'])  ); } else { echo $value['std']; } ?>" />
 <small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
 
 </div>
<?php
break;
 
case 'textarea':
?>

<div class="wlcms_input_welcome_last wlcms_textarea">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
 	<textarea name="<?php echo $value['id']; ?>" type="<?php echo $value['type']; ?>" cols="" rows=""><?php if ( get_settings( $value['id'] ) != "") { echo stripslashes(get_settings( $value['id']) ); } else { echo $value['std']; } ?></textarea>
 	<small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
 </div>
 </div><!-- end of subsection -->
  
<?php
break;
 
case 'select':
?>

<div class="wlcms_input wlcms_select">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
	
<select name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
<?php foreach ($value['options'] as $option) { ?>
		<option <?php if (get_settings( $value['id'] ) == $option) { echo 'selected="selected"'; } ?>><?php echo $option; ?></option><?php } ?>
</select>

	<small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
</div>
<?php
break;
 case "checkbox":
?>

<div class="wlcms_input wlcms_checkbox">
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>
	
<?php if(get_option($value['id'])){ $checked = "checked=\"checked\""; }else{ $checked = "";} ?>
<input type="checkbox" name="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>" value="true" <?php echo $checked; ?> />


	<small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
 </div>
<?php break; 
case "radio":
?>

<div class="wlcms_input wlcms_radio" <?php if($value['id'] == 'wlcms_o_show_welcome') { echo ' id="form-show-welcome" '; }?> >
	<label for="<?php echo $value['id']; ?>"><?php echo $value['name']; ?></label>

<?php 
$counter = 1;
foreach ($value['options'] as $option) { ?>
	<?php if(get_option($value['id']) ==  $option){ $checked = "checked=\"checked\""; }else{ $checked = ""; } ?>
	<label class="radioyesno"><?php if ($counter == 1) { echo 'yes'; } else { echo 'no'; } ?><input type="radio" name="<?php echo $value['id']; ?>" class="<?php echo $value['id']; ?>" value="<?php echo $option; ?>" <?php echo $checked; ?> /></label>
<?php
$counter++;
}
?>

	<small><?php echo $value['desc']; ?></small><div class="clearfix"></div>
 </div>
<?php break; 
case "section":

$i++;

?>

<div class="wlcms_section">
<div class="wlcms_title"><h3><img src="<?php bloginfo('wpurl')?>/wp-content/plugins/white-label-cms/images/trans.png" class="inactive" alt="""><?php echo $value['name']; ?></h3><span class="submit"><input name="save<?php echo $i; ?>" type="submit" value="Save changes" />
</span><div class="clearfix"></div></div>
<div class="wlcms_options" style="display: none;">

 
<?php break;
case "subsection":
?>
<div id="v<?php echo str_replace(" ", "", $value['name']); ?>" class="video-h">
<h4><?php echo $value['name']; ?> <span class="submit"><input type="submit" value="clear" onclick="clearvid('v<?php echo str_replace(" ", "", $value['name']); ?>');return false;" /></span></h4>
<div class="clearfix"></div>


<?php break;
case "subsectionvars":
?>
<div id="v<?php echo str_replace(" ", "", $value['name']); ?>" class="video-h">
<h4><?php echo $value['name']; ?></h4>
<div class="clearfix"></div>
 
<?php break;
 
}
}
?>
 
<input type="hidden" name="action" value="save" />
</form>
 </div> 
 
<form method="post">
<p class="submit" id="wlcm-reset">
Click here to reset the plugin: 
<input name="reset" type="submit" value="Reset" />
<input type="hidden" name="action" value="reset" />
</p>
</form> 
 
<div id="ajax_msg"></div>
<div id="ajax_content"></div>
<script type="text/javascript">
		jQuery.getJSON('http://wordpress.videousermanuals.com/white-label-cms.php?jsoncallback=?',
			{word:'foo'},
			function(data, textStatus){
				jQuery('#ajax_content').append(data);
			});
</script>

<?php
}
?>
<?php
add_action('admin_init', 'wlcms_add_init');
add_action('admin_menu', 'wlcms_add_admin');
?>