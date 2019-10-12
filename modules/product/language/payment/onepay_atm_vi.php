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

// Text
$lang_module['heading_title'] 				= 'Onepay ATM Card';
$lang_module['text_reason'] 				= 'REASON';
$lang_module['text_attn_email']			= 'ATTN: Paypal Order %s needs manual verification';
$lang_module['text_testmode']	   	 		= 'ATTENTION!!! The Payment Gateway is in \'Sandbox Mode\'. Your account will not be charged.';

// Error
$lang_module['error_referer'] 			= 'PP_Standard - Possible Scam: IPN/PDT Referrer URL "%s" was not Paypal.com. Order needs manual verification';
$lang_module['error_amount_mismatch']		= 'PP_Standard - Possible Scam: IPN/PDT Price "%s" does not match OpenCart Total "%s". Order needs manual verification';
$lang_module['error_email_mismatch']		= 'PP_Standard - Possible Scam: IPN/PDT Receiver Email does not match seller email. Order needs manual verification';
$lang_module['error_verify']				= 'PP_Standard - IPN/PDT Auto-Verification Failed. This is often caused by strange characters in the customer address or name. Verify manually.';
$lang_module['error_non_complete']		= 'PP_Standard - Non-complete order status received for order. Research needed.';
$lang_module['error_no_data']				= 'PP_Standard - No data/response from verification.';
 