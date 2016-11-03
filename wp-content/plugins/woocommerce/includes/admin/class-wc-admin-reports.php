<?php
/**
 * Admin Reports
 *
 * Functions used for displaying sales and customer reports in admin.
 *
 * @author      WooThemes
 * @category    Admin
 * @package     WooCommerce/Admin/Reports
 * @version     2.0.0
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('WC_Admin_Reports')) :
	/**
	 * WC_Admin_Reports Class.
	 */
	class WC_Admin_Reports {
		/**
		 * Handles output of the reports page in admin.
		 */
		public static function output() {
			$reports        = self::get_reports();
			$first_tab      = array_keys($reports);
			$current_tab    = !empty($_GET['tab']) ? sanitize_title($_GET['tab']) : $first_tab[0];
			$current_report = isset($_GET['report']) ? sanitize_title($_GET['report'])
				: current(array_keys($reports[ $current_tab ]['reports']));
			include_once('reports/class-wc-admin-report.php');
			include_once('views/html-admin-page-reports.php');
		}

		/**
		 * Returns the definitions for the reports to show in admin.
		 *
		 * @return array
		 */
		public static function get_reports() {
			$reports = [
				'orders'    => [
					'title'   => __('Orders', 'woocommerce'),
					'reports' => [
						"sales_by_date"     => [
							'title'       => __('Sales by date', 'woocommerce'),
							'description' => '',
							'hide_title'  => TRUE,
							'callback'    => [__CLASS__, 'get_report']
						],
						"sales_by_product"  => [
							'title'       => __('Sales by product', 'woocommerce'),
							'description' => '',
							'hide_title'  => TRUE,
							'callback'    => [__CLASS__, 'get_report']
						],
						"sales_by_category" => [
							'title'       => __('Sales by category', 'woocommerce'),
							'description' => '',
							'hide_title'  => TRUE,
							'callback'    => [__CLASS__, 'get_report']
						],
						"coupon_usage"      => [
							'title'       => __('Coupons by date', 'woocommerce'),
							'description' => '',
							'hide_title'  => TRUE,
							'callback'    => [__CLASS__, 'get_report']
						]
					]
				],
				'customers' => [
					'title'   => __('Customers', 'woocommerce'),
					'reports' => [
						"customers"     => [
							'title'       => __('Customers vs. Guests', 'woocommerce'),
							'description' => '',
							'hide_title'  => TRUE,
							'callback'    => [__CLASS__, 'get_report']
						],
						"customer_list" => [
							'title'       => __('Customer List', 'woocommerce'),
							'description' => '',
							'hide_title'  => TRUE,
							'callback'    => [__CLASS__, 'get_report']
						],
					]
				],
				'stock'     => [
					'title'   => __('Stock', 'woocommerce'),
					'reports' => [
						"low_in_stock" => [
							'title'       => __('Low in stock', 'woocommerce'),
							'description' => '',
							'hide_title'  => TRUE,
							'callback'    => [__CLASS__, 'get_report']
						],
						"out_of_stock" => [
							'title'       => __('Out of stock', 'woocommerce'),
							'description' => '',
							'hide_title'  => TRUE,
							'callback'    => [__CLASS__, 'get_report']
						],
						"most_stocked" => [
							'title'       => __('Most Stocked', 'woocommerce'),
							'description' => '',
							'hide_title'  => TRUE,
							'callback'    => [__CLASS__, 'get_report']
						],
					]
				]
			];
			if (wc_tax_enabled()) {
				$reports['taxes'] = [
					'title'   => __('Taxes', 'woocommerce'),
					'reports' => [
						"taxes_by_code" => [
							'title'       => __('Taxes by code', 'woocommerce'),
							'description' => '',
							'hide_title'  => TRUE,
							'callback'    => [__CLASS__, 'get_report']
						],
						"taxes_by_date" => [
							'title'       => __('Taxes by date', 'woocommerce'),
							'description' => '',
							'hide_title'  => TRUE,
							'callback'    => [__CLASS__, 'get_report']
						],
					]
				];
			}
			$reports = apply_filters('woocommerce_admin_reports', $reports);
			$reports = apply_filters('woocommerce_reports_charts', $reports); // Backwards compat
			foreach ($reports as $key => $report_group) {
				if (isset($reports[ $key ]['charts'])) {
					$reports[ $key ]['reports'] = $reports[ $key ]['charts'];
				}
				foreach ($reports[ $key ]['reports'] as $report_key => $report) {
					if (isset($reports[ $key ]['reports'][ $report_key ]['function'])) {
						$reports[ $key ]['reports'][ $report_key ]['callback']
							= $reports[ $key ]['reports'][ $report_key ]['function'];
					}
				}
			}

			return $reports;
		}

		/**
		 * Get a report from our reports subfolder.
		 */
		public static function get_report($name) {
			$name  = sanitize_title(str_replace('_', '-', $name));
			$class = 'WC_Report_' . str_replace('-', '_', $name);
			include_once(apply_filters('wc_admin_reports_path', 'reports/class-wc-report-' . $name . '.php', $name,
			                           $class));
			if (!class_exists($class)) {
				return;
			}
			$report = new $class();
			$report->output_report();
		}
	}
endif;
