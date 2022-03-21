<?php
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles', 999 );
function my_theme_enqueue_styles() {
    $parenthandle = 'houzez-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.
    $theme = wp_get_theme();
    wp_enqueue_style( 'child-style', get_stylesheet_uri(),
        array( $parenthandle ),
        $theme->get('Version') // this only works if you have Version in the style header
    );
}
// Constants
define('AG_DIR', __DIR__.'/');

// Helpers
include_once ( AG_DIR.'helpers/ag_helpers.php' );

// Classes
include_once ( AG_DIR.'classes/class-crb.php' );
new AG_CF;

include_once ( AG_DIR.'classes/class-prop.php' );
new AG_Prop;

include_once ( AG_DIR . 'classes/aqargate-class.php' );
new AqarGate();

include_once ( AG_DIR . 'classes/aqargate-export.php' );

function csv_to_array($file) {

    if (($handle = fopen($file, 'r')) === false) {
        die('Error opening file');
    }
    
    $headers = fgetcsv($handle, 256, ';');
    $headers = preg_replace('/ ^[\pZ\p{Cc}\x{feff}]+|[\pZ\p{Cc}\x{feff}]+$/ux', '', $headers);
    $_data = array();
    
    while ($row = fgetcsv($handle, 256, ';')) {
        $row = preg_replace('/ ^[\pZ\p{Cc}\x{feff}]+|[\pZ\p{Cc}\x{feff}]+$/ux', '', $row);
        $_data[] = array_combine($headers, $row);
    }
    fclose($handle);

    return array_filter($_data);
  
  }