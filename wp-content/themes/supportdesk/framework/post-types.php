<?php
/**
 * Add Post Types
 */
/**
 * Add Knowledge Base Post Type
 */
if (!function_exists('st_create_kb_post_type')) {
// Register Custom Post Type
	function st_create_kb_post_type() {
		// Get Knowledge slug from options
		$st_kb_slug = 'knowledgebase';
		$st_kb_slug = of_get_option('st_kb_slug');
		$labels = [
			'name'               => __('Knowledge Base', 'framework'),
			'singular_name'      => __('Knowledge Base', 'framework'),
			'menu_name'          => __('Knowledge Base', 'framework'),
			'parent_item_colon'  => __('Parent Article:', 'framework'),
			'all_items'          => __('All Articles', 'framework'),
			'view_item'          => __('View Article', 'framework'),
			'add_new_item'       => __('Add New Article', 'framework'),
			'add_new'            => __('New Article', 'framework'),
			'edit_item'          => __('Edit Article', 'framework'),
			'update_item'        => __('Update Article', 'framework'),
			'search_items'       => __('Search Articles', 'framework'),
			'not_found'          => __('No articles found', 'framework'),
			'not_found_in_trash' => __('No articles found in Trash', 'framework'),
		];
		$rewrite = [
			'slug'       => $st_kb_slug,
			'with_front' => FALSE,
			'pages'      => TRUE,
			'feeds'      => TRUE,
		];
		$args = [
			'label'               => __('st_kb', 'framework'),
			'description'         => __('Knowledge Base Post Type', 'framework'),
			'labels'              => $labels,
			'supports'            => ['title', 'author', 'comments', 'revisions', 'editor',],
			'taxonomies'          => ['st_kb_category'],
			'hierarchical'        => FALSE,
			'public'              => TRUE,
			'show_ui'             => TRUE,
			'show_in_menu'        => TRUE,
			'show_in_nav_menus'   => TRUE,
			'show_in_admin_bar'   => TRUE,
			'menu_position'       => 5,
			'can_export'          => TRUE,
			'has_archive'         => TRUE,
			'exclude_from_search' => TRUE,
			'publicly_queryable'  => TRUE,
			'rewrite'             => $rewrite,
			'capability_type'     => 'post',
		];
		register_post_type('st_kb', $args);
	}

// Hook into the 'init' action
	add_action('init', 'st_create_kb_post_type', 0);
}
if (!function_exists('st_create_kb_category_taxonomy')) {
// Register Article Category Custom Taxonomy
	function st_create_kb_category_taxonomy() {
		$labels = [
			'name'                       => __('Article Categories', 'Taxonomy General Name', 'framework'),
			'singular_name'              => __('Article Category', 'Taxonomy Singular Name', 'framework'),
			'menu_name'                  => __('Article Categories', 'framework'),
			'all_items'                  => __('All Categories', 'framework'),
			'parent_item'                => __('Parent Category', 'framework'),
			'parent_item_colon'          => __('Parent Category:', 'framework'),
			'new_item_name'              => __('New Category Name', 'framework'),
			'add_new_item'               => __('Add New Category', 'framework'),
			'edit_item'                  => __('Edit Category', 'framework'),
			'update_item'                => __('Update Category', 'framework'),
			'separate_items_with_commas' => __('Separate categories with commas', 'framework'),
			'search_items'               => __('Search categories', 'framework'),
			'add_or_remove_items'        => __('Add or remove categories', 'framework'),
			'choose_from_most_used'      => __('Choose from the most used categories', 'framework'),
		];
		$rewrite = [
			'slug'         => 'section',
			'with_front'   => FALSE,
			'hierarchical' => TRUE,
		];
		$args = [
			'labels'            => $labels,
			'hierarchical'      => TRUE,
			'public'            => TRUE,
			'show_ui'           => TRUE,
			'show_admin_column' => TRUE,
			'show_in_nav_menus' => TRUE,
			'show_tagcloud'     => FALSE,
			'query_var'         => 'article_category',
			'rewrite'           => $rewrite,
		];
		register_taxonomy('st_kb_category', 'st_kb', $args);
	}

// Hook into the 'init' action
	add_action('init', 'st_create_kb_category_taxonomy', 0);
}
if (!function_exists('st_create_kb_tag_taxonomy')) {
// Register Article Tag Custom Taxonomy
	function st_create_kb_tag_taxonomy() {
		$labels = [
			'name'                       => __('Article Tags', 'Taxonomy General Name', 'framework'),
			'singular_name'              => __('Article Tag', 'Taxonomy Singular Name', 'framework'),
			'menu_name'                  => __('Article Tags', 'framework'),
			'all_items'                  => __('All Tags', 'framework'),
			'parent_item'                => __('Parent Tag', 'framework'),
			'parent_item_colon'          => __('Parent Tag:', 'framework'),
			'new_item_name'              => __('New Tag Name', 'framework'),
			'add_new_item'               => __('Add New Tag', 'framework'),
			'edit_item'                  => __('Edit Tag', 'framework'),
			'update_item'                => __('Update Tag', 'framework'),
			'separate_items_with_commas' => __('Separate tags with commas', 'framework'),
			'search_items'               => __('Search tags', 'framework'),
			'add_or_remove_items'        => __('Add or remove tags', 'framework'),
			'choose_from_most_used'      => __('Choose from the most used tags', 'framework'),
		];
		$rewrite = [
			'slug'         => 'kb-tag',
			'with_front'   => FALSE,
			'hierarchical' => FALSE,
		];
		$args = [
			'labels'            => $labels,
			'hierarchical'      => FALSE,
			'public'            => TRUE,
			'show_ui'           => TRUE,
			'show_admin_column' => TRUE,
			'show_in_nav_menus' => TRUE,
			'show_tagcloud'     => TRUE,
			'query_var'         => 'article_tag',
			'rewrite'           => $rewrite,
		];
		register_taxonomy('st_kb_tag', 'st_kb', $args);
	}

// Hook into the 'init' action
	add_action('init', 'st_create_kb_tag_taxonomy', 0);
}
/**
 * Add FAQ Post Type
 */
