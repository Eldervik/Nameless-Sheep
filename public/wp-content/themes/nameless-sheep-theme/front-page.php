<?php
// Parent
$args = array(
    'taxonomy' => 'product_cat',
    'hide_empty' => false,
    'parent'   => 0
);
$product_cat = get_terms( $args );

foreach ($product_cat as $parent_product_cat) { ?>
    <div class="card">
        <div class="card-header" id="headingTwo">
            <h2 class="mb-0">
                <button class="btn btn-link collapsed p-0" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseTwo">
                    <i class="fas fa-apple-alt"></i><span><?php echo $parent_product_cat->name; ?></span>
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
            <a href="<?php echo get_term_link($parent_product_cat->term_id); ?>"><?php esc_html_e('All products', 'nameless-sheep') ?></a>
        <?php
        foreach ($child_product_cats as $child_product_cat) { ?>
            <a href="<?php echo get_term_link($child_product_cat->term_id); ?>" class="card-header"><?php echo $child_product_cat->name; ?></a>
        <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>