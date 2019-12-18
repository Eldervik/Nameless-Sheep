<?php include_once('header.php'); ?>
    <main class="d-flex flex-row-reverse">
        <?php get_template_part( 'global-templates/sidebar' ); ?>
        <div class="content col-9">
            <h1><?php esc_html_e( 'Search result', 'nameless-sheep' ); ?></h1>
            
            <ul class="products">
                <?php
                    $args = array(
                        'post_type' => 'product',
                        'posts_per_page' => 12
                        );
                    $loop = new WP_Query( $args );
                    if ( $loop->have_posts() ) {
                        while ( $loop->have_posts() ) : $loop->the_post();
                            wc_get_template_part( 'content', 'product' );
                        endwhile;
                    } else {
                        echo __( 'No products found' );
                    }
                    wp_reset_postdata();
                ?>
            </ul><!--/.products-->

            <?php get_template_part( 'global-templates/chat' ); ?>
        </div>
    </main>
<?php include_once('footer.php'); ?>
