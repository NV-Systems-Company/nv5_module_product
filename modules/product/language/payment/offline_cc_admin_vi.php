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

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$lang_module['heading_title']      = 'Offline Credit Card Payment';

// Text 
$lang_module['text_edit']       = 'Sửa Thanh toán tín dụng tại cửa hàng';
$lang_module['text_payment']       = 'Thanh toán';
$lang_module['text_success']       = 'Cập nhật Offline Credit Card Payment thành công!';
$lang_module['text_authorization'] = 'Cho phép';
$lang_module['text_capture']       = 'Chụp lại';

// Entry
$lang_module['entry_total']        = 'Tổng:';
$lang_module['entry_order_status'] = 'Trạng thái:';
$lang_module['entry_geo_zone']     = 'Geo Zone:'; 
$lang_module['entry_all_zone']     = 'Tất cả Zone'; 
$lang_module['entry_status']       = 'Trạng thái:';
$lang_module['entry_sort_order']   = 'Sắp xếp:';
$lang_module['entry_email']   	 = 'Tài khoản email CC:';
$lang_module['entry_encryption']   = 'Khóa mã hóa:';
$lang_module['entry_save_card_name']         = 'Lưu lại tên Credit Card riêng biệt từ tên thanh toán?';
$lang_module['entry_save_card_type']         = 'Lưu lại kiểu Credit Card ?';
$lang_module['entry_accept_credit_card']         = 'Chấp nhận Credit Cards';
$lang_module['entry_select']         = 'Hãy lựa chọn ';
$lang_module['entry_safe_password']         = 'Tạo mật khẩu an toàn';
$lang_module['help_total']         = 'Việc kiểm tra tổng số đơn đặt hàng phải đạt trước khi phương thức thanh toán này sẽ được kích hoạt.';

//lang cc
$lang_module['cc_visa'] = 'Visa';
$lang_module['cc_masterCard'] = 'MasterCard';
$lang_module['cc_american_express'] = 'American Express';
$lang_module['cc_china_unionPay'] = 'China UnionPay';
$lang_module['cc_jsb'] = 'JCB';


// Error 
$lang_module['error_permission']   = 'Warning: You do not have permission to modify payment Authorize.Net (SIM)!';
$lang_module['error_geo_zone']        = 'Geo Zone Required!';
$lang_module['error_email']        = 'Tài khoản email CC cần được nhập!';
$lang_module['error_encryption']        = 'Khóa mã hóa cần được nhập!';
$lang_module['error_login']        = 'ID đăng nhập cần được nhập!';
$lang_module['error_total']        = 'Tổng giá cần được nhập!';
$lang_module['error_order_status']        = 'Trạng thái đơn hàng cần được nhập!';
$lang_module['error_key']          = 'Khóa giao dịch cần được nhập!';
$lang_module['error_hash']         = 'MD5 Hash Cần được nhập!';