<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
if (!class_exists('WC_Email_Customer_Completed_Order')) :
	/**
	 * Customer Completed Order Email.
	 *
	 * Order complete emails are sent to the customer when the order is marked complete and usual indicates that the order has been shipped.
	 *
	 * @class       WC_Email_Customer_Completed_Order
	 * @version     2.0.0
	 * @package     WooCommerce/Classes/Emails
	 * @author      WooThemes
	 * @extends     WC_Email
	 */
	class WC_Email_Customer_Completed_Order extends WC_Email {
		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->id             = 'customer_completed_order';
			$this->customer_email = TRUE;
			$this->title          = __('Completed order', 'woocommerce');
			$this->description
			                      = __('Order complete emails are sent to customers when their orders are marked completed and usually indicate that their orders have been shipped.',
			                           'woocommerce');
			$this->heading = __('Your order is complete', 'woocommerce');
			$this->subject = __('Your {site_title} order from {order_date} is complete', 'woocommerce');
			$this->template_html  = 'emails/customer-completed-order.php';
			$this->template_plain = 'emails/plain/customer-completed-order.php';
			// Triggers for this email
			add_action('woocommerce_order_status_completed_notification', [$this, 'trigger']);
			// Other settings
			$this->heading_downloadable = $this->get_option('heading_downloadable',
			                                                __('Your order is complete - download your files',
			                                                   'woocommerce'));
			$this->subject_downloadable = $this->get_option('subject_downloadable',
			                                                __('Your {site_title} order from {order_date} is complete - download your files',
			                                                   'woocommerce'));
			// Call parent constuctor
			parent::__construct();
		}

		/**
		 * Trigger.
		 *
		 * @param int $order_id
		 */
		public function trigger($order_id) {
			if ($order_id) {
				$this->object    = wc_get_order($order_id);
				$this->recipient = $this->object->billing_email;
				$this->find['order-date']   = '{order_date}';
				$this->find['order-number'] = '{order_number}';
				$this->replace['order-date']   = date_i18n(wc_date_format(), strtotime($this->object->order_date));
				$this->replace['order-number'] = $this->object->get_order_number();
			}
			if (!$this->is_enabled() || !$this->get_recipient()) {
				return;
			}
			$this->send($this->get_recipient(), $this->get_subject(), $this->get_content(), $this->get_headers(),
			            $this->get_attachments());
		}

		/**
		 * Get email subject.
		 *
		 * @access public
		 * @return string
		 */
		public function get_subject() {
			if (!empty($this->object) && $this->object->has_downloadable_item()) {
				return apply_filters('woocommerce_email_subject_customer_completed_order',
				                     $this->format_string($this->subject_downloadable), $this->object);
			} else {
				return apply_filters('woocommerce_email_subject_customer_completed_order',
				                     $this->format_string($this->subject), $this->object);
			}
		}

		/**
		 * Get email heading.
		 *
		 * @access public
		 * @return string
		 */
		public function get_heading() {
			if (!empty($this->object) && $this->object->has_downloadable_item()) {
				return apply_filters('woocommerce_email_heading_customer_completed_order',
				                     $this->format_string($this->heading_downloadable), $this->object);
			} else {
				return apply_filters('woocommerce_email_heading_customer_completed_order',
				                     $this->format_string($this->heading), $this->object);
			}
		}

		/**
		 * Get content html.
		 *
		 * @access public
		 * @return string
		 */
		public function get_content_html() {
			return wc_get_template_html($this->template_html, [
				'order'         => $this->object,
				'email_heading' => $this->get_heading(),
				'sent_to_admin' => FALSE,
				'plain_text'    => FALSE,
				'email'         => $this
			]);
		}

		/**
		 * Get content plain.
		 *
		 * @return string
		 */
		public function get_content_plain() {
			return wc_get_template_html($this->template_plain, [
				'order'         => $this->object,
				'email_heading' => $this->get_heading(),
				'sent_to_admin' => FALSE,
				'plain_text'    => TRUE,
				'email'         => $this
			]);
		}

		/**
		 * Initialise settings form fields.
		 */
		public function init_form_fields() {
			$this->form_fields = [
				'enabled'              => [
					'title'   => __('Enable/Disable', 'woocommerce'),
					'type'    => 'checkbox',
					'label'   => __('Enable this email notification', 'woocommerce'),
					'default' => 'yes'
				],
				'subject'              => [
					'title'       => __('Subject', 'woocommerce'),
					'type'        => 'text',
					'description' => sprintf(__('Defaults to <code>%s</code>', 'woocommerce'), $this->subject),
					'placeholder' => '',
					'default'     => '',
					'desc_tip'    => TRUE
				],
				'heading'              => [
					'title'       => __('Email Heading', 'woocommerce'),
					'type'        => 'text',
					'description' => sprintf(__('Defaults to <code>%s</code>', 'woocommerce'), $this->heading),
					'placeholder' => '',
					'default'     => '',
					'desc_tip'    => TRUE
				],
				'subject_downloadable' => [
					'title'       => __('Subject (downloadable)', 'woocommerce'),
					'type'        => 'text',
					'description' => sprintf(__('Defaults to <code>%s</code>', 'woocommerce'),
					                         $this->subject_downloadable),
					'placeholder' => '',
					'default'     => '',
					'desc_tip'    => TRUE
				],
				'heading_downloadable' => [
					'title'       => __('Email Heading (downloadable)', 'woocommerce'),
					'type'        => 'text',
					'description' => sprintf(__('Defaults to <code>%s</code>', 'woocommerce'),
					                         $this->heading_downloadable),
					'placeholder' => '',
					'default'     => '',
					'desc_tip'    => TRUE
				],
				'email_type'           => [
					'title'       => __('Email type', 'woocommerce'),
					'type'        => 'select',
					'description' => __('Choose which format of email to send.', 'woocommerce'),
					'default'     => 'html',
					'class'       => 'email_type wc-enhanced-select',
					'options'     => $this->get_email_type_options(),
					'desc_tip'    => TRUE
				]
			];
		}
	}
endif;
return new WC_Email_Customer_Completed_Order();
