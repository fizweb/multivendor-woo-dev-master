<?php

class Multivendor_Order_Button extends \Elementor\Widget_Base {

	public function get_name() {
		return 'multivendor-order-button';
	}
    
	public function get_title() {
		return __( 'Multivendor Order Button', 'ppm-quickstart' );
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

		$html = '
			<button id="user-location" class="shop-now-button" type="button">Shop Now</button>
			
			<script>
				jQuery( document ).ready( function($) {
					$( "#user-location" ).click( function() {
						getLocation();
					});
				});

				function getLocation() {
					if (navigator.geolocation) {
						navigator.geolocation.getCurrentPosition(showPosition, showError);
					} else { 
						console.log("Geolocation is not supported by this browser!");
					}
				}
				
				function showPosition(position) {
					let lat  = position.coords.latitude;
					let long = position.coords.longitude;
					let url  = "'. get_page_link(63) .'?lat=" + lat + "&long=" + long;
					window.location.href = url;
				}

				function showError(error) {
					alert("Please, enable your location!");
				}
			</script>
		';

		echo $html;

	}

}