<?php

class PPM_Puppies_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'ppm-puppies';
    }
    
	public function get_title() {
		return __( 'Puppies', 'ppm-quickstart' );
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
        
		
		$this->end_controls_section();

	}

	

	protected function render() {

		$settings = $this->get_settings_for_display();

		$search 	  = $_GET['search'];
		$puppyCat   = $_GET['cat'];
		$date_order = $_GET['date_order'];

		$html = '<div class="puppies-wrapper">
			<div class="puppy-search-area">
				<div class="container">
					<div class="puppy-element">
						<form action="">
							<input type="search" name="search" Placeholder="Search..." value="'.$search.'" />
						
							<select name="cat" id="cat">
								<option value="">Filter by</option>';
								
								$puppy_categories = get_terms( 'puppy_cat' );
								foreach ( $puppy_categories as $cat ) {
									$cat_info = get_term( $cat );
									$selected = ($puppyCat == $cat_info->term_id) ? "selected" : "";
									$html .= '<option value="'. $cat_info->term_id .'" '. $selected .'>'
									. $cat_info->name 
									.'</option>';
								}
								
								$html .= '
							</select>
						
							<select name="date_order" id="date_order">
								<option value="">Date modified</option>';
								
								$dateAsc  = ($date_order == "asc") ? "selected" : "";
								$dateDesc = ($date_order == "desc") ? "selected" : "";

								$html .= '
								<option value="asc"'. $dateAsc .'>Ascending</option>
								<option value="desc"'. $dateDesc .'>Descending</option>
							</select>

							<button type="submit" class="submit">
								<i class="fa fa-search"></i>
							</button>
						</form>
					</div>
				</div>
			</div>

			<div class="puppy-list-area">
				<div class="container">

					<div id="puppies-list">
					
						<div class="puppies-list">';
							$tax_query = array('relation' => 'AND');

							if( !empty($puppyCat) ){
								$tax_query[] = array(
									'taxonomy' => 'puppy_cat',
									'fields' 	 => 'id',
									'terms'  	 => $puppyCat
								);
							}

							$orderby = "date";

							if( empty($date_order) ){
								$date_order = "desc";
							}

							$args = array(
								"posts_per_page" => -1,
								"post_type" 		 => "puppy",
								"s" 						 => $search,
								"tax_query" 		 => $tax_query,
								"orderby" 			 => $orderby,
								"order"   			 => $date_order
							);
						
							$q = new WP_Query( $args );

							// $html .= var_dump($q);

							while ( $q->have_posts() ) : $q->the_post();
							$html .= '
							<div class="single-puppy-item">
								<a href="/" class="single-puppy-wrapper">
									<div class="puppy-bg" style="background-image: url('.
										get_the_post_thumbnail_url(get_the_ID(), "medium")
									.')"></div>

									<h3>'. get_the_title() .'</h3>
									<h5>'. get_the_date() .'</h5>
								</a>
							</div>';
							endwhile; wp_reset_query();

							$html .= '
						</div>
					
					</div>

				</div>
			</div>
		</div>';
		
		echo $html;

	}

}