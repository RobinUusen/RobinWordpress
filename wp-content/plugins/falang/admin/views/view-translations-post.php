<?php
/**
* Displays the translations fields for posts
*/

if ( ! defined( 'ABSPATH' ) ) {exit;} // Don't access directly
?>

<p><strong><?php esc_html_e( 'Translations', 'falang' ); ?></strong></p>
<table>
    <?php
    $falang_model = new \Falang\Model\Falang_Model();
    $admin_links = new \Falang\Core\Admin_Links();
    //$locale = get_post_meta( $post_id, '_locale', true );


	foreach ( $falang_model->get_languages_list(array('hide_default' => true)) as $language ) {
	    //if ( isset( $locale ) && ( $locale != $language->locale ) ) {
		    $link = $admin_links->display_post_translation_link( $post_id, $language );
		    echo '<span>'.$language->get_flag().'</span> '. $link;
	    //}
	    echo '<br/>';
    }
    ?>

</table>
