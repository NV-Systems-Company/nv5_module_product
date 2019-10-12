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
$lang_module['heading_title']							= 'Onepay ATM Card';

// Text
$lang_module['text_payment']							= 'Thanh toán';
$lang_module['text_success']							= 'Cập nhật thông tin tài khoản onepay thành công!';
$lang_module['text_onepay_atm']						='<a onclick="window.open(\'http://www.onepay.vn\');"><img src="/images/payment/onepay.png" alt="OnePAY" title="OnePAY" style="border: 1px solid #EEEEEE;" /></a>';
$lang_module['text_authorization']					= 'cho phép';
$lang_module['text_sale']								= 'bán hàng';

// Entry
$lang_module['entry_url_paygate']					 = 'Url PayGate:';
$lang_module['entry_url_return']					 = 'Url Return:<br/><span class="help">Url receive payment result</span>';
$lang_module['entry_merchant_id']					 = 'Merchant ID:';
$lang_module['entry_access_code']					 = 'Access Code:';
$lang_module['entry_hash_code']					 = 'Hash Code:';

// Entry
$lang_module['entry_email']							= 'E-Mail:';
$lang_module['entry_test']							= 'Chế độ thử nghiệm:';
$lang_module['entry_transaction']						= 'Phương pháp giao dịch:';
$lang_module['entry_geo_zone']						= 'Vùng địa lý:';
$lang_module['entry_status']							= 'Trạng thái:';
$lang_module['entry_sort_order']						= 'Sắp xếp:';
$lang_module['entry_pdt_token']						= 'PDT Token:<br/><span class="help">Payment Data Transfer Token is used for additional security and reliability. Find out how to enable PDT <a href="https://cms.paypal.com/us/cgi-bin/?&cmd=_render-content&content_ID=developer/howto_html_paymentdatatransfer" alt="">here</a></span>';
$lang_module['entry_itemized']						= 'Itemize Products:<br/><span class="help">Show itemized list of products on Paypal invoice instead of store name.</span>';
$lang_module['entry_debug']							= 'Debug Mode:<br/>Log thông tin đăng nhập hệ thống';

$lang_module['entry_help_status_completed']					= 'This is the status set when the payment has been completed successfully.';
$lang_module['entry_help_status_pending']			= 'The payment is pending; see the pending_reason variable for more information. Please note, you will receive another Instant Payment Notification when the status of the payment changes to Completed, Failed, or Denied.';
$lang_module['entry_help_status_denied']				= 'You, the merchant, denied the payment. This will only happen if the payment was previously pending due to one of the following pending reasons.';
$lang_module['entry_help_status_failed']				= 'The payment has failed. This will only happen if the payment was attempted from your customers bank account.';
$lang_module['entry_help_status_refunded']			= 'You, the merchant, refunded the payment.';
$lang_module['entry_help_status_canceled_reversal']	= 'This means a reversal has been canceled; for example, you, the merchant, won a dispute with the customer and the funds for the transaction that was reversed have been returned to you';
$lang_module['entry_help_status_reversed']			= 'This means that a payment was reversed due to a chargeback or other type of reversal. The funds have been debited from your account balance and returned to the customer. The reason for the reversal is given by the reason_code variable.';
$lang_module['entry_help_status_unspecified']		= 'Order Status Unspecified Error:';

$lang_module['entry_completed_status']		= 'Trạng thái thanh toán hoàn thành:';
$lang_module['entry_failed_status']		= 'Trạng thái thanh toán thất bại:';
$lang_module['entry_pending_status']		= 'Trạng thái chờ thanh toán:';
$lang_module['entry_order_status_unspecified']		= 'Order Status Unspecified Error:';
$lang_module['entry_completed_status']		= 'Trạng thái thanh toán hoàn thành:';
$lang_module['entry_failed_status']		= 'Trạng thái thanh toán thất bại:';
$lang_module['entry_pending_status']		= 'Trạng thái chờ thanh toán:';
$lang_module['entry_zone']   = 'Vùng địa lý';
$lang_module['entry_all_zone']   = 'Tất cả khu vực';
// Error
$lang_module['error_permission']						= 'Bạn không có quyền truy cập Onepay!';
$lang_module['error_email']							= 'E-Mail bắt buộc!';
$lang_module['error_url_paygate']						= 'Yêu cầu nhập  Url paygate';
$lang_module['error_merchant_id']						= 'Yêu cầu nhập  Merchant Id';
$lang_module['error_access_code']						= 'Yêu cầu nhập  Access code';
$lang_module['error_hash_code']						= 'Yêu cầu nhập  Hash code';

/* coupon */
$lang_module['text_coupon_success']     = 'Thành công: Phiếu giảm giá đã được áp dụng!';

// Error
//$lang_module['error_permission'] = 'Warning: You do not have permission to access the API!';
$lang_module['error_coupon']     = 'Lỗi: Phiếu giảm giá này không hợp lệ, hết hạn hoặc hết hạn chế sử dụng!';
 