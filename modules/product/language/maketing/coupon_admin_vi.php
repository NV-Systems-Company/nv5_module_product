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
$lang_module['heading_title']       = 'Phiếu giảm giá';

// Text
$lang_module['text_insert_success']        = 'Thêm phiếu giảm giá thành công';
$lang_module['text_update_success']        = 'Sửa phiếu giảm giá thành công';
$lang_module['text_delete_success']        = 'Xóa phiếu giảm giá thành công';
$lang_module['text_list']           = 'Danh sách phiếu giảm giá';
$lang_module['text_add']            = 'Thêm phiếu giảm giá';
$lang_module['text_edit']           = 'Sửa phiếu giảm giá';
$lang_module['text_percent']        = 'Phần trăm';
$lang_module['text_amount']         = 'Giá cố định';

// Column
$lang_module['column_name']         = 'Tên phiếu giảm giá';
$lang_module['column_code']         = 'Mã giảm giá';
$lang_module['column_discount']     = 'Giảm giá';
$lang_module['column_date_start']   = 'Ngày bắt đầu';
$lang_module['column_date_end']     = 'Ngày kết thúc';
$lang_module['column_status']       = 'Tình trạng';
$lang_module['column_order_id']     = 'Thứ tự ID';
$lang_module['column_customer']     = 'Khách hàng';
$lang_module['column_amount']       = 'Số tiền';
$lang_module['column_date_added']   = 'Ngày thêm';
$lang_module['column_action']       = 'Thao tác';

// Entry
$lang_module['entry_name']          = 'Tên phiếu giảm giá';
$lang_module['entry_code']          = 'Mã giảm giá';
$lang_module['entry_type']          = 'Tùy chọn';
$lang_module['entry_discount']      = 'Giảm giá';
$lang_module['entry_logged']        = 'Đăng nhập khách hàng';
$lang_module['entry_shipping']      = 'Miễn phí vận chuyển';
$lang_module['entry_total']         = 'Tổng tiền ';
$lang_module['entry_category']      = 'Chuyên mục';
$lang_module['entry_product']       = 'Sản phẩm';
$lang_module['entry_date_start']    = 'Ngày bắt đầu';
$lang_module['entry_date_end']      = 'Ngày kết thúc';
$lang_module['entry_uses_total']    = 'Sử dụng 1 phiếu giảm giá';
$lang_module['entry_uses_customer'] = 'Sử dụng cho 1  khách hàng';
$lang_module['entry_status']        = 'Tình trạng';

// Help
$lang_module['help_code']           = 'Các mã khách hàng đi vào để có được giảm giá';
$lang_module['help_type']           = 'Tỷ lệ phần trăm hoặc số tiền giảm.';
$lang_module['help_logged']         = 'Khách hàng phải đăng nhập để sử dụng phiếu giảm giá';
$lang_module['help_total']          = 'Tổng số tiền cần phải đạt được trước khi các phiếu giảm giá là hợp lệ';
$lang_module['help_category']       = 'Chọn tất cả các sản phẩm thuộc danh mục đã chọn';
$lang_module['help_product']        = 'Chọn các sản phẩm cụ thể các phiếu giảm giá sẽ được áp dụng để. Chọn các sản phẩm không áp dụng phiếu giảm giá cho toàn bộ giỏ hàng.';
$lang_module['help_uses_total']     = 'Số lần tối đa các phiếu giảm giá có thể được sử dụng bởi bất kỳ khách hàng. Để trống cho không giới hạn';
$lang_module['help_uses_customer']  = 'Số lần tối đa các phiếu giảm giá có thể được sử dụng bởi một khách hàng duy nhất. Để trống cho không giới hạn';

// Error
$lang_module['error_permission']    = 'Cảnh báo: Bạn không có quyền sửa đổi phiếu giảm giá';
$lang_module['error_del_permission']= 'Cảnh báo: Bạn không có quyền xóa các phiếu giảm giá này';
$lang_module['error_not_exist']		= 'Cảnh báo: Phiếu giảm giá không tồn tại';
$lang_module['error_exists']        = 'Cảnh báo: Mã phiếu giảm giá đã được sử dụng';
$lang_module['error_name']          = 'Tên phải là giữa 3 và 128 ký tự';
$lang_module['error_code']          = 'Mã phải có từ 3 đến 10 ký tự!';
$lang_module['error_type']          = 'Cảnh báo: Kiểu lựa chọn không nằm trong Phần trăm(P) hoặc Giá cố định(F)';
$lang_module['error_date_start'] 	 = 'Cảnh báo: Ngày bắt đầu phải là dd/mm/YYY!';
$lang_module['error_date_end']		 = 'Cảnh báo: Ngày cuối cùng phải là dd/mm/YYY';
$lang_module['error_no_delete']        = 'Cảnh báo: Bạn có thể không sửa đổi phiếu giảm giá';