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

// gọi hàm liên quan tới địa chỉ khách hàng
require_once NV_ROOTDIR . '/modules/' . $module_file . '/global/address.php';

// lấy thông tin zone
if( ACTION_METHOD == 'zone' )
{
	$info = array();
	$country_id = $nv_Request->get_int( 'country_id', 'get', 0 );
	$getCountry = getCountry();
	$info = $getCountry[$country_id];

	$sql = 'SELECT zone_id, code, status, name FROM ' . TABLE_PRODUCT_NAME . '_zone WHERE country_id=' . $country_id;
	$result = $db->query( $sql );

	while( list( $_zone_id, $code, $status, $name ) = $result->fetch( 3 ) )
	{
		$info['zone'][] = array(
			'code' => $code,
			'country_id' => $country_id,
			'status' => $status,
			'name' => nv_htmlspecialchars( $name ),
			'zone_id' => $_zone_id );

	}

	header( 'Content-Type: application/json' );
	echo json_encode( $info );
	exit();
}

// Đăng ký thành viên thông thường
if( $nv_Request->isset_request( 'save', 'get,post' ) )
{

	function nv_check_username_reg( $login )
	{
		global $db, $db_config, $lang_module;

		$error = nv_check_valid_login( $login, NV_UNICKMAX, NV_UNICKMIN );
		if( $error != '' ) return preg_replace( '/\&(l|r)dquo\;/', '', strip_tags( $error ) );
		if( "'" . $login . "'" != $db->quote( $login ) ) return sprintf( $lang_module['account_deny_name'], '<strong>' . $login . '</strong>' );

		$sql = "SELECT content FROM " . NV_USERS_GLOBALTABLE . "_config WHERE config='deny_name'";
		$result = $db->query( $sql );
		$deny_name = $result->fetchColumn();
		$result->closeCursor();

		if( ! empty( $deny_name ) and preg_match( '/' . $deny_name . '/i', $login ) ) return sprintf( $lang_module['account_deny_name'], '<strong>' . $login . '</strong>' );

		$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE md5username= :md5username' );
		$stmt->bindValue( ':md5username', nv_md5safe( $login ), PDO::PARAM_STR );
		$stmt->execute();
		if( $stmt->fetchColumn() ) return sprintf( $lang_module['account_registered_name'], '<strong>' . $login . '</strong>' );

		$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE md5username= :md5username' );
		$stmt->bindValue( ':md5username', nv_md5safe( $login ), PDO::PARAM_STR );
		$stmt->execute();
		if( $stmt->fetchColumn() ) return sprintf( $lang_module['account_registered_name'], '<strong>' . $login . '</strong>' );

		return '';
	}

	function nv_check_email_reg( $email )
	{
		global $db, $db_config, $lang_module;

		$error = nv_check_valid_email( $email );
		if( $error != '' ) return preg_replace( '/\&(l|r)dquo\;/', '', strip_tags( $error ) );

		$sql = "SELECT content FROM " . NV_USERS_GLOBALTABLE . "_config WHERE config='deny_email'";
		$result = $db->query( $sql );
		$deny_email = $result->fetchColumn();
		$result->closeCursor();

		if( ! empty( $deny_email ) and preg_match( '/' . $deny_email . '/i', $email ) ) return sprintf( $lang_module['email_deny_name'], $email );

		list( $left, $right ) = explode( '@', $email );
		$left = preg_replace( '/[\.]+/', '', $left );
		$pattern = str_split( $left );
		$pattern = implode( '.?', $pattern );
		$pattern = '^' . $pattern . '@' . $right . '$';

		$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE email RLIKE :pattern' );
		$stmt->bindParam( ':pattern', $pattern, PDO::PARAM_STR );
		$stmt->execute();
		if( $stmt->fetchColumn() ) return sprintf( $lang_module['email_registered_name'], $email );

		$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE email RLIKE :pattern' );
		$stmt->bindParam( ':pattern', $pattern, PDO::PARAM_STR );
		$stmt->execute();
		if( $stmt->fetchColumn() ) return sprintf( $lang_module['email_registered_name'], $email );

		$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE email RLIKE :pattern' );
		$stmt->bindParam( ':pattern', $pattern, PDO::PARAM_STR );
		$stmt->execute();
		if( $stmt->fetchColumn() ) return sprintf( $lang_module['email_registered_name'], $email );

		return '';
	}

	$json = array();

	$data['first_name'] = $nv_Request->get_title( 'first_name', 'post', '', 1 );
	$data['last_name'] = $nv_Request->get_title( 'last_name', 'post', '', 1 );
	$data['username'] = $data['email'] = $nv_Request->get_title( 'email', 'post', '', 1 );
	$data['telephone'] = $nv_Request->get_title( 'telephone', 'post', '', 1 );
	$data['fax'] = $nv_Request->get_title( 'fax', 'post', '', 1 );
	$data['company'] = $nv_Request->get_title( 'company', 'post', '', 1 );
	$data['address_1'] = $nv_Request->get_title( 'address_1', 'post', '', 1 );
	$data['address_2'] = $nv_Request->get_title( 'address_2', 'post', '', 1 );
	$data['password'] = $nv_Request->get_title( 'password', 'post', '', 1 );
	$data['confirm'] = $nv_Request->get_title( 'confirm', 'post', '', 1 );
	$data['city'] = $nv_Request->get_title( 'city', 'post', '', 1 );
	$data['postcode'] = $nv_Request->get_title( 'postcode', 'post', '', 1 );
	$data['shipping_address'] = $nv_Request->get_title( 'shipping_address', 'post', '', 1 );
	$data['agree'] = $nv_Request->get_int( 'agree', 'post', 0 );
	$data['newsletter'] = $nv_Request->get_int( 'newsletter', 'post', 0 );
	$data['zone_id'] = $nv_Request->get_int( 'zone_id', 'post', 0 );
	$data['country_id'] = $nv_Request->get_int( 'country_id', 'post', 0 );
	$data['customer_group_id'] = $nv_Request->get_int( 'customer_group_id', 'post', 0 );
	$capcha = $nv_Request->get_title( 'capcha', 'post', '' );

	$capcha = ! $gfx_chk ? true : ( nv_capcha_txt( $capcha ) ? true : false );

	if( ! $capcha )
	{
		$json['error']['capcha'] = $lang_global['securitycodeincorrect'];
	}

	if( ( ( $check_login = nv_check_username_reg( $data['username'] ) ) ) != '' )
	{
		$json['error']['email'] = $check_login;
	}
	if( ( nv_strlen( $data['password'] ) < 4 ) || ( nv_strlen( $data['password'] ) > 20 ) )
	{
		$json['error']['password'] = $lang_ext['error_password'];
	}
	else
	{
		if( ( $check_pass = nv_check_valid_pass( $data['password'], NV_UPASSMAX, NV_UPASSMIN ) ) != '' )
		{
			$json['error']['password'] = $check_pass;
		}
	}

	if( ( nv_strlen( trim( $data['first_name'] ) ) < 1 ) || ( nv_strlen( trim( $data['first_name'] ) ) > 32 ) )
	{
		$json['error']['first_name'] = $lang_ext['error_first_name'];
	}

	if( ( nv_strlen( trim( $data['last_name'] ) ) < 1 ) || ( nv_strlen( trim( $data['last_name'] ) ) > 32 ) )
	{
		$json['error']['last_name'] = $lang_ext['error_last_name'];
	}

	if( ( nv_strlen( $data['email'] ) > 96 ) || ! preg_match( '/^[^\@]+@.*.[a-z]{2,15}$/i', $data['email'] ) )
	{
		$json['error']['email'] = $lang_ext['error_email'];
	}else
	{
		if( ( $check_email = nv_check_email_reg( $data['email'] ) ) != '' )
		{
			$json['error']['email'] = $check_email;
		}
	}
 
	if( ( nv_strlen( $data['telephone'] ) < 3 ) || ( nv_strlen( $data['telephone'] ) > 32 ) )
	{
		$json['error']['telephone'] = $lang_ext['error_telephone'];
	}

	if( ( nv_strlen( trim( $data['address_1'] ) ) < 3 ) || ( nv_strlen( trim( $data['address_1'] ) ) > 128 ) )
	{
		$json['error']['address_1'] = $lang_ext['error_address_1'];
	}

	if( ( nv_strlen( trim( $data['city'] ) ) < 2 ) || ( nv_strlen( trim( $data['city'] ) ) > 128 ) )
	{
		$json['error']['city'] = $lang_ext['error_city'];
	}

	$getCountry = getCountry();

	if( $getCountry[$data['country_id']] && $getCountry[$data['country_id']]['postcode_required'] && ( nv_strlen( trim( $data['postcode'] ) ) < 2 || nv_strlen( trim( $data['postcode'] ) ) > 10 ) )
	{
		$json['error']['postcode'] = $lang_ext['error_postcode'];
	}

	if( $data['country_id'] == '' )
	{
		$json['error']['country'] = $lang_ext['error_country'];
	}

	if( ! isset( $data['zone_id'] ) || $data['zone_id'] == '' )
	{
		$json['error']['zone'] = $lang_ext['error_zone'];
	}

	if( isset( $data['customer_group_id'] ) && is_array( $ProductGeneral->config['config_customer_group_display'] ) && in_array( $data['customer_group_id'], $ProductGeneral->config['config_customer_group_display'] ) )
	{
		$customer_group_id = $data['customer_group_id'];
	}
	else
	{
		$customer_group_id = $ProductGeneral->config['config_customer_group_id'];
	}
 
	if( $data['confirm'] != $data['password'] )
	{
		$json['error']['confirm'] = $lang_ext['error_confirm'];
	}
	
	if ( $ProductGeneral->config['config_account_id']) 
	{
			
		$getInformation = getInformation();

		$information_info = $getInformation[$ProductGeneral->config['config_account_id']];

		if ( $information_info && !isset( $data['agree'] ) ) 
		{
			$json['error']['warning'] = sprintf( $lang_ext['error_agree'], $information_info['title'] );
		}
	}

	if( ! isset( $json['error'] ) )
	{
		$password = $crypt->hash_password( $data['password'], $global_config['hashprefix'] );
		$checknum = nv_genpass( 10 );
		$checknum = md5( $checknum );
		$your_question = '';
		$answer = '';
		if( $global_config['allowuserreg'] == 2 or $global_config['allowuserreg'] == 3 )
		{
			$sql = "INSERT INTO " . NV_USERS_GLOBALTABLE . "_reg (username, md5username, password, email, first_name, last_name, regdate, question, answer, checknum, users_info, customer_group_id, store_id, telephone, fax, newsletter) VALUES (
					:username,
					:md5username,
					:password,
					:email,
					:first_name,
					:last_name,
					" . NV_CURRENTTIME . ",
					:your_question,
					:answer,
					:checknum,
					:users_info,
					" . intval( $customer_group_id ) . ",
					" . intval( $shops_config->config_store_id ) . ",
					:telephone,
					:fax,
					" . intval( $data['newsletter'] ) . "
				)";
			$data_insert = array();
			$data_insert['username'] = $data['email'];
			$data_insert['md5username'] = nv_md5safe( $data['email'] );
			$data_insert['password'] = $password;
			$data_insert['email'] = $data['email'];
			$data_insert['first_name'] = $data['first_name'];
			$data_insert['last_name'] = $data['last_name'];
			$data_insert['your_question'] = $your_question;
			$data_insert['answer'] = $answer;
			$data_insert['checknum'] = $checknum;
			$data_insert['users_info'] = nv_base64_encode( serialize( $query_field ) );
			$data_insert['telephone'] = $data['telephone'];
			$data_insert['fax'] = $data['fax'];
			$userid = $db->insert_id( $sql, 'userid', $data_insert );

			if( ! $userid )
			{
				$json['error']['warning'] = $lang_module['err_no_save_account'];
			}
			else
			{
				$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_address SET 
					userid = 0, 
					customer_temp_id = ' . intval( $userid ) . ', 
					temp = 1, 
					first_name = :first_name,
					last_name = :last_name, 
					company = :company, 
					address_1 = :address_1, 
					address_2= :address_2, 
					city= :city, 
					postcode= :postcode, 
					country_id = ' . intval( $data['country_id'] ) . ', 
					zone_id = ' . intval( $data['zone_id'] ) . ', 
					custom_field= :custom_field ' );

				if( ! isset( $data['custom_field'] ) )
				{
					$data['custom_field'] = array();
				}
				$sth->bindParam( ':first_name', $data['first_name'], PDO::PARAM_STR );
				$sth->bindParam( ':last_name', $data['last_name'], PDO::PARAM_STR );
				$sth->bindParam( ':company', $data['company'], PDO::PARAM_STR );
				$sth->bindParam( ':address_1', $data['address_1'], PDO::PARAM_STR );
				$sth->bindParam( ':address_2', $data['address_2'], PDO::PARAM_STR );
				$sth->bindParam( ':city', $data['city'], PDO::PARAM_STR );
				$sth->bindParam( ':postcode', $data['postcode'], PDO::PARAM_STR );
				$sth->bindParam( ':custom_field', serialize( $data['custom_field'] ), PDO::PARAM_STR );
				$sth->execute();
				$address_id = $db->lastInsertId();

			}

			if( $global_config['allowuserreg'] == 2 )
			{
				$subject = $lang_module['account_active'];
				$message = sprintf( $lang_module['account_active_info'], $data['first_name'], $global_config['site_name'], NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=active&userid=' . $userid . '&checknum=' . $checknum, $data['email'], $data['email'], $data['password'], nv_date( 'H:i d/m/Y', NV_CURRENTTIME + 86400 ) );
				$send = nv_sendmail( $global_config['site_email'], $data['email'], $subject, $message );
				if( $send )
				{
					$info = $lang_module['account_active_mess'] . "<br /><br />\n";
				}
				else
				{
					$info = $lang_module['account_active_mess_error_mail'] . "<br /><br />\n";
				}
			}
			else
			{
				$info = $lang_module['account_register_to_admin'] . "<br /><br />\n";
			}

			$info .= "<img border=\"0\" src=\"" . NV_BASE_SITEURL . "images/load_bar.gif\"><br /><br />\n";
			$info .= '[<a href="' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '">' . $lang_module['redirect_to_login'] . '</a>]';

			$json['info'] = $info;

			nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['register'], $data['email'] . ' | ' . $client_info['ip'] . ' | Simple', 0 );

		}
		else
		{

			$sql = "INSERT INTO " . NV_USERS_GLOBALTABLE . "
					(username, md5username, password, email, first_name, last_name, gender, photo, birthday, regdate,
					question, answer, passlostkey, view_mail, remember, in_groups,
					active, checknum, last_login, last_ip, last_agent, last_openid, idsite, customer_group_id, store_id, telephone, fax, newsletter) VALUES (
					:username,
					:md5username,
					:password,
					:email,
					:first_name,
					:last_name,
					'', '', 0, " . NV_CURRENTTIME . ",
					:your_question,
					:answer,
					'', 0, 1, '', 1, '', 0, '', '', '', 
					" . $global_config['idsite'] . ",
					" . intval( $customer_group_id ) . ",
					" . intval( $shops_config->config_store_id ) . ",
					:telephone,
					:fax,
					" . intval( $data['newsletter'] ) . ")";

			$data_insert = array();
			$data_insert['username'] = $data['email'];
			$data_insert['md5username'] = nv_md5safe( $data['email'] );
			$data_insert['password'] = $password;
			$data_insert['email'] = $data['email'];
			$data_insert['first_name'] = $data['first_name'];
			$data_insert['last_name'] = $data['last_name'];
			$data_insert['your_question'] = $your_question;
			$data_insert['answer'] = $answer;
			$data_insert['telephone'] = $data['telephone'];
			$data_insert['fax'] = $data['fax'];
			$userid = $db->insert_id( $sql, 'userid', $data_insert );

			if( ! $userid )
			{

				$json['error']['warning'] = $lang_module['err_no_save_account'];

			}
			else
			{
				$db->query( 'UPDATE ' . NV_GROUPS_GLOBALTABLE . ' SET numbers = numbers+1 WHERE group_id=4' );

				$query_field['userid'] = $userid;
				$db->query( 'INSERT INTO ' . NV_USERS_GLOBALTABLE . '_info (' . implode( ', ', array_keys( $query_field ) ) . ') VALUES (' . implode( ', ', array_values( $query_field ) ) . ')' );

				$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_address SET 
					userid = ' . intval( $userid ) . ', 
					customer_temp_id = 0, 
					temp = 0, 
					first_name = :first_name,
					last_name = :last_name, 
					company = :company, 
					address_1 = :address_1, 
					address_2= :address_2, 
					city= :city, 
					postcode= :postcode, 
					country_id = ' . intval( $data['country_id'] ) . ', 
					zone_id = ' . intval( $data['zone_id'] ) . ', 
					custom_field= :custom_field ' );

				if( ! isset( $data['custom_field'] ) )
				{
					$data['custom_field'] = array();
				}
				$sth->bindParam( ':first_name', $data['first_name'], PDO::PARAM_STR );
				$sth->bindParam( ':last_name', $data['last_name'], PDO::PARAM_STR );
				$sth->bindParam( ':company', $data['company'], PDO::PARAM_STR );
				$sth->bindParam( ':address_1', $data['address_1'], PDO::PARAM_STR );
				$sth->bindParam( ':address_2', $data['address_2'], PDO::PARAM_STR );
				$sth->bindParam( ':city', $data['city'], PDO::PARAM_STR );
				$sth->bindParam( ':postcode', $data['postcode'], PDO::PARAM_STR );
				$sth->bindParam( ':custom_field', serialize( $data['custom_field'] ), PDO::PARAM_STR );
				$sth->execute();
				$address_id = $db->lastInsertId();

				$db->query( 'UPDATE ' . NV_USERS_GLOBALTABLE . ' SET address_id = ' . ( int )$address_id . ' WHERE userid = ' . ( int )$userid );

				$subject = $lang_module['account_register'];
				$message = sprintf( $lang_module['account_register_info'], $data['first_name'], $global_config['site_name'], NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name, $data['email'], $data['password'] );
				nv_sendmail( $global_config['site_email'], $data['email'], $subject, $message );
 
				nv_insert_logs( NV_LANG_DATA, $module_name, $lang_module['register'], $data['email'] . ' | ' . $client_info['ip'] . ' | Simple', 0 );
				
				
				// auto login
				$row = $db->query( 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' WHERE email =' . $db->quote( $data['email'] ) )->fetch();
				validUserLog( $row, 1, '' );
 
				$json['info'] = $lang_module['register_ok'];
			}

		}
	}

	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

$data = array();

$data['country_id'] = $ProductGeneral->config['config_country_id'];

$contents = checkout_register( $data, $lang_ext );

echo $contents;
exit();
