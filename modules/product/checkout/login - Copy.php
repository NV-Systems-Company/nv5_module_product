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
 
// Kiểm tra và đăng nhập thành viên
if( $nv_Request->isset_request( 'save', 'get,post' ) )
{ 
	$json = array();

	if( empty( $ProductContent ) ) $ProductContent = new shops_product( $productRegistry );

	if( ! empty( $user_info ) )
	{
		$json['redirect'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout', true );
	}

	if( ! $ProductContent->hasProducts() && empty( $_SESSION[$module_data . '_vouchers'] ) || ( ! $ProductContent->hasStock() && ! $ProductGeneral->config['config_stock_checkout'] ) )
	{
		$json['redirect'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart', true );
	}

	if( ! $json )
	{

		$row = '';
		if( $nv_Request->isset_request( 'nv_login', 'post' ) )
		{
			$nv_username = $nv_Request->get_title( 'nv_login', 'post', '', 1 );
			$nv_password = $nv_Request->get_title( 'nv_password', 'post', '' );
			$nv_seccode = $nv_Request->get_title( 'nv_seccode', 'post', '' );

			$check_seccode = ! $gfx_chk ? true : ( nv_capcha_txt( $nv_seccode ) ? true : false );

			if( ! $check_seccode )
			{
				$json['error']['warning'] = $lang_global['securitycodeincorrect'];
			}
			elseif( empty( $nv_username ) )
			{
				$json['error']['warning'] = $lang_global['username_empty'];
			}
			elseif( empty( $nv_password ) )
			{
				$json['error']['warning'] = $lang_global['password_empty'];
			}
			else
			{
				if( defined( 'NV_IS_USER_FORUM' ) )
				{
					require_once NV_ROOTDIR . '/' . DIR_FORUM . '/nukeviet/login.php' ;
				}
				else
				{
					$json['error']['warning'] = $lang_global['loginincorrect'];
					if( nv_check_valid_email( $nv_username ) == '' )
					{
						$sql = "SELECT * FROM " . NV_USERS_GLOBALTABLE . " WHERE email =" . $db->quote( $nv_username );
						$login_email = true;
					}
					else
					{
						$sql = "SELECT * FROM " . NV_USERS_GLOBALTABLE . " WHERE md5username ='" . nv_md5safe( $nv_username ) . "'";
						$login_email = false;
					}
					$row = $db->query( $sql )->fetch();
					if( ! empty( $row ) )
					{
						if( ( ( $row['username'] == $nv_username and $login_email == false ) or ( $row['email'] == $nv_username and $login_email == true ) ) and $crypt->validate_password( $nv_password, $row['password'] ) )
						{
							if( ! $row['active'] )
							{
								$json['error']['warning'] = $lang_module['login_no_active'];
							}
							else
							{
								validUserLog( $row, 1, '' );
							}
						}
					}
				}
			}

		}

		if( ! $json )
		{
			unset( $_SESSION[$module_data . '_guest'] );

			$userid = $row['userid'];

			if( $ProductGeneral->config['config_tax_customer'] == 'payment' )
			{
				$address_data = array();

				$_SESSION[$module_data . '_payment_addess'] = getAddress( $address_id, $userid );
			}

			if( $ProductGeneral->config['config_tax_customer'] == 'shipping' )
			{
				$_SESSION[$module_data . '_shipping_addess'] = getAddress( $address_id, $userid );
			}

			$json['redirect'] = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=checkout', true );

		}

	}
	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}
 
$data = array();

$contents = checkout_login( $data, $lang_ext );

echo $contents;
exit();