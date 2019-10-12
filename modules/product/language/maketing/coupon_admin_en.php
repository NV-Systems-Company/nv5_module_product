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
$lang_module['heading_title']       = 'Coupons';

// Text
$lang_module['text_success']        = 'Success: You have modified coupons!';
$lang_module['text_list']           = 'Coupon List';
$lang_module['text_add']            = 'Add Coupon';
$lang_module['text_edit']           = 'Edit Coupon';
$lang_module['text_percent']        = 'Percentage';
$lang_module['text_amount']         = 'Fixed Amount';

// Column
$lang_module['column_name']         = 'Coupon Name';
$lang_module['column_code']         = 'Code';
$lang_module['column_discount']     = 'Discount';
$lang_module['column_date_start']   = 'Date Start';
$lang_module['column_date_end']     = 'Date End';
$lang_module['column_status']       = 'Status';
$lang_module['column_order_id']     = 'Order ID';
$lang_module['column_customer']     = 'Customer';
$lang_module['column_amount']       = 'Amount';
$lang_module['column_date_added']   = 'Date Added';
$lang_module['column_action']       = 'Action';

// Entry
$lang_module['entry_name']          = 'Coupon Name';
$lang_module['entry_code']          = 'Code';
$lang_module['entry_type']          = 'Type';
$lang_module['entry_discount']      = 'Discount';
$lang_module['entry_logged']        = 'Customer Login';
$lang_module['entry_shipping']      = 'Free Shipping';
$lang_module['entry_total']         = 'Total Amount';
$lang_module['entry_category']      = 'Category';
$lang_module['entry_product']       = 'Products';
$lang_module['entry_date_start']    = 'Date Start';
$lang_module['entry_date_end']      = 'Date End';
$lang_module['entry_uses_total']    = 'Uses Per Coupon';
$lang_module['entry_uses_customer'] = 'Uses Per Customer';
$lang_module['entry_status']        = 'Status';

// Help
$lang_module['help_code']           = 'The code the customer enters to get the discount.';
$lang_module['help_type']           = 'Percentage or Fixed Amount.';
$lang_module['help_logged']         = 'Customer must be logged in to use the coupon.';
$lang_module['help_total']          = 'The total amount that must be reached before the coupon is valid.';
$lang_module['help_category']       = 'Choose all products under selected category.';
$lang_module['help_product']        = 'Choose specific products the coupon will apply to. Select no products to apply coupon to entire cart.';
$lang_module['help_uses_total']     = 'The maximum number of times the coupon can be used by any customer. Leave blank for unlimited';
$lang_module['help_uses_customer']  = 'The maximum number of times the coupon can be used by a single customer. Leave blank for unlimited';

// Error
$lang_module['error_permission']    = 'Warning: You do not have permission to modify coupons!';
$lang_module['error_del_permission']= 'Warning: You do not have permission to delete this coupons!';
$lang_module['error_not_exist']		= 'Warning: Coupon not exist';
$lang_module['error_exists']        = 'Warning: Coupon code is already in use!';
$lang_module['error_name']          = 'Coupon Name must be between 3 and 128 characters!';
$lang_module['error_code']          = 'Code must be between 3 and 10 characters!';
$lang_module['error_type']          = 'Warning: Type not in Percentage(P) or Fixed Amount(F)';
$lang_module['error_date_start'] 	= 'Warning: Date start must be dd/mm/YYY!';
$lang_module['error_date_end'] 		= 'Warning: Date end must be dd/mm/YYY!';
$lang_module['error_no_delete']     = 'Warning: You can not modify coupons!';