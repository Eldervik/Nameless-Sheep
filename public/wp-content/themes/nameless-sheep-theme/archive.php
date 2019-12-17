<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    <img src="<?php the_post_thumbnail_url('post_image'); ?>" alt="<?php the_title(); ?>">
    <?php the_excerpt(); ?>
<?php endwhile; else: endif; ?>
