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

$lang_module['heading_title']    = 'Weight Classes';

$lang_module['text_success']     = 'Success: You have modified weight classes!';
$lang_module['text_list']        = 'Weight Class List';
$lang_module['text_add']         = 'Add Weight Class';
$lang_module['text_edit']        = 'Edit Weight Class';
$lang_module['text_insert_success'] = 'Thêm đơn vị khối lượng thành công!';
$lang_module['text_update_success'] = 'Cập nhật đơn vị khối lượng thành công!';
$lang_module['text_delete_success'] = 'Xoá đơn vị khối lượng thành công!';
$lang_module['text_delete_error'] = 'Lỗi: không có đơn vị khối lượng nào được xoá!';

$lang_module['column_title']     = 'Weight Title';
$lang_module['column_unit']      = 'Weight Unit';
$lang_module['column_value']     = 'Value';
$lang_module['column_action']    = 'Action';

$lang_module['entry_title']      = 'Weight Title';
$lang_module['entry_unit']       = 'Weight Unit';
$lang_module['entry_value']      = 'Value';

$lang_module['help_value']       = 'Set to 1.00000 if this is your default weight.';

$lang_module['error_permission'] = 'Warning: You do not have permission to modify weight classes!';
$lang_module['error_title']      = 'Weight Title must be between 3 and 32 characters!';
$lang_module['error_unit']       = 'Weight Unit must be between 1 and 4 characters!';
$lang_module['error_default']    = 'Warning: This weight class cannot be deleted as it is currently assigned as the default store weight class!';
$lang_module['error_product']    = 'Warning: This weight class cannot be deleted as it is currently assigned to %s products!';
$lang_module['error_save']    = 'Warning: Can not save weight class please check input';
$lang_module['error_no_delete']     = 'Warning: You can not modify weight class ';