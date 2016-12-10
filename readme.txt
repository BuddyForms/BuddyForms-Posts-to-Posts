=== BuddyForms Posts 2 Posts ===

Contributors: svenl77, konradS, buddyforms, themekraft
Tags: collaborative, publishing, buddypress, groups, custom post types, taxonomy, frontend, posting, editing, forms, form builder, wp-posts-to-posts, post relationship, connections, custom post types, relationships, many-to-many, users
Requires at least: 3.9
Tested up to: 4.7
Stable tag: 1.0.9
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds WP Posts 2 Posts Plugin Integration to BuddyForms

== Description ==

This is the BuddyForms Posts 2 Posts Integration Extension. You need the BuddyForms plugin installed for the plugin to work. <a href=https://wordpress.org/plugins/buddyforms/" target="_blank">Get BuddyForms now!</a>

You need the Posts 2 Posts plugin installed for the plugin to work. For more information on Posts 2 Posts see here: https://wordpress.org/plugins/posts-to-posts/

<h4>How it Works</h4>
With BuddyForms Posts 2 Posts Integration you can create complex connections and post relationships across your site.
From posts to pages or to users all the Posts 2 Posts Plugin functionality is in your BuddyForms Form Builder available.

With the 'BuddyForms Attach Posts to Groups Extension‘ and the 'Posts 2 Posts Plugin' you can create groups to posts or even groups to groups relationships based on the Groups Attached Posts.

Find out more about 'BuddyForms Attach Posts to Groups Extension' here: http://wordpress.org/plugins/buddyforms-attach-posts-to-groups-extension/


<h4>Form Elements</h4>

The Plugin creates a new form element "posts-to-posts" with 2 options for your BuddyForms FormBuilder.

from: post, page or any post type<br>
to: post, page, user, any post type


<h4>Add Additional Parameter</h4>

Before the connection gets registered with p2p_register_connection_type($args) you can manipulate the $args with a filter.

$args = array(<br>
    'name'  => $field['slug'],<br>
    'from'  => $field['posts_to_posts_from'],<br>
    'to'    => $field['posts_to_posts_to']<br>
);<br>
$args  = apply_filters('connection_types_args', $args, $buddyform['slug']);<br>

There is documentation available for the Posts 2 Posts Plugin and all the available parameter.

http://github.com/scribu/wp-posts-to-posts/wiki

== Documentation & Support ==

You can find all help buttons in your BuddyForms Settings Panel in your WP Dashboard!

Documentation
http://docs.buddyforms.com

Support Tickets
You can also create support tickets from the BuddyForms Settings Panel in your WP Dashboard!

== Installation ==

You can download and install BuddyForms Posts 2 Posts Integration using the built in WordPress plugin installer. If you download BuddyForms Posts 2 Posts Integration manually,
make sure it is uploaded to "/wp-content/plugins/".

Activate BuddyForms Posts 2 Posts Integration Plugin in the "Plugins" admin panel using the "Activate" link. If you're using WordPress Multisite, you can optionally activate BuddyForms Posts 2 Posts Integration Network Wide.

== Screenshots ==

coming soon

== Changelog ==

= 1.0.9 =
Fixed and issue with the dependencies check. The function tgmpa does not accepted an empty array.

= 1.0.8 =
Add dependencies management with tgm
Rename buddyforms_add_form_element_to_select to buddyforms_add_form_element_select_option

= 1.0.7 =
New option Select multiple
New option Only display posts of the logged in user in the relation select
Fixed some notice of undefined index
Only show form type related form elements
Optimise and clean up the code

= 1.0.6 =
add admin notice to check if all requred plugins are activated
add support for the form builder form element select

= 1.0.5 =
fixed smaller issues
add compatibility for the latest buddyforms version

= 1.0.4 =
Make it work with the latest version of BuddyForms. the BuddyForms array has changed so I adjust the code too the new structure

= 1.0.3 =
switch from choose to select2
rename session
change the url to buddyforms.com

= 1.0.2 =
changed the hook to $form_args instead of attributes

= 1.0.1 =
Rename hooks
Spelling Corrections

Version 1.0
