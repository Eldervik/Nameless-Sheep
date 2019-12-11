<?php include_once('header.php'); ?>
<main class="d-flex flex-row-reverse">
    <?php get_template_part( 'global-templates/sidebar' ); ?>
    <div class="content col-9">
        <h1><?php the_title();?></h1>
        page
        <?php get_template_part( 'global-templates/chat' ); ?>
        <img src="<?php the_post_thumbnail_url('post_image');?>">
        <?php if(have_posts()) : while(have_posts()) : the_post();?>
            <?php the_content();?>
        <?php endwhile; else: endif; ?>
    </div>
</main>
<?php include_once('footer.php'); ?>