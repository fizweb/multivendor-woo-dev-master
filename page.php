<?php get_header(); if(have_posts()) : while(have_posts())  : the_post(); 
    if(get_post_meta(get_the_ID(), 'ppm_meta', true)) {
        $page_meta = get_post_meta(get_the_ID(), 'ppm_meta', true);
    } else {
        $page_meta = array();
    }

    if(array_key_exists('enable_page_title', $page_meta)) {
        $enable_page_title = $page_meta['enable_page_title'];
    } else {
        $enable_page_title = true;
    }

    if(array_key_exists('default_padding', $page_meta)) {
        $default_padding = $page_meta['default_padding'];
    } else {
        $default_padding = true;
    }
?> 



    <section style="overflow:hidden;" class="<?php if($default_padding == true) : ?>content-block elementor-section elementor-section-boxed<?php endif; ?>">
        <div class="elementor-container">
            <div class="internal-content-wrap">
                <?php if($enable_page_title == true) : ?>
                    <h2 class="internal-page-title"><?php the_title(); ?></h2>
                <?php endif; ?>

                <?php the_content(); ?>
            </div>
        </div>
    </section>

<?php endwhile; endif; get_footer(); ?>