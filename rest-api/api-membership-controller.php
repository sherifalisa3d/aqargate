<?php

/**
 * ag_user_membership
 *
 * @param  mixed $user_id
 * @return void
 */
function ag_user_membership( $user_id ){
        $response = [];
        $remaining_listings = houzez_get_remaining_listings( $user_id );
        $pack_featured_remaining_listings = houzez_get_featured_remaining_listings( $user_id );
        $package_id = houzez_get_user_package_id( $user_id );

        if( $remaining_listings == -1 ) {
            $remaining_listings = esc_html__('Unlimited', 'houzez');
        }

        if( !empty( $package_id ) ) {

            $seconds = 0;
            $pack_title = get_the_title( $package_id );
            $pack_listings = get_post_meta( $package_id, 'fave_package_listings', true );
            $pack_unmilited_listings = get_post_meta( $package_id, 'fave_unlimited_listings', true );
            $pack_featured_listings = get_post_meta( $package_id, 'fave_package_featured_listings', true );
            $pack_billing_period = get_post_meta( $package_id, 'fave_billing_time_unit', true );
            $pack_billing_frequency = get_post_meta( $package_id, 'fave_billing_unit', true );
            $pack_date = strtotime ( get_user_meta( $user_id, 'package_activation',true ) );

            switch ( $pack_billing_period ) {
                case 'Day':
                    $seconds = 60*60*24;
                    break;
                case 'Week':
                    $seconds = 60*60*24*7;
                    break;
                case 'Month':
                    $seconds = 60*60*24*30;
                    break;
                case 'Year':
                    $seconds = 60*60*24*365;
                    break;
            }

            $pack_time_frame = $seconds * $pack_billing_frequency;
            $expired_date    = $pack_date + $pack_time_frame;
            $expired_date = date_i18n( get_option('date_format'),  $expired_date );

            $response['pack_title'] = esc_attr( $pack_title );

            if( $pack_unmilited_listings == 1 ) {
            $response['pack_listings'] = esc_html__('unlimited','houzez');
            $response['remaining_listings'] = esc_html__('unlimited','houzez');
            } else {
            $response['pack_listings'] = esc_attr( $pack_listings );
            $response['remaining_listings'] = esc_attr( $remaining_listings );
            }
            $response['pack_featured_listings'] = esc_attr( $pack_featured_listings );
            $response['pack_featured_remaining_listings'] = esc_attr( $pack_featured_remaining_listings );
            $response['expired_date'] = esc_attr( $expired_date );
           

        }
        return $response;
}

/**
 * ag_membership_type
 *
 * @return void
 */
function ag_membership_type(){

    $response = [];
    $response['currency_symbol'] = houzez_option( 'currency_symbol' );
    $response['where_currency'] = houzez_option( 'currency_position' );
    if(class_exists('Houzez_Currencies')) {
        $multi_currency = houzez_option('multi_currency');
        $default_currency = houzez_option('default_multi_currency');
        if(empty($default_currency)) {
            $default_currency = 'USD';
        }

        if($multi_currency == 1) {
            $response['currency'] = Houzez_Currencies::get_currency_by_code($default_currency);
            $response['currency_symbol'] = $currency['currency_symbol'];
        }
    }


    $args = array(
        'post_type'       => 'houzez_packages',
        'posts_per_page'  => -1,
        'meta_query'      =>  array(
            array(
                'key' => 'fave_package_visible',
                'value' => 'yes',
                'compare' => '=',
            )
        )
    );
    $packages_qry = get_posts( $args );

    if( count($packages_qry) == 0 ) {
        return $this->error_response(
            'rest_invalid_data',
            __( 'No Memebership Package(s) Found'  )
        );
    }

    foreach ( $packages_qry as $key => $pack ) { 
    
    $response['packages'][]= [
        'pack_title'              => get_the_title( $pack->ID ),
        'pack_price'              => get_post_meta( $pack->ID, 'fave_package_price', true ),
        'pack_listings'           => get_post_meta( $pack->ID, 'fave_package_listings', true ),
        'pack_featured_listings'  => get_post_meta( $pack->ID, 'fave_package_featured_listings', true ),
        'pack_unlimited_listings' => get_post_meta( $pack->ID, 'fave_unlimited_listings', true ),
        'pack_billing_period'     => get_post_meta( $pack->ID, 'fave_billing_time_unit', true ),
        'pack_billing_frquency'   => get_post_meta( $pack->ID, 'fave_billing_unit', true ),
        'fave_package_images'     => get_post_meta( $pack->ID, 'fave_package_images', true ),
        'pack_package_tax'        => get_post_meta( $pack->ID, 'fave_package_tax', true ),
        'fave_package_popular'    => get_post_meta( $pack->ID, 'fave_package_popular', true ),
        'package_custom_link'     => get_post_meta( $pack->ID, 'fave_package_custom_link', true ),
    ];

    }
    wp_reset_query();
    return $response;
}