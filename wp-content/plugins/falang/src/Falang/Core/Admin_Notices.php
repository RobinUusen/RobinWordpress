<?php
/**
 * The file that defines the Admin Notices
 *
 * @link       www.faboba.com
 * @since      1.
 *
 * @package    Falang
 */
namespace Falang\Core;


class Admin_Notices {

	/**
	 * Stores notices.
	 *
	 * @var array
	 */
	private static $notices = array();

	/**
	 * Constructor.
	 */
	public function __construct() {

		add_action( 'admin_init', array( $this, 'hide_notice' ) );
		add_action( 'admin_notices', array( $this, 'display_notices' ) );

	}

	/**
	 * Add a custom notice
	 *
	 * @since 1.2
	 *
	 * @param string $name Notice name
	 * @param string $html Content of the notice
	 */
	public static function add_notice( $name, $html ) {
		self::$notices[ $name ] = $html;
	}

	/**
	 * Get custom notices
	 *
	 * @since 1.2
	 *
	 * @return array
	 */
	public static function get_notices() {
		return self::$notices;
	}

	/**
	 * Stores a dismissed notice in database
	 *
	 * @since 1.2
	 *
	 * @param string $notice
	 */
	public static function dismiss( $notice ) {
		$dismissed = get_option( 'falang_dismissed_notices', array() );

		if ( ! in_array( $notice, $dismissed ) ) {
			$dismissed[] = $notice;
			update_option( 'falang_dismissed_notices', array_unique( $dismissed ) );
		}
	}

	/**
	 * Has a notice been dismissed?
	 *
	 * @since 1.2
	 *
	 * @param string $notice Notice name
	 * @return bool
	 */
	public static function is_dismissed( $notice ) {
		$dismissed = get_option( 'falang_dismissed_notices', array() );

		// Handle legacy user meta
		$dismissed_meta = get_user_meta( get_current_user_id(), 'falang_dismissed_notices', true );
		if ( is_array( $dismissed_meta ) ) {
			if ( array_diff( $dismissed_meta, $dismissed ) ) {
				$dismissed = array_merge( $dismissed, $dismissed_meta );
				update_option( 'falang_dismissed_notices', $dismissed );
			}
			if ( ! is_multisite() ) {
				// Don't delete on multisite to avoid the notices to appear in other sites.
				delete_user_meta( get_current_user_id(), 'falang_dismissed_notices' );
			}
		}

		return in_array( $notice, $dismissed );
	}

	/**
	 * Handle a click on the dismiss button
	 *
	 * @since 1.2
	 */
	public function hide_notice() {
		if ( isset( $_GET['falang-hide-notice'], $_GET['_falang_notice_nonce'] ) ) {
			$notice = sanitize_key( $_GET['falang-hide-notice'] );
			check_admin_referer( $notice, '_falang_notice_nonce' );
			self::dismiss( $notice );
			wp_safe_redirect( remove_query_arg( array( 'falang-hide-notice', '_falang_notice_nonce' ), wp_get_referer() ) );
			exit;
		}
	}

	/**
	 * Displays notices
	 *
	 * @since 1.2
	 */
	public function display_notices() {
		if ( current_user_can( 'manage_options' ) ) {

			//Core notices

			//fist activation
			if ($this->can_display_notice( 'first-activation' ) && ! $this->is_dismissed( 'first-activation' )  ) {
				$this->show('first-activation');
				return;
			}

			//review
//			if ($this->can_display_notice( 'review' ) && ! $this->is_dismissed( 'review' )  ) {
//				$this->show('review');
//				return;
//			}
//
//			// Custom notices
//			foreach ( $this->get_notices() as $notice => $html ) {
//				if ( $this->can_display_notice( $notice ) && ! $this->is_dismissed( $notice ) ) {
//					$this->show($notice);
//				}
//			}

		}
	}

	/**
	 * Should we display notices on this screen?
	 *
	 * @since 1.2
	 *
	 * @param  string $notice The notice name.
	 * @return bool
	 */
	protected function can_display_notice( $notice ) {
		$screen          = get_current_screen();
		$screen_id       = $screen ? $screen->id : '';
		$show_on_screens = array(
			'dashboard',
			'plugins',
		);

		/**
		 * Filter admin notices which can be displayed
		 * Notices should only show on Falang screens, the main dashboard, and on the plugins screen.
		 *
		 * @since 1.2
		 *
		 * @param bool   $display Whether the notice should be displayed or not.
		 * @param string $notice  The notice name.
		 */
		return apply_filters(
			'falang_can_display_notice',
			in_array(
				$screen->id,
				array(
					'dashboard',
					'plugins',
					'toplevel_page_falang-translation',
					'falang_page_falang-terms',
					'falang_page_falang-menus',
					'falang_page_falang-strings',
					'falang_page_falang-options',
					'falang_page_falang-language',
					'falang_page_falang-settings',
					'falang_page_falang-help'
				)
			),
			$notice
		);
	}


	/**
	 * Show a notice
	 *
	 * @since 1.2
	 */
	private function show($notice) {
		// Ask for some love.
		if ( ( is_super_admin() || current_user_can( 'manage_options' ) ) ) {
			if (file_exists(FALANG_ADMIN.'/views/html-notice-'.$notice.'.php')){
				include FALANG_ADMIN.'/views/html-notice-'.$notice.'.php';
			}
		}

	}

}