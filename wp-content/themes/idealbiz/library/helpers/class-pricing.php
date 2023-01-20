<?php

/**
 * Pricing helper
 * 
 * 
 *
 * @author MD3 <https://www.md3.pt>
 */


class IDB_Pricing
{
    public function get_listings_max_price()
    {
        $max_price_args = array(
            'posts_per_page' => 1,
            'post_type' => 'listing',
            'post_status' => 'publish',
            'meta_key' => 'price_manual',
            'orderby' => 'meta_value_num',
            'exclude_main_order' => true,
            'exclude_main_price' => true,
            'order'    => 'DESC'
        );

        $max_price = get_field('price_manual', get_posts($max_price_args)[0]->ID);

        return $max_price;
    }

    public function get_listings_min_price()
    {
        $min_price_args = array(
            'posts_per_page' => 1,
            'post_type' => 'listing',
            'post_status' => 'publish',
            'meta_key' => 'price_manual',
            'orderby' => 'meta_value_num',
            'exclude_main_order' => true,
            'exclude_main_price' => true,
            'order'    => 'ASC'
        );
        $min_price = get_field('price_manual', get_posts($min_price_args)[0]->ID);
        return $min_price;
    }



    /**
     * Format a value into a currency, e.g. 168.000 €
     *
     * @param float $price
     * @return float
     */
    public function pricing_format($price, $broadcasted_site_id = false)
    {

        if (empty($price) || '' === $price) {
            return $price;
        }

        if ($broadcasted_site_id) {
            switch_to_blog($broadcasted_site_id);
            $currency_symbol = \get_woocommerce_currency_symbol();
            restore_current_blog();
        }
        else {
            $currency_symbol = \get_woocommerce_currency_symbol();
        }

        return number_format($price, 0, ',', '.') . $currency_symbol;
    }
}
