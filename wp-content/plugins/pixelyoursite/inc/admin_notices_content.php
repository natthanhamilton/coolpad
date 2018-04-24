<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'PYS_FREE_NOTICES_VERSION', '5.0.0' );

## WOO activated or WOO and EDD
$GLOBALS['PYS_FREE_WOO_NOTICES'] = array(
	
	array(
		'from'    => 1,
		'to'      => 1,
		'content' => '<strong>Customize WooCommerce Conversion Value:</strong>  With PixelYourSite Pro you can fine-tune WooCommerce Events Values. This can improve your Facebook ads delivery and ROI: <a href="http://www.pixelyoursite.com/facebook-pixel-plugin/woocommerce-facebook-pixel?utm_source=wpa-woo&utm_medium=wp&utm_campaign=wp-woo1" target="_blank"><strong>FIND MORE</strong></a>',
		'visible' => true
	),

	array(
		'from'    => 2,
		'to'      => 2,
		'content' => 'Our expert users have improved their Facebook ads ROI by <strong>fine-tuning WooCommerce events value</strong> (using a price percent). This is one of the many PixelYourSite Pro killer options: <a href="http://www.pixelyoursite.com/facebook-pixel-plugin/woocommerce-facebook-pixel?utm_source=wpa-woo&utm_medium=wp&utm_campaign=wp-woo2" target="_blank"><strong>FIND MORE</strong></a>',
		'visible' => true
	),

	array(
		'from'    => 3,
		'to'      => 3,
		'content' => 'Reach new clients with Lifetime Value (LTV) Lookalikes. With PixelYourSite Pro, you can export an <strong>LTV customers file</strong> and use it for LTV Lookalikes (Facebook recommended strategy): <a href="http://www.pixelyoursite.com/facebook-pixel-plugin/woocommerce-facebook-pixel?utm_source=wpa-woo&utm_medium=wp&utm_campaign=wp-woo3" target="_blank"><strong>FIND MORE</strong></a>',
		'visible' => true
	),

	array(
		'from'    => 4,
		'to'      => 7,
		'content' => 'PixelYourSite: our pro users are able to do some <strong>extraordinary remarketing campaigns</strong> using all the parameters that the pro version tracks: <a href="http://www.pixelyoursite.com/facebook-pixel-plugin/woocommerce-facebook-pixel?utm_source=wpa-woo&utm_medium=wp&utm_campaign=wp-woo4" target="_blank"><strong>FIND MORE</strong></a>',
		'visible' => true
	),

	array(
		'from'    => 8,
		'to'      => 12,
		'content' => '<strong>Your Facebook Pixel FREE Guide by PixelYourSite:</strong> After <em>more than 65 000 users</em> and many hours spent on answering questions, we decided to make a comprehensive guide about the Facebook Pixel. <br>Have you got it yet? <a href="http://www.pixelyoursite.com/facebook-pixel-pdf-guide?utm_source=wp-pixel-guide&utm_medium=wp&utm_campaign=wp-pixel-guide-woo" target="_blank">Click here for your own FREE copy</a>',
		'visible' => true
	),

);

