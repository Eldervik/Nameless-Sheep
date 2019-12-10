<div id="sidebar" class="col-lg-3 col-md-4 col-sm-6 col-11 p-0 d-flex flex-column">
    <div id="arrow" class="arrow d-flex justify-content-center align-items-center">
        <i class="fas fa-angle-left"></i>
    </div>
    <form>
        <input type="text" name="Search" placeholder="Search Product...">
    </form>
    <div class="accordion flex-grow-1" id="accordionExample">
        <div class="card">
            <div class="card-header" id="headingTwo">
                <h2 class="mb-0">
                    <button class="btn btn-link collapsed p-0" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="fas fa-apple-alt"></i><span>Category #1</span>
                    </button>
                </h2>
            </div>
            <div id="collapseOne" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                <div class="d-flex flex-column">
                    <a href="#" class="card-header">All Products</a>
                    <a href="#" class="card-header">Sub Category #1</a>
                    <a href="#" class="card-header">Sub Category #2</a>
                    <a href="#" class="card-header">Sub Category #3</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingTwo">
                <h2 class="mb-0">
                    <button class="btn btn-link collapsed p-0" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <i class="fas fa-apple-alt"></i><span>Category #2</span>
                    </button>
                </h2>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                <div class="d-flex flex-column">
                    <a href="#" class="card-header">Sub Category #1</a>
                    <a href="#" class="card-header">Sub Category #2</a>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header" id="headingThree">
                <h2 class="mb-0">
                    <button class="btn btn-link collapsed p-0" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                    <i class="fas fa-apple-alt"></i><span>Category #3</span>
                    </button>
                </h2>
            </div>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                <div class="d-flex flex-column">
                    <a href="#" class="card-header">Sub Category #1</a>
                    <a href="#" class="card-header">Sub Category #2</a>
                    <a href="#" class="card-header">Sub Category #3</a>
                </div>
            </div>
        </div>
    </div>
    <div class="socials d-flex">
        <a href="#"><i class="fab fa-facebook-square"></i></a>
        <a href="#"><i class="fab fa-facebook-square"></i></a>
        <a href="#"><i class="fab fa-facebook-square"></i></a>
    </div>
</div>

<?php wp_enqueue_script( 'sidebar-new', get_template_directory_uri() . '/js/sidebar.js', array(), 1, true); ?>