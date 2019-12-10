<?php include_once('header.php'); ?>
    <main class="d-flex flex-row-reverse">
        <?php get_template_part( 'global-templates/sidebar' ); ?>
        <div class="content col-9">
             <?php include_once('global-templates/single_faq.php'); ?>
        </div>
        <?php get_template_part( 'global-templates/chat' ); ?>
    </main>
<?php include_once('footer.php'); ?>
