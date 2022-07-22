<?php 
global $post, $top_area; 
$fave_property_images = get_post_meta(get_the_ID(), 'fave_property_images', false);
$tools_position = houzez_option('property_tools_mobile_pos', 'under_banner');
?>
<div class="visible-on-mobile">
    <div class="mobile-top-wrap">
        <div class="mobile-property-tools clearfix">
            <?php 
            if( !empty($fave_property_images) ) {
                get_template_part('property-details/partials/banner-nav'); 
            }?>
            <?php 
            if( $tools_position == 'under_banner' ) {
                get_template_part('property-details/partials/tools'); 
            } ?> 
        </div><!-- mobile-property-tools -->
        <div class="mobile-property-title clearfix">
            <?php 
            if( houzez_option( 'detail_featured_label', 1 ) != 0 ) {
                get_template_part('template-parts/listing/partials/item-featured-label'); 
            }?>
            <?php get_template_part('property-details/partials/item-labels-mobile'); ?>
            <div class="qr-mobile">
               <?php get_template_part('property-details/partials/qr'); ?>
            </div>
            <div class="prop-title-mobile">
<<<<<<< HEAD
            <?php  get_template_part('property-details/partials/title'); ?>             
=======
            <?php  get_template_part('property-details/partials/title'); ?> 
            </div>
>>>>>>> 72c0d7dc0ea0bc22fdcda7aa47a48b488f4c6617
            <?php get_template_part('property-details/partials/item-address'); ?>
            <?php get_template_part('property-details/partials/item-price'); ?>
			</div>
            <?php if( $tools_position == 'under_title' ) { ?>
            <div class="mobile-property-tools mobile-property-tools-bottom clearfix">
                <?php get_template_part('property-details/partials/tools'); ?> 
            </div>
            <?php } ?>
            
        </div><!-- mobile-property-title -->
    </div><!-- mobile-top-wrap -->
    <?php 
    if($top_area == 'v6') {
        get_template_part('property-details/overview');  
    }
    ?>
</div><!-- visible-on-mobile -->