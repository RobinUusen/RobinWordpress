<?php
/**
 * The translator external functionality of the plugin.
 *
 * @link       www.faboba.com
 * @since      1.3.5
 *
 * @package    Falang
 */
namespace Falang\Translator;

use Falang\Model\Falang_Model;

class TranslatorLingvanex extends TranslatorDefault {

    function __construct()
    {
        $falang_model = new Falang_Model();
        $this->token= $falang_model->get_option('lingvanex_key');

        $this->script = 'translatorLingvanex.js';
    }

    public function installScripts ($from,$to)
    {
        parent::installScripts($from,$to);

        $inline_script = "var translator = {'from' : '".strtolower($from). "','to' : '".strtolower($to). "'};\n";
        $inline_script .= "var LingvanexKey = '".$this->token."';\n";
        wp_add_inline_script('translatorService',$inline_script,'before');
    }

    //return the language code in specific format aa_AA
    //The language code is represented only in lowercase letters, the country code only in uppercase letters
    //example en_GB, es_ES, ru_RU
    public function languageCodeToISO ($language){
        $lang_code = substr($language,0,strpos($language, '_'));
        $country_code = strtoupper(substr($language,strpos($language,'_')+1));
        return $lang_code.'_'.$country_code;
    }

}