<?php include_once('header.php'); ?>
    <main class="d-flex flex-row-reverse">
        <?php get_template_part( 'global-templates/sidebar' ); ?>
        <div class="content col-9">
            front-page

            <?php while ( have_posts() ) : the_post(); ?>

                <?php get_template_part( 'loop-templates/content' ); ?>

                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;
                ?>

            <?php endwhile; // end of the loop. ?>


            <?php dynamic_sidebar('main-bar'); ?>
            <?php get_template_part( 'global-templates/chat' ); ?>
        </div>
    </main>
<?php include_once('footer.php'); ?>