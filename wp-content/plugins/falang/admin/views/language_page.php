<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       www.faboba.com
 * @since      1.0.0
 *
 * @package    Falang
 * @subpackage Falang/admin/partials
 */
?>

<div id="col-container">
        <div class="col-wrap">
            <?php
            // Displays the language list in a table
            $language_list_table->display();
            ?>
        </div><!-- col-wrap -->

        <form id="language-addnew" method="get" action="admin.php?page=falang_language" style="display: inline;">
            <!-- For plugins, we also need to ensure that the form posts back to our current page -->
            <input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ) ?>" />
            <input type="hidden" name="action" value="add_new_language" />

            <input type="submit" value="<?php echo __('Add New Language','falang') ?>" name="submit" class="button">
        </form>

</div><!-- col-container -->