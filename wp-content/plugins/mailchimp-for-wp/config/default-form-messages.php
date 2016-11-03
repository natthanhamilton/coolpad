<?php
return [
	'subscribed'              => [
		'type' => 'success',
		'text' => __('Thank you, your sign-up request was successful! Please check your email inbox to confirm.',
		             'mailchimp-for-wp')
	],
	'error'                   => [
		'type' => 'error',
		'text' => __('Oops. Something went wrong. Please try again later.', 'mailchimp-for-wp'),
	],
	'invalid_email'           => [
		'type' => 'error',
		'text' => __('Please provide a valid email address.', 'mailchimp-for-wp'),
	],
	'already_subscribed'      => [
		'type' => 'notice',
		'text' => __('Given email address is already subscribed, thank you!', 'mailchimp-for-wp'),
	],
	'required_field_missing'  => [
		'type' => 'error',
		'text' => __('Please fill in the required fields.', 'mailchimp-for-wp'),
	],
	'unsubscribed'            => [
		'type' => 'success',
		'text' => __('You were successfully unsubscribed.', 'mailchimp-for-wp'),
	],
	'not_subscribed'          => [
		'type' => 'notice',
		'text' => __('Given email address is not subscribed.', 'mailchimp-for-wp'),
	],
	'no_lists_selected'       => [
		'type' => 'error',
		'text' => __('Please select at least one list.', 'mailchimp-for-wp')
	],
	'previously_unsubscribed' => [
		'type' => 'error',
		'text' => __('It seems that you have previously unsubscribed, so we can not automatically resubscribe you.',
		             'mailchimp-for-wp')
	],
];