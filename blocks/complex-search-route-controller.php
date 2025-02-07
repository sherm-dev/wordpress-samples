<?php

class Complex_Search_Route_Controller extends WP_REST_Controller {

  /**
   * Register the routes for the objects of the controller.
   */
  public function register_routes() {
    $version = '1';
    $namespace = 'jewishla/v' . $version;
    $base = 'complex-search';
    register_rest_route( $namespace, '/' . $base, array(
      array(
        'methods'             => WP_REST_Server::CREATABLE,
        'callback'            => array( $this, 'get_items' ),
        'permission_callback' => array( $this, 'get_items_permissions_check' ),
        'args'                => array(

        ),
      )
    ) );

    register_rest_route( $namespace, '/' . $base . '/schema', array(
      'methods'  => WP_REST_Server::CREATABLE,
      'callback' => array( $this, 'get_public_item_schema' ),
    ) );
  }

  /**
   * Get a collection of items
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|WP_REST_Response
   */
  public function get_items( $request ) {
	$args = array(
		'post_type'				=>	$request['types'],
		'paged' 				=> 	$request['page'],
		'posts_per_page'		=> 	$request['per_page'],
		'order'					=>	$request['order'],
		'orderby'				=>	$request['orderby'],
		's'						=>	isset($request['search']) ? filter_var($request['search'], FILTER_SANITIZE_FULL_SPECIAL_CHARS) : "",
		'offset'				=>	isset($request['offset']) ? $request['offset'] : 0,
		'post_status'			=>	'publish',
		'post_parent'			=>	0, //only pages without parent
		'cache_results'			=>	true
	);
	  
		
	  
	  
	if(isset($request['exclude']))
		$args['exclude'] = $request['exclude'];
	  
	if(isset($request['term_slugs']) && !empty($request['term_slugs'])){
		$args['tax_query']['relation'] = 'OR';
		
		foreach($request['term_slugs'] as $key => $value){
			if(isset($value['filters']) && !empty($value['filters'])){
				foreach($value['filters'] as $term){
					if($term['checked'])
						$args['tax_query'][] = array(
							'taxonomy' => $key == "program_subject" || $key == "interest" || $key == "need" || $key == "age_group" ? $key . "s" : $key,
							'field'    => 'slug',
							'terms'    =>  $term['slug']
						);
				}
			}
		}
	}
	 
	  $query = new WP_Query($args);
	  
    $items = $query->posts; //do a query, call another class, etc
	
    $data = array(
		'max_pages'	=> $query->max_num_pages,
		'posts'		=>	array()
	);
	  
    foreach( $items as $item ) {
      $itemdata = $this->prepare_item_for_response( $item, $request );
      $data['posts'][] = $this->prepare_response_for_collection( $itemdata );
    }

    return new WP_REST_Response( $data, 200 );
  }



  /**
   * Check if a given request has access to get items
   *
   * @param WP_REST_Request $request Full data about the request.
   * @return WP_Error|bool
   */
  public function get_items_permissions_check( $request ) {
    //return true; <--use to make readable by all
    return true;
  }
	
  private function get_item_link($item){
	  if('program' == $item->post_type){
		  return !empty(get_post_meta($item->ID, 'program_external_url', TRUE)) ? get_post_meta($item->ID, 'program_external_url', TRUE) : get_the_permalink($item->ID);
	  }else if('press-media' == $item->post_type){
		  return !empty(get_post_meta($item->ID, 'src_link', TRUE)) && FALSE != get_post_meta($item->ID, 'src_link', TRUE) ? get_post_meta($item->ID, 'src_link', TRUE)['url'] : "";
	  }else{
		  return get_the_permalink($item->ID);
	  }
  }

  /**
   * Prepare the item for the REST response
   *
   * @param mixed $item WordPress representation of the item.
   * @param WP_REST_Request $request Request object.
   * @return mixed
   */
  public function prepare_item_for_response( $item, $request ) {
	  $item->permalink = $this->get_item_link($item);
	  $item->featured_image = get_the_post_thumbnail_url($item->ID);
	  $item->terms = wp_get_object_terms($item->ID, array('category', 'post_tag', 'needs', 'tribe_events_cat', 'interests', 'age_groups', 'program_subjects'));
	  
	  if(in_array('program', $request['types'])){
		  $meta = array(
		  		'address'		=>		get_post_meta($item->ID, 'address1', TRUE),
			  	'address2'		=>		get_post_meta($item->ID, 'address2', TRUE),
			  	'city'			=>		get_post_meta($item->ID, 'city', TRUE),
			  	'state'			=>		get_post_meta($item->ID, 'state', TRUE),
			  	'zip'			=>		get_post_meta($item->ID, 'zip', TRUE),
			  	'phone'			=>		get_post_meta($item->ID, 'program_phone', TRUE),
			  	'email'			=>		get_post_meta($item->ID, 'program_email', TRUE),
			  	'contact'		=>		get_post_meta($item->ID, 'program_contact', TRUE)
		  );
		  
		  $item->meta = $meta;
	  }
    return $item;
  }

  /**
   * Get the query params for collections
   *
   * @return array
   */
  public function get_collection_params() {
	 
		
    return array(
      'page'     => array(
        'description'       => 'Current page of the collection.',
        'type'              => 'integer',
        'default'           => 1,
        'sanitize_callback' => 'absint',
      ),
      'per_page' => array(
        'description'       => 'Maximum number of items to be returned in result set.',
        'type'              => 'integer',
        'default'           => 10,
        'sanitize_callback' => 'absint',
      ),
      'search'   => array(
        'description'       => 'Limit results to those matching a string.',
        'type'              => 'string',
        'sanitize_callback' => 'sanitize_text_field',
      ),
	  'orderby' => array(
			'description' => __( 'Sort collection by post attribute.' ),
			'type'        => 'string',
			'default'     => 'date',
			'enum'        => array(
				'author',
				'date',
				'id',
				'include',
				'modified',
				'parent',
				'relevance',
				'slug',
				'include_slugs',
				'title',
			),
	   ),
	   'order' =>	array(
			'description' => __( 'Order sort attribute ascending or descending.' ),
			'type'        => 'string',
			'default'     => 'desc',
			'enum'        => array( 'asc', 'desc' ),
		),
		'types'	=>	array(
			'description' => __( 'Limit results to specific post types.' ),
			'type'        => 'array',
			'items'       => array(
				'type' => 'string',
			),
		),
		'context'	=>	$this->get_context_param( array( 'default' => 'view' ) ),
		'exclude'	=>	array(
			'description' => __( 'Ensure result set excludes specific IDs.' ),
			'type'        => 'array',
			'items'       => array(
				'type' => 'integer',
			),
			'default'     => array(),
		),
		'term_slugs'	=>	array(
			'description' => __( 'Limit result set to terms with one or more specific slugs.' ),
			'type'        => 'array',
			'items'       => array(
				'type' => 'string',
			),
		)
    );
  }
}
