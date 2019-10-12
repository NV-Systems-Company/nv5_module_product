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
$lang_module['heading_title']     = 'Customer Groups';

// Text
$lang_module['text_success']      = 'Success: You have modified customer groups!';
$lang_module['text_list']         = 'Customer Group List';
$lang_module['text_add']          = 'Add Customer Group';
$lang_module['text_edit']         = 'Edit Customer Group';

// Column
$lang_module['column_name']       = 'Customer Group Name';
$lang_module['column_sort_order'] = 'Sort Order';
$lang_module['column_action']     = 'Action';

// Entry
$lang_module['entry_name']        = 'Customer Group Name';
$lang_module['entry_description'] = 'Description';
$lang_module['entry_approval']    = 'Approve New Customers';
$lang_module['entry_sort_order']  = 'Sort Order';

// Help
$lang_module['help_approval']     = 'Customers must be approved by an administrator before they can login.';

// Error
$lang_module['error_permission']   = 'Warning: You do not have permission to modify customer groups!';
$lang_module['error_name']         = 'Customer Group Name must be between 3 and 32 characters!';
$lang_module['error_default']      = 'Warning: This customer group cannot be deleted as it is currently assigned as the default store customer group!';
$lang_module['error_store']        = 'Warning: This customer group cannot be deleted as it is currently assigned to %s stores!';
$lang_module['error_customer']     = 'Warning: This customer group cannot be deleted as it is currently assigned to %s customers!';
$lang_module['error_save'] 		= 'Warning: system can not update information, please check if the customer group name exist'; 
$lang_module['error_no_delete'] 		= 'Warning: No customer group has been deleted'; 
//$lang_module['error_warning'] 		= 'Warning: Please check the form carefully for errors!'; 