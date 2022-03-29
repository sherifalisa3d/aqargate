<?php
use Carbon_Fields\Container;
use Carbon_Fields\Field;

class AG_CF
{
    public function __construct(){
        add_action( 'after_setup_theme', array ( $this , 'crb_load' ) );
        add_action( 'carbon_fields_register_fields', array( $this , 'ag_settings_panel' ) );
        add_action( 'carbon_fields_register_fields', array( $this , 'ag_tax_select' ) );
    }

    public function crb_load() {
        include_once ( AG_DIR.'libs/cf/vendor/autoload.php' );
        \Carbon_Fields\Carbon_Fields::boot();
    }

    public function ag_settings_panel() {
        if ( is_admin() && isset($_GET['page']) == 'crb_carbon_fields_container_ag_settings.php') {
            $this->ag_html_export_field();
            $current_page = admin_url("admin.php?page=".$_GET["page"]);
            $html = '<a href="' . add_query_arg('export', '1', $current_page) . '" class="button button-primary">Export</a>';
        } else {
            $html = '';
        }
        Container::make( 'theme_options','ag_settings', __( 'AG Settings' ) )
        
        ->add_tab(
            __( 'REGA', 'ag' ),
            array(
                Field::make( 'text', 'client_id', __( 'Client ID', 'ag' ) ),
                Field::make( 'text', 'client_secret', __( 'Client Secret', 'ag' ) ),
                Field::make( 'checkbox', 'aq_show_api', 'Enable Api' ),
               
            )
        )
        ->add_tab( __( 'Property Export' ), array(
            Field::make( 'html', 'crb_information_text' )
            ->set_html( $html )
        ) );
    }

    public function ag_html_export_field() {
        $get_array_property_data = get_array_property_data(); 
        $time_now = date('Ymd_his');
        $filename = "aqargate.com_". $time_now .".csv";
 
        if (isset($_GET['export']) && $_GET['export'] === '1') {
            $AqarGate_Export = new AqarGate_Export();
            $AqarGate_Export->array_csv_download( $get_array_property_data, $filename, $delimiter=";" );
            die();
        }
    }

    public function ag_tax_select(){
        Container::make( 'term_meta', __( 'Select Fileds To Show' ) )
        ->where( 'term_taxonomy', '=', 'property_type' )
        ->add_fields( array(
        Field::make( 'multiselect', 'crb_available_fields', __( 'Select Fileds To Show' ) )
        ->add_options( $this->ag_fields_array() )
        
    ) );
    }

    public function ag_fields_array(){
        //get Fields
        $fields_builder = array();
        $adp_details_fields = houzez_option('adp_details_fields');
        if( is_array($adp_details_fields) ){
            $fields_builder = $adp_details_fields['enabled'];
            unset($fields_builder['placebo']);
        }
        
         return $fields_builder;
    }

}
