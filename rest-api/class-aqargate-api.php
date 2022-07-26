<?php

/**
 * AqarGateApi
 */
class AqarGateApi {

    /**
	 * data routes
	 *
	 * @var array
	 */
	protected $routes = array(
		'property_post' => array(
			'path'                => '/properties/(?P<id>[\d]+)',
			'callback'            => 'get_property',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		),
        'property_search_pram' => array(
			'path'                => '/properties',
			'callback'            => 'get_properties',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		),
        'property_field' => array(
			'path'                => '/properties/fields',
			'callback'            => 'prop_type_fields',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		),
        'submit_property' => array(
			'path'                => '/properties/submit',
			'callback'            => 'submit_property',
			'permission_callback' => 'create_item_permissions_check',
			'methods'             => 'POST',
		),
        'delete_property' => array(
			'path'                => '/properties/delete/(?P<id>[\d]+)',
			'callback'            => 'delete_property',
			'permission_callback' => 'create_item_permissions_check',
			'methods'             => WP_REST_Server::DELETABLE,
            'args'                => 'delete_property_args_schema'
		),
        'agency_post' => array(
			'path'                => '/agency/(?P<id>[\d]+)',
			'callback'            => 'get_agency',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		),	
        'all_agency_posts' => array(
			'path'                => '/agency',
			'callback'            => 'get_agencies',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		),
        'property_state' => array(
			'path'                => '/state',
			'callback'            => 'get_state',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		),	
        'property_city' => array(
			'path'                => '/city',
			'callback'            => 'get_cites',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		),		
        'property_area' => array(
			'path'                => '/area',
			'callback'            => 'get_area',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		),
        'aqargate_login' => array(
			'path'                => '/login',
			'callback'            => 'login',
			'permission_callback' => 'allow_access',
			'methods'             => 'POST',
		),		
        'aqargate_signup' => array(
			'path'                => '/signup',
			'callback'            => 'signup',
			'permission_callback' => 'allow_access',
			'methods'             => 'POST',
		),
        'aqargate_membership' => array(
			'path'                => '/membership',
			'callback'            => 'membership',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		),
        'favorite_properties' => array(
			'path'                => '/favorite_properties',
			'callback'            => 'favorite_properties',
			'permission_callback' => 'allow_access',
			'methods'             => 'GET',
		),
        'favorite_properties_add' => array(
			'path'                => '/favorite_properties/add-remove',
			'callback'            => 'favorite_properties_add',
			'permission_callback' => 'allow_access',
			'methods'             => 'POST',
		),
        
	);	

    /**
	 * Version of the API
	 *
	 * @see set_version()
	 * @see get_version()
	 * @var string
	 */
	protected $version = '1';

	/**
	 * Vendor slug for the API
	 *
	 * @see set_vendor()
	 * @see get_vendor()
	 * @var string
	 */
	protected $vendor = 'aqargate';
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'register_routes'], 15 );
        add_action( 'rest_api_init', [ $this, 'load_controllers' ] ) ;
    }
    
        
    /**
     * load_controllers
     *
     * @return void
     */
    public function load_controllers()
    {
        include_once ( 'api-fields-controller.php' );
        include_once ( 'api-prop-controller.php' );
        include_once ( 'api-agency-controller.php' );
        include_once ( 'api-register-controller.php' );
        include_once ( 'api-membership-controller.php' );
    }

    /**
	 * Set version
	 *
	 * @param string $version
	 */
	public function set_version( $version ) {
		$this->version = $version;
	}

	/**
	 * Return version
	 *
	 * @return string
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Set vendor
	 *
	 * @param string $vendor
	 */
	public function set_vendor( $vendor ) {
		$this->vendor = $vendor;
	}

	/**
	 * Return vendor
	 *
	 * @return string
	 */
	public function get_vendor() {
		return $this->vendor;
	}

	/**
	 * Allow access to an endpoint
	 *
	 * @return bool
	 */
	public function allow_access() {
		return true;
	}

    /**
	 * Set routes
	 *
	 * @param array $routes
	 */
	public function set_routes( $routes ) {
		$this->routes = $routes;
	}

	/**
	 * Return routes
	 *
	 * @return array
	 */
	public function get_routes() {
		return $this->routes;
	}

    /**
	 * Register custom routes
	 *
	 * @see  register_route()
	 */
	public function register_routes() {
		foreach ( $this->routes as $route ) {
			$this->register_route( $route );
		}
	}

	/**
	 * Register a custom REST route
	 *
	 * @param  array $route
	 */
	protected function register_route( $route ) {
		register_rest_route($this->get_vendor() . '/v' . $this->get_version(), $route['path'], array(
			'methods'             => $route['methods'],
            'permission_callback' => array( $this, $route['permission_callback'] ),
			'callback'            => array( $this, $route['callback'] ),
			'args'                => isset( $route['args'] ) ? call_user_func( array( $this, $route['args'] ) ) : array(),
		) );
	}


    
    /**
     * error_response
     *
     * @param  mixed $error_code
     * @param  mixed $error_message
     * @return void
     */
    public function error_response( $error_code, $error_message = '' )
    {
        $msgs = array(
            '2000' => 'No Properties Found',
            '2001' => 'No Correct data collection provided',
            '2002' => 'No Correct property id',
        );

        return array(
            'error_code' => $error_code,
            'message' => empty( $error_message ) ? $msgs[$error_code] :  $error_message,
        );

    }
    
    /**
     * response
     *
     * @param  mixed $date
     * @return void
     */
    public function response( $date )
    {
        return new WP_REST_Response(
            array( 'data' => $date )
        );
    }

    
    /**
     * prop_type_fields
     *
     * @param  mixed $data
     * @return void
     */
    public function prop_type_fields( WP_REST_Request $request )
    {
        $fields = ag_get_property_fields();

        // $prop_type_enabled_fields = carbon_field_value(); 


        // foreach ($prop_type_enabled_fields as $key => $value) {
            
        // }
        $response_data = $fields;

        return $this->response( $response_data );

    }

    
   
    /**
     * data_collections
     *
     * @return array
     */
    public function data_collections()
    {
        return array('list', 'popup-search', 'search', 'property');
    }
    
    /**
     * is_property
     *
     * @param  mixed $prop_id
     * @return true/false
     */
    public function is_property( $prop_id = null )
    {
        global $post;   
        $post = $prop_id;
        setup_postdata( $post ); 
          if(  get_post_type( get_the_ID()  ) === 'property' ) { $is_prop = true; }
          else { $is_prop = false; }
        wp_reset_postdata();
        return $is_prop;
    }    
    /**
     * is_agency
     *
     * @param  mixed $agency_id
     * @return true/false
     */
    public function is_agency( $agency_id = null )
    {
        global $post;   
        $post = $agency_id ;
        setup_postdata( $post ); 
          if(  get_post_type( get_the_ID()  ) === 'houzez_agency' ) { $is_agency = true; } 
          else {  $is_agency = false; }
        wp_reset_postdata();
        return $is_agency;
    }

     /**
	 * get_property
	 *
	 * @param  array $data
	 * @return array
	 */
	public function get_property( WP_REST_Request $data ) {
     
        if( !isset( $data['data_collection'] ) ||  !in_array( $data['data_collection'] ,  $this->data_collections() )  ){
            return $this->error_response ( '2001');
        }

        if( !isset( $data['id'] ) ){
            return $this->error_response ( '2000');
        }
        
        if(  $this->is_property( $data['id'] ) === false ){
            return $this->error_response ( '2002');
        }

        $response_data = get_prop_data( $data['id'] , $data['data_collection']  );

        return $this->response($response_data);
	}

    
   
    /**
	 * get_property
	 *
	 * @param  array $data
	 * @return array
	 */
	public function get_properties( WP_REST_Request $data ) {

        if(!isset( $data['data_collection'] ) ||  !in_array( $data['data_collection'] ,  $this->data_collections() )  ){
            $this->error_response( '2001' );
        }
       
        $properties_data = [];

        $search_qry = array(
            'post_type' => 'property',
            'posts_per_page' => -1
        );
                
        $_tax_query = Array();

        $_tax_query['relation'] = 'OR';

        if( !empty( $_GET['area'] ) && isset($_GET['area'])){
            $_tax_query[] = array(
                'taxonomy' => 'property_area',
                'field' => 'slug',
                'terms' => $_GET['area']
            );
        }       

        if( !empty( $_GET['city'] ) && isset($_GET['city'])){
            $_tax_query[] = array(
                'taxonomy' => 'property_city',
                'field' => 'slug',
                'terms' => $_GET['city']
            );
        }

        if( !empty( $_GET['state'] ) && isset($_GET['state'])){
            $_tax_query[] = array(
                'taxonomy' => 'property_state',
                'field' => 'slug',
                'terms' => $_GET['state']
            );
        }     

        $tax_query[] = $_tax_query;
        $tax_count = count($tax_query);
        $tax_query['relation'] = 'AND';
        if ($tax_count > 0) {
            $search_qry['tax_query'] = $tax_query;
        }
        
        $meta_query[] = houzez_search_min_max_price($meta_query);
        $meta_query[] = houzez_search_min_max_area($meta_query);
        $meta_count = count($meta_query);
        if ($meta_count > 0 || !empty($keyword_array)) {
            $search_qry['meta_query'] = array(
                array(
                    'relation' => 'AND',
                    $meta_query
                ),
            );
        }

        $search_qry['fields'] = 'ids';

		$props = get_posts( $search_qry );

        if( count( $props ) == 0 ){
            return $this->error_response( '2000' );
        }

        foreach ((array)$props as $prop_id) {
            $properties_data[] = get_prop_data( $prop_id , $data['data_collection']  );
        }

        return $this->response( $properties_data );
	}

    /**
     * get_agency
     *
     * @param  mixed $request
     * @return void
     */
    public function get_agencies( WP_REST_Request $request )
    {

        
        $agency_qry = array(
            'post_type' => 'houzez_agency',
            'posts_per_page' => -1
        );

        /* Keyword Based Search */
        if( isset ( $request['agency_name'] ) ) {
            $keyword = trim( $request['agency_name'] );
            $keyword = sanitize_text_field($keyword);
            if ( ! empty( $keyword ) ) {
                $agency_qry['s'] = $keyword;
            }
        }
        
        $agency_qry['fields'] = 'ids';
        $agency = get_posts( $agency_qry );

        if( count( $agency ) == 0 ){
            return $this->error_response( '2000' );
        }

        foreach ( (array)$agency as $agency_id ) {
            $agency_data[] = ag_get_agency_data( $agency_id );
        }

        return $this->response( $agency_data );
    }
    
    /**
     * get_agency
     *
     * @param  mixed $request
     * @return void
     */
    public function get_agency( WP_REST_Request $request )
    {
        if( !isset( $request['id'] ) ){
            return $this->error_response ( '2000');
        }

        if(  $this->is_agency( $request['id'] ) === false ){
            return $this->error_response ( '2002');
        }

        if( isset( $request['data_collection'] ) && !in_array( $request['data_collection'] ,  $this->data_collections() )  ){
            return $this->error_response ( '2001' );
        }
  
        if( isset( $request['data_collection'] ) ){

            $agency_agents = Houzez_Query::get_agency_agents_ids();
            $loop_get_agent_properties_ids = Houzez_Query::loop_get_agent_properties_ids($agency_agents);
            $loop_agency_properties_ids = Houzez_Query::loop_agency_properties_ids();
            $properties_ids = array_merge($loop_get_agent_properties_ids, $loop_agency_properties_ids);

            if(empty($properties_ids)) {
                $agency_qry = Houzez_Query::loop_agency_properties();
                $agency_total_listing = Houzez_Query::loop_agency_properties_count();
            } else {
                $agency_qry = Houzez_Query::loop_properties_by_ids($properties_ids);
                $agency_total_listing = Houzez_Query::loop_properties_by_ids_for_count($properties_ids);
           }

            
           if( count($properties_ids) > 0 ) { 
            foreach ((array)$properties_ids as $prop_id) {
                $properties_data[] = get_prop_data( $prop_id , $request['data_collection'] );
            }
            return $this->response( $properties_data );
          }
          else{
            return $this->error_response ( '2000');
          }
    
            
        }

        $agency_data = ag_get_agency_data( $request['id'] );

        return $this->response( $agency_data );
    }
        
    /**
     * get_state
     *
     * @param  mixed $request
     * @return void
     */
    public function get_state( WP_REST_Request $request )
    {
        $property_state_terms = get_terms (
            array(
                "property_state"
            ),
            array(
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => false,
                'parent' => 0
            )
        );
        $searched_term = isset( $request[ 'country' ] ) ? $request[ 'country' ] : -1 ;
        $property_state = ag_hirarchical_options( 'property_state', $property_state_terms, $searched_term );

        if( count( $property_state ) == 0 ) {
            return $this->error_response( '2007' , ' No state found .' );
        }

        $prop_state = [
            [
                'id'          => 'administrative_area_level_1',
                'field_id'    => 'administrative_area_level_1',
                'type'        => 'select',
                'label'       => houzez_option('cl_state', 'County/State').houzez_required_field('state'),
                'placeholder' => '',
                'options'     => $property_state,
                'required'    => 1,
            ],
        ];

        return $this->response( $prop_state );
    }
    
    /**
     * get_cites
     *
     * @param  mixed $request
     * @return void
     */
    public function get_cites( WP_REST_Request $request )
    {
        $property_city_terms = get_terms (
            array(
                "property_city"
            ),
            array(
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => false,
                'parent' => 0
            )
        );
        $searched_term = isset( $request[ 'state' ] ) ? $request[ 'state' ] : -1 ;
        $property_city = ag_hirarchical_options( 'property_city', $property_city_terms, $searched_term);

        if( count( $property_city ) == 0 ) {
            return $this->error_response( '2007' , ' No city found .' );
        }

        $prop_city = [
            [
                'id'          => 'city',
                'field_id'    => 'locality',
                'type'        => 'select',
                'label'       => houzez_option( 'cl_city', 'City' ).houzez_required_field('city'),
                'placeholder' => '',
                'options'     => $property_city,
                'required'    => 1,
            ],
        ];

        return $this->response( $prop_city ); 
    }
    
    /**
     * get_area
     *
     * @param  mixed $request
     * @return void
     */
    public function get_area( WP_REST_Request $request )
    {
        $property_area_terms = get_terms (
            array(
                "property_area"
            ),
            array(
                'orderby' => 'name',
                'order' => 'ASC',
                'hide_empty' => false,
                'parent' => 0
            )
        );
        $searched_term = isset( $request[ 'city' ] ) ? $request[ 'city' ] : -1 ;
        $property_area = ag_hirarchical_options( 'property_area', $property_area_terms, $searched_term);

        if( count( $property_area ) == 0 ) {
            return $this->error_response( '2007' , ' No area found .' );
        }

        $prop_area = [
            [
                'id'          => 'neighborhood',
                'field_id'    => 'neighborhood',
                'type'        => 'select',
                'label'       => houzez_option( 'cl_area', 'Area' ).houzez_required_field('area'),
                'placeholder' => '',
                'options'     => $property_area,
                'required'    => 1,
            ],
        ];

        return $this->response( $prop_area ); 
    }
    
    /**
     * submit_property
     *
     * @param  mixed $request
     * @return void
     */
    public function submit_property( WP_REST_Request $request )
    {
        
        if( !isset( $request['prop_title'] ) ){
            return $this->error_response ( '2000', 'missing parameter prop_title');
        }
        $new_property = ag_submit_property( $request );

        $new_prop_api_url = rest_url( $this->get_vendor() . '/v' . $this->get_version() . '/properties' . '/' . $new_property.'/?data_collection=property'  );

        return $this->response( $new_prop_api_url ); 
    }
    
    /**
     * delete_property
     *
     * @param  mixed $request
     * @return void
     */
    public function delete_property( WP_REST_Request $request )
    {
        if( !isset( $request['id'] ) ) {
            return $this->error_response ( 
                'rest_invalide_id',
                __( 'Missing Property parameter : [ id ]' )
            ); 
        }

        if(  $this->is_property( $request['id'] ) === false ){
            return $this->error_response ( 
                'rest_invalide_id',
                __( 'Invalide Property Id' )
            );
        }
        $id = $request['id'];
		$force = (bool) $request['force'];
        $post = get_post( (int) $id );

        // If we're forcing, then delete permanently.
		if ( $force ) {

            $result   = wp_delete_post( $id, true );
			$response = new WP_REST_Response();

            return $this->response( [ 
                'deleted'  => true ,
                'prop_id'  => $id
                ] );

        } else {

            // Otherwise, only trash if we haven't already.
			if ( 'trash' === $post->post_status ) {
				return $this->error_response(
					'rest_already_trashed',
					__( 'The post has already been deleted.' )
				);
			}
            // (Note that internally this falls through to `wp_delete_post()`
			// if the Trash is disabled.)
			$result   = wp_trash_post( $id );
			$post     = get_post( $id );

        }

        if ( ! $result ) {
			return $this->error_response(
				'rest_cannot_delete',
				__( 'The post cannot be deleted.' )
			);
		}

        return $this->response( [ 
            'trashed'  => true ,
            'prop_id'  => $id
            ] );
    }

    /**
	 * Checks if a given request has access to create a post.
	 *
	 * @since 4.7.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return true|WP_Error True if the request has access to create items, WP_Error object otherwise.
	 */
	public function create_item_permissions_check( $request ) {
		// if ( ! empty( $request['id'] ) ) {
		// 	return new WP_Error(
		// 		'rest_post_exists',
		// 		__( 'Cannot create existing post.' ),
		// 		array( 'status' => 400 )
		// 	);
		// }

		// $post_type = get_post_type_object( 'property' );

		// if ( ! empty( $request['author'] ) && get_current_user_id() !== $request['author'] && ! current_user_can( $post_type->cap->edit_others_posts ) ) {
		// 	return new WP_Error(
		// 		'rest_cannot_edit_others',
		// 		__( 'Sorry, you are not allowed to create posts as this user.' ),
		// 		array( 'status' => rest_authorization_required_code() )
		// 	);
		// }

		// if ( ! empty( $request['sticky'] ) && ! current_user_can( $post_type->cap->edit_others_posts ) && ! current_user_can( $post_type->cap->publish_posts ) ) {
		// 	return new WP_Error(
		// 		'rest_cannot_assign_sticky',
		// 		__( 'Sorry, you are not allowed to make posts sticky.' ),
		// 		array( 'status' => rest_authorization_required_code() )
		// 	);
		// }

		// if ( ! current_user_can( $post_type->cap->create_posts ) ) {
		// 	return new WP_Error(
		// 		'rest_cannot_create',
		// 		__( 'Sorry, you are not allowed to create posts as this user.' ),
		// 		array( 'status' => rest_authorization_required_code() )
		// 	);
		// }

		// if ( ! $this->check_assign_terms_permission( $request ) ) {
		// 	return new WP_Error(
		// 		'rest_cannot_assign_term',
		// 		__( 'Sorry, you are not allowed to assign the provided terms.' ),
		// 		array( 'status' => rest_authorization_required_code() )
		// 	);
		// }

		return true;
	}
        
    /**
     * delete_property_args_schema
     *
     * @return void
     */
    public function delete_property_args_schema()
    {
        return array(
            'force' => array(
                'type'        => 'boolean',
                'default'     => false,
                'description' => __( 'Whether to bypass Trash and force deletion.' ),
            ),
        );
    }
    /**
     * login
     *
     * @param  mixed $request
     * @return user/id
     */
    public function login( WP_REST_Request $request ){
        $creds = array();
        if( !isset ( $request["username"] ) ) {
            return $this->error_response(
                'rest_invalid_param',
                __( 'Missing parameter(s) : username.' )
            );
        }
        if( !isset ( $request["password"] ) ) {
            return $this->error_response(
                'rest_invalid_param',
                __( 'Missing parameter(s) : password.' )
            );
        }
        $creds['user_login'] = $request["username"];
        $creds['user_password'] =  $request["password"];
        $creds['remember'] = true;
        $user = wp_signon( $creds, false );
    
        if ( is_wp_error($user) ) {
            return $this->error_response(
                'login error',
                __( $user->get_error_message() )
            );
        }

        return $this->response( $user );
    }
    
    /**
     * signup
     *
     * @param  mixed $request
     * @return user/id
     */
    public function signup( WP_REST_Request $request )
    {
        if ( ! empty( $request['id'] ) ) {
			return $this->error_response(
				'rest_user_exists',
				__( 'Cannot create existing user.' )
			);
		}

        $allow_signup_parameters = [
            'username',
            'first_name',
            'last_name',
            'useremail',
            'phone_number',
            'register_pass',
            'register_pass_retype',
            'role',
            'term_condition',
            'privacy_policy'
        ];

        foreach ( $allow_signup_parameters as $parameter) {
            if( ! isset( $request[$parameter] ) ) {
                $error[] = $parameter ;
                
            }  
        }

        if( ! empty( $error ) ) {
            return $this->error_response(
                'rest_invalid_param',
                __( 'Missing parameter(s) : [ ' . implode( ", ", $error ) . ' ]'  )
            );
        }

        $allowed_role = [
            'houzez_agent',
            'houzez_agency',
            'houzez_owner',
            'houzez_buyer',
            'houzez_seller',
            'houzez_manager'
        ];

        if( empty ( $request[ 'role' ] ) ) {
            return $this->error_response(
                'rest_invalid_role',
                __( 'Missing Role : [ Name ]'  )
            ); 
        }

        if( ! empty ( $request[ 'role' ] )  ){
            foreach ( $allowed_role as $role) {
                if( !in_array( $request[ 'role' ], $allowed_role ) ) {
                    return $this->error_response(
                        'rest_invalid_role',
                        __( 'Invalid Given Role : [ ' . $request[ 'role' ] . ' ]'  )
                    );                    
                }  
            }
        }

        $response = ag_register( $request );
        
        if( isset( $response[ 'error_code' ] ) ) {
            return $response;
        }
        
        return $this->response( $response );
    }
    
    /**
     * membership
     *
     * @param  mixed $request
     * @return void
     */
    public function membership( WP_REST_Request $request ){

        if( isset( $_GET['user_id'] ) && !is_numeric( $_GET['user_id'] ) ){
            return $this->error_response(
                'rest_invalid_data',
                __( 'Invalid User ID data'  )
            );
        }

        if( isset( $_GET['user_id'] ) && !empty( $_GET['user_id'] ) && is_numeric( $_GET['user_id'] ) ){
            $user_id = intval( $_GET['user_id'] );
            $response = ag_user_membership( $user_id );
        } else {
            $response = ag_membership_type();
        }


        return $this->response( $response );
    }

     
     /**
      * favorite_properties
      *
      * @param  mixed $request
      * @return void
      */
     public function favorite_properties( WP_REST_Request $request ){

        if( isset( $_GET['user_id'] ) && !is_numeric( $_GET['user_id'] ) ){
            return $this->error_response(
                'rest_invalid_data',
                __( 'Invalid User ID data'  )
            );
        }

        if( isset( $_GET['user_id'] ) && !empty( $_GET['user_id'] ) && is_numeric( $_GET['user_id'] ) ){
            $userID  = intval( $_GET['user_id'] );
            $fav_ids = 'houzez_favorites-'.$userID;
            $fav_ids = get_option( $fav_ids );
        } 

        if( empty( $fav_ids ) ) {
            return $this->error_response(
                'rest_invalid_data',
                __("You don't have any favorite listings yet!", 'houzez')
            );
        }
        $search_qry = array('post_type' => 'property', 'post__in' => $fav_ids, 'numberposts' => -1 );
        $search_qry['fields'] = 'ids';

		$fav_props = get_posts( $search_qry );

        if( count( $fav_props ) == 0 ){
            return $this->error_response(
                'rest_invalid_data',
                __("You don't have any favorite listings yet!", 'houzez')
            );
        }

        foreach ((array)$fav_props as $prop_id) {
            $response[] = get_prop_data( $prop_id , $_GET['data_collection'] );
        }
        wp_reset_postdata();

        return $this->response( $response );
     }
     
     /**
      * favorite_properties_add
      *
      * @param  mixed $request
      * @return void
      */
     public function favorite_properties_add( WP_REST_Request $request ){

        if( isset( $_GET['user_id'] ) && !is_numeric( $_GET['user_id'] ) ){
            return $this->error_response(
                'rest_invalid_data',
                __( 'Invalid User ID data'  )
            );
        }

        if( isset( $_GET['prop_id'] ) && !is_numeric( $_GET['prop_id'] ) && $this->is_property( $_GET['prop_id'] )){
            return $this->error_response(
                'rest_invalid_data',
                __( 'Invalid Property ID data'  )
            );
        }

        if( isset( $_GET['user_id'] ) && !empty( $_GET['user_id'] ) && is_numeric( $_GET['user_id'] ) ){
            $userID  = intval( $_GET['user_id'] );
            $fav_option = 'houzez_favorites-'.$userID;
            $property_id = intval( $_GET['prop_id'] );
            $current_prop_fav = get_option( 'houzez_favorites-'.$userID );

            // Check if empty or not
            if( empty( $current_prop_fav ) ) {
                $prop_fav = array();
                $prop_fav['1'] = $property_id;
                update_option( $fav_option, $prop_fav );
            } else {
                if(  ! in_array ( $property_id, $current_prop_fav )  ) {
                    $current_prop_fav[] = $property_id;
                    update_option( $fav_option,  $current_prop_fav );
                } else {
                    $key = array_search( $property_id, $current_prop_fav );

                    if( $key != false ) {
                        unset( $current_prop_fav[$key] );
                    }
                    update_option( $fav_option, $current_prop_fav );

                }
            }
        } 

        return $this->response( $current_prop_fav );
     }

}


new AqarGateApi();