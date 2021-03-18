<?php
/**
 * The translator external functionality of the plugin.
 *
 * @link       www.faboba.com
 * @since      1.3
 *
 * @package    Falang
 */

namespace Falang\Factory;

use Falang\Model\Falang_Model;

class TranslatorFactory
{
	private static $translator;

	static public function getTranslator($target_language_locale)
	{
		if (translatorFactory::$translator != null)
		{
			return translatorFactory::$translator;
		}

		$falang_model = new Falang_Model();
		$translator_name = $falang_model->get_option('service_name');

        $service_class_name    = 'Falang\Translator\Translator' . ucfirst($translator_name);
        $translator_ref = new \ReflectionClass($service_class_name);
        $translator = $translator_ref->newInstance();

		$from = $translator->languageCodeToISO($falang_model->get_default_language()->locale);
		$to   = $translator->languageCodeToISO($target_language_locale);

		$translator->installScripts($from,$to);

		TranslatorFactory::$translator = $translator;

		return $translator;
	}
}