## EDD but not WOO activated
$GLOBALS['PYS_FREE_EDD_NOTICES'] = array(

	array(
		'from'    => 1,
		'to'      => 1,
		'content' => '<strong>Customize EDD Conversion Value:</strong>  With PixelYourSite Pro you can <strong>fine tune EDD Events Values</strong>. This can improve your ads delivery and ROI. <a href="http://www.pixelyoursite.com/facebook-pixel-plugin/easy-digital-downloads-facebook-pixel?utm_source=wpa-woo&utm_medium=wp&utm_campaign=wp-edd1" target="_blank"><strong>FIND MORE</strong></a>',
		'visible' => true
	),

	array(
		'from'    => 2,
		'to'      => 2,
		'content' => 'Our expert users have improved their Facebook ads ROI by <strong>fine-tuning EDD events value</strong> (using a price percent). This is one of the many pro version options: <a href="http://www.pixelyoursite.com/facebook-pixel-plugin/easy-digital-downloads-facebook-pixel?utm_source=wpa-woo&utm_medium=wp&utm_campaign=wp-edd2" target="_blank"><strong>FIND MORE</strong></a>',
		'visible' => true
	),

	array(
		'from'    => 3,
		'to'      => 3,
		'content' => 'Reach new clients with Lifetime Value (LTV) Lookalikes. With PixelYourSite Pro, you can export an <strong>LTV customers file</strong> and use it for LTV Lookalikes (Facebook recommended strategy): <a href="http://www.pixelyoursite.com/facebook-pixel-plugin/easy-digital-downloads-facebook-pixel?utm_source=wpa-woo&utm_medium=wp&utm_campaign=wp-edd3" target="_blank"><strong>FIND MORE</strong></a>',
		'visible' => true
	),

	array(
		'from'    => 4,
		'to'      => 7,
		'content' => 'Fire the Purchase event on transaction only to improve conversion tracking. This way, when a client revisits the "thank you page" it will not mess up your ads reports: <a href="http://www.pixelyoursite.com/facebook-pixel-plugin/easy-digital-downloads-facebook-pixel?utm_source=wpa-woo&utm_medium=wp&utm_campaign=wp-edd4" target="_blank"><strong>FIND MORE</strong></a>',
		'visible' => true
	),

	array(
		'from'    => 8,
		'to'      => 12,
		'content' => '<strong>Your Facebook Pixel FREE Guide by PixelYourSite:</strong> After <em>more than 65 000 users</em> and many hours spent on answering questions, we decided to make a comprehensive guide about the Facebook Pixel. <br>Have you got it yet? <a href="http://www.pixelyoursite.com/facebook-pixel-pdf-guide?utm_source=wp-pixel-guide&utm_medium=wp&utm_campaign=wp-pixel-guide-edd" target="_blank">Click here for your own FREE copy</a>',
		'visible' => true
	),

);

## Both WOO and EDD not activated
$GLOBALS['PYS_FREE_NO_WOO_NO_EDD_NOTICES'] = array(

	array(
		'from'    => 1,
		'to'      => 1,
		'content' => 'Most PixelYourSite Pro users are doing some incredible <strong>retargeting campaings</strong> using our Custom Audiences optimization features: <a href="http://www.pixelyoursite.com/facebook-pixel-plugin/custom-audiences-conversions?utm_source=wp1-non-woo-non-edd&utm_medium=wp&utm_campaign=wp1" target="_blank">Find out how the pro version can help you</a>',
		'visible' => true
	),

	array(
		'from'    => 2,
		'to'      => 2,
		'content' => 'PixelYourSite: Our most succesful clients use <strong>Dynamic Events</strong> for ads optimization and retargeting campaigns: <a href="http://www.pixelyoursite.com/facebook-pixel-plugin/facebook-pixel-events?utm_source=wp2-non-woo-non-edd&utm_medium=wp&utm_campaign=wp2" target="_blank">FIND MORE</a>',
		'visible' => true
	),

	array(
		'from'    => 3,
		'to'      => 3,
		'content' => '<strong>The WatchVideo Event:</strong> optimize your ads and retarget embedded videos with this super-useful PixelYourSite Pro feature: <a href="http://www.pixelyoursite.com/facebook-pixel-plugin/watchvideo-event?utm_source=wp3-non-woo-non-edd&utm_medium=wp&utm_campaign=wp3" target="_blank">FIND MORE</a>',
		'visible' => true
	),

	array(
		'from'    => 4,
		'to'      => 7,
		'content' => '<strong>The ClickEvent:</strong> Track every single click and use this event for Custom Audiences or Custom Conversions. This is a PixelYourSite Pro feature: <a href="http://www.pixelyoursite.com/facebook-pixel-plugin/custom-audiences-conversions?utm_source=wp3-non-woo-non-edd&utm_medium=wp&utm_campaign=wp4" target="_blank">FIND MORE</a>',
		'visible' => true
	),

	array(
		'from'    => 8,
		'to'      => 12,
		'content' => '<strong>Your Facebook Pixel FREE Guide by PixelYourSite:</strong> After <em>more than 65 000 users</em> and many hours spent on answering questions, we decided to make a comprehensive guide about the Facebook Pixel. <br>Have you got it yet? <a href="http://www.pixelyoursite.com/facebook-pixel-pdf-guide?utm_source=wp-pixel-guide&utm_medium=wp&utm_campaign=wp-pixel-guide-no-woo-no-edd" target="_blank">Click here for your own FREE copy</a>',
		'visible' => true
	),

);