<?php
/*
Plugin Name: Show Template Name
Plugin URI: http://mintcreative.github.io/show-template-name/
Description: A simple plugin that puts the filename of any page template into a comment tag in the &lt;head&gt; of your HTML. You can also add an optional comment of your own using the plugin's settings page.
Author: Jim Richards
Author URI: http://mintcreative.github.io
Version: 1.0
*/

// function to create / register a settings page for the wp admin menu

function stn_plugin_settings() {

    add_menu_page('Show Template Name Settings', 'Show Template Name Settings', 'administrator', 'stn_settings', 'stn_display_settings');

}


// define the content of the settings page
function stn_display_settings() {


	// put the user-defined message setting into a variable
    $mymsg = get_option('stn_msg');

    // define html for the form, to be rendered on the plugin options page
    $html = '<div class="wrap">
<form action="options.php" method="post" name="options">
<h2>Enter your additional comment text</h2>
<h4>Whatever you type here will be inserted as an HTML comment, before the template name.</h4>
' . wp_nonce_field('update-options') . '
<table class="form-table" width="100%" cellpadding="10">
<tbody>
<tr>
<td scope="row" align="left">
 <label>Message:</label><input type="text" name="stn_msg" value="' . $mymsg . '" /></td>
</tr>
</tbody>
</table>
 <input type="hidden" name="action" value="update" />

 <input type="hidden" name="page_options" value="stn_msg" />

 <input type="submit" name="Submit" value="Update" />
 </form>
 </div>
';

    echo $html;

}



// add settings page to admin menu using admin_menu action hook
add_action('admin_menu', 'stn_plugin_settings');


// main function to show the page template filename in an HTML comment
function show_template_name()
{
	// get_option the user's message
	// if something exists, echo it in a comment tag
	$user_msg = get_option('stn_msg');
	if($user_msg != ""){
	echo '<!-- ' . $user_msg . ' -->';
	echo "\r\n";
} else {echo '<!-- No user msg -->';echo "\r\n";}

	// query the template to get the filename
global $wp_query;
	$template_name = get_post_meta( $wp_query->post->ID, '_wp_page_template', true );
	// if the template name exists, echo it into a comment tag
	if($template_name != ""){
	echo '<!-- Show Template Name: ' . $template_name . ' -->';
	echo "\r\n";
				} else {echo '<!-- Show Template Name: nothing was found. -->';echo "\r\n";}

}

// add our function when wp_head is called
add_action('wp_head', 'show_template_name');
