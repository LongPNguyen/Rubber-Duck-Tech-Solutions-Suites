<?php
// Make sure this file is being included by a parent file, not accessed directly
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

?>
<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <form method="post" action="">
        <table class="form-table">
            <tbody>
                <?php rdts_display_addon_settings(); ?>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
        </p>
        <?php wp_nonce_field('rdts_settings_action', 'rdts_settings_nonce'); ?>
    </form>
</div>