<?php

class PPM_Events_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'ppm-events';
	}
    
	public function get_title() {
		return __( 'Upcoming Events', 'ppm-quickstart' );
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

		// Get the next 3 upcoming events from DB
		$events_all = tribe_get_events( [
			'posts_per_page' => 5,
			'start_date'   	 => 'now', // now, today, last month, next monday / any date
			'end_date'     	 => '2021-12-31 00:00:00',
			// 'tag'            => 'party',
			// 'featured'       => true,
		] );

		$html = '<div class="upcoming-events"><div class="row">';

		// Loop through the events, displaying the single event content
		foreach ( $events_all as $index => $event ) {

			$single_event = tribe_get_event($event->ID);

			$venue = ''; $organizer = '';
			foreach ( $single_event->venues as $key => $venue ) {
				$venue = $venue;
			}
			foreach ( $single_event->organizers as $key => $organizer ) {
				$organizer = $organizer;
			}

			// $html .= var_dump($venue);

			$html .= '<div class="col-md-4">
				<div class="single-event-item p-3">
					<div class="single-event-wrapper">
						<h3 class="single-event-title fs-4 mb-3">' . $single_event->post_title . '</h3>

						<div class="single-event-content mb-3">
							' . wpautop( $single_event->post_content ) . '
						</div>

						<div class="single-event-meta mb-3">
							<div class="start-date">
								Start:
								<span class="mx-1">' . date('M-d-Y', strtotime($single_event->start_date)) . '</span> @
								<span class="mx-1">' . date('h:i A', strtotime($single_event->start_date)) . '</span>	
							</div>
							<div class="end-date">
								End:
								<span class="mx-1">' . date('M-d-Y', strtotime($single_event->end_date)) . '</span> @
								<span class="mx-1">' . date('h:i A', strtotime($single_event->end_date)) . '</span>	
							</div>
						</div>

						<div class="single-event-organizer mb-3">'
							. $organizer->post_title .
						'</div>

						<div class="single-event-location">
							<h6 class="mb-1">Venue:</h6>

							<div>
								<span class="venue">' . tribe_get_venue($single_event->ID) . ',</span><br/>
								<span class="city">' . tribe_get_city($single_event->ID) . ',</span>
								<span class="country ml-5">' . tribe_get_country($single_event->ID) . '.</span>
							</div>
						</div>
					</div>
				</div>
			</div>';

		}


		$html .= '</div></div>';

		echo $html;

	}

}