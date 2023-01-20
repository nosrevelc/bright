<?php

/**
 * Handles Homepage IO Data
 * 
 * 
 *
 * @author MD3 <https://www.md3.pt>
 */


// make sure this file is called by wp
defined('ABSPATH') or die();


class IDB_Country_Select
{
    /**
     * Setup hooks.
     *
     * @since 1.0.0
     */
    public function ready()
    {
        add_action('wp_ajax_site_languages_ajax_handler', array($this, 'site_languages_ajax_handler')); // wp_ajax_{action}
        add_action('wp_ajax_nopriv_site_languages_ajax_handler', array($this, 'site_languages_ajax_handler')); // wp_ajax_nopriv_{action}
    }

    public function get_multisite_countries()
    {
        $args = array(
            'site__not_in' => array(1)
        );

        $deleted = get_sites( 
            array(
                'site__not_in' => array(1), 
                'deleted'=> 1, 
                'fields' => 'ids'
            )
        );

        $sites_countries = array(); 

        foreach (get_sites($args) as $blog) {
            switch_to_blog($blog->blog_id);

            if (in_array($blog->blog_id, $deleted)) {

            }else{
                $sites_countries[] = array(
                    'site_id' => $blog->blog_id,
                    'url' => get_site_url(),
                    'country' => get_option('country_market')
                );
            }

            restore_current_blog();
        }

        return $sites_countries;
    }

    public function site_languages_ajax_handler()
    {
        if(isset($_POST['site_id']) && !empty($_POST['site_id'])) {
            $site_id = $_POST['site_id'];
            $search_text = $_POST['search_text'];
            $languages = $this->get_site_languages($site_id, $search_text);
            wp_send_json($languages);
        }
        else {
            wp_send_json_error(null, 422);
        }
    }

    private function get_site_languages($site_id, $search_text)
    {
        switch_to_blog($site_id);
        $languages_terms = get_terms('term_language', ['hide_empty' => false, 'name__like' => $search_text]);
        restore_current_blog();

        $languages = array_map(function ($language) {
            return array(
                'name' => $language->name,
                'slug' => str_replace('pll_', '', $language->slug)
            );
        }, $languages_terms);

        return $languages;
    }
}
