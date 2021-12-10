<?php get_header(); if(have_posts()) : while(have_posts())  : the_post(); ?> 

    <div class="content-block elementor-section elementor-section-boxed">
        <div class="elementor-container">
            <div class="internal-content-wrap">
                <h2 class="internal-page-title"><?php the_title(); ?></h2>
                <?php the_content(); ?>
            </div>
        </div>
    </div>

<?php endwhile; endif; get_footer(); ?>