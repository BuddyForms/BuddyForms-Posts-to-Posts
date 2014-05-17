=== BuddyForms Posts to Posts Integration ===

Contributors: svenl77
Tags: collaborative, publishing, buddypress, groups, custom post types, taxonomy, frontend, posting, editing, forms, form builder, wp-posts-to-posts, post relationship, connections, custom post types, relationships, many-to-many, users
Requires at least: WordPress 3.x
Tested up to: WordPress 3.9.1
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds WP Posts to Posts Plugin Integration to BuddyForms


== Description ==

This is the BuddyForms Posts to Posts Integration Extension. You need the BuddyForms plugin installed for the plugin to work. <a href="http://themekraft.com/store/wordpress-front-end-editor-and-form-builder-buddyforms/" target="_blank">Get BuddyForms now!</a>

You need the Posts to Posts plugin installed for the plugin to work. For more information on Posts to Posts see here: https://wordpress.org/plugins/posts-to-posts/

<h4>How it Works</h4>
With BuddyForms Posts to Posts Integration you can create complex connections and post relationships across your Site.
From Posts to Pages or to Users all the Posts to Posts Plugin functionality is in your BuddyForms Form Builder available.

With the ( BuddyForms Attach Posts to Groups Extension ) and the ( Posts to Posts Plugin ), you can Create Groups to Posts or even Groups to Groups relationships based on the Groups Attached Posts.

Find out more about BuddyForms Attach Posts to Groups Extension here: http://wordpress.org/plugins/buddyforms-attach-posts-to-groups-extension/


<h4>Form Elements</h4>

The Plugin creates a new Form Element "posts-to-posts" with 2 options for your BuddyForms FormBuilder.

from: post, page or any post type<br>
to: post, page, user, any post type


<h4>Add Additional Parameter</h4>

Before the connection gets registered with p2p_register_connection_type($args); you can manipulate the $args with a filter.

$args = array(<br>
    'name'  => $field['slug'],<br>
    'from'  => $field['posts_to_posts_from'],<br>
    'to'    => $field['posts_to_posts_to']<br>
);<br>
$args  = apply_filters('connection_types_args', $args, $buddyform['slug']);<br>

There is documentation available for the Posts to Posts Plugin and all the available parameter.

http://github.com/scribu/wp-posts-to-posts/wiki

== Documentation & Support ==

You can find all help buttons in your BuddyForms Settings Panel in your WP Dashboard! 

Documentation
http://support.themekraft.com/categories/20110697-BuddyForms 

Support Tickets
You can also create support tickets from the BuddyForms Settings Panel in your WP Dashboard!

== Installation ==

You can download and install BuddyForms Posts to Posts Integration using the built in WordPress plugin installer. If you download BuddyForms Posts to Posts Integration manually,
make sure it is uploaded to "/wp-content/plugins/".

Activate BuddyForms Posts to Posts Integration Plugin in the "Plugins" admin panel using the "Activate" link. If you're using WordPress Multisite, you can optionally activate BuddyForms Posts to Posts Integration Network Wide.

== Screenshots ==

coming soon

== Changelog ==

Version 1.0