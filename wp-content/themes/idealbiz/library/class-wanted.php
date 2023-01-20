<?php

/**
 * Handles Wanted Businesses Page Data
 * 
 *
 *
 * @author MD3 <https://www.md3.pt>
 */


// make sure this file is called by wp
defined('ABSPATH') or die();


class IDB_Wanted
{
	public $posts_per_page = 9;

	/**
	 * Setup hooks.
	 *
	 * @since 1.0.0
	 */
	public function ready()
	{
		add_filter('query_vars',    array($this, 'query_vars'));
		//add_action('pre_get_posts', array($this, 'not_expired'));
		add_action('pre_get_posts', array($this, 'filter_by_search'));
		add_action('pre_get_posts', array($this, 'filter_by_category'));
		add_action('pre_get_posts', array($this, 'filter_by_location'));
		add_action('wp_ajax_wanted_ajax_handler', array($this, 'wanted_ajax_handler')); // wp_ajax_{action}
		add_action('wp_ajax_nopriv_wanted_ajax_handler', array($this, 'wanted_ajax_handler')); // wp_ajax_nopriv_{action}
	}

	/**
	 * Filters the query variables whitelist before processing.
	 *
	 * @since  1.0.0
	 * @param  array $query_vars The array of whitelisted query variables.
	 * @return array
	 */
	public function query_vars($query_vars)
	{
		return array_merge($query_vars, array(
			'listing_cat',
			'location',
			'lang',
		));
	}

	/*

	public function not_expired($query)
	{

		if (!$this->is_wanted_query($query)) {
			return;
		}

		$meta_query = array(
			'relation' => 'OR',
			array(
				'key' => 'expire_date',
				'value' => date('Ymd'),
				'type' => 'DATE',
				'compare' => '>='
			),
			array(
				'key' => 'expire_date',
				'compare' => 'NOT EXISTS'
			)
		);

		$this->set_meta_query($query, array($meta_query));
	}

	*/

	public function filter_by_search($query)
	{

		if (!$this->is_wanted_query($query)) {
			return;
		}

		$search_query = $_REQUEST['search'];

		if (empty($search_query)) {
			return;
		}

		$query->set('s', $search_query);
	}


	public function filter_by_category($query)
	{

		if (!$this->is_wanted_query($query)) {
			return;
		}

		$cat         = $_REQUEST['listing_cat'];
		$tax         = 'listing_cat';

		if (isset($cat) && '' !== $cat) {

			$term = get_term($cat, $tax);

			if (isset($term->slug)) {
				$query->set($tax, $term->slug);
			}
		}
	}

	function filter_by_location($query)
	{

		if (!$this->is_wanted_query($query)) {
			return;
		}

		$cat         = $_REQUEST['location'];
		$tax         = 'location';

		if (isset($cat) && '' !== $cat) {

			$term = get_term($cat, $tax);

			if (isset($term->slug)) {
				$query->set($tax, $term->slug);
			}
		}
	}

	/*

	private function set_meta_query($query, $new_meta_query)
	{
		$meta_query = $query->get('meta_query');
		if (empty($meta_query)) {
			$meta_query = array();
		}

		$query->set('meta_query', array_merge($meta_query, $new_meta_query));
	}

	*/

	private function is_wanted_query($query)
	{
		if (is_admin() && !wp_doing_ajax()) { 
			return false;
		}
		
		return isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'wanted';
	}

	public function wanted_ajax_handler()
	{
		// prepare our arguments for the query
		//$args = json_decode( stripslashes( $_POST['query'] ), true );
		//$args['paged'] = $_POST['page'] + 1; // we need next page to be loaded
		$paged = isset($_POST['page']) ? $_POST['page'] : 1;
		$posts_per_page = $this->posts_per_page;

		$args['paged'] = $paged;
		$args['post_type'] = 'wanted';
		$args['posts_per_page'] = $posts_per_page;
		//$args['meta_query'] = $meta_query;

		$post_status = 'publish';
		if (isset($_POST['page_slug'])) {
			$template = get_page_template_slug(get_page_by_path($_POST['page_slug'])->ID);
			if (isPremiumBuyer() && $template === 'premium-buyer.php') {
				$post_status = array('publish', 'pending', 'draft');
				$args['date_query'] = array(
					array(
						'after' => '-30 days',
						'column' => 'post_date',
					)
				);
			}
		}
		$args['post_status'] = $post_status;

		global $wp_query;

		// it is always better to use WP_Query but not here
		query_posts($args);

		if (have_posts()) :

			// run the loop
			while (have_posts()) : the_post();

				// look into your theme code how the posts are inserted, but you can use your own HTML of course
				// do you remember? - my example is adapted for Twenty Seventeen theme
				get_template_part('/elements/wanted', get_post_format());
			// for the test purposes comment the line above and uncomment the below one
			// the_title();


			endwhile;
		else :
			get_template_part('/elements/no_results');
		endif;
		?>
			<div class="d-none ajax-listing-data">
				<?php
						get_template_part('/elements/data/pagination');
						get_template_part('/elements/data/results_count');
						?>
				<div class="d-none results-count-data"><?php Component_Listings::results_count($paged, $posts_per_page, $wp_query->found_posts); ?></div>
			</div>
	<?php

			die; // here we exit the script and even no wp_reset_query() required!
		}
	}