add_action('init', 'st_create_faq_post_type');
if (!function_exists('st_create_faq_post_type')) {
	function st_create_faq_post_type() {
		register_post_type('st_faq',
		                   [
			                   'labels'              => [
				                   'name'               => __('FAQs', 'framework'),
				                   'singular_name'      => __('FAQ', 'framework'),
				                   'add_new'            => __('Add FAQ', 'framework'),
				                   'add_new_item'       => __('Add New FAQ', 'framework'),
				                   'edit_item'          => __('Edit FAQ', 'framework'),
				                   'new_item'           => __('New FAQ', 'framework'),
				                   'view_item'          => __('View FAQ', 'framework'),
				                   'search_items'       => __('Search FAQs', 'framework'),
				                   'not_found'          => __('No FAQs found', 'framework'),
				                   'not_found_in_trash' => __('No FAQs found in Trash', 'framework')
			                   ],
			                   'public'              => TRUE,
			                   'menu_position'       => 5,
			                   'rewrite'             => ['slug'         => 'faq',
			                                             'hierarchical' => 'true',
			                                             'with_front'   => FALSE],
			                   'supports'            => [
				                   'title',
				                   'editor',
				                   'page-attributes'],
			                   'public'              => TRUE,
			                   'show_ui'             => TRUE,
			                   'publicly_queryable'  => TRUE,
			                   'capability_type'     => 'page',
			                   'hierarchical'        => TRUE,
			                   'exclude_from_search' => TRUE
		                   ]
		);
	}
}
/**
 * Add Homepage Block Post Type
 */
add_action('init', 'st_create_hpblock_post_type');
if (!function_exists('st_create_hpblock_post_type')) {
	function st_create_hpblock_post_type() {
		register_post_type('st_hpblock',
		                   [
			                   'public'              => TRUE,
			                   'publicly_queryable'  => FALSE,
			                   'show_in_nav_menus'   => TRUE,
			                   'show_in_admin_bar'   => TRUE,
			                   'menu_position'       => 5,
			                   'exclude_from_search' => TRUE,
			                   'hierarchical'        => FALSE,
			                   'map_meta_cap'        => TRUE,
			                   'labels'              => [
				                   'name'               => __('Homepage Blocks', 'framework'),
				                   'singular_name'      => __('Homepage Blocks', 'framework'),
				                   'add_new'            => __('Add New Block', 'framework'),
				                   'add_new_item'       => __('Add New Block', 'framework'),
				                   'edit_item'          => __('Edit Block', 'framework'),
				                   'new_item'           => __('New Block', 'framework'),
				                   'view_item'          => __('View Block', 'framework'),
				                   'search_items'       => __('Search Blocks', 'framework'),
				                   'not_found'          => __('No Blocks found', 'framework'),
				                   'not_found_in_trash' => __('No Blocks found in Trash', 'framework')
			                   ],
			                   'supports'            => ['title', 'page-attributes'],
		                   ]
		);
	}
}
/**
 * Order FAQ & HP Blocks by menu_order
 */
function st_set_custom_post_types_admin_order($wp_query) {
	if (is_admin()) {
		// Get the post type from the query
		$post_type = $wp_query->query['post_type'];
		if ($post_type == 'st_hpblock' || $post_type == 'st_faq') {
			// 'orderby' value can be any column name
			$wp_query->set('orderby', 'menu_order');
			// 'order' value can be ASC or DESC
			$wp_query->set('order', 'ASC');
		}
	}
}

add_filter('pre_get_posts', 'st_set_custom_post_types_admin_order');