<?php include_once('header.php'); ?>
    <main class="d-flex flex-row-reverse">
        <?php get_template_part( 'global-templates/sidebar' ); ?>
        <div class="content col-9">
            front-page
            <?php dynamic_sidebar('main-bar'); ?>
            <?php get_template_part( 'global-templates/chat' ); ?>
        </div>
    </main>
<?php include_once('footer.php'); ?>