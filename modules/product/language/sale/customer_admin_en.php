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
if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );


// Heading
$lang_module['heading_title']         = 'Customers';

// Text
$lang_module['text_success']          = 'Success: You have modified customers!';
$lang_module['text_list']             = 'Customer List';
$lang_module['text_add']              = 'Add Customer';
$lang_module['text_edit']             = 'Edit Customer';
$lang_module['text_default']          = 'Default';
$lang_module['text_balance']          = 'Balance';
$lang_module['text_add_ban_ip']       = 'Add Ban IP';
$lang_module['text_remove_ban_ip']    = 'Remove Ban IP';
$lang_module['text_login']            = 'Login into Store';

// Column
$lang_module['column_name']           = 'Customer Name';
$lang_module['column_email']          = 'E-Mail';
$lang_module['column_customer_group'] = 'Customer Group';
$lang_module['column_status']         = 'Status';
$lang_module['column_date_added']     = 'Date Added';
$lang_module['column_approval']        = 'Approval';
$lang_module['column_comment']        = 'Comment';
$lang_module['column_description']    = 'Description';
$lang_module['column_amount']         = 'Amount';
$lang_module['column_points']         = 'Points';
$lang_module['column_ip']             = 'IP';
$lang_module['column_total']          = 'Total Accounts';
$lang_module['column_action']         = 'Action';

// Entry
$lang_module['entry_customer_group']  = 'Customer Group';
$lang_module['entry_first_name']       = 'First Name';
$lang_module['entry_last_name']        = 'Last Name';
$lang_module['entry_email']           = 'E-Mail';
$lang_module['entry_telephone']       = 'Telephone';
$lang_module['entry_fax']             = 'Fax';
$lang_module['entry_newsletter']      = 'Newsletter';
$lang_module['entry_status']          = 'Status';
$lang_module['entry_safe']            = 'Safe';
$lang_module['entry_username']			= 'Username';
$lang_module['entry_password']        = 'Password';
$lang_module['entry_confirm']         = 'Confirm';
$lang_module['entry_company']         = 'Company';
$lang_module['entry_address_1']       = 'Address 1';
$lang_module['entry_address_2']       = 'Address 2';
$lang_module['entry_city']            = 'City';
$lang_module['entry_postcode']        = 'Postcode';
$lang_module['entry_country']         = 'Country';
$lang_module['entry_zone']            = 'Region / State';
$lang_module['entry_default']         = 'Default Address';
$lang_module['entry_comment']         = 'Comment';
$lang_module['entry_description']     = 'Description';
$lang_module['entry_amount']          = 'Amount';
$lang_module['entry_points']          = 'Points';
$lang_module['entry_name']            = 'Customer Name';
$lang_module['entry_approved']        = 'Approved';
$lang_module['entry_ip']              = 'IP';
$lang_module['entry_date_added']      = 'Date Added';
$lang_module['entry_transactions']			= 'Transactions';
$lang_module['entry_history']			= 'History';
$lang_module['entry_ip_address']			= 'IP Addresses';
$lang_module['address']			= 'Addresses';
$lang_module['add_history']			= 'Add History';
$lang_module['add_reward_points']			= 'Add Reward Points';

$lang_module['add_address']			= 'Add Address';
// Help
$lang_module['help_safe']             = 'Set to true to avoid this customer from being caught by the anti-fraud system';
$lang_module['help_points']           = 'Use minus to remove points';

// Error
$lang_module['error_warning']         = 'Warning: Please check the form carefully for errors!';
$lang_module['error_permission']      = 'Warning: You do not have permission to modify customers!';
$lang_module['error_email_exists']		= 'Warning: E-Mail Address is already registered!';
$lang_module['error_first_name']       = 'First Name must be between 1 and 32 characters!';
$lang_module['error_last_name']        = 'Last Name must be between 1 and 32 characters!';
$lang_module['error_email']           = 'E-Mail Address does not appear to be valid!';
$lang_module['error_telephone']       = 'Telephone must be between 3 and 32 characters!';
$lang_module['account_deny_name'] = 'Sorry, Account %s banned.';
$lang_module['error_username_exist'] = 'User name used by another client. Please choose another name';
$lang_module['error_password']        = 'Password must be between 4 and 20 characters!';
$lang_module['error_confirm']         = 'Password and password confirmation do not match!';
$lang_module['error_address_1']       = 'Address 1 must be between 3 and 128 characters!';
$lang_module['error_city']            = 'City must be between 2 and 128 characters!';
$lang_module['error_postcode']        = 'Postcode must be between 2 and 10 characters for this country!';
$lang_module['error_country']         = 'Please select a country!';
$lang_module['error_zone']            = 'Please select a region / state!';
$lang_module['error_custom_field']    = '%s required!';
$lang_module['error_comment']         = 'You must enter a comment!';
$lang_module['no_del_customer']     = 'Warning: You can not modify weight class ';
$lang_module['errorsave'] = 'Error: system can not update information, please check if the username exist';