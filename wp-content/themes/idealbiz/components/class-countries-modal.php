<?php
// pais nosrevelc
/**
 * Prints html data for countries modal
 * 
 *
 *
 * @author MD3 <https://www.md3.pt>
 */


// make sure this file is called by wp


defined('ABSPATH') or die();


class Component_Countries_Modal
{

    public function render()
    {

                /* var_dump($config_landing_page['enable']); */
            // Comentar ou Descomentar esta parte para passar a ter landig page com seletor de pais.
 /*            if(get_field('enable_landing_page')== ''){
                    
                    get_template_part('elements/homepage-io/country-modal/modal');
                    
                 header("Location: https://google.com"); 
                }*/
                /*if(get_field('enable_landing_page')== 1){ */
                /* header("Location: https://www.tgsglobal.com"); */
                // Comentar ou Descomentar esta parte para passar o browser a selcionar o pais.
                    
                    $acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
                    
                        $langs = array();
                        foreach( explode(',', $acceptLanguage) as $lang) {
                            $lang = explode(';q=', $lang);
                            $langs[$lang[0]] = count($lang)>1?floatval($lang[1]):1;
                        }
                        arsort($langs);
                    
                        //Alimentar o Array abaixo somente quando houver novo subsite.
                        $ourLanguages = array(
                        'pt-br'=>'br/pt',
                        'pt'=>'pt/pt',
                        'es'=>'es',
                        'ms'=>'my/en',
                        'es-AR'=>'ar/es'
                        
                        );
    
                        //$choice = 'pt/pt'; //Caso nenhuma outra sirva
                        foreach($langs as $lang=>$q) {
                        if(in_array($lang,array_flip($ourLanguages))) {
                            //$choice=$ourLanguages[$lang];
                            break;
                        }
                    }
                    
                    $choice = 'international/en/';
                    $cl_dominio = $_SERVER['HTTP_HOST'];                    
                    header("Location: https://$cl_dominio/$choice/");

                    /* header("Location: https://idealbiz.eu/$choice/"); */
                    
    
                /* }*/
            
            } 

    public function country_select()
    {
        $json = file_get_contents(get_template_directory() . '/data/countries.json');
        $site_countries = IDB_Country_Select::get_multisite_countries();
        $all_countries = json_decode($json);
        set_query_var('sites_countries', $site_countries);
        set_query_var('all_countries', $all_countries);
        get_template_part('elements/homepage-io/country-modal/country_select');
    }

    public function language_select()
    {
        get_template_part('elements/homepage-io/country-modal/language_select');
    }
}


