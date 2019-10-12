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

// Heading
$lang_module['heading_title'] = 'PayPal Express Checkout';

// Text
$lang_module['text_success'] = 'Cập nhật PayPal Express Checkout thành công!';
$lang_module['text_edit'] = 'Sửa PayPal Express Checkout';
$lang_module['text_pp_express'] = '<a target="_BLANK" href="https://www.paypal.com/uk/mrb/pal=V4T754QB63XXL"><img src="view/image/payment/paypal.png" alt="PayPal Website Payment Pro" title="PayPal Website Payment Pro iFrame" style="border: 1px solid #EEEEEE;" /></a>';
$lang_module['text_authorization'] = 'cho phép';
$lang_module['text_sale'] = 'Bán';
$lang_module['text_clear'] = 'Xóa';
$lang_module['text_browse'] = 'Duyệt';
$lang_module['text_image_manager'] = 'quản lý hình ảnh';
$lang_module['text_ipn'] = 'IPN url';

// Entry
$lang_module['entry_username'] = 'API Username';
$lang_module['entry_password'] = 'API Password';
$lang_module['entry_signature'] = 'API Signature';
$lang_module['entry_test'] = 'Chế độ (Sandbox)';
$lang_module['entry_method'] = 'Phương pháp giao dịch';
$lang_module['entry_all_zone'] = 'Tất cả Zone';
$lang_module['entry_geo_zone'] = 'Geo Zone';
$lang_module['entry_status'] = 'Trạng thái';
$lang_module['entry_sort_order'] = 'Sắp xếp';
$lang_module['entry_icon_sort_order'] = 'Icon Sort Order';
$lang_module['entry_debug'] = 'Gỡ lỗi đăng nhập';
$lang_module['entry_total'] = 'Tổng';
$lang_module['entry_currency'] = 'Tiền tệ mặc định';
$lang_module['entry_recurring_cancellation'] = 'Cho phép khách hàng để hủy bỏ thanh toán định kỳ';
$lang_module['entry_canceled_reversal_status'] = 'Trạng thái bỏ hủy';
$lang_module['entry_completed_status'] = 'Trạng thái hoàn thành';
$lang_module['entry_denied_status'] = 'Trạng thái bị từ chối';
$lang_module['entry_expired_status'] = 'Trạng thái hết hạn';
$lang_module['entry_failed_status'] = 'Trạng thái thất bại';
$lang_module['entry_pending_status'] = 'Trạng thái chưa xử lý';
$lang_module['entry_processed_status'] = 'Trạng thái xử lý';
$lang_module['entry_refunded_status'] = 'Trạng thái hoàn lại';
$lang_module['entry_reversed_status'] = 'Trạng thái hủy';
$lang_module['entry_voided_status'] = 'Trạng thái phiếu khống';
$lang_module['entry_display_checkout'] = 'Hiển thị biểu tượng thanh toán nhanh';
$lang_module['entry_allow_notes'] = 'cho phép ghi chú';
$lang_module['entry_logo'] = 'Logo';
$lang_module['entry_border_colour'] = 'Màu viền';
$lang_module['entry_header_colour'] = 'Màu nền';
$lang_module['entry_page_colour'] = 'Màu nền trang';

// Tab
$lang_module['tab_general'] = 'Thông tin chung';
$lang_module['tab_api_details'] = 'Thông tin API';
$lang_module['tab_order_status'] = 'Trạng thái đơn hàng';
$lang_module['tab_customise'] = 'Tùy chỉnh thanh toán';

// Help
$lang_module['help_ipn'] = 'Đăng ký địa chỉ ipn';
$lang_module['help_total'] = 'Việc kiểm tra tổng số đơn đặt hàng phải đạt trước khi phương thức thanh toán này sẽ được kích hoạt';
$lang_module['help_logo'] = 'Max 750px(w) x 90px(h)<br />You should only use a logo if you have SSL set up.';
$lang_module['help_colour'] = '6 ký tự mã màu HTML';
$lang_module['help_currency'] = 'Được sử dụng cho các tìm kiếm giao dịch';

// Error
$lang_module['error_permission'] = 'Cảnh báo: Bạn không có quyền chỉnh sửa thanh toán PayPal Express Checkout!';
$lang_module['error_username'] = '"API Username" cần được nhập!';
$lang_module['error_password'] = '"API Password" cần được nhập!';
$lang_module['error_signature'] = '"API Signature "cần được nhập!';
$lang_module['error_data'] = 'Dữ liệu bị mất từ yêu cầu';
$lang_module['error_timeout'] = 'Thời gian yêu cầu';
