<?php

class PPM_Dokan_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'ppm-dokans';
	}
    
	public function get_title() {
		return __( 'Dokans', 'ppm-quickstart' );
	}

	public function get_icon() {
		return 'fa fa-code';
	}

	public function get_categories() {
		return [ 'general' ];
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Settings', 'plugin-name' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		
		/* $this->add_control(
			'slides',
			[
				'label' => __( 'Slides', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls()
			]
		); */

		$this->end_controls_section();

	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$vendors = dokan_get_sellers(-1);

		$args = array(
			'role' 			 => 'seller',
			/* 'meta_query' => array(
				array(
					'meta_key'     => 'street_1',
					'meta_value'   => ['Banani-1'],
					'meta_compare' => 'IN'
				), */
				//'relation' => 'AND',
				//dokan_store_address()
				/* array(
					'key'     => 'address_2',
					'value'   => 'Mohakhali_2',
					//'value'   => array( 20, 30 ),
					'type'    => 'numeric',
					'compare' => 'BETWEEN'
				) */
			//)
		);

		$users_query = new WP_User_Query( $args );
		$sellers 		 = $users_query->get_results();
		
		//'address_1' => 'Mohakhali_1' 

		$html = '<div class="dokans-area">';
		// var_dump( $sellers );

		foreach ( $sellers as $key => $user){
			$user_meta = get_user_meta( $user->ID, 'custom_profile_options', true );

			$html .= '<pre>' . var_export( $user_meta, true ) . '</pre>';
		}

		// $html .= '<pre>' . var_export($sellers, true) . '</pre>';

		/*
		foreach ( $sellers as $key => $user){
			$html .= '<div class="border-1">
				<div>ID= '. esc_attr( $user->ID ) .'</div>'.
				'<div>Display-Name= '. esc_html( $user->display_name ) .'</div>
			</div>';
		} */

		$html .='</div>';


		echo $html;

	}

}