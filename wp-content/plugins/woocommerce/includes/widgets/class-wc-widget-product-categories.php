<?php
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Product Categories Widget.
 *
 * @author   WooThemes
 * @category Widgets
 * @package  WooCommerce/Widgets
 * @version  2.3.0
 * @extends  WC_Widget
 */
class WC_Widget_Product_Categories extends WC_Widget {
	/**
	 * Category ancestors.
	 *
	 * @var array
	 */
	public $cat_ancestors;
	/**
	 * Current Category.
	 *
	 * @var bool
	 */
	public $current_cat;

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->widget_cssclass    = 'woocommerce widget_product_categories';
		$this->widget_description = __('A list or dropdown of product categories.', 'woocommerce');
		$this->widget_id          = 'woocommerce_product_categories';
		$this->widget_name        = __('WooCommerce Product Categories', 'woocommerce');
		$this->settings           = [
			'title'              => [
				'type'  => 'text',
				'std'   => __('Product Categories', 'woocommerce'),
				'label' => __('Title', 'woocommerce')
			],
			'orderby'            => [
				'type'    => 'select',
				'std'     => 'name',
				'label'   => __('Order by', 'woocommerce'),
				'options' => [
					'order' => __('Category Order', 'woocommerce'),
					'name'  => __('Name', 'woocommerce')
				]
			],
			'dropdown'           => [
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __('Show as dropdown', 'woocommerce')
			],
			'count'              => [
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __('Show product counts', 'woocommerce')
			],
			'hierarchical'       => [
				'type'  => 'checkbox',
				'std'   => 1,
				'label' => __('Show hierarchy', 'woocommerce')
			],
			'show_children_only' => [
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __('Only show children of the current category', 'woocommerce')
			],
			'hide_empty'         => [
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __('Hide empty categories', 'woocommerce')
			]
		];
		parent::__construct();
	}

	/**
	 * Output widget.
	 *
	 * @see WP_Widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget($args, $instance) {
		global $wp_query, $post;
		$count              = isset($instance['count']) ? $instance['count'] : $this->settings['count']['std'];
		$hierarchical       = isset($instance['hierarchical']) ? $instance['hierarchical']
			: $this->settings['hierarchical']['std'];
		$show_children_only = isset($instance['show_children_only']) ? $instance['show_children_only']
			: $this->settings['show_children_only']['std'];
		$dropdown           = isset($instance['dropdown']) ? $instance['dropdown'] : $this->settings['dropdown']['std'];
		$orderby            = isset($instance['orderby']) ? $instance['orderby'] : $this->settings['orderby']['std'];
		$hide_empty         = isset($instance['hide_empty']) ? $instance['hide_empty']
			: $this->settings['hide_empty']['std'];
		$dropdown_args      = ['hide_empty' => $hide_empty];
		$list_args
		                    = ['show_count' => $count, 'hierarchical' => $hierarchical, 'taxonomy' => 'product_cat', 'hide_empty' => $hide_empty];
		// Menu Order
		$list_args['menu_order'] = FALSE;
		if ($orderby == 'order') {
			$list_args['menu_order'] = 'asc';
		} else {
			$list_args['orderby'] = 'title';
		}
		// Setup Current Category
		$this->current_cat   = FALSE;
		$this->cat_ancestors = [];
		if (is_tax('product_cat')) {
			$this->current_cat   = $wp_query->queried_object;
			$this->cat_ancestors = get_ancestors($this->current_cat->term_id, 'product_cat');
		} elseif (is_singular('product')) {
			$product_category = wc_get_product_terms($post->ID, 'product_cat',
			                                         apply_filters('woocommerce_product_categories_widget_product_terms_args',
			                                                       ['orderby' => 'parent']));
			if (!empty($product_category)) {
				$this->current_cat   = end($product_category);
				$this->cat_ancestors = get_ancestors($this->current_cat->term_id, 'product_cat');
			}
		}
		// Show Siblings and Children Only
		if ($show_children_only && $this->current_cat) {
			// Top level is needed
			$top_level = get_terms(
				'product_cat',
				[
					'fields'       => 'ids',
					'parent'       => 0,
					'hierarchical' => TRUE,
					'hide_empty'   => FALSE
				]
			);
			// Direct children are wanted
			$direct_children = get_terms(
				'product_cat',
				[
					'fields'       => 'ids',
					'parent'       => $this->current_cat->term_id,
					'hierarchical' => TRUE,
					'hide_empty'   => FALSE
				]
			);
			// Gather siblings of ancestors
			$siblings = [];
			if ($this->cat_ancestors) {
				foreach ($this->cat_ancestors as $ancestor) {
					$ancestor_siblings = get_terms(
						'product_cat',
						[
							'fields'       => 'ids',
							'parent'       => $ancestor,
							'hierarchical' => FALSE,
							'hide_empty'   => FALSE
						]
					);
					$siblings          = array_merge($siblings, $ancestor_siblings);
				}
			}
			if ($hierarchical) {
				$include = array_merge($top_level, $this->cat_ancestors, $siblings, $direct_children,
				                       [$this->current_cat->term_id]);
			} else {
				$include = array_merge($direct_children);
			}
			$dropdown_args['include'] = implode(',', $include);
			$list_args['include']     = implode(',', $include);
			if (empty($include)) {
				return;
			}
		} elseif ($show_children_only) {
			$dropdown_args['depth']        = 1;
			$dropdown_args['child_of']     = 0;
			$dropdown_args['hierarchical'] = 1;
			$list_args['depth']            = 1;
			$list_args['child_of']         = 0;
			$list_args['hierarchical']     = 1;
		}
		$this->widget_start($args, $instance);
		// Dropdown
		if ($dropdown) {
			$dropdown_defaults = [
				'show_count'         => $count,
				'hierarchical'       => $hierarchical,
				'show_uncategorized' => 0,
				'orderby'            => $orderby,
				'selected'           => $this->current_cat ? $this->current_cat->slug : ''
			];
			$dropdown_args     = wp_parse_args($dropdown_args, $dropdown_defaults);
			// Stuck with this until a fix for https://core.trac.wordpress.org/ticket/13258
			wc_product_dropdown_categories(apply_filters('woocommerce_product_categories_widget_dropdown_args',
			                                             $dropdown_args));
			wc_enqueue_js("
				jQuery( '.dropdown_product_cat' ).change( function() {
					if ( jQuery(this).val() != '' ) {
						var this_page = '';
						var home_url  = '" . esc_js(home_url('/')) . "';
						if ( home_url.indexOf( '?' ) > 0 ) {
							this_page = home_url + '&product_cat=' + jQuery(this).val();
						} else {
							this_page = home_url + '?product_cat=' + jQuery(this).val();
						}
						location.href = this_page;
					}
				});
			");
			// List
		} else {
			include_once(WC()->plugin_path() . '/includes/walkers/class-product-cat-list-walker.php');
			$list_args['walker']                     = new WC_Product_Cat_List_Walker;
			$list_args['title_li']                   = '';
			$list_args['pad_counts']                 = 1;
			$list_args['show_option_none']           = __('No product categories exist.', 'woocommerce');
			$list_args['current_category']           = ($this->current_cat) ? $this->current_cat->term_id : '';
			$list_args['current_category_ancestors'] = $this->cat_ancestors;
			echo '<ul class="product-categories">';
			wp_list_categories(apply_filters('woocommerce_product_categories_widget_args', $list_args));
			echo '</ul>';
		}
		$this->widget_end($args);
	}
}
