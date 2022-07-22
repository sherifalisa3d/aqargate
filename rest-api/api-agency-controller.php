<?php
     /**
     * get_agency_data
     *
     * @param  mixed $agency_id
     * @return void
     */
    function ag_get_agency_data( $agency_id )
    {
        $agency_data = [];
        global $post;   
        $post = $agency_id;
        setup_postdata( $post ); 

        $agency_data[ 'agency_id' ] = get_the_ID();
        $agency_data[ 'name' ] = get_the_title();
        $agency_data[ 'desc' ] = strip_tags( get_the_content() ) ;

        if ( has_post_thumbnail() ) {
            $thumbnail_id         = get_post_thumbnail_id();
            $thumbnail_array = wp_get_attachment_image_src( $thumbnail_id, 'medium_large' );
            if ( ! empty( $thumbnail_array[ 0 ] ) ) {
                $agency_data[ 'thumbnail' ] = $thumbnail_array[ 0 ];
            }
        } else {
            $agency_data[ 'thumbnail' ] = houzez_get_image_placeholder_url('medium_large');
        }

        $agency_data[ 'address' ] = get_post_meta( get_the_ID(), 'fave_agency_address', true );
        $agency_data[ 'phone' ] = get_post_meta( get_the_ID(), 'fave_agency_phone', true );
        $agency_data[ 'office_call' ] = str_replace(array('(',')',' ','-'),'', $agency_phone);
        $agency_data[ 'mobile' ] = get_post_meta( get_the_ID(), 'fave_agency_mobile', true );
        $agency_data[ 'mobile_call' ] = str_replace(array('(',')',' ','-'),'', $agency_mobile);
        $agency_data[ 'fax' ] = get_post_meta( get_the_ID(), 'fave_agency_fax', true );
        $agency_data[ 'fax_call' ] = str_replace(array('(',')',' ','-'),'', $agency_fax);
        $agency_data[ 'email' ] = get_post_meta( get_the_ID(), 'fave_agency_email', true );

        $agency_data[ 'facebook' ] = get_post_meta( get_the_ID(), 'fave_agency_facebook', true );
        $agency_data[ 'twitter' ] = get_post_meta( get_the_ID(), 'fave_agency_twitter', true );
        $agency_data[ 'linkedin' ] = get_post_meta( get_the_ID(), 'fave_agency_linkedin', true );
        $agency_data[ 'googleplus' ] = get_post_meta( get_the_ID(), 'fave_agency_googleplus', true );
        $agency_data[ 'youtube' ] = get_post_meta( get_the_ID(), 'fave_agency_youtube', true );
        $agency_data[ 'pinterest' ] = get_post_meta( get_the_ID(), 'fave_agency_pinterest', true );
        $agency_data[ 'instagram' ] = get_post_meta( get_the_ID(), 'fave_agency_instagram', true );
        $agency_data[ 'vimeo' ] = get_post_meta( get_the_ID(), 'fave_agency_vimeo', true );
        $agency_data[ 'whatsapp' ] = get_post_meta( get_the_ID(), 'fave_agency_whatsapp', true );
        $agency_data[ 'whatsapp_call' ] = str_replace(array('(',')',' ','-'),'', $agency_whatsapp);

        $agency_address = get_post_meta( get_the_ID(), 'fave_agency_address', true );

        if( ! empty( $agency_address ) ) {
            if(houzez_get_map_system() == 'google') {
                $mapData = houzez_getLatLongFromAddress($agency_address);
            } else {
                $mapData = houzezOSM_getLatLngFromAddress($agency_address);
            }
        }

        $agency_data[ 'map_data' ] = $mapData;

 
        wp_reset_postdata();
        return $agency_data;
    }