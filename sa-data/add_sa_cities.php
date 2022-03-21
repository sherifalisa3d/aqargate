<?php
$file = AG_DIR.'sa-data/property_cities.csv';
$property_cities = csv_to_array($file);

// 2- insert province to property tax .
    foreach ( $property_cities as $mdaKey ) {
        // prr($mdaKey);wp_die();
        $provinceId = $mdaKey['provinceId'];
        $nameAr = $mdaKey['nameAr'];
        $_id = $mdaKey['id'];  
        $terms = get_terms( array(
            'taxonomy' => 'property_state',
            'hide_empty' => false,
        ) );
        foreach($terms as $term){
            $province_Id = get_option( '_houzez_property_state_'.$term->term_id, true );
            if($provinceId == $province_Id['provinceId'] ){
                $houzez_meta['parent_state'] = $term->slug;
            }
            
        }
        $inserted_term =  wp_insert_term($nameAr, 'property_city');
        if (is_wp_error($inserted_term)) {
            $new_term_id = $inserted_term->error_data['term_exists'];
        } else {
            $new_term_id = $inserted_term['term_id'];
        }

        // var_dump($new_term_id);wp_die();
        $houzez_meta['cityId'] = $_id;

        update_option( '_houzez_property_city_'.$new_term_id, $houzez_meta );
    }     
