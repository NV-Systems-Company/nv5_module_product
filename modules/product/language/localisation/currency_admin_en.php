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
$lang_module['heading_title']        = 'Currencies';

// Text
$lang_module['text_success']         = 'Success: You have modified currencies!';
$lang_module['text_list']            = 'Currency List';
$lang_module['text_add']             = 'Add Currency';
$lang_module['text_edit']            = 'Edit Currency';

// Column
$lang_module['column_title']         = 'Currency Title';
$lang_module['column_code']          = 'Code';
$lang_module['column_value']         = 'Value';
$lang_module['column_date_modified'] = 'Last Updated';
$lang_module['column_action']        = 'Action';

// Entry
$lang_module['entry_title']          = 'Currency Title';
$lang_module['entry_code']           = 'Code';
$lang_module['entry_value']          = 'Value';
$lang_module['entry_symbol_left']    = 'Symbol Left';
$lang_module['entry_symbol_right']   = 'Symbol Right';
$lang_module['entry_decimal_place']  = 'Decimal Places';
$lang_module['entry_status']         = 'Status';

// Help
$lang_module['help_code']            = 'Do not change if this is your default currency. Must be valid <a href="http://www.xe.com/iso4217.php" target="_blank">ISO code</a>.';
$lang_module['help_value']           = 'Set to 1.00000 if this is your default currency.';

// Error
$lang_module['error_permission']     = 'Warning: You do not have permission to modify currencies!';
$lang_module['error_title']          = 'Currency Title must be between 3 and 32 characters!';
$lang_module['error_code']           = 'Currency Code must contain 3 characters!';
$lang_module['error_default']        = 'Warning: This currency cannot be deleted as it is currently assigned as the default store currency!';
$lang_module['error_store']          = 'Warning: This currency cannot be deleted as it is currently assigned to %s stores!';
$lang_module['error_order']          = 'Warning: This currency cannot be deleted as it is currently assigned to %s orders!';