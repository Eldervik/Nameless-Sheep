<div class="card">
    
    <?php the_title(); ?>
    
    <?php echo get_the_post_thumbnail( $post->ID, 'post_image' ); ?>
    
    <?php $price = get_post_meta( get_the_ID(), '_price', true ); ?>
    
    <p><?php echo wc_price( $price ); ?></p>
    
</div>

<style>
    
</style>