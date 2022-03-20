<?php
class AqarGate_Export {

    public function __construct() {
        $this->init_actions();
    }

    public function init_actions(){}

    public function array_csv_download( array &$array, $filename = "export.csv", $delimiter=";" )
    {
        if (count($array) == 0) {
            return null;
          }
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '";' );
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        
        // clean output buffer
        ob_end_clean();
        
        $handle = fopen( 'php://output', 'w' );
        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
        // use keys as column titles
        fputcsv( $handle, array_keys(reset($array) ), $delimiter );
  
        foreach ( $array as $value ) {
            fputcsv( $handle, $value, $delimiter );
        }
        
        fclose( $handle );
    
        // flush buffer
        ob_flush();
        
        // use exit to get rid of unexpected output afterward
        exit();
    }
}