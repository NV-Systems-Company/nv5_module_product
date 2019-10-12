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
$lang_module['heading_title']     = 'Gift Vouchers';

// Text
$lang_module['text_list']         = 'Gift Voucher List';
$lang_module['text_add']          = 'Add Gift Voucher';
$lang_module['text_edit']         = 'Edit Gift Voucher';
$lang_module['text_sent']         = 'Success: Gift Voucher e-mail has been sent!';
$lang_module['text_success']      = 'Success: You have modified vouchers!';
$lang_module['text_insert_success']      = 'Thêm mã voucher thành công!';
$lang_module['text_update_success']      = 'Cập nhật mã voucher thành công!';

// Column
$lang_module['column_name']       = 'Voucher Name';
$lang_module['column_code']       = 'Code';
$lang_module['column_from']       = 'From';
$lang_module['column_to']         = 'To';
$lang_module['column_theme']      = 'Theme';
$lang_module['column_amount']     = 'Amount';
$lang_module['column_status']     = 'Status';
$lang_module['column_order_id']   = 'Order ID';
$lang_module['column_customer']   = 'Customer';
$lang_module['column_date_added'] = 'Date Added';
$lang_module['column_action']     = 'Action';

// Entry
$lang_module['entry_code']        = 'Code';
$lang_module['entry_from_name']   = 'From Name';
$lang_module['entry_from_email']  = 'From E-Mail';
$lang_module['entry_to_name']     = 'To Name';
$lang_module['entry_to_email']    = 'To E-Mail';
$lang_module['entry_theme']       = 'Theme';
$lang_module['entry_message']     = 'Message';
$lang_module['entry_amount']      = 'Amount';
$lang_module['entry_status']      = 'Status';

// Help
$lang_module['help_code']         = 'The code the customer enters to activate the voucher.';

// Error
$lang_module['error_message']		= 'Message must be between 3 and 1000 characters!';
$lang_module['error_selection']   = 'Warning: No vouchers selected!';
$lang_module['error_permission']  = 'Warning: You do not have permission to modify vouchers!';
$lang_module['error_exists']      = 'Warning: Voucher code is already in use!';
$lang_module['error_no_delete']     = 'Warning: No Voucher to delete';
$lang_module['error_code']        = 'Code must be between 3 and 10 characters!';
$lang_module['error_to_name']     = 'Recipient\'s Name must be between 1 and 64 characters!';
$lang_module['error_from_name']   = 'Your Name must be between 1 and 64 characters!';
$lang_module['error_email']       = 'E-Mail Address does not appear to be valid!';
$lang_module['error_amount']      = 'Amount must be greater than or equal to 1!';
$lang_module['error_order']       = 'Warning: This voucher cannot be deleted as it is part of an <a href="%s">order</a>!';
$lang_module['error_save']     = 'Warning: You can not modify voucher, it not save';
$lang_module['error_warning']     = 'Lỗi: Hãy kiểm tra các trường thông báo lỗi';