<?php

/**
 * Handles Listings Page Data
 * 
 *
 *
 * @author MD3 <https://www.md3.pt>
 */


// make sure this file is called by wp
defined('ABSPATH') or die();


class IDB_Listings
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
		add_action('pre_get_posts', array($this, 'order_by'));
		add_action('pre_get_posts', array($this, 'filter_by_date'));
		add_action('pre_get_posts', array($this, 'filter_by_price'));
		add_action('pre_get_posts', array($this, 'filter_by_franchise'));
		add_action('pre_get_posts', array($this, 'filter_by_property_type'));
		add_action('pre_get_posts', array($this, 'filter_by_certify'));
		add_action('pre_get_posts', array($this, 'filter_by_golden'));
		add_action('the_posts', array($this, 'listings_not_expired'), 10, 2 );
		add_action('wp_ajax_listings_ajax_handler', array($this, 'listings_ajax_handler')); // wp_ajax_{action}
		add_action('wp_ajax_nopriv_listings_ajax_handler', array($this, 'listings_ajax_handler')); // wp_ajax_nopriv_{action}
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
			'age',
			'property_type',
			'is_franchise',
			'disclosed_price',
			'price_min',
			'price_max',
			'disclosed_revenue',
			'certified_listing',
			'golden',
			'post__in',
			'revenue_min',
			'revenue_max',
			'disclosed_cash_flow',
			'cash_flow_min',
			'cash_flow_max',
			'location',
			'alphabet',
			'lang',
		));
	}

	public function not_expired($query)
	{

		if (!$this->is_listing_query($query)) {
			return;
		}

		$brokers = getAllBrokers();

		$meta_query = array(
			'relation' => 'OR',
			array(
				'key'     => 'owner',
				'value'   => $brokers,
				'compare' => 'IN',
			),
			array(
				'key' => 'expire_date',
				'value' => date('Ymd'),
				'type' => 'DATE',
				'compare' => '>='
			),
			array(
				'key' => 'expire_date',
				'value' => '',
				'compare' => '='
			)
		);

		$this->set_meta_query($query, array($meta_query));
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
			$order_by = array(
				'post__in' => 'ASC',
				'date' => 'DESC',
			);
			$query->set('orderby', $order_by);
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

		switch ($orderby) {
			case 'price':
				$orderby = 'meta_value_num';
				$query->set('meta_key', 'price_manual');
				break;
		}

		/*
		$ob = array(
			'post__in' => 'ASC',
			esc_attr($orderby) => esc_attr($order),
		);
		$query->set('orderby', $ob);
		*/
		$query->set('orderby', $orderby);
		$query->set('order', esc_attr($order));
	}

	public function filter_by_search($query)
	{

		if (!$this->is_listing_query($query)) {
			return;
		}
		
		if(isset($_REQUEST['search']))
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

		if(isset($_REQUEST['listing_cat']))
			$cat = $_REQUEST['listing_cat'];

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

		if(isset($_REQUEST['location']))
			$cat = $_REQUEST['location'];

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

		if(isset($_REQUEST['age']))
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

		if(isset($_REQUEST['price_min']))
			$price_min = $_REQUEST['price_min'];

		if (!empty($price_min)) {
			$meta_query[] = array(
				'key'     => 'price_manual',
				'value'   => esc_attr($price_min),
				'compare' => '>=',
				'type'    => 'NUMERIC',
			);
		}

		if(isset($_REQUEST['price_max']))
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

		if(isset($_REQUEST['is_franchise']))
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

		if(isset($_REQUEST['property_type']))
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

		if(isset($_REQUEST['certified_listing']))
			$certified_listing = $_REQUEST['certified_listing'];

		if (!empty($certified_listing)) {
			$meta_query['certification'] = array(
				'key'     => 'listing_certification_status',
				'value'   => 'certification_finished',
				'compare' => '==',
			);
		}

		$meta_query_golden = array();

		if(isset($_REQUEST['golden']))
			$certified_golden = $_REQUEST['golden'];

		if (!empty($certified_golden)) {
			$meta_query_golden['golden'] = array(
				'key'     => 'golden_visa',
				'value'   => 'yes_golden_visa',
				'compare' => '==',
			);
		}

		if (empty($meta_query)) {
			return;
		}

		$this->set_meta_query($query, $meta_query);
	}

	public function filter_by_golden($query)
	{
		if (!$this->is_listing_query($query)) {
			return;
		}

		$meta_query = array();

		if(isset($_REQUEST['certified_listing']))
			$certified_listing = $_REQUEST['certified_listing'];

		if (!empty($certified_listing)) {
			$meta_query['certification'] = array(
				'key'     => 'listing_certification_status',
				'value'   => 'certification_finished',
				'compare' => '==',
			);
		}

		$meta_query = array();

		if(isset($_REQUEST['golden']))
			$certified_golden = $_REQUEST['golden'];

		if (!empty($certified_golden)) {
			$meta_query['golden'] = array(
				'key'     => 'golden_visa',
				'value'   => 'yes_golden_visa',
				'compare' => '==',
			);
		}

		if (empty($meta_query)) {
			return;
		}

		$this->set_meta_query($query, $meta_query);
	}

	public function listings_not_expired($posts, $query)
	{	
		if (!$this->is_listing_query($query)) {
			return $posts;
		}
		
		$filtered = [];

		foreach($posts as $k => $post) {
			$user_id = get_field('owner', $post->ID)['ID'];
			$isBroker = isBroker($user_id, pll_current_language());
			$expire_date = get_field('expire_date', $post->ID);
			$valid_date = $expire_date >= date('Ymd');

			if($isBroker || $valid_date || $expire_date == '') {
				$filtered[] = $post;
			}		
		}

		return $filtered;
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
		if (is_admin() && !wp_doing_ajax()) {
			return false;
		}

		if(isset($query->query_vars['suppress_filters'])){
			if ($query->query_vars['suppress_filters'])
				return false;
		}


		return isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'listing';
	}

	public function listings_ajax_handler()
	{
		global $wpdb;
		// prepare our arguments for the query
		//$args = json_decode( stripslashes( $_POST['query'] ), true );
		//$args['paged'] = $_POST['page'] + 1; // we need next page to be loaded
		$paged = isset($_POST['page']) ? $_POST['page'] : 1;
		$posts_per_page = $this->posts_per_page;

		/* get all highlighted */

		$args['paged'] = $paged;
		$args['post_type'] = 'listing';
		$args['posts_per_page'] = $posts_per_page;

		if (isset($_POST['post__in'])) {
			$args['post__in'] = explode(',', $_POST['post__in']);
		}

		$post_status = 'publish';
		if (isset($_POST['page_slug'])) {

			$template = get_page_template_slug(get_page_by_path($_POST['page_slug'])->ID);
			if ($template === 'premium-buyer.php') {
				if (isPremiumBuyer() || current_user_can('administrator')) {
					$post_status = array('publish', 'pending', 'draft');
					$args['inclusive'] = true;
					$args['suppress_filters'] = true;
					$args['date_query'] = array(
						array(
							'after' => '-30 days',
							'column' => 'post_date',
						)
					);
				}
			}
		}
		$args['post_status'] = $post_status;

		$current_blod_id = get_current_blog_id();
		$q_broadcasted = "SELECT * FROM ib__3wp_broadcast_broadcastdata WHERE blog_id = {$current_blod_id}";
		$countries = array();
		$r_breadcasted = $wpdb->get_results($q_broadcasted, ARRAY_A);
		foreach ($r_breadcasted as $kr_breadcasted => $b) {
			$b_aux = array();
			$b_aux['post_id'] = $b['post_id'];
			$b_aux['blog_id'] = $b['blog_id'];
			$b_aux['data'] = unserialize(base64_decode($b['data']));
			if ($b_aux['data']["linked_parent"]) {
				$countries[$b_aux['data']["linked_parent"]['blog_id']][] = $b['post_id'];
			}
		}

		global $wp_query;
		query_posts($args);
		if (have_posts()) :
			while (have_posts()) : the_post();
				set_query_var('countries', $countries);
				get_template_part('/elements/listings', get_post_format());
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
			wp_reset_postdata();
			die; // here we exit the script and even no wp_reset_query() required!
		}
	}
