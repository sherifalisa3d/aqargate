<?php
add_action( 'wp_loaded', 'get_array_property_data');
function get_array_property_data(){
    global $post, $hide_fields, $top_area, $property_layout, $map_street_view;
    $property_data= array();
    $args = array(
         'post_type'        =>  'property',
         'posts_per_page'   => -1,
         'post_status'      =>  'publish',
         'suppress_filters' => false
    );
    $prop_qry = get_posts($args); 
    if( $prop_qry && is_array($prop_qry) ){
        foreach( $prop_qry as $prop ){
            $prop_id = $prop->ID;
            $userID = $prop->post_author;
            $prop_date = $prop->post_date;
            $prop_date_modified = $prop->post_modified;
            $id_number  = get_the_author_meta( 'aqar_author_id_number' , $userID );
            $ad_number  = get_the_author_meta( 'aqar_author_ad_number', $userID );
            $type_id    = get_the_author_meta( 'aqar_author_type_id', $userID );
            $first_name = get_the_author_meta( 'first_name' , $userID );
            $last_name  = get_the_author_meta( 'last_name' , $userID );
            $user_email = get_the_author_meta( 'user_email' , $userID );
            $user_mobile = get_the_author_meta( 'fave_author_mobile' , $userID );
            $license     = get_the_author_meta( 'fave_author_license' , $userID );
            $expiration_date = get_post_meta( $prop_id,'_houzez_expiration_date',true );
            $fave_property_price = get_post_meta( $prop_id, 'fave_property_price', true);
            $Selling_Meter_Price = get_post_meta($prop_id, 'fave_d8b3d8b9d8b1-d985d8aad8b1-d8a7d984d8a8d98ad8b9', true);
            $The_main_type_of_ad =  wp_get_post_terms( $prop_id, 'property_type', array("fields" => "names"));
            $Lattitude = get_post_meta( $prop_id, 'houzez_geolocation_lat', true );
            $Longitude = get_post_meta( $prop_id, 'houzez_geolocation_long', true );
            $Street_Name = get_post_meta( $prop_id, 'fave_property_map_address', true );
            $additional_features = get_post_meta($prop_id, 'additional_features', true);
            $prop_beds = get_post_meta( $prop_id, 'fave_property_bedrooms', true );
            $prop_baths = get_post_meta( $prop_id, 'fave_property_bathrooms', true );
            $Land_Number = get_post_meta($prop_id, 'fave_d8b1d982d985-d8a7d984d8a3d8b1d8b6', true);
            $Plan_Number = get_post_meta($prop_id, 'fave_d8b1d982d985-d8a7d984d985d8aed8b7d8b7', true); 
            $Number_Of_Units = get_post_meta($prop_id, 'fave_d8b9d8afd8af-d8a7d984d988d8add8afd8a7d8aa', true);
            $prop_size = houzez_get_listing_area_size( $prop_id );
            $Rooms_Number = get_post_meta( $prop_id, 'fave_property_rooms', true );
            $Construction_Date = get_post_meta( $prop_id, 'fave_prop_year_built', true );
            $Street_Width = get_post_meta($prop_id, 'fave_d8b9d8b1d8b6-d8a7d984d8b4d8a7d8b1d8b9', true); 
            $Property_limits_and_lenghts = get_post_meta($prop_id, 'fave_d8add8afd988d8af-d988d8a3d8b7d988d8a7d984-d8a7d984d8b9d982d8a7d8b1', true);        
            $Is_there_mortgage = get_post_meta($prop_id, 'fave_d987d984-d98ad988d8acd8af-d8a7d984d8b1d987d986-d8a3d988-d8a7d984d982d98ad8af-d8a7d984d8b0d98a-d98ad985d986d8b9-d8a7d988-d98ad8add8af', true); 
            $Rights_and_obligations = get_post_meta($prop_id, 'fave_d8a7d984d8add982d988d982-d988d8a7d984d8a7d984d8aad8b2d8a7d985d8a7d8aa-d8b9d984d989-d8a7d984d8b9d982d8a7d8b1-d8a7d984d8bad98ad8b1-d985', true); 
            $Information_that_may_affect_the_property = get_post_meta($prop_id, 'fave_d8a7d984d985d8b9d984d988d985d8a7d8aa-d8a7d984d8aad98a-d982d8af-d8aad8a4d8abd8b1-d8b9d984d989-d8a7d984d8b9d982d8a7d8b1-d8b3d988d8a7d8a1', true); 
            $Property_disputes = get_post_meta($prop_id, 'fave_d8a7d984d986d8b2d8a7d8b9d8a7d8aa-d8a7d984d982d8a7d8a6d985d8a9-d8b9d984d989-d8a7d984d8b9d982d8a7d8b1', true); 
            $Availability_of_elevators = get_post_meta($prop_id, 'fave_d8aad988d8a7d981d8b1-d8a7d984d985d8b5d8a7d8b9d8af', true); 
            $Number_of_elevators = get_post_meta($prop_id, 'fave_d8b9d8afd8af-d8a7d984d985d8b5d8a7d8b9d8af', true); 
            $Availability_of_Parking = get_post_meta($prop_id, 'fave_d8aad988d981d8b1-d8a7d984d985d988d8a7d982d981', true); 
            $Number_of_parking = get_post_meta($prop_id, 'fave_prop_garage', true); 
            if(empty($Number_of_parking) || empty($Number_of_elevators) || empty($Rooms_Number)){
                $Number_of_parking = 0 ; $Number_of_elevators = 0 ;
                $Rooms_Number = 0 ;
            }
            if(empty($Availability_of_elevators) || empty($Is_there_mortgage) || empty($Availability_of_Parking)
            || empty($Property_disputes) || empty($Information_that_may_affect_the_property )
            || empty($Rights_and_obligations) || empty($Is_there_mortgage)){
                $Availability_of_elevators = 'لا يوجد';
                $Is_there_mortgage = 'لا يوجد';
                $Availability_of_Parking = 'لا يوجد';
                $Property_disputes = 'لا يوجد';
                $Information_that_may_affect_the_property = 'لا يوجد';
                $Rights_and_obligations = 'لا يوجد'; 
                $Is_there_mortgage = 'لا يوجد'; 
            }
            $Authorization_number = get_post_meta($prop_id, 'fave_d8b1d982d985-d8a7d984d8aad981d988d98ad8b6', true); 
            $Real_Estate_Facade = get_post_meta($prop_id, 'fave_d988d8a7d8acd987d8a9-d8a7d984d8b9d982d8a7d8b1', true); 
            // prr($prop);
            
            $property_data[] = array(
                 'Ad_Id' => $ad_number,
                 'Advertiser_character' => 'مفوض',
                 'Advertiser_name' => $first_name.' '.$last_name,
                 'Advertiser_mobile_number' => $user_mobile,
                 'The_main_type_of_ad' =>'',
                 'Ad_description' => $prop->post_content,
                 'Ad_subtype' => 'بيع',
                 'Advertisement_publication_date' => $prop_date,
                 'Ad_update_date' => $prop_date_modified,
                 'Ad_expiration' => $expiration_date ,
                 'Ad_status' => 'Valid',
                 'Ad_Views' => 1,
                 'District_Name' => 'منطقة مكة المكرمة',
                 'City_Name' => 'مكة المكرمة',
                 'Neighbourhood_Name' => 'أجياد',
                 'Street_Name' => $Street_Name,
                 'Longitude' => $Longitude,
                 'Lattitude' => $Lattitude,
                 'Furnished' => 'لا',
                 'Kitchen' => 'لا',
                 'Air_Condition' => 'لا',
                 'facilities' => 'لا يوجد',
                 'Using_For' => 'سكني',
                 'Property_Type' =>' أرض',
                 'The_Space' => $prop_size,
                 'Land_Number' => $Land_Number,
                 'Plan_Number' => $Plan_Number,
                 'Number_Of_Units' => $Number_Of_Units,
                 'Floor_Number' => 0,
                 'Unit_Number' => 0,
                 'Rooms_Number' => isset($Rooms_Number) ? $Rooms_Number : '0',
                 'Rooms_Type' => 0,
                 'Real_Estate_Facade' => $Real_Estate_Facade,
                 'Street_Width' => $Street_Width,
                 'Construction_Date' => $Construction_Date,
                 'Rental_Price' => 0,
                 'Selling_Price' =>  $fave_property_price ,
                 'Selling_Meter_Price' => $Selling_Meter_Price,
                 'Property_limits_and_lenghts' => $Property_limits_and_lenghts,
                 'Is there a mortgage or restriction that prevents or limits the use of the property' =>  $Is_there_mortgage ,
                 'Rights and obligations over real estate that are not documented in the real estate document' => $Rights_and_obligations ,
                 'Information that may affect the property' => $Information_that_may_affect_the_property ,
                 'Property disputes' => $Property_disputes ,
                 'Availability of elevators' =>  $Availability_of_elevators ,
                 'Number of elevators' => $Number_of_elevators ,
                 'Availability of Parking' => $Availability_of_Parking  ,
                 'Number of parking' => $Number_of_parking ,
                 'Advertiser category' => 'فرد',
                 'Advertiser license number' => $license ,
                 'Advertiser`s email' => $user_email,
                 'Advertiser registration number' => 1,
                 'Authorization number' => $Authorization_number,
         );
            
        }
        return $property_data;
    }
 }