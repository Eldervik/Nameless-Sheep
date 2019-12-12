<?php
$args = array(
    'taxonomy' => 'product_cat',
    'hide_empty' => false,
    'parent'   => 0
);
$product_cat = get_terms( $args );
?>

<div id="sidebar" class="col-lg-3 col-md-4 col-sm-6 col-11 p-0 d-flex flex-column">
    <div id="arrow" class="arrow d-flex justify-content-center align-items-center">
        <i class="fas fa-angle-left"></i>
    </div>
    <?php get_search_form();?>
    <div class="accordion flex-grow-1" id="accordionExample">

    <?php foreach ($product_cat as $parent_product_cat) { ?>
        <div class="card">
            <div class="card-header" id="headingTwo">
                <h2 class="mb-0">
                    <button class="btn btn-link collapsed p-0" type="button" data-toggle="collapse" data-target="#cat-<?php echo $parent_product_cat->term_id; ?>" aria-expanded="false" aria-controls="collapseTwo">
                    <?php the_field('cat_icon', $parent_product_cat); ?><span><?php echo $parent_product_cat->name; ?></span>
                    </button>
                </h2>
            </div>
            <?php
                $child_args = array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => false,
                    'parent'   => $parent_product_cat->term_id
                );
                $child_product_cats = get_terms( $child_args );
            ?>
            <div id="cat-<?php echo $parent_product_cat->term_id; ?>" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">

                <div class="d-flex flex-column">
                    <a href="<?php echo get_term_link($parent_product_cat->term_id); ?>" class="card-header"><?php esc_html_e('All products', 'nameless-sheep') ?></a>
                    <?php
                    foreach ($child_product_cats as $child_product_cat) { ?>
                        <a href="<?php echo get_term_link($child_product_cat->term_id); ?>" class="card-header"><?php echo $child_product_cat->name; ?></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>
    </div>
    <div class="socials d-flex">
        <a href="#"><i class="fab fa-facebook-square"></i></a>
        <a href="#"><i class="fab fa-facebook-square"></i></a>
        <a href="#"><i class="fab fa-facebook-square"></i></a>
    </div>
</div>

<?php wp_enqueue_script( 'sidebar-new', get_template_directory_uri() . '/js/sidebar.js', array(), 1, true); ?>