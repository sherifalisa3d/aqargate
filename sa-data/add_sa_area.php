<?php
    $file = AG_DIR.'sa-data/property_area.csv';
    $property_area = csv_to_array($file);
    // 3- insert province to property tax .
        $terms = get_terms( array(
            'taxonomy' => 'property_city',
            'hide_empty' => false,
        ) );
        $tertm_Id = array();
        foreach($terms as $term){
            $tertm_Id[] = $term->term_id ;  
        }
        foreach ( $property_area as $mdaKey ) {
            // prr($mdaKey);wp_die();
            $cityId = $mdaKey['cityId'];
            $nameAr = $mdaKey['NameAr'];
            $_id = $mdaKey['id'];  

                foreach( $tertm_Id as $term ){
                    $_term = get_term( $term, 'property_city' );
                    $slug = $_term->slug;
                    $city_Id = get_option( '_houzez_property_city_'.$term, true ); 
                    if( $cityId == $city_Id['cityId'] ){
                        $houzez_meta['parent_city'] = $slug;
                    }
                 }
            // prr($houzez_meta);
            $inserted_term =  wp_insert_term($nameAr, 'property_area');
            if (is_wp_error($inserted_term)) {
                $new_term_id = $inserted_term->error_data['term_exists'];
            } else {
                $new_term_id = $inserted_term['term_id'];
            }
            // var_dump($new_term_id);wp_die();
            $houzez_meta['areaId'] = $_id;

            update_option( '_houzez_property_area_'.$new_term_id, $houzez_meta );
        }     