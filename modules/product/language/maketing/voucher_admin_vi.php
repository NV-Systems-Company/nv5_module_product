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
$lang_module['heading_title']     = 'Phiếu quà tặng';

// Text
$lang_module['text_list']         = 'Danh sách Phiếu quà tặng';
$lang_module['text_add']          = 'Thêm Phiếu quà tặng';
$lang_module['text_edit']         = 'Sửa Phiếu quà tặng';
$lang_module['text_sent']         = 'Thành công: Phiếu quà tặng e-mail đã được gửi đi';
$lang_module['text_success']      = 'Cập nhật thành công!';
$lang_module['text_insert_success']      = 'Thêm mã voucher thành công!';
$lang_module['text_update_success']      = 'Cập nhật mã voucher thành công!';

$lang_module['text_subject']  = 'Bạn đã nhận được một phiếu quà tặng từ';
$lang_module['text_from']     = 'Phiếu quà tặng này được gửi đến cho bạn từ';
$lang_module['text_message']  = 'Với thông điệp';
$lang_module['text_redeem']   = 'Để sử dụng phiếu quà tặng này, Hãy sử dụng mã <strong>%s</strong> sau đó nhấn vào các liên kết dưới đây và mua các sản phẩm mà bạn muốn sử dụng phiếu quà tặng này . Bạn có thể nhập mã phiếu quà tặng trên trang giỏ hàng trước khi bạn nhấp vào kiểm tra.';
$lang_module['text_received_gif']   = 'Xin chúc mừng, bạn đã nhận được một phiếu quà tặng trị giá %s';
$lang_module['text_footer']   = 'Vui lòng trả lời email này nếu bạn có bất kỳ câu hỏi nào.';
// Column
$lang_module['column_name']       = 'Tên người gửi';
$lang_module['column_code']       = 'Mã phiếu quà tặng';
$lang_module['column_from']       = 'Đến';
$lang_module['column_to']         = 'Tên người nhận';
$lang_module['column_theme']      = 'Chủ đề';
$lang_module['column_amount']     = 'Số tiền';
$lang_module['column_status']     = 'Trạng thái';
$lang_module['column_order_id']   = 'ID';
$lang_module['column_customer']   = 'Khách hàng';
$lang_module['column_date_added'] = 'Ngày thêm';
$lang_module['column_action']     = 'Thao tác';

// Entry
$lang_module['entry_code']        = 'Mã voucher';
$lang_module['entry_from_name']   = 'Gửi từ';
$lang_module['entry_from_email']  = 'Từ E-Mail';
$lang_module['entry_to_name']     = 'Gửi tới';
$lang_module['entry_to_email']    = 'Tới E-Mail';
$lang_module['entry_theme']       = 'Chủ đề';
$lang_module['entry_message']     = 'Tin nhắn';
$lang_module['entry_amount']      = 'Số tiền';
$lang_module['entry_status']      = 'Trạng thái';

// Help
$lang_module['help_code']         = 'Mã code để khách hàng có thể kích hoạt voucher';

// Error
$lang_module['error_message']		= 'Tin nhắn phải có từ 3 đến 1000 ký tự!';
$lang_module['error_selection']   = 'Cảnh báo: Không có voucher nào được lựa chọn!';
$lang_module['error_permission']  = 'Cảnh báo: Bạn không có quyền chỉnh sửa voucher!';
$lang_module['error_exists']      = 'Cảnh báo: Mã Voucher đã được sử dụng!';
$lang_module['error_no_delete']     = 'Cảnh báo: Không có Voucher nào được xóa';
$lang_module['error_code']        = 'Mã voucher phải từ 3 đến 10 ký tự!';
$lang_module['error_to_name']     = 'Tên của người nhận phải có từ 1 đến 64 ký tự!';
$lang_module['error_from_name']   = 'Tên của bạn phải có từ 1 đến 64 ký tự!';
$lang_module['error_email']       = 'Địa chỉ email không hợp lệ';
$lang_module['error_amount']      = 'Số tiền phải lớn hơn hoặc bằng 1!';
$lang_module['error_order']       = 'Cảnh báo: voucher này không thể bị xóa vì nó đang được sử dụng trong <a href="%s">đơn hàng</a>!';
$lang_module['error_save']     = 'Cảnh báo: Không thể cập nhật lòng kiểm tra dữ liệu nhập có thể bị trùng';
$lang_module['success_sendmail']     = 'Tin nhắn đã được gửi đi';
$lang_module['error_not_exist_voucher']     = 'Lỗi không tồn tại voucher';
$lang_module['error_warning']     = 'Lỗi: Hãy kiểm tra các trường thông báo lỗi';

