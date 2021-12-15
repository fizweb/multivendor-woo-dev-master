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

  $search 	  = $_POST['search'];
  $category   = $_POST['category'];
  $date_order = $_POST['date_order'];
  $puppies_nonce = $_POST['puppies_nonce'];

  if ( ! isset($puppies_nonce) || ! wp_verify_nonce($puppies_nonce, 'puppies_ajax_action') ){
    die('Sorry, your query did not verify.');
  }

  $tax_query = array('relation' => 'AND');

  if( !empty($category) ){
    $tax_query[] = array(
      'taxonomy' => 'puppy_cat',
      'fields' 	 => 'id',
      'terms'  	 => $category
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

  $html = '<div class="puppies-list">';
  
    while ( $q->have_posts() ) : $q->the_post();
      $html .= '
      <div class="single-puppy-item">
        <a href="/" class="single-puppy-wrapper">
          <div class="puppy-bg" style="background-image: url('
          . get_the_post_thumbnail_url(get_the_ID(), "medium")
          . ')"></div>

          <h3>'. get_the_title() .'</h3>
          <h5>'. get_the_date() .'</h5>
        </a>
      </div>';
    endwhile; wp_reset_query();

  $html .= '</div>';

  echo $html;
  wp_die();
  
}


add_shortcode( 'puppies_ajax', 'puppies_ajax_shortcode' );

function puppies_ajax_shortcode() {

  $html = '<div class="puppies-wrapper">
    <div class="puppy-search-area">
      <div class="container">
        <div class="puppy-element">
          <form action="">'
          . wp_nonce_field('puppies_ajax_action', 'puppies_nonce')

          . '<input type="search" name="search" id="search" Placeholder="Search..." value="" />
          
            <select name="category" id="category">
              <option value="">Filter by</option>';
              
              $puppy_categories = get_terms( 'puppy_cat' );
              foreach ( $puppy_categories as $cat ) {
                $cat_info = get_term( $cat );
                $html .= '<option value="'. $cat_info->term_id .'">'
                . $cat_info->name 
                . '</option>';
              }
              
              $html .= '
            </select>
          
            <select name="date_order" id="date_order">
              <option value="">Date modified</option>
              <option value="asc">Ascending</option>
              <option value="desc">Descending</option>
            </select>
          </form>
        </div>
      </div>
    </div>
    
    <div class="puppy-list-area">
      <div class="container">

        <div id="puppies-list"></div>

      </div>
    </div>
  </div>

  <script>
    jQuery(document).ready( function ($) {

      $(window).load( function () {
        let puppies_nonce = $("form #puppies_nonce").val();
        $.ajax({
          url: "'.admin_url('admin-ajax.php').'",
          type: "POST",
          data: {
            action: "puppies_ajax_action",
            puppies_nonce: puppies_nonce,
          },
          beforeSend: function () {
            $("#puppies-list").empty();
            $("#puppies-list").append("Loading...");
          },
          success: function (html) {
            $("#puppies-list").empty();
            $("#puppies-list").append(html);
          }
        });
      });

      $("form select, form #search").change( function () {
        let search     = $("form #search").val();
        let category   = $("form #category").val();
        let date_order = $("form #date_order").val();
        let puppies_nonce = $("form #puppies_nonce").val();
        $.ajax({
          url: "'.admin_url('admin-ajax.php').'",
          type: "POST",
          data: {
            action: "puppies_ajax_action",
            search: search,
            category: category,
            date_order: date_order,
            puppies_nonce: puppies_nonce,
          },
          beforeSend: function () {
            $("#puppies-list").empty();
            $("#puppies-list").append("Loading...");
          },
          success: function (html) {
            $("#puppies-list").empty();
            $("#puppies-list").append(html);
          }
        });
      });

      $("form #search").keypress( function () {
        let search     = $("form #search").val();
        let puppies_nonce = $("form #puppies_nonce").val();
        $.ajax({
          url: "'.admin_url('admin-ajax.php').'",
          type: "POST",
          data: {
            action: "puppies_ajax_action",
            search: search,
            puppies_nonce: puppies_nonce,
          },
          beforeSend: function () {
            $("#puppies-list").empty();
            $("#puppies-list").append("Loading...");
          },
          success: function (html) {
            $("#puppies-list").empty();
            $("#puppies-list").append(html);
          }
        });
      });

    });
  </script>';
  
  return $html;

}


/* function get_puppies_list( $search, $category, $date_order ){

  $tax_query = array('relation' => 'AND');

  if( !empty($category) ){
    $tax_query[] = array(
      'taxonomy' => 'puppy_cat',
      'fields' 	 => 'id',
      'terms'  	 => $category
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

  return $q;

}


function render_puppies_list( $lists ){

  $html = '<div class="puppies-list">';
  
    while ( $lists->have_posts() ) : $lists->the_post();
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

  $html .= '</div>';

  echo $html;

}


add_shortcode( 'puppies_ajax', 'puppies_ajax_shortcode' );

function puppies_ajax_shortcode() {

  $search 	  = $_GET['search'];
  $category   = $_GET['cat'];
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
              $("form select").click( function () {
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
      <div class="container">';
          
        render_puppies_list( get_puppies_list( $search, $category, $date_order ) );

        $html .= '
      </div>
    </div>
  </div>';
  
  echo $html;

} */

