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
$lang_module['heading_title']        = 'Danh sách tiền tệ';

// Text
$lang_module['text_insert_success']         = 'Thêm đơn vị tiền tệ thành công!';
$lang_module['text_update_success']         = 'Cập nhật đơn vị tiền tệ thành công!';
$lang_module['text_delete_success']         = 'Xóa đơn vị tiền tệ thành công!';
$lang_module['text_list']            = 'Danh sách tiền tệ  ';
$lang_module['text_add']             = 'Thêm tiền tệ';
$lang_module['text_edit']            = 'Sửa tiền tệ';

// Column
$lang_module['column_title']         = 'Tên tiền tệ';
$lang_module['column_code']          = 'Mã code';
$lang_module['column_value']         = 'Giá trị';
$lang_module['column_date_modified'] = 'Lần cập nhật cuối';
$lang_module['column_action']        = 'Thao tác';

// Entry
$lang_module['entry_title']          = 'Tên tiền tệ';
$lang_module['entry_code']           = 'Mã code';
$lang_module['entry_value']          = 'Giá trị';
$lang_module['entry_symbol_left']    = 'Ký hiệu trái';
$lang_module['entry_symbol_right']   = 'Ký hiệu phải';
$lang_module['entry_decimal_place']  = 'Vị trí thập phân';
$lang_module['entry_status']         = 'Trạng thái';

// Help
$lang_module['help_code']            = 'Mã code:<br /><span class="help">Không thay đổi nếu điều này là tiền tệ mặc định của bạn. Phải có giá trị <a href="http://www.xe.com/iso4217.php" target="_blank">ISO code</a>.</span>';
$lang_module['help_value']           = 'Giá trị:<br /><span class="help">Đặt 1,00000 nếu điều này là tiền tệ mặc định của bạn.</span>';

// Error
$lang_module['error_permission']     = 'Cảnh báo: Bạn không có quyền sửa đổi tiền tệ!';
$lang_module['error_title']          = 'Tên tiền tệ phải từ 3 đến 32 ký tự!';
$lang_module['error_code']           = 'Mã tiền tệ phải có 3 ký tự!';
$lang_module['error_default']        = 'Cảnh báo: Loại tiền tệ này không thể bị xóa vì nó hiện đang được sử dụng như tiền tệ mặc định!';
$lang_module['error_store']          = 'Cảnh báo: Loại tiền tệ này đang được sử dụng tại cửa hàng %s!';
$lang_module['error_order']          = 'Cảnh báo: Loại tiền tệ này không thể bị xóa vì nó  đang được sử dụng tại đơn hàng %s !';
$lang_module['error_save']          = 'Lỗi không lưu được vào cơ sở dữ liệu!';
$lang_module['error_delete']          = 'Lỗi không có đối tượng nào được xóa';
