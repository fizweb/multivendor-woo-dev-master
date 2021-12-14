<?php 

/* function phone_btn_shortcode( $atts, $content = null  ) {
  extract( shortcode_atts( array(
    'class' => 'boxed-btn phone-btn',
  ), $atts) );

  $options = get_option('ppm_theme_options');
  $phone = $options['phone'];

  if(!empty($phone)) {
    $html = '<a href="tel:'.$phone.'" class="'.$class.'">'.$phone.'</a>';
  } else {
    $html = '';
  }

  return $html;
}   
add_shortcode('phone_btn', 'phone_btn_shortcode'); */



// AJAX Shortcode

add_action( 'wp_ajax_puppies_ajax_action', 'puppies_ajax_function' );
add_action( 'wp_ajax_nopriv_puppies_ajax_action', 'puppies_ajax_function' );

function puppies_ajax_function(){
  echo 'AJAX Loaded';

  wp_die();
}


add_shortcode( 'puppies_ajax', 'puppies_ajax_shortcode' );

function puppies_ajax_shortcode() {

  $search 	  = $_GET['search'];
  $puppyCat   = $_GET['cat'];
  $date_order = $_GET['date_order'];

  $html = '<div class="puppies-wrapper">
    <div class="puppy-search-area">
      <div class="container">
        <div class="puppy-element">
          <form action="">
            <input type="search" name="search" Placeholder="Search..." value="'.$search.'" />
          
            <select name="cat" id="category">
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
          </form>

          <script>
            jQuery(document).ready( function ($) {
              $("form select").change( function () {
                let category   = $("form #category").val();
                let date_order = $("form #date_order").val();
                $.ajax({
                  url: "'.admin_url('admin-ajax.php').'",
                  type: "POST",
                  data: {
                    action: "puppies_ajax_action"
                  },
                  success: function (data) {
                    $(".puppies-list").append(data);
                  }
                });
              });
            });
          </script>

        </div>
      </div>
    </div>
    
    <div class="puppy-list-area">
      <div class="container">
        <div class="puppies-list">';
              
          $search 	  = $_GET['search'];
          $puppyCat   = $_GET['cat'];
          $date_order = $_GET['date_order'];
          
          puppies_list( $puppies_list );

          $html .= '
        </div>
      </div>
    </div>
  </div>';
  

  echo $html;

}


function puppies_list( $puppies_list ){

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

  $html = "";

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

  return $html;

}

