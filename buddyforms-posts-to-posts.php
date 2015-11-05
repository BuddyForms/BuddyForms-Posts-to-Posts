<?php

/*
Plugin Name: BuddyForms Posts 2 Posts
Plugin URI: http://buddyforms.com/downloads/buddyforms-posts-2-posts/
Description: BuddyForms Posts to Posts Integration
Version: 1.0.3
Author: svenl77, buddyforms
Author URI: https://profiles.wordpress.org/svenl77
Licence: GPLv3
Network: false

 *****************************************************************************
 *
 * This script is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 ****************************************************************************
 */


/*
 * Create new connection type for each form with a form field posts-to-posts
 *
 */
add_action( 'p2p_init', 'bf_posts_to_posts_connection_types' );
function bf_posts_to_posts_connection_types() {
    global $buddyforms, $post;

    if(!isset($buddyforms))
        return;

    foreach ($buddyforms as $key => $buddyform) {

        if( isset( $buddyform['form_fields'] ) ) {

            foreach ($buddyform['form_fields'] as $field) {

                if($field['type'] === 'posts-to-posts'){

                    $args = array(
                        'name'  => $field['slug'],
                        'from'  => $field['posts_to_posts_from'],
                        'to'    => $field['posts_to_posts_to']
                    );

                    $args  = apply_filters('connection_types_args', $args, $buddyform['slug']);

                    p2p_register_connection_type($args);

                }

            }

        }

    }
}

/*
 * Save new connections on post save
 *
 * @param array the custom field meta
 * @param string the post id
 *
 */
add_action('buddyforms_update_post_meta', 'bf_posts_to_posts_update_post_meta', 99, 2);
function bf_posts_to_posts_update_post_meta($customfield, $post_id){

    $form_slug = get_post_meta($post_id, '_bf_form_slug', true);

    if(!isset($form_slug))
        return;


    if( $customfield['type'] == 'posts-to-posts' ){

        if(!isset($_POST[ $customfield[ 'slug' ] ]))
            return;

        // Get the connections
        $connections = $_POST[ $customfield[ 'slug' ] ];


        // if the form element 'to' value is 'user' delete all users otherwise delete all post ID's
        if($customfield['posts_to_posts_to'] == 'user'){

            // Get the connected user ID's
            $connected =  new WP_User_Query( array(
                'connected_type' => $customfield[ 'slug' ],
                'connected_items' => $user,
            ) );

            if ( ! empty( $connected->results ) ) {
                foreach ( $connected->results as $user ) {
                    p2p_type( $customfield[ 'slug' ] )->disconnect( $post_id, $user->ID );
                }
            }

        } else {

            // Get the connected post ID's
            $connected = p2p_type( $customfield[ 'slug' ] )->get_connected( $post_id );

            // Delete all connected ID's
            if ( $connected->have_posts() ) :
                while ( $connected->have_posts() ) : $connected->the_post();
                    p2p_type( $customfield[ 'slug' ] )->disconnect( $post_id, get_the_ID() );
                endwhile;
                wp_reset_postdata();
            endif;

        }


        // Create connections from the form values
        if( isset( $connections ) ) {
            foreach ( $connections as $connection) {
                p2p_type( $customfield[ 'slug' ] )->connect( $post_id, $connection, array(
                    'date' => current_time('mysql')
                ) );
            }
        }

    }
}

/*
 * Add a new form element to the form create view sidebar
 *
 * @param object the form object
 * @param array selected form
 *
 * @return the form object
 */
add_filter('buddyforms_add_form_element_to_sidebar','bf_posts_to_posts_add_form_element_to_sidebar');
function bf_posts_to_posts_add_form_element_to_sidebar($sidebar_elements){
    global $post;

    if($post->post_type != 'buddyforms')
        return;


    $sidebar_elements[] = new Element_HTML('<p><a href="#" data-fieldtype="posts-to-posts" class="bf_add_element_action">Posts to Posts</a></p>');

    return $sidebar_elements;

}

/*
 * Display the form element in the frontend form
 *
 */
add_filter('buddyforms_create_edit_form_display_element','bf_posts_to_posts_create_edit_form_display_element',1,2);
function bf_posts_to_posts_create_edit_form_display_element($form, $form_args){

    extract($form_args);

    //  If the custom field type is not posts-to-posts get out of here ;-)
    if($customfield['type']  != 'posts-to-posts')
        return $form;

    $customfield_to = '';
    if(isset($customfield['posts_to_posts_to']))
        $customfield_to	= $customfield['posts_to_posts_to'];

    $element_attr = isset($customfield['required']) ? array('required' => true, 'value' => $customfield_val, 'class' => 'settings-input bf-select2', 'shortDesc' =>  $customfield['description']) : array('value' => $customfield_val, 'class' => 'settings-input bf-select2', 'shortDesc' =>  $customfield['description']);

    // If the custom field 'to' option is sett to 'user' display user otherwise display posts
    if($customfield_to == 'user'){
        global $wpdb;

        $wp_user_search = $wpdb->get_results("SELECT ID, display_name FROM $wpdb->users ORDER BY ID");

        foreach ( $wp_user_search as $userid ) {
            $user_id       = (int) $userid->ID;
            $display_name  = stripslashes($userid->display_name);

            $options[$user_id] = $display_name;
        }

    } else {
        $args = array(
            'post_type'     => $customfield_to,
            'posts_per_page' => '-1',
        );

        $the_query = new WP_Query( $args );

        if ( $the_query->have_posts() ) {
            $options['none'] = 'none';
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                $options[get_the_ID()] = get_the_title();
            }
        }

    }

    // If posts or users exist create the form element
    if(is_array($options)){

        $element = new Element_Select($customfield['name'], $customfield['slug'], $options, $element_attr);
        $element->setAttribute('multiple', 'multiple');

        bf_add_element($form, $element);

    }

    return $form;

}

/*
 * Create the new form field for the form create view
 *
 */
add_filter('buddyforms_form_element_add_field','bf_posts_to_posts_form_element_add_field_ge',1,5);
function bf_posts_to_posts_form_element_add_field_ge($form_fields, $form_slug, $field_type, $field_id){
    global $buddyforms;

    if($field_type != 'posts-to-posts')
        return $form_fields;

    // Get the from value
    $customfield_from = '';
    if(isset($buddyforms[$form_slug]['form_fields'][$field_id]['posts_to_posts_from']))
        $customfield_from	= $buddyforms[$form_slug]['form_fields'][$field_id]['posts_to_posts_from'];

    // Get the to value
    $customfield_to = '';
    if(isset($buddyforms[$form_slug]['form_fields'][$field_id]['posts_to_posts_to']))
        $customfield_to	= $buddyforms[$form_slug]['form_fields'][$field_id]['posts_to_posts_to'];

    // Get all post types
    $args=array(
        'public' => true,
        'show_ui' => true
    );
    $output = 'names'; // names or objects, note: names is the default
    $operator = 'and'; // 'and' or 'or'
    $post_types = get_post_types($args,$output,$operator);


    $form_fields['general']['posts_to_posts_from'] 	= new Element_Select("from:", "buddyforms_options[".$form_slug."][form_fields][".$field_id."][posts_to_posts_from]", $post_types, array('value' => $customfield_from));
    $post_types['user'] = 'user';
    $form_fields['general']['posts_to_posts_to'] 	= new Element_Select("to:", "buddyforms_options[".$form_slug."][form_fields][".$field_id."][posts_to_posts_to]", $post_types, array('value' => $customfield_to));

    return $form_fields;
}