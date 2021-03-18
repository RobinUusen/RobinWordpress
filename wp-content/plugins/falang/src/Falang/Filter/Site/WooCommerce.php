<?php

namespace Falang\Filter\Site;


class WooCommerce {

	/**
	 * Constructor
	 *
	 * @since 1.3.1
	 *
	 */
	public function __construct( ) {
		//woocommerce attribute label support
		add_filter( 'woocommerce_attribute_label', array( $this,'translate_wc_attribute_label'), 10, 3 );
		//woocommerce the_excerpt short_description translation => wc don't use get_the_excerpt
		add_filter('woocommerce_short_description', array($this, 'translate_wc_post_excerpt'), 9);
		//translate product variation description
		add_filter( 'woocommerce_available_variation', array($this,'translate_variation_descriptions'),10,3) ;
	}

	/** Display the translated attribute label
	 *
	 * @since 1.2.1
	 */
	public function translate_wc_attribute_label( $label, $name, $product ) {
		//TODO put this in the constructor
		$falang_wc_options = get_option('falang_wc_attributes');

		//old version send name without pa_ key stored without pa_ in falang_wc_attributes
		if (strpos($name, 'pa_') === 0){
			$name = str_replace('pa_','',$name);
		}

		$key = $name.'_label_'.Falang()->get_current_language()->locale;
		if (isset( $falang_wc_options[$key])){
			$translated_label = $falang_wc_options[$key];
		}

		//return orginal or translated
		if (!empty($translated_label)){
			return $translated_label;
		} else {
			return $label;
		}

	}

	/**
	 * Display the wc short_description post_exc
	 *
	 * @since 1.2.2
	 */
	public function translate_wc_post_excerpt( $post_excerpt) {
		global $post;
		if (is_product()){//wc function
			$falang_post = new \Falang\Core\Post($post->ID);
			//need to check empty in other can be displayed several times
			if (!empty($post_excerpt) && !Falang()->is_default() && $falang_post->is_post_type_translatable($post->post_type)){
				$post_excerpt = $falang_post->translate_post_field($post, 'post_excerpt', Falang()->get_current_language(), $post_excerpt);
			}
		}
		return $post_excerpt;
	}

	/**
	 * Display the wc variation description
	 *
	 * @since 1.3.2
	 */
	public function translate_variation_descriptions( $data, $product, $variation ) {
		$falang_post = new \Falang\Core\Post($data['variation_id']);
		if (!Falang()->is_default()) {
			$data['variation_description'] = !empty($variation->get_description())?$variation->get_description():$data['variation_description'];
		}
		return $data;
	}
}