<?php

function ag_publish_edit_alert( $prop_id, $msg, $user_id ){

    include_once ( AG_DIR.'classes/class-rega.php' );
    $valid_status = REGA::is_valid_ad( $prop_id, $user_id );

    if( $valid_status === true ){
        $class = 'success';
    }else{
        $msg = 'عذرا لن يتم نشر الاعلان بناء علي تعليمات الهيئة العامه للعقار للاسباب الاتية </br></br>';
        foreach ($valid_status as $error_msg) {
            $msg .= "- $error_msg </br>";
        }
        $class = 'danger';
    }

    if( (isset($_GET['success']) && $_GET['success'] == 1 ) || (isset($_GET['updated']) && $_GET['updated'] == 1 )  ) { ?>
        <div class="alert alert-<?= $class; ?>" role="alert">
            <?= $msg; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php } 

};
function houzez_get_search_taxonomies($taxonomy_name, $searched_data = "", $args = array() ){
        
    $hide_empty = false;
    if($taxonomy_name == 'property_city' || $taxonomy_name == 'property_area' || $taxonomy_name == 'property_country' || $taxonomy_name == 'property_state') {
        $hide_empty = houzez_hide_empty_taxonomies();
    }
    
    $defaults = array(
        'taxonomy' => $taxonomy_name,
        'orderby'       => 'name',
        'order'         => 'ASC',
        'hide_empty'    => $hide_empty,
    );

    $args       = wp_parse_args( $args, $defaults );
    $taxonomies = get_terms( $args );

    if ( empty( $taxonomies ) || is_wp_error( $taxonomies ) ) {
        return false;
    }

    $output = '';
    foreach( $taxonomies as $category ) {
        if( $category->parent == 0 ) {

            $data_attr = $data_subtext = '';

            if( $taxonomy_name == 'property_city' ) {
                $term_meta= get_option( "_houzez_property_city_$category->term_id");
                $parent_state = isset($term_meta['parent_state']) ? $term_meta['parent_state'] : '';
                $parent_state = sanitize_title($parent_state);
                $data_attr = 'data-belong="'.esc_attr($parent_state).'"';
                $data_subtext = '';

            } elseif( $taxonomy_name == 'property_area' ) {
                $term_meta= get_option( "_houzez_property_area_$category->term_id");
                $parent_city = isset($term_meta['parent_city']) ? $term_meta['parent_city'] : '';
                $parent_city = sanitize_title($parent_city);
                $data_attr = 'data-belong="'.esc_attr($parent_city).'"';
                $data_subtext = '';

            } elseif( $taxonomy_name == 'property_state' ) {
                $term_meta = get_option( "_houzez_property_state_$category->term_id");
                $parent_country = isset($term_meta['parent_country']) ? $term_meta['parent_country'] : '';
                $parent_country = sanitize_title($parent_country);
                $data_attr = 'data-belong="'.esc_attr($parent_country).'"';
                $data_subtext = 'data-subtext="';

            }

            if ( !empty($searched_data) && in_array( $category->slug, $searched_data ) ) {
                $output.= '<option data-ref="'.esc_attr($category->slug).'" '.$data_attr.' '.$data_subtext.' value="' . esc_attr($category->slug) . '" selected="selected">'. esc_attr($category->name) . '</option>';
            } else {
                $output.= '<option data-ref="'.esc_attr($category->slug).'" '.$data_attr.' '.$data_subtext.' value="' . esc_attr($category->slug) . '">' . esc_attr($category->name) . '</option>';
            }

            foreach( $taxonomies as $subcategory ) {
                if($subcategory->parent == $category->term_id) {

                    $data_attr_child = '';
                    if( $taxonomy_name == 'property_city' ) {
                        $term_meta= get_option( "_houzez_property_city_$subcategory->term_id");
                        $parent_state = isset($term_meta['parent_state']) ? $term_meta['parent_state'] : '';
                        $parent_state = sanitize_title($parent_state);
                        $data_attr_child = 'data-belong="'.esc_attr($parent_state).'"';

                    } elseif( $taxonomy_name == 'property_area' ) {
                        $term_meta= get_option( "_houzez_property_area_$subcategory->term_id");
                        $parent_city = isset($term_meta['parent_city']) ? $term_meta['parent_city'] : '';
                        $parent_city = sanitize_title($parent_city);
                        $data_attr_child = 'data-belong="'.esc_attr($parent_city).'"';

                    } elseif( $taxonomy_name == 'property_state' ) {
                        $term_meta= get_option( "_houzez_property_state_$subcategory->term_id");
                        $parent_country = isset($term_meta['parent_country']) ? $term_meta['parent_country'] : '';
                        $parent_country = sanitize_title($parent_country);
                        $data_attr_child = 'data-belong="'.esc_attr($parent_country).'"';
                    }

                    if ( !empty($searched_data) && in_array( $subcategory->slug, $searched_data ) ) {
                        $output.= '<option data-ref="'.esc_attr($subcategory->slug).'" '.$data_attr_child.' value="' . esc_attr($subcategory->slug) . '" selected="selected"> - '. esc_attr($subcategory->name) . '</option>';
                    } else {
                        $output.= '<option data-ref="'.esc_attr($subcategory->slug).'" '.$data_attr_child.' value="' . esc_attr($subcategory->slug) . '"> - ' . esc_attr($subcategory->name) . '</option>';
                    }

                    foreach( $taxonomies as $subsubcategory ) {
                        if($subsubcategory->parent == $subcategory->term_id) {

                            $data_attr_child = '';
                            if( $taxonomy_name == 'property_city' ) {
                                $term_meta= get_option( "_houzez_property_city_$subsubcategory->term_id");
                                $parent_state = isset($term_meta['parent_state']) ? $term_meta['parent_state'] : '';
                                $parent_state = sanitize_title($parent_state);
                                $data_attr_child = '';

                            } elseif( $taxonomy_name == 'property_area' ) {
                                $term_meta= get_option( "_houzez_property_area_$subsubcategory->term_id");
                                $parent_city = isset($term_meta['parent_city']) ? $term_meta['parent_city'] : '';
                                $parent_city = sanitize_title($parent_city);
                                $data_attr_child = 'data-belong="'.esc_attr($parent_city).'"';

                            } elseif( $taxonomy_name == 'property_state' ) {
                                $term_meta= get_option( "_houzez_property_state_$subsubcategory->term_id");
                                $parent_country = isset($term_meta['parent_country']) ? $term_meta['parent_country'] : '';
                                $parent_country = sanitize_title($parent_country);
                                $data_attr_child = 'data-belong="'.esc_attr($parent_country).'"';
                            }

                            if ( !empty($searched_data) && in_array( $subsubcategory->slug, $searched_data ) ) {
                                $output.= '<option data-ref="'.esc_attr($subsubcategory->slug).'" '.$data_attr_child.' value="' . esc_attr($subsubcategory->slug) . '" selected="selected"> - '. esc_attr($subsubcategory->name) . '</option>';
                            } else {
                                $output.= '<option data-ref="'.esc_attr($subsubcategory->slug).'" '.$data_attr_child.' value="' . esc_attr($subsubcategory->slug) . '"> -- ' . esc_attr($subsubcategory->name) . '</option>';
                            }
                        }
                    }
                }
            }
        }
    }
    echo $output;

}