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
$lang_module['heading_title'] = 'Khu vực';
// Text
$lang_module['text_success'] = 'thành công: Khu vực đã được cập nhật thành công: !';
$lang_module['text_error'] = 'Cảnh báo: bBạn không thể chỉnh sửa khu vực!';
$lang_module['text_list'] = 'Danh sách khu vực';
$lang_module['text_add'] = 'Thêm khu vực';
$lang_module['text_edit'] = 'Sửa khu vực';
 

// Column
$lang_module['column_name'] = 'Tên khu vực';
$lang_module['column_code'] = 'Mã khu vực';
$lang_module['column_country'] = 'Quốc gia';
$lang_module['column_action'] = 'Chức năng';

// Entry
$lang_module['entry_status'] = 'Trạng thái khu vực';
$lang_module['entry_name'] = 'Tên khu vực';
$lang_module['entry_code'] = 'Mã khu vực';
$lang_module['entry_country'] = 'Quốc gia';

// Error
$lang_module['error_permission'] = 'Cảnh báo: Bạn không có quyền chỉnh sửa zones!';
$lang_module['error_name'] = 'Tên khu vực phải có từ 3 đến 128 ký tự!';
$lang_module['error_default'] = 'Cảnh báo: Khu vực này không thể bị xóa vì nó hiện đang được sử dụng là vùng lưu trữ mặc định!';
$lang_module['error_store'] = 'Cảnh báo: Khu vực này không thể bị xóa vì nó hiện đang được sử dụng tại %s của hàng!';
$lang_module['error_address'] = 'Cảnh báo: Khu vực này không thể bị xóa vì nó hiện đang được sử dụng tại %s sổ địa chỉ!';
$lang_module['error_affiliate'] = 'Cảnh báo: Khu vực này không thể bị xóa vì nó hiện đang được sử dụng tại %s chi nhánh!';
$lang_module['error_to_geo_zone'] = 'Cảnh báo: Khu vực này không thể bị xóa vì nó hiện đang được sử dụng tại %s zones to geo zones!';
$lang_module['error_no_del_to_geo_zone'] = 'Cảnh báo: Không có khu vực nào bị xóa!';
