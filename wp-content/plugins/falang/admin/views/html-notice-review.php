<?php
/**
 * Admin View: Notice - Review
 *
 * @package Falang\Admin\Notice
 */

defined( 'ABSPATH' ) || exit;

?>

<div id="message" class="falang-notice falang-review-notice notice notice-info">
    <a class="falang-message-close notice-dismiss" href="<?php echo esc_url( wp_nonce_url( add_query_arg( 'falang-hide-notice', 'review' ),'review', '_falang_notice_nonce') ); ?>">
		<?php esc_html_e( 'Dismiss', 'falang' )?>
    </a>
	<div class="falang-logo">
	</div>
	<div class="falang-message-content">
		<h3 class="falang-message__title"><?php esc_html_e( 'Please help us spread the word', 'falang' ); ?></h3>
		<p class="falang-message__description">
			<?php
			/* translators: %1$s: Plugin Name, %2$s: Rating link */
			printf( esc_html__( 'Enjoying the experience with %1$s? Please take a moment to spread your love by rating us on %2$s', 'falang' ), '<strong>Falang</strong>', '<a href="https://wordpress.org/support/plugin/falang/reviews?rate=5#new-post" target="_blank"><strong>WordPress.org</strong>!</a>' );
			?>
		</p>
		<p class="falang-message__action submit">
			<a href="https://wordpress.org/support/plugin/falang/reviews?rate=5#new-post" class="button button-primary falang-dismiss-review-notice falang-review-received" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Sure, I\'d love to!', 'falang' ); ?></a>
			<a href="#" class="button button-secondary falang-dismiss-review-notice" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Remind me later', 'falang' ); ?></a>
			<a href="#" class="falang-button-link falang-dismiss-review-notice" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'I already did', 'falang' ); ?></a>
		</p>

	</div>

</div>
<script type="text/javascript">
    jQuery( document ).ready( function ( $ ) {
        $( document ).on( 'click', '.falang-dismiss-review-notice, .falang-review-notice button', function ( event ) {
            if ( ! $( this ).hasClass( 'falang-review-received' ) ) {
                event.preventDefault();
            }
            $.post( ajaxurl, {
                action: 'falang_review_dismiss'
            } );
            $( '.falang-review-notice' ).remove();
        } );
    } );
</script>
