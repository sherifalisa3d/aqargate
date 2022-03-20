<?php
$sa_provinces = array( 
    ' الحدود الشمالية',
    ' الجوف', 
    ' تبوك', 
    ' حائل', 
    ' القصيم', 
    ' الرياض', 
    ' المدينة المنورة', 
    ' عسير', 
    ' الباحة', 
    ' جازان', 
    ' مكة المكرمة', 
    ' نجران', 
    'الشرقية', 
);

foreach ($sa_provinces as $provinc) {
    $inserted_term =  wp_insert_term($provinc, 'property_state');
    if( is_wp_error($inserted_term) ) {
        $new_term_id = $inserted_term->error_data['term_exists'];
    } else {
        $new_term_id = $inserted_term['term_id'];
    }
    // var_dump($new_term_id);wp_die(); 
    $houzez_meta['parent_country'] = 'saudi-arabia';

    update_option( '_houzez_property_state_'.$new_term_id, $houzez_meta );
}