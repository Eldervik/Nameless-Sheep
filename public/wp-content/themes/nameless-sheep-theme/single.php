<?php include_once('header.php'); ?>
<h1><?php the_title();?></h1>
<img src="<?php the_post_thumbnail_url('post_image');?>">
<?php if(have_posts()) : while(have_posts()) : the_post();?>
<?php the_content();?>
<?php endwhile; else: endif; ?>