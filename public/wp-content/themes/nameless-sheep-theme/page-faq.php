<?php $faqs = get_field('show_faq'); ?>


<?php include_once('header.php'); ?>
    <main class="d-flex flex-row-reverse">
        <?php get_template_part( 'global-templates/sidebar' ); ?>
        <div class="content col-9">
            <div class="accordion" id="accordionExample">
                <?php
                    if ($faqs) { ?>
                        <?php
                            foreach ($faqs as $post) {
                                setup_postdata($post);
                                ?>
                                    <?php get_template_part('loop-templates/content', 'faq'); ?>
                                    <?php
                            }
                            wp_reset_postdata();
                        ?>
                    <?php
                    }
                ?>
            </div>
            <?php get_template_part( 'global-templates/chat' ); ?>
        </div>
    </main>
<?php include_once('footer.php'); ?>
