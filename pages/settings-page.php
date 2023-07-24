<div class="wrap">
    <h1><?php echo get_admin_page_title() ?></h1>
    <?php settings_errors(); ?>
    <form method="post" action="options.php">
        <?php            
            settings_fields( 'vidiow_plugin_settings' ); // settings group name
            do_settings_sections( 'vidiow_plugin' ); // just a page slug
            submit_button( __( 'Save Changes', 'vidiow' ), 'primary', 'submit_vidiow_settings' );
        ?>
    </form>
</div>