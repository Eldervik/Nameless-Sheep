<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div class="form-group has-search">
        <span class="fa fa-search form-control-feedback"></span>
        <input type="text" class="form-control" name="s" id="s" placeholder="<?php esc_attr_e( 'Search product...', 'nameless-sheep' ); ?>" />
    </div>
</form>
