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

if( ! defined( 'NV_SYSTEM' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );


// Heading
$lang_ext['heading_title']                  = 'Checkout';

// Text
$lang_ext['text_cart']                      = 'Shopping Cart';
$lang_ext['text_checkout_option']           = 'Step 1: Checkout Options';
$lang_ext['text_checkout_account']          = 'Step 2: Account &amp; Billing Details';
$lang_ext['text_checkout_payment_address']  = 'Step 2: Billing Details';
$lang_ext['text_checkout_shipping_address'] = 'Step 3: Delivery Details';
$lang_ext['text_checkout_shipping_method']  = 'Step 4: Delivery Method';
$lang_ext['text_checkout_payment_method']   = 'Step 5: Payment Method';
$lang_ext['text_checkout_confirm']          = 'Step 6: Confirm Order';
$lang_ext['text_modify']                    = 'Modify &raquo;';
$lang_ext['text_new_customer']              = 'New Customer';
$lang_ext['text_returning_customer']        = 'Returning Customer';
$lang_ext['text_checkout']                  = 'Checkout Options:';
$lang_ext['text_i_am_returning_customer']   = 'I am a returning customer';
$lang_ext['text_register']                  = 'Register Account';
$lang_ext['text_guest']                     = 'Guest Checkout';
$lang_ext['text_register_account']          = 'By creating an account you will be able to shop faster, be up to date on an order\'s status, and keep track of the orders you have previously made.';
$lang_ext['text_forgotten']                 = 'Forgotten Password';
$lang_ext['text_your_details']              = 'Your Personal Details';
$lang_ext['text_your_address']              = 'Your Address';
$lang_ext['text_your_password']             = 'Your Password';
$lang_ext['text_agree']                     = 'I have read and agree to the <a href="%s" class="agree"><b>%s</b></a>';
$lang_ext['text_address_new']               = 'I want to use a new address';
$lang_ext['text_address_existing']          = 'I want to use an existing address';
$lang_ext['text_shipping_method']           = 'Please select the preferred shipping method to use on this order.';
$lang_ext['text_payment_method']            = 'Please select the preferred payment method to use on this order.';
$lang_ext['text_comments']                  = 'Add Comments About Your Order';
$lang_ext['text_recurring']                 = 'Recurring item';
$lang_ext['text_payment_recurring']           = 'Payment Profile';
$lang_ext['text_trial_description']         = '%s every %d %s(s) for %d payment(s) then';
$lang_ext['text_payment_description']       = '%s every %d %s(s) for %d payment(s)';
$lang_ext['text_payment_until_canceled_description'] = '%s every %d %s(s) until canceled';
$lang_ext['text_day']                       = 'day';
$lang_ext['text_week']                      = 'week';
$lang_ext['text_semi_month']                = 'half-month';
$lang_ext['text_month']                     = 'month';
$lang_ext['text_year']                      = 'year';

// Column
$lang_ext['column_name']                    = 'Product Name';
$lang_ext['column_model']                   = 'Model';
$lang_ext['column_quantity']                = 'Quantity';
$lang_ext['column_price']                   = 'Unit Price';
$lang_ext['column_total']                   = 'Total';

// Entry
$lang_ext['entry_email_address']            = 'E-Mail Address';
$lang_ext['entry_email']                    = 'E-Mail';
$lang_ext['entry_password']                 = 'Password';
$lang_ext['entry_confirm']                  = 'Password Confirm';
$lang_ext['entry_first_name']                = 'First Name';
$lang_ext['entry_last_name']                 = 'Last Name';
$lang_ext['entry_telephone']                = 'Telephone';
$lang_ext['entry_fax']                      = 'Fax';
$lang_ext['entry_address']                  = 'Choose Address';
$lang_ext['entry_company']                  = 'Company';
$lang_ext['entry_customer_group']           = 'Customer Group';
$lang_ext['entry_address_1']                = 'Address 1';
$lang_ext['entry_address_2']                = 'Address 2';
$lang_ext['entry_postcode']                 = 'Post Code';
$lang_ext['entry_city']                     = 'City';
$lang_ext['entry_country']                  = 'Country';
$lang_ext['entry_zone']                     = 'Region / State';
$lang_ext['entry_newsletter']               = 'I wish to subscribe to the %s newsletter.';
$lang_ext['entry_shipping'] 	             = 'My delivery and billing addresses are the same.';

// Error
$lang_ext['error_warning']                  = 'There was a problem while trying to process your order! If the problem persists please try selecting a different payment method or you can contact the store owner by <a href="%s">clicking here</a>.';
$lang_ext['error_login']                    = 'Warning: No match for E-Mail Address and/or Password.';
$lang_ext['error_approved']                 = 'Warning: Your account requires approval before you can login.';
$lang_ext['error_exists']                   = 'Warning: E-Mail Address is already registered!';
$lang_ext['error_first_name']                = 'First Name must be between 1 and 32 characters!';
$lang_ext['error_last_name']                 = 'Last Name must be between 1 and 32 characters!';
$lang_ext['error_email']                    = 'E-Mail address does not appear to be valid!';
$lang_ext['error_telephone']                = 'Telephone must be between 3 and 32 characters!';
$lang_ext['error_password']                 = 'Password must be between 3 and 20 characters!';
$lang_ext['error_confirm']                  = 'Password confirmation does not match password!';
$lang_ext['error_address_1']                = 'Address 1 must be between 3 and 128 characters!';
$lang_ext['error_city']                     = 'City must be between 2 and 128 characters!';
$lang_ext['error_postcode']                 = 'Postcode must be between 2 and 10 characters!';
$lang_ext['error_country']                  = 'Please select a country!';
$lang_ext['error_zone']                     = 'Please select a region / state!';
$lang_ext['error_agree']                    = 'Warning: You must agree to the %s!';
$lang_ext['error_address']                  = 'Warning: You must select address!';
$lang_ext['error_shipping']                 = 'Warning: Shipping method required!';
$lang_ext['error_no_shipping']              = 'Warning: No Shipping options are available. Please <a href="%s">contact us</a> for assistance!';
$lang_ext['error_payment']                  = 'Warning: Payment method required!';
$lang_ext['error_no_payment']               = 'Warning: No Payment options are available. Please <a href="%s">contact us</a> for assistance!';
$lang_ext['error_custom_field']             = '%s required!';