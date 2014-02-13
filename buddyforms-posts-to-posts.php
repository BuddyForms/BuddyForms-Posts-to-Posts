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
add_action( 'p2p_init', 'bf_posts_to_posts_connection_types' );


function bf_posts_to_posts_add_form_element_in_sidebar($form, $selected_post_types){


    $form->addElement(new Element_HTML('<p><a href="posts-to-posts/'.$selected_post_types.'" class="action">Posts to Posts</a></p>'));

    return $form;
}
add_filter('buddyforms_add_form_element_in_sidebar','bf_posts_to_posts_add_form_element_in_sidebar',1,2);

function bf_posts_to_posts_create_edit_form_display_element($form,$post_id,$form_slug,$customfield,$customfield_val){
    global $buddyforms;

    if($customfield['type']  == 'posts-to-posts'){

        $posts_to_posts_to_value = '';
        if(isset($customfield['posts_to_posts_to']))
            $posts_to_posts_to_value	= $customfield['posts_to_posts_to'];

        $multiple = '';
        if(isset($customfield['multiple']))
            $multiple = $customfield['multiple'];

        $element_attr = isset($customfield['required']) ? array('required' => true, 'value' => $customfield_val, 'class' => 'settings-input', 'shortDesc' =>  $customfield['description']) : array('value' => $customfield_val, 'class' => 'settings-input chosen', 'shortDesc' =>  $customfield['description']);


        $args = array(
            'post_type'     => 'page',
            'echo'          => false

        );

        $dropdown = wp_dropdown_pages($args);

        if (isset($customfield['multiple']) && is_array( $customfield['multiple'] ))
            $dropdown = str_replace('id=', 'multiple="multiple" class="postform chosen" id=', $dropdown);

        if (is_array($customfield_val)) {
            foreach ($customfield_val as $value) {
                $dropdown = str_replace(' value="' . $value . '"', ' value="' . $value . '" selected="selected"', $dropdown);
            }
        }

        $element = new Element_HTML('<label>'.$customfield['name'] . ':</label><p><i>' . $customfield['description'] . '</i></p>');
        bf_add_element($form, $element);

        $element = new Element_HTML($dropdown);
        bf_add_element($form, $element);




    }
    return $form;

}
add_filter('buddyforms_create_edit_form_display_element','bf_posts_to_posts_create_edit_form_display_element',1,5);

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

    $multiple = 'false';
    if(isset($buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['multiple']))
        $multiple = $buddyforms_options['buddyforms'][$form_slug]['form_fields'][$field_id]['multiple'];
    $form_fields['left']['multiple'] = new Element_Checkbox("Multiple:","buddyforms_options[buddyforms][".$form_slug."][form_fields][".$field_id."][multiple]",array(''),array('value' => $multiple));

    return $form_fields;
}
add_filter('buddyforms_form_element_add_field','bf_posts_to_posts_form_element_add_field_ge',1,5);