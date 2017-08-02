=== Udorami Lists ===
Contributors: udorami
Donate link: http://www.udorami.com/adding-udorami-lists-to-your-blog/
Tags: lists, collaborative lists, udorami-lists, udorami
Requires at least: 3.9
Tested up to: 4.7.5
Stable tag: 1.3.5 
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Udorami-Lists allow a WordPress site to embed a Udorami list in a post or page, with various styling options.

== Description ==
Udorami.com is a social list site. Lists can be built by a single user or a group.
Lists can be public or group viewable. The groups that build the list can be 
different from the groups that can view the lists. Public lists can be embedded 
in WordPress websites using this plugin. The lists can be displayed in several 
different style, from a compact text only to masonry grids. Note: udorami-lists
uses the Salvattore masonry library. See js/LICENSE for further information.

== Installation ==

1. Prerequites: you need to have a Udorami account.
2. Upload the plugin files to the `/wp-content/plugins/udorami-lists` directory, 
   or install the plugin through the WordPress plugins screen directly.
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Settings->Udorami Lists - Add your API_Key to configure the plugin.
   This can be found in on the My Account page in the Udorami tool.

== Frequently Asked Questions ==

= Can we view a private list? =

Not at this point in time. We may have a bridge between WordPress
accounts and Udorami accounts in the future.

= How do I configure the plugin? =

Login to Udorami, View your account. There is a unique API Key generated
for each Udorami customer. Grab the API Key, login to your WordPress
site as an admin. Select "Configure Udorami" under settings and paste
the API Key in. 

= How do I show a list? =

When viewing a list on the Udorami site you'll notice the list number
on the URL: http://www.udorami.com/wish_lists/view/<list number>
While editing a post or page you can add a short code to view any
public list.

   [ udorami_list list=<list number> ]

= Are there other options? =

Yes, when you configure the plugin with your API key you will find
additional documentation on other available options for the plugin.

== Screenshots ==

== Changelog ==

= 1.3.5 =
  Only enqueue the js/css if the shortcode is actually on the page.
  This helps with the conflict issue with the DIVI blog module (grid mode)

= 1.3.3 =
  Added some better error handling when is_wp_error is true.

= 1.3.2 =
  Fixing up plugin to indicate udorami.com the company owns the plugin
  Running with WP_DEBUG produces some issues, these are fixed.

= 1.3.1 =
  Getting ready to submit the plugin to the WP repository.

= 1.3 =
  Added notitle, and nolink options.
  Notitle - don't show the list name
  Nolink - don't link back to an internal Udorami list.
   
= 1.21, 1.22 =
* Minor bug fixes

= 1.2 =
* Become responsive in the masonry grid

= 1.1 = 
* Added a masonry grid layout

= 1.0 =
* Initial version.

== Upgrade Notice ==

= 1.3.5 =
  If you've having problems with other plugins/themes using Masonry layout this
  upgrade should help.
