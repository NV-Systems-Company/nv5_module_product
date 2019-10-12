<?php

/**
 * @Project NUKEVIET 4.x
 * @Author DANGDINHTU (dlinhvan@gmail.com)
 * @Copyright (C) 2013 Webdep24.com. All rights reserved
 * @Blog http://dangdinhtu.com
 * @Developers http://developers.dangdinhtu.com/
 * @License GNU/GPL version 2 or any later version
 * @Createdate  Mon, 20 Oct 2014 14:00:59 GMT
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

// Heading
$lang_module['heading_title']					= 'PayPal Express Checkout';

// Text
$lang_module['text_success']					= 'Success: You have modified PayPal Express Checkout account details!';
$lang_module['text_edit']                     = 'Edit PayPal Express Checkout';
$lang_module['text_pp_express']				= '<a target="_BLANK" href="https://www.paypal.com/uk/mrb/pal=V4T754QB63XXL"><img src="view/image/payment/paypal.png" alt="PayPal Website Payment Pro" title="PayPal Website Payment Pro iFrame" style="border: 1px solid #EEEEEE;" /></a>';
$lang_module['text_authorization']			= 'Authorization';
$lang_module['text_sale']						= 'Sale';
$lang_module['text_clear']					= 'Clear';
$lang_module['text_browse']					= 'Browse';
$lang_module['text_image_manager']			= 'Image manager';
$lang_module['text_ipn']						= 'IPN url';

// Entry
$lang_module['entry_username']				= 'API Username';
$lang_module['entry_password']				= 'API Password';
$lang_module['entry_signature']				= 'API Signature';
$lang_module['entry_test']					= 'Test (Sandbox) Mode';
$lang_module['entry_method']					= 'Transaction Method';
$lang_module['entry_all_zone']				= 'All Zone';
$lang_module['entry_geo_zone']				= 'Geo Zone';
$lang_module['entry_status']					= 'Status';
$lang_module['entry_sort_order']				= 'Sort Order';
$lang_module['entry_icon_sort_order']			= 'Icon Sort Order';
$lang_module['entry_debug']					= 'Debug logging';
$lang_module['entry_total']					= 'Total';
$lang_module['entry_currency']				= 'Default currency';
$lang_module['entry_recurring_cancellation']	= 'Allow customers to cancel recurring payments';
$lang_module['entry_canceled_reversal_status'] = 'Canceled Reversal Status';
$lang_module['entry_completed_status']		= 'Completed Status';
$lang_module['entry_denied_status']			= 'Denied Status';
$lang_module['entry_expired_status']			= 'Expired Status';
$lang_module['entry_failed_status']			= 'Failed Status';
$lang_module['entry_pending_status']			= 'Pending Status';
$lang_module['entry_processed_status']		= 'Processed Status';
$lang_module['entry_refunded_status']			= 'Refunded Status';
$lang_module['entry_reversed_status']			= 'Reversed Status';
$lang_module['entry_voided_status']			= 'Voided Status';
$lang_module['entry_display_checkout']		= 'Display quick checkout icon';
$lang_module['entry_allow_notes']				= 'Allow notes';
$lang_module['entry_logo']					= 'Logo';
$lang_module['entry_border_colour']			= 'Header border colour';
$lang_module['entry_header_colour']			= 'Header background colour';
$lang_module['entry_page_colour']				= 'Page background colour';

// Tab
$lang_module['tab_general']					= 'General';
$lang_module['tab_api_details']				= 'API details';
$lang_module['tab_order_status']				= 'Order status';
$lang_module['tab_customise']					= 'Customise checkout';

// Help
$lang_module['help_ipn']						= 'Required for subscriptions';
$lang_module['help_total']					= 'The checkout total the order must reach before this payment method becomes active';
$lang_module['help_logo']						= 'Max 750px(w) x 90px(h)<br />You should only use a logo if you have SSL set up.';
$lang_module['help_colour']					= '6 character HTML colour code';
$lang_module['help_currency']					= 'Used for transaction searches';

// Error
$lang_module['error_permission']				= 'Warning: You do not have permission to modify payment PayPal Express Checkout!';
$lang_module['error_username']				= 'API Username Required!';
$lang_module['error_password']				= 'API Password Required!';
$lang_module['error_signature']				= 'API Signature Required!';
$lang_module['error_data']					= 'Data missing from request';
$lang_module['error_timeout']					= 'Request timed out';