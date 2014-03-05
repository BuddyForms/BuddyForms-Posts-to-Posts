<?php

/*
Plugin Name: BuddyForms Posts to Posts
Plugin URI: http://themekraft.com
Description: BuddyForms Posts to Posts
Version: 0.1
Author: Sven Lehnert
Author URI: http://themekraft.com/members/svenl77/
Licence: GPLv3
Network: false
*/


/*
 * Create new connection type for each form with a form field posts-to-posts
 *
 */
add_action( 'p2p_init', 'bf_posts_to_posts_connection_types' );
function bf_posts_to_posts_connection_types() {
    global $buddyforms, $post;

    foreach ($buddyforms['buddyforms'] as $key => $buddyform) {
        foreach ($buddyform['form_fields'] as $field) {

            if($field['type'] === 'posts-to-posts'){

                p2p_register_connection_type( array(
                    'name' => $field['slug'],
                    'from' => $field['posts_to_posts_from'],
                    'to' => $field['posts_to_posts_to']
                ) );

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
add_action('buddyforms_update_post_meta', 'bf_posts_to_posts_updtae_post_meta', 99, 2);
function bf_posts_to_posts_updtae_post_meta($customfield, $post_id){

    $form_slug = get_post_meta($post_id, '_bf_form_slug', true);

    if(!isset($form_slug))
        return;

    if( $customfield['type'] == 'posts-to-posts' ){

        $connections = $_POST[ $customfield['slug'] ];
        // Create connection
        if( isset( $connections ) ) {
            foreach ( $connections as $connection) {
                p2p_type( 'post-to-post' )->connect( $post_id, $connection, array(
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
 * @param array selected post types
 *
 * @return the form object
 */
add_filter('buddyforms_add_form_element_in_sidebar','bf_posts_to_posts_add_form_element_in_sidebar',1,2);
function bf_posts_to_posts_add_form_element_in_sidebar($form, $selected_post_types){
   $form->addElement(new Element_HTML('<p><a href="posts-to-posts/'.$selected_post_types.'" class="action">Posts to Posts</a></p>'));
    return $form;
}

/*
 * Display the form element in the frontend form
 *
 */
add_filter('buddyforms_create_edit_form_display_element','bf_posts_to_posts_create_edit_form_display_element',1,5);
function bf_posts_to_posts_create_edit_form_display_element($form,$post_id,$form_slug,$customfield,$customfield_val){
    global $buddyforms, $WP_Query;

    if($customfield['type']  == 'posts-to-posts'){

        $posts_to_posts_to_value = '';
        if(isset($customfield['posts_to_posts_to']))
            $posts_to_posts_to_value	= $customfield['posts_to_posts_to'];

        $element_attr = isset($customfield['required']) ? array('required' => true, 'value' => $customfield_val, 'class' => 'settings-input chosen', 'shortDesc' =>  $customfield['description']) : array('value' => $customfield_val, 'class' => 'settings-input chosen', 'shortDesc' =>  $customfield['description']);

        $args = array(
            'post_type'     => $posts_to_posts_to_value,
        );

        $the_query = new WP_Query( $args );

        if ( $the_query->have_posts() ) {
            $options['none'] = 'none';
            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                $options[get_the_ID()] = get_the_title();
            }
        }

        if(is_array($options)){

            $element = new Element_Select($customfield['name'], $customfield['slug'], $options, $element_attr);
            $element->setAttribute('multiple', 'multiple');

            bf_add_element($form, $element);
        }

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

    $posts_to_posts_from_value = '';
    if(isset($buddyforms['buddyforms'][$form_slug]['form_fields'][$field_id]['posts_to_posts_from']))
        $posts_to_posts_from_value	= $buddyforms['buddyforms'][$form_slug]['form_fields'][$field_id]['posts_to_posts_from'];
    $posts_to_posts_to_value = '';
    if(isset($buddyforms['buddyforms'][$form_slug]['form_fields'][$field_id]['posts_to_posts_to']))
        $posts_to_posts_to_value	= $buddyforms['buddyforms'][$form_slug]['form_fields'][$field_id]['posts_to_posts_to'];

// Get all post types
    $args=array(
        'public' => true,
        'show_ui' => true
    );
    $output = 'names'; // names or objects, note: names is the default
    $operator = 'and'; // 'and' or 'or'
    $post_types = get_post_types($args,$output,$operator);
    $post_types_none['none'] = 'none';
    $post_types = array_merge($post_types_none,$post_types);

    $form_fields['left']['posts_to_posts_from'] 	= new Element_Select("from:", "buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][posts_to_posts_from]", $post_types, array('value' => $posts_to_posts_from_value));
    $form_fields['left']['posts_to_posts_to'] 	= new Element_Select("to:", "buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][posts_to_posts_to]", $post_types, array('value' => $posts_to_posts_to_value));

    return $form_fields;
}