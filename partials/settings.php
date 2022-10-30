<div class="wrap">
  <h2>Coming Soon Mode</h2>
  <p>From here you can customize your coming soon/maintenance mode experience</p>
  <?php settings_errors(); ?>

  <form method="post" action="options.php">
    <?php
                settings_fields('ecsm_option_group');
    do_settings_sections('ecsm-admin');
    submit_button(); ?>
  </form>
</div>
