<?php

/**
 *
 * Displays the strings translations tab in Falang.
 *
 * @link       www.faboba.com
 * @since      1.0.0
 *
 * @package    Falang
 * @subpackage Falang/admin/views
 */
//use for popup translation
add_thickbox();

?>
<div id="col-container">
	<div class="col-wrap">
		<?php $strings_list_table->views(); ?>
		<!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
		<form id="listings-filter" method="post">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
			<?php $strings_list_table->search_box( __('Search','falang'), 's' );?>

			<!-- Displays the post list in a table -->
			<?php $strings_list_table->display();?>
		</form>
	</div><!-- col-wrap -->