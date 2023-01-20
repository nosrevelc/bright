<?php

/**
 * Handles Experts Page Data
 * 
 *
 *
 * @author MD3 <https://www.md3.pt>
 */


// make sure this file is called by wp
defined('ABSPATH') or die();


class IDB_Experts
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
		add_action('pre_get_posts', array($this, 'filter_by_category'));
		add_action('pre_get_posts', array($this, 'filter_by_location'));
		add_action('pre_get_posts', array($this, 'order_by'));
		add_action('pre_get_posts', array($this, 'filter_by_name'));
		
		add_action('wp_ajax_listings_ajax_handlere', array($this, 'listings_ajax_handlere')); // wp_ajax_{action}
		add_action('wp_ajax_nopriv_listings_ajax_handlere', array($this, 'listings_ajax_handlere')); // wp_ajax_nopriv_{action}
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
			'location'
		));
	}

	/**
	 * Order by.
	 *
	 * @since 1.0.0
	 * @param \WP_Query $query The \WP_Query object.
	 */
	public function order_by($query)
	{

		if (isset($query->query_vars['exclude_main_order']) && $query->query_vars['exclude_main_order']) {
			return;
		}

		if (!$this->is_listing_query($query)) {
			return;
		}

		// Default orderby.
		if (empty($_REQUEST['orderby'])) {
			$query->set('orderby', 'date');
			$query->set('order', 'DESC');
			return;
		}

		$query_var = sanitize_text_field($_REQUEST['orderby']);
		$query_var = explode('-', $query_var);

		$orderby = $query_var[0];
		$orderby = strtolower($orderby);

		$order = !empty($query_var[1]) ? esc_attr($query_var[1]) : 'desc';
		$order = strtoupper($order);



		if ($order !== 'DESC') { // Force order to be ASC or DESC.
			$order = 'ASC';
		}

		if (isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'expert') {

			switch ($orderby) {
				case 'name':
					$orderby = 'title';
					//$query->set('meta_key', 'price_manual');
					break;
			}
		}

		$query->set('orderby', esc_attr($orderby));
		$query->set('order', esc_attr($order));
	}

	public function filter_by_seach($query)
	{

		if (!$this->is_listing_query($query)) {
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

		if (!$this->is_listing_query($query)) {
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

		if (!$this->is_listing_query($query)) {
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


	public function filter_by_date($query)
	{
		if (isset($query->query_vars['count']) && $query->query_vars['count']) {
			return;
		}

		if (!$this->is_listing_query($query)) {
			return;
		}

		$date = sanitize_text_field($_REQUEST['age']);

		// Default is all days.
		if (empty($date)) {
			$date = 'all';
		}

		if ($date === 'all') {
			return;
		}

		if ($date !== 'today') {
			$date = sprintf('- %d days', intval($date));
		}

		$query->set(
			'date_query',
			array(
				'column'    => 'post_date',
				'after'     => esc_attr($date),
				'inclusive' => true,
			)
		);
	}

	public function filter_by_price($query)
	{
		if (isset($query->query_vars['exclude_main_price']) && $query->query_vars['exclude_main_price']) {
			return;
		}

		if (!$this->is_listing_query($query)) {
			return;
		}

		$meta_query = array();

		$price_min = $_REQUEST['price_min'];
		if (!empty($price_min)) {
			$meta_query[] = array(
				'key'     => 'price_manual',
				'value'   => esc_attr($price_min),
				'compare' => '>=',
				'type'    => 'NUMERIC',
			);
		}

		$price_max = $_REQUEST['price_max'];
		if (!empty($price_max)) {
			$meta_query[] = array(
				'key'     => 'price_manual',
				'value'   => esc_attr($price_max),
				'compare' => '<=',
				'type'    => 'NUMERIC',
			);
		}

		if (empty($meta_query)) {
			return;
		}

		$this->set_meta_query($query, $meta_query);
	}

	public function filter_by_franchise($query)
	{
		if (!$this->is_listing_query($query)) {
			return;
		}

		$is_franchise = $_REQUEST['is_franchise'];
		if (empty($is_franchise)) {
			return;
		}

		$this->set_meta_query(
			$query,
			array(
				'is_franchise' => array(
					'key'   => 'is_franchise',
					'value' => esc_attr($is_franchise),
				),
			)
		);
	}

	public function filter_by_property_type($query)
	{
		if (!$this->is_listing_query($query)) {
			return;
		}

		$property_type = explode(',', $_REQUEST['property_type']);

		if (empty($property_type)) {
			return;
		}

		$meta_query = array();

		foreach ($property_type as $type) {

			if (!in_array($type, array('real', 'lease', 'noproperty'), true)) {
				continue;
			}

			$meta_query[$type] = array(
				'key'   => 'property_type',
				'value' => esc_attr($type),
			);
		}

		if (empty($meta_query)) {
			return;
		}

		$meta_query['relation'] = 'OR';

		$this->set_meta_query(
			$query,
			array($meta_query)
		);
	}

	public function filter_by_certify($query)
	{
		if (!$this->is_listing_query($query)) {
			return;
		}

		$meta_query = array();

		$certified_listing = $_REQUEST['certified_listing'];
		if (!empty($certified_listing)) {
			$meta_query['certification'] = array(
				'key'     => 'listing_certification_status',
				'value'   => 'certification_finished',
				'compare' => '==',
			);
		}

		if (empty($meta_query)) {
			return;
		}

		$this->set_meta_query($query, $meta_query);
	}

	private function set_meta_query($query, $new_meta_query)
	{
		$meta_query = $query->get('meta_query');
		if (empty($meta_query)) {
			$meta_query = array();
		}

		$query->set('meta_query', array_merge($meta_query, $new_meta_query));
	}

	/************************************* Count Listings
	 * @param string date as string.
	 * @return int number of posts.
	 */
	public function count_listings($date)
	{
		$args = array(
			'post_type' => 'listing',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'count' => true,
			'exclude_main_price' => true,
			'date_query' => array(
				'column'    => 'post_date',
				'after'     => esc_attr($date),
				'inclusive' => true,
			)
		);

		$listings = new WP_Query($args);
		return $listings->post_count;
	}

	private function is_listing_query($query)
	{
		return isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'listing';
	}

	public function listings_ajax_handlere()
	{
		// prepare our arguments for the query
		//$args = json_decode( stripslashes( $_POST['query'] ), true );
		//$args['paged'] = $_POST['page'] + 1; // we need next page to be loaded
		$paged = isset($_POST['page']) ? $_POST['page'] : 1;
		$posts_per_page = $this->posts_per_page;
		$args['paged'] = $paged;
		$args['post_type'] = 'listing';
		$args['posts_per_page'] = $posts_per_page;

		$post_status = 'publish';
		if(isset($_POST['page_slug'])){
			$template = get_page_template_slug(get_page_by_path($_POST['page_slug'])->ID);
			if(isPremiumBuyer() && $template === 'premium-buyer.php'){
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
				get_template_part('/elements/listings', get_post_format());
			// for the test purposes comment the line above and uncomment the below one
			// the_title();


			endwhile;
		else :
			get_template_part('/elements/no_results');
		endif;
		?>
			<div class="d-none ajax-listing-data">
				<?php
						get_template_part('/elements/data/min_max_price');
						get_template_part('/elements/data/age');
						get_template_part('/elements/data/pagination');
						get_template_part('/elements/data/results_count');
						?>
				<div class="d-none results-count-data"><?php Component_Listings::results_count($paged, $posts_per_page, $wp_query->found_posts); ?></div>
			</div>
	<?php

			die; // here we exit the script and even no wp_reset_query() required!
		}
	}
