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
$lang_module['text_list']             = 'Danh sách khách hàng';
$lang_module['text_add']              = 'Thêm khách hàng';
$lang_module['text_edit']             = 'Cập nhật thông tin khách hàng';
$lang_module['text_default']          = 'Mặc định';
$lang_module['text_balance']          = 'Balance';
$lang_module['text_add_ban_ip']       = 'Thêm Ban IP';
$lang_module['text_remove_ban_ip']    = 'Xóa Ban IP';
$lang_module['text_login']            = 'Đăng nhập vào cửa hàng';
$lang_module['text_add_transition']            = 'Thêm giao dịch';

// Column
$lang_module['column_name']           = 'Tên khách hàng';
$lang_module['column_email']          = 'E-Mail';
$lang_module['column_customer_group'] = 'Nhóm khách hàng';
$lang_module['column_status']         = 'Trạng thái';
$lang_module['column_date_added']     = 'Ngày tạo';
$lang_module['column_approval']        = 'Xác nhận';
$lang_module['column_comment']        = 'Bình luận';
$lang_module['column_description']    = 'Mô tả';
$lang_module['column_amount']         = 'Số tiền';
$lang_module['column_points']         = 'Điểm';
$lang_module['column_ip']             = 'IP';
$lang_module['column_total']          = 'Total Accounts';
$lang_module['column_action']         = 'Chức năng';

// Entry
$lang_module['entry_customer_group']  = 'Nhóm khách hàng';
$lang_module['entry_first_name']       = 'Họ đệm';
$lang_module['entry_last_name']        = 'Tên';
$lang_module['entry_gender'] = 'Giới tính';
$lang_module['entry_email']           = 'E-Mail';
$lang_module['entry_telephone']       = 'Số điện thoại';
$lang_module['entry_fax']             = 'Fax';
$lang_module['entry_newsletter']      = 'Đăng ký nhận tin';
$lang_module['entry_status']          = 'Trạng thái';
$lang_module['entry_safe']            = 'An toàn';
$lang_module['entry_username']			= 'Tài khoản';
$lang_module['entry_password']        = 'Mật khẩu';
$lang_module['entry_confirm']         = 'Xác nhận mật khẩu';
$lang_module['entry_company']         = 'Công ty';
$lang_module['entry_address_1']       = 'Địa chỉ 1';
$lang_module['entry_address_2']       = 'Địa chỉ 2';
$lang_module['entry_city']            = 'Thành phố';
$lang_module['entry_postcode']        = 'Mã bưu chính';
$lang_module['entry_country']         = 'Quốc gia';
$lang_module['entry_zone']            = 'Khu vực';
$lang_module['entry_default']         = 'Địa chỉ mặc định';
$lang_module['entry_comment']         = 'Bình luận';
$lang_module['entry_description']     = 'Mô tả';
$lang_module['entry_amount']          = 'Số tiền';
$lang_module['entry_points']          = 'Điểm';
$lang_module['entry_reward_points']          = 'Điểm thưởng';
$lang_module['entry_name']            = 'Tên khahcs hàng';
$lang_module['entry_approved']        = 'Đã được phê duyệt';
$lang_module['entry_ip']              = 'IP';
$lang_module['entry_date_added']      = 'Ngày tạo';
$lang_module['entry_transactions']			= 'Giao dịch';
$lang_module['entry_history']			= 'Lịch sử';
$lang_module['entry_ip_address']			= 'IP Addresses';
$lang_module['address']			= 'Địa chỉ';
$lang_module['add_history']			= 'Thêm lịch sử';
$lang_module['add_reward_points']			= 'Thêm điểm thưởng';

$lang_module['add_address']			= 'Thêm địa chỉ';
// Help
$lang_module['help_safe']             = 'Thiết lập để thực sự để tránh khách hàng này bị bắt bởi hệ thống chống gian lận';
$lang_module['help_points']           = 'Sử dụng trừ để loại bỏ các điểm';

// Error
$lang_module['error_warning']         = 'Lỗi: Hãy kiểm tra cẩn thận các vấn đề gây lỗi!';
$lang_module['error_save']         = 'Lỗi: Không cập nhật được nội dung!';
$lang_module['error_permission']      = 'Lỗi: Bạn không có quyền chỉnh sửa khách hàng!';
$lang_module['error_email_exists']		= 'Cành báo: Địa chỉ email này đã được đăng ký!';
$lang_module['error_first_name']       = 'Họ đệm phải có từ 1 đến 32 kí tự!';
$lang_module['error_last_name']        = 'Tên phải được từ 1 đến 32 kí tự!';
$lang_module['error_email']           = 'Địa chỉ email không hợp lệ!';
$lang_module['error_telephone']       = 'Điện thoại phải có từ 3 đến 32 ký tự!';
$lang_module['account_deny_name'] = 'Tài khoản %s banned.';
$lang_module['error_username_exist'] = 'Tên người dùng sử dụng bởi khách hàng khác. Hãy chọn tên khác';
$lang_module['error_password']        = 'Mật khẩu phải từ 4 đến 20 ký tự!';
$lang_module['error_confirm']         = 'Mật khẩu và xác nhận mật khẩu không không phù hợp!';
$lang_module['error_address_1']       = 'Địa chỉ 1 phải từ 3 đến 128 ký tự!';
$lang_module['error_city']            = 'Thành phố phải có từ 2 đến 128 ký tự!';
$lang_module['error_postcode']        = 'Mã bưu chính phải nằm giữa 2 và 10 ký tự cho đất nước này!';
$lang_module['error_country']         = 'Hãy chọn một quốc gia!';
$lang_module['error_zone']            = 'Hãy chọn một vùng / tiểu bang!';
$lang_module['error_custom_field']    = '%s cần được nhập!';
$lang_module['error_comment']         = 'Bạn phải nhập một bình luận!';
$lang_module['no_del_customer']     = 'Không có khách hàng nào được xóa ';
$lang_module['errorsave'] = 'Lỗi: Hệ thống không thể cập nhật thông tin, xin vui lòng kiểm tra xem tên người dùng tồn tại';