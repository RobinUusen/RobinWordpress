<?php
/**
 * WPML Compatibility class
 * Defines some WPML constants
 * Registers strings in a persistent way as done by WPML
 * from polylang
 *
 * @since 1.3.2
 */

class FALANG_WPML_Config {
	protected static $instance; // For singleton
	protected $xmls, $options;

	/**
	 * Constructor
	 *
	 * @since 1.3.1
	 */
	public function __construct() {
		if ( extension_loaded( 'simplexml' ) ) {
			$this->init();
		}
	}

	/**
	 * Access to the single instance of the class
	 *
	 * @since 1.3.2
	 *
	 * @return object
	 */
	public static function instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Finds the wpml-config.xml files to parse and setup filters
	 *
	 * @since 1.3.1
	 */
	public function init() {
		$this->xmls = array();

		// Plugins
		// Don't forget sitewide active plugins thanks to Reactorshop http://wordpress.org/support/topic/polylang-and-yoast-seo-plugin/page/2?replies=38#post-4801829
		$plugins = ( is_multisite() && $sitewide_plugins = get_site_option( 'active_sitewide_plugins' ) ) && is_array( $sitewide_plugins ) ? array_keys( $sitewide_plugins ) : array();
		$plugins = array_merge( $plugins, get_option( 'active_plugins', array() ) );

		foreach ( $plugins as $plugin ) {
			if ( file_exists( $file = WP_PLUGIN_DIR . '/' . dirname( $plugin ) . '/wpml-config.xml' ) && false !== $xml = simplexml_load_file( $file ) ) {
				$this->xmls[ dirname( $plugin ) ] = $xml;
			}
		}

		// Theme
		if ( file_exists( $file = ( $template = get_template_directory() ) . '/wpml-config.xml' ) && false !== $xml = simplexml_load_file( $file ) ) {
			$this->xmls[ get_template() ] = $xml;
		}

		// Child theme
		if ( ( $stylesheet = get_stylesheet_directory() ) !== $template && file_exists( $file = $stylesheet . '/wpml-config.xml' ) && false !== $xml = simplexml_load_file( $file ) ) {
			$this->xmls[ get_stylesheet() ] = $xml;
		}

		// Custom
//		if ( file_exists( $file = PLL_LOCAL_DIR . '/wpml-config.xml' ) && false !== $xml = simplexml_load_file( $file ) ) {
//			$this->xmls['Polylang'] = $xml;
//		}

		if ( ! empty( $this->xmls ) ) {
//			add_filter( 'pll_copy_post_metas', array( $this, 'copy_post_metas' ), 20, 2 );
//			add_filter( 'pll_copy_term_metas', array( $this, 'copy_term_metas' ), 20, 2 );
//			add_filter( 'pll_get_post_types', array( $this, 'translate_types' ), 10, 2 );
//			add_filter( 'pll_get_taxonomies', array( $this, 'translate_taxonomies' ), 10, 2 );

			foreach ( $this->xmls as $context => $xml ) {
				foreach ( $xml->xpath( 'admin-texts/key' ) as $key ) {
					$attributes = $key->attributes();
					$name = (string) $attributes['name'];
					if ( Falang() instanceof Falang_Public ) {
						$this->options[ $name ] = $key;
						add_filter( 'option_' . $name, array( $this, 'translate_strings' ) );
					} else {
						$this->register_string_recursive( $context, $name, get_option( $name ), $key );
					}
				}
			}
		}
	}

	/**
	 * Recursively registers strings for a serialized option
	 *
	 * @since 1.3.2
	 *
	 * @param string $context The group in which the strings will be registered.
	 * @param string $option  Option name.
	 * @param array  $values  Option value.
	 * @param object $key     XML node.
	 */
	protected function register_string_recursive( $context, $option, $values, $key ) {
		if ( is_object( $values ) ) {
			$values = (array) $values;
		}

		$children = $key->children();

		if ( is_array( $values ) ) {
			if ( count( $children ) ) {
				foreach ( $children as $child ) {
					$attributes = $child->attributes();
					$name = (string) $attributes['name'];

					if ( isset( $values[ $name ] ) ) {
						$this->register_string_recursive( $context, $name, $values[ $name ], $child );
						continue;
					}

					$pattern = '#^' . str_replace( '*', '(?:.+)', $name ) . '$#';

					foreach ( $values as $n => $value ) {
						// The first case could be handled by the next one, but we avoid calls to preg_match here.
						if ( '*' === $name || ( false !== strpos( $name, '*' ) && preg_match( $pattern, $n ) ) ) {
							$this->register_string_recursive( $context, $n, $value, $child );
						}
					}
				}
			} else {
				foreach ( $values as $n => $value ) {
					// Parent key is a wildcard and no sub-key has been whitelisted.
					$this->register_string_recursive( $context, $n, $value, $key );
				}
			}
		} else {
			falang_register_string( $option, $values, $context, true );  // Multiline as in WPML.
		}
	}

	/**
	 * Translates the strings for an option
	 *
	 * @since 1.3.2
	 *
	 * @param array|string $value Either a string to translate or a list of strings to translate
	 * @return array|string translated string(s)
	 */
	public function translate_strings( $value ) {
		$option = substr( current_filter(), 7 );
		return $this->translate_strings_recursive( $value, $this->options[ $option ] );
	}

	/**
	 * Recursively translates strings for a serialized option
	 *
	 * @since 1.3.2
	 *
	 * @param array|string $values Either a string to translate or a list of strings to translate.
	 * @param object       $key     XML node.
	 * @return array|string Translated string(s)
	 */
	protected function translate_strings_recursive( $values, $key ) {
		$children = $key->children();

		if ( is_array( $values ) || is_object( $values ) ) {
			if ( count( $children ) ) {
				foreach ( $children as $child ) {
					$attributes = $child->attributes();
					$name = (string) $attributes['name'];

					if ( is_array( $values ) && isset( $values[ $name ] ) ) {
						$values[ $name ] = $this->translate_strings_recursive( $values[ $name ], $child );
						continue;
					}

					if ( is_object( $values ) && isset( $values->$name ) ) {
						$values->$name = $this->translate_strings_recursive( $values->$name, $child );
						continue;
					}

					$pattern = '#^' . str_replace( '*', '(?:.+)', $name ) . '$#';

					foreach ( $values as $n => &$value ) {
						// The first case could be handled by the next one, but we avoid calls to preg_match here.
						if ( '*' === $name || ( false !== strpos( $name, '*' ) && preg_match( $pattern, $n ) ) ) {
							$value = $this->translate_strings_recursive( $value, $child );
						}
					}
				}
			} else {
				// Parent key is a wildcard and no sub-key has been whitelisted.
				foreach ( $values as &$value ) {
					$value = $this->translate_strings_recursive( $value, $key );
				}
			}
		} else {
			$values = falang__( $values );
		}

		return $values;
	}
}