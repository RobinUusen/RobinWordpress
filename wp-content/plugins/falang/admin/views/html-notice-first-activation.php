<?php
/**
 * Admin View: Notice - First Activation
 *
 * @package Falang\Admin\Notice
 */

defined( 'ABSPATH' ) || exit;

?>

<div id="message" class="falang-notice falang-fa-notice notice notice-info">
    <a class="falang-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'falang-hide-notice', 'first-activation' ),'first-activation', '_falang_notice_nonce') ); ?>">
        <?php esc_html_e( 'Dismiss', 'falang' )?>
    </a>
	<div class="falang-logo">
	</div>
	<div class="falang-message-content">
		<h3 class="falang-message__title"><?php esc_html_e( 'Thanks for using Falang', 'falang' ); ?></h3>
		<p class="falang-message__description">
			<?php
			/* translators: %1$s: Plugin Name, %2$s: Rating link */
			printf( esc_html__( 'Enjoying the experience with %1$s? Please take a moment to spread your love by rating us on %2$s', 'falang' ), '<strong>Falang</strong>', '<a href="https://wordpress.org/support/plugin/falang/reviews?rate=5#new-post" target="_blank"><strong>WordPress.org</strong>!</a>' );
			?>
		</p>
        <p class="falang-message__action submit">
            <a  href="https://www.faboba.com/falangw/documentation/" class="btn button button-secondary" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Documentation', 'falang' ); ?></a>
            <a  href="https://www.faboba.com/falangw/contact/" class="btn button button-secondary" target="_blank" rel="noopener noreferrer"><span class="dashicons dashicons-edit"></span><span><?php esc_html_e( 'Do you have question?', 'falang' ); ?></span></a>
        </p>
	</div>

</div>
