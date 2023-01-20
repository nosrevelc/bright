<?php

/**
 * Prints Listings HTML Data
 * 
 * 
 *
 * @author MD3 <https://www.md3.pt>
 */


// make sure this file is called by wp
defined('ABSPATH') or die();


class Component_Listings
{
    public function pagination($total_pages, $is_ajax = false)
    {
        if ($total_pages > 1) {

            if ($is_ajax)
                $current_page = isset($_POST['page']) ? $_POST['page'] : 1;
            else
                $current_page = max(1, get_query_var('paged'));

            $pagination_data = paginate_links(array(
                'current' => $current_page,
                'total' => $total_pages,
                'prev_text'    => '',
                'next_text'    => '',
                'mid_size' => 1,
                'end_size' => 2,
            ));

            if(isset($_POST['page_slug'])) {
                $pageList = $_POST['page_slug'];
            }
            else {
                $pageList = 'listings';
            }

            /*
            $pageList = 'listings';
            $template = get_page_template_slug(get_page_by_path($_POST['page_slug'])->ID);
            if($template === 'premium-buyer.php'){
				$pageList = $_POST['page_slug'];
            }
            */

            if ($is_ajax)
                $pagination_data = str_replace('wp-admin/admin-ajax.php', $pageList, $pagination_data);

            echo $pagination_data;
        }
    }


    public function results_count($page, $post_per_page, $total)
    {

        if (0 === $page) {
            $page = 1;
            $init = 1;
        } else {
            $init = ($page - 1) * $post_per_page + 1;
        }
        $end = $page * $post_per_page;
        if ($end > $total) {
            $end = $total;
        }

        $showing_count = sprintf(
            __('%1$d-%2$d of %3$d', 'idealbiz'),
            $init,
            $end,
            $total
        );

        echo $showing_count;
    }
}
