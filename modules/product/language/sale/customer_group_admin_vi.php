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
$lang_module['heading_title']     = 'Nhóm khách hàng';

// Text
$lang_module['text_list']         = 'Danh sách nhóm khách hàng';
$lang_module['text_add']          = 'Thêm nhóm khách hàng';
$lang_module['text_edit']         = 'Sửa nhóm khách hàng';
$lang_module['text_insert_success']      = 'Thêm nhóm thành công!';
$lang_module['text_update_success']      = 'Cập nhật nhóm thành công!';
$lang_module['text_delete_success']      = 'Xoá nhóm thành công!';
$lang_module['text_change_weight_success']      = 'Thay đổi thứ tự thành công!';
$lang_module['text_change_weight_error']      = 'Lỗi: không có đối tượng nào được thay đổi!';
$lang_module['text_security_error']      = 'Lỗi bảo mật thao tác bị dừng lại';

// Column
$lang_module['column_name']       = 'Tên nhóm';
$lang_module['column_sort_order'] = 'Sắp xếp';
$lang_module['column_action']     = 'Thao tác';

// Entry
$lang_module['entry_name']        = 'Tên nhóm';
$lang_module['entry_description'] = 'Mô tả';
$lang_module['entry_approval']    = 'Phê duyệt khách hàng mới';
$lang_module['entry_sort_order']  = 'Sắp xếp';

// Help
$lang_module['help_approval']     = 'Khách hàng phải được sự chấp thuận của người quản trị trước khi có thể đăng nhập.';

// Error
$lang_module['error_permission']   = 'Cảnh báo: Bạn không có quyền sửa đổi các nhóm khách hàng!';
$lang_module['error_name']         = 'Tên nhóm phải từ 3 đến 240 ký tự!';
$lang_module['error_default']      = 'Cảnh báo: Nhóm khách hàng này không thể bị xóa vì đang được sử dụng làm nhóm hàng mặc định!';
$lang_module['error_store']        = 'Cảnh báo: nhóm khách hàng này không thể bị xóa vì nó hiện đang được giao cho %s cửa hàng!';
$lang_module['error_customer']     = 'Cảnh báo: nhóm khách hàng này không thể bị xóa vì nó hiện đang được giao cho %s khách hàng!';
$lang_module['error_save'] 		= 'Cảnh báo: Hệ thống không thể cập nhật thông tin, xin vui lòng kiểm tra xem tên nhóm khách hàng hiện hữu'; 
$lang_module['error_no_delete'] 		= 'Cảnh báo: không có nhóm khách hàng nào được xóa'; 
//$lang_module['error_warning'] 		= 'Warning: Please check the form carefully for errors!'; 