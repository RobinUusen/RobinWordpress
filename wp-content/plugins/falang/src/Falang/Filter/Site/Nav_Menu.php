<?php
namespace Falang\Filter\Site;

use Falang\Core\Language_Switcher;

class Nav_Menu  {

	/**
	 * Constructor
	 *
	 * @since 1.3.1
	 *
	 */
	public function __construct( ) {

		// Split the language switcher menu item in several language menu items
		add_filter( 'wp_get_nav_menu_items', array( $this, 'wp_get_nav_menu_items' ), 20 ); // after the customizer menus

	}


	/**
	 * Sort menu items by menu order
	 *
	 * @since 1.3.1
	 *
	 * @param object $a The first object to compare
	 * @param object $b The second object to compare
	 * @return int -1 or 1 if $a is considered to be respectively less than or greater than $b.
	 */
	protected function usort_menu_items( $a, $b ) {
		return ( $a->menu_order < $b->menu_order ) ? -1 : 1;
	}


	/**
	 * Format a language switcher menu item title based on options
	 *
	 * @since 1.2.3
	 *
	 * @param string $flag    Formatted flag
	 * @param string $name    Language name
	 * @param array  $options Language switcher options
	 * @return string Formatted menu item title
	 */
	protected function get_item_title( $flag, $name, $options ) {
		if ( $options['display_flags'] ) {
			$file = FALANG_DIR.'/flags/' . $flag . '.png';
			if ( ! empty($flag ) && file_exists( $file) ) {
				$flag_url = plugins_url( 'flags/'.$flag . '.png', FALANG_FILE );
				$flag_img = '<img src="'.$flag_url.'" alt="'.$name.'"/>';
			}
			if ( $options['display_names'] ) {
				$title = sprintf( '%1$s<span style="margin-%2$s:0.3em;">%3$s</span>', $flag_img, is_rtl() ? 'right' : 'left', esc_html( $name ) );
			} else {
				$title = $flag_img;
			}
		} else {
			$title = esc_html( $name );
		}
		return $title;
	}

	/**
	 * Splits the one item of backend in several items on frontend
	 * take care to menu_order as it is used later in wp_nav_menu
	 *
	 * @since 1.3.1
	 *
	 * @param array $items menu items
	 * @return array modified items
	 */
	public function wp_get_nav_menu_items( $items ) {
		if ( doing_action( 'customize_register' ) ) { // needed since WP 4.3, doing_action available since WP 3.9
			return $items;
		}

		// The customizer menus does not sort the items and we need them to be sorted before splitting the language switcher
		usort( $items, array( $this, 'usort_menu_items' ) );

		$new_items = array();
		$offset = 0;

		foreach ( $items as $item ) {
			if ( $options = get_post_meta( $item->ID, '_falang_menu_item', true ) ) {
				$i = 0;

				/** This filter is documented in include/switcher.php */
				$options = apply_filters( 'falang_the_languages_args', $options ); // Honor the filter here for 'display_flags', 'display_names' and 'dropdown'.

				$switcher = new Language_Switcher();
				$args = array_merge( array( 'raw' => 1 ), $options );
				$the_languages = $switcher->get_links($options['hide_current']);

				// parent item for dropdown
				if ( ! empty( $options['dropdown'] ) ) {
					$current_language = Falang()->get_current_language();
					$name = $current_language->name;
					$item->title = $this->get_item_title( $current_language->flag_code, $name, $options );
					$item->attr_title = '';
					$item->classes = array( 'falang-parent-menu-item' );
					$new_items[] = $item;
					$offset++;
				}

				foreach ( $the_languages as $lang ) {
					$lang_item = clone $item;
					$lang_item->ID = $lang_item->ID . '-' . $lang['slug']; // A unique ID
					$lang_item->title = $this->get_item_title( $lang['flag'], $lang['title'], $options );
					$lang_item->attr_title = '';
					$lang_item->url = $lang['href'];
					$lang_item->lang = $lang['locale']; // Save this for use in nav_menu_link_attributes
					$lang_item->classes = 'falang-menu-item falang-'.$lang['slug'];
					$lang_item->menu_order += $offset + $i++;
					if ( ! empty( $options['dropdown'] ) ) {
						$lang_item->menu_item_parent = $item->db_id;
						$lang_item->db_id = 0; // to avoid recursion
					}
					$new_items[] = $lang_item;
				}
				$offset += $i - 1;
			} else {
				$item->menu_order += $offset;
				$new_items[] = $item;
			}
		}
		return $new_items;
	}

}