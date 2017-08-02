<?php
/**
 * @package Udorami_Lists
 * @version 1.3.5
 */
/*
Plugin Name: Udorami Lists
Plugin URI: http://wordpress.org/extend/plugins/udorami-lists/
Description: Include a list from the Udorami social lists website.
Version: 1.3.5
Author: Jamii Corley
Author URI: http://www.udorami.com/us/
License:     GPL2
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

$site = "https://www.udorami.com/lists";

if (is_admin()) {
    add_action( 'admin_menu', 'udorami_lists_menu' );
    add_action( 'admin_init', 'register_udorami_settings' );
}

function udorami_list_view ( $atts ) {
    global $site;
    $opt_name = 'udorami_api_key';
    $api_key = get_option( $opt_name );
    if ($atts['list']) { $list_id = $atts['list']; }
    else { return; }
    if ($atts['picsize'] && is_numeric($atts['picsize'])) { 
       $width = $atts['picsize']; 
       if ($width < 1 || $width > 1000) { $width = 100; }
    } else { 
       $width = 100; 
    }
    $author = 1;
    $title = 1;
    $link = 1;
    if (ISSET($atts['noauthor'])) { $author = 0; }
    if (ISSET($atts['nolink'])) { $link = 0; }
    if (ISSET($atts['notitle'])) { $title = 0; }
    $layout = 0;
    if (ISSET($atts['layout'])) { $layout = $atts['layout']; }
    $picture = 1;
    if (ISSET($atts['nopic'])) { $picture = 0; }
    $url = $site . "/wish_lists/rest_view/" .
           $list_id . "/" . $api_key . ".json";
    $response = wp_remote_get( $url );
    if ( is_wp_error( $response ) ) {
       $error_string = $response->get_error_message();
       return sprintf( '%s<br>The URL %1s could not be retrieved.', $error_string, $url );
    }
    $data = wp_remote_retrieve_body( $response );
    if ( ! is_wp_error( $data )  ) {
       $list = json_decode( $data, true );
       $wish_list = $list['wish_list'];
       if (! $wish_list['WishList']) { 
           print "No list available<br>"; 
           return;
       }
       $list_name = $wish_list['WishList']['name'];
       $owner = $wish_list['User']['first_name'] . " " .  
                $wish_list['User']['last_name'];
 
       $list_content = "";
       if ($title) {
           $list_content .= "<h2 class='umi_header'>$list_name</h2>\n";
       }
       if($author) {
          $list_content .= "by $owner<br>\n";
       }
       $num_items = $wish_list['WishList']['item_count'];
       if ($layout < 2) { $list_content .= "<table>"; }
       else { 
          $list_content .= "<div id='umi_bento' data-columns>"; 
       }
              
       $item = $wish_list['Item'];
       for ($i = 0; $i < $num_items; $i++) {
           $prod = $item[$i]['Product'];
           $description = $item[$i]['description'];
           $url = $prod['url'];
           $pict_url = $item[$i]['pict_url'];
           $name = $prod['name'];
           $add = " width=$width ";
           if ($layout == 2) { $add = " width=90% class='umi_horiz_center' "; }
           if ($pict_url && $picture) {
                $box_pict = "<img $add src='$pict_url'>\n";
           } else {
               if ($picture) {
                   $box_pict = "&nbsp;";
               } else {
                   $box_pict = "";
               }
           }
           if (! $link && $prod['local_url'] == 1) {
               $box_text = "<br /><br /><b>$name</b>\n"; 
           } else {
               $box_text = "<br /><br /><a target='_prod' href='$url'>$name</a>\n"; 
           }
           $box_text .= "<br>$description"; 
           if ($layout < 2) { $list_content .= "<tr><td>\n"; }
           else { $list_content .= "<div class='item'>"; }
           if ($layout == 2) {
               $list_content .= "<div class='umi_pict umi_center'>$box_pict</div>";
               $list_content .= "<div class='umi_center umi_name'>$box_text</div>";
           } else {
               if ($layout == 1 && (($i % 2) == 1)) {
                   $list_content .= "<div class='umi_horiz_center umi_pict' style='float:right;'>$box_pict</div>";
                   $list_content .= "<div class='umi_name umi_horiz_center'>$box_text</div>";
               } else {
                   $list_content .= "<div class='umi_horiz_center umi_pict' style='float:left;'>$box_pict</div>";
                   $list_content .= "<div class='umi_name umi_horiz_center'>$box_text</div>";
               }
           }
           if ($layout < 2) {
               $list_content .= "</td></tr>\n";
           } else {
               $list_content .= "</div>\n";
           }
       }
       if ($layout < 2) {
           $list_content .= "</table>";
       } else { 
           $list_content .= "</div>";
       }
    }
    return $list_content;
}

function udorami_lists_menu() {
	add_options_page( 'Udorami API Key', 'Udorami Lists', 'manage_options', 'udorami', 'udorami_options' );
}

function register_udorami_settings() {
  register_setting( 'udorami-group', 'udorami_api_key' );
}

function udorami_options() {
        global $site;
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
        $opt_name = 'udorami_api_key';
        $opt_val = get_option( $opt_name );

	echo '<div class="wrap">';
        echo '<h2>Udorami Lists Setup</h2>';
        echo '<form method="post" action="options.php">';
        settings_fields( 'udorami-group' );
        do_settings_sections( 'udorami-group' );
        echo 'API Key <input type="text" name="' . $opt_name .
             '" value="' . $opt_val . '" size=50>';
        submit_button();
        echo '</form>';
        echo "<h2>Usage</h2>";
        echo "A Udorami list is displayed by the shortcode <em>udorami_list</em>\n";
        echo "You need to know the list-id of the list you want to display.<br>\n";
        echo "To find your list-id, login to udorami.com, go to your list.<br>\n";
        echo "The URL for you list will look like this:<br>\n";
        echo "<p>$site/wish_lists/view/41</p>\n";
        echo "The list-id in this example is 41. Your short-code will look like this:<br>\n";
        echo "<p>[udorami_list list=41]</p>\n";
        echo "Options you can are are ";
        echo "<ol><li><b>nopic=1</b> - Don't show the picture.</li>\n";
        echo "<li><b>picsize=200</b> - Set the max picture size in pixels.</li>\n";
        echo "<li><b>layout=0</b> - Default. List, pictures to the left.</li>\n";
        echo "<li><b>layout=1</b> - List, alternating pictures left, then right.</li>\n";
        echo "<li><b>layout=2</b> - Masonry grid.</li>\n";
        echo "<li><b>noauthor=1</b> - Don't show the list author.</li>\n";
        echo "<li><b>notitle=1</b> - Don't show the list title.</li>\n";
        echo "<li><b>nolink=1</b> - Don't link to www.udorami.com items.</li>\n";
        echo "</ul>\n";
	echo '</div>';
}

function umi_enqueue() {
    global $post;
    if( is_a( $post, 'WP_Post' ) && 
              has_shortcode( $post->post_content, 'udorami_list') ) {
         wp_register_style( 'umi_salvattore_style', plugins_url('css/umi_salvattore.css', __FILE__) );
         wp_register_script('umi_salvattore', 
                            plugin_dir_url(__FILE__) . 'js/salvattore.min.js', 
                            array('jquery'), '1.0', true);
         wp_enqueue_script('umi_salvattore');
         wp_enqueue_style( 'umi_salvattore_style' );
    }
}
add_action( 'wp_enqueue_scripts', 'umi_enqueue' );
add_shortcode( 'udorami_list', 'udorami_list_view' ); 

?>
