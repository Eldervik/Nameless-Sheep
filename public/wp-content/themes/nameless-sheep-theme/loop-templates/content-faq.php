<div class="card">
    <div class="card-header" id="headingOne">
        <h2 class="mb-0">
            <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#faq-<?php echo get_the_ID(); ?>" aria-expanded="true" aria-controls="collapseOne">
                <?php the_title(); ?>
            </button>
        </h2>
    </div>
    <div id="faq-<?php echo get_the_ID(); ?>" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
        <div class="card-body">
            <?php the_content(); ?>
        </div>
    </div>
</div>