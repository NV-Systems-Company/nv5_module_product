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

$lang_module['heading_title']      = 'Offline Credit Card Payment';

// Text 
$lang_module['text_edit']       = 'Edit Offline Credit Payment';
$lang_module['text_payment']       = 'Payment';
$lang_module['text_success']       = 'Success: You have modified Offline Credit Card Payment account details!';
$lang_module['text_authorization'] = 'Authorization';
$lang_module['text_capture']       = 'Capture';

// Entry
$lang_module['entry_total']        = 'Total:';
$lang_module['entry_order_status'] = 'Order Status:';
$lang_module['entry_geo_zone']     = 'Geo Zone:'; 
$lang_module['entry_all_zone']     = 'All Zone'; 
$lang_module['entry_status']       = 'Status:';
$lang_module['entry_sort_order']   = 'Sort Order:';
$lang_module['entry_email']   	 = 'Email Account For CC:';
$lang_module['entry_encryption']   = 'Encryption Key:';
$lang_module['entry_save_card_name']         = 'Save Credit Card Name Seperate From Billing Name?';
$lang_module['entry_save_card_type']         = 'Save Credit Card Type?';
$lang_module['entry_accept_credit_card']         = 'Accepted Credit Cards';
$lang_module['entry_select']         = 'Select one';
$lang_module['entry_safe_password']         = 'Generate Safe Password';
$lang_module['help_total']         = 'The checkout total the order must reach before this payment method becomes active.';

//lang cc
$lang_module['cc_visa'] = 'Visa';
$lang_module['cc_masterCard'] = 'MasterCard';
$lang_module['cc_american_express'] = 'American Express';
$lang_module['cc_china_unionPay'] = 'China UnionPay';
$lang_module['cc_jsb'] = 'JCB';


// Error 
$lang_module['error_permission']   = 'Warning: You do not have permission to modify payment Authorize.Net (SIM)!';
$lang_module['error_geo_zone']        = 'Geo Zone Required!';
$lang_module['error_email']        = 'Email Account For CC Required!';
$lang_module['error_encryption']        = 'Encryption Key Required!';
$lang_module['error_login']        = 'Login ID Required!';
$lang_module['error_total']        = 'Total Required!';
$lang_module['error_order_status']        = 'Order Status Required!';
$lang_module['error_key']          = 'Transaction Key Required!';
$lang_module['error_hash']         = 'MD5 Hash Required!';