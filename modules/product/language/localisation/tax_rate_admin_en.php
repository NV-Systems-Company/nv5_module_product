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

$lang_module['heading_title']        = 'Tax Rates';
$lang_module['text_success']         = 'Success: You have modified tax rates!';
$lang_module['text_list']            = 'Tax Rate List';
$lang_module['text_add']             = 'Add Tax Rate';
$lang_module['text_edit']            = 'Edit Tax Rate';
$lang_module['text_percent']         = 'Percentage';
$lang_module['text_amount']          = 'Fixed Amount';
$lang_module['column_name']          = 'Tax Name';
$lang_module['column_rate']          = 'Tax Rate';
$lang_module['column_type']          = 'Type';
$lang_module['column_geo_zone']      = 'Geo Zone';
$lang_module['column_date_added']    = 'Date Added';
$lang_module['column_date_modified'] = 'Date Modified';
$lang_module['column_action']        = 'Action';
$lang_module['entry_name']           = 'Tax Name';
$lang_module['entry_rate']           = 'Tax Rate';
$lang_module['entry_type']           = 'Type';
$lang_module['entry_customer_group'] = 'Customer Group';
$lang_module['entry_geo_zone']       = 'Geo Zone';
$lang_module['error_permission']     = 'Warning: You do not have permission to modify tax rates!';
$lang_module['error_no_delete']     = 'Cảnh báo: Không có đối tượng nào được xóa';
$lang_module['error_tax_rule']       = 'Warning: This tax rate cannot be deleted as it is currently assigned to %s tax classes!';
$lang_module['error_name']           = 'Tax Name must be between 3 and 32 characters!';
$lang_module['error_rate']           = 'Tax Rate required!';