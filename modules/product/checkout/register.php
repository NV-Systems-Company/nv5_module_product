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
// require_once NV_ROOTDIR . '/modules/' . $module_file . '/global/address.php';

// lấy thông tin zone
if( ACTION_METHOD == 'zone' )
{
	$json = array();
	$country_id = $nv_Request->get_int( 'country_id', 'get', 0 );
 
	$sql = 'SELECT zone_id, code, status, name FROM ' . NV_USERS_GLOBALTABLE . '_zone WHERE country_id=' . $country_id;
	$result = $db->query( $sql );

	while( list( $_zone_id, $code, $status, $name ) = $result->fetch( 3 ) )
	{
		$json['zone'][] = array(
			'code' => $code,
			'country_id' => $country_id,
			'status' => $status,
			'name' => nv_htmlspecialchars( $name ),
			'zone_id' => $_zone_id );

	}

	nv_jsonOutput( $json );
}
elseif( ACTION_METHOD == 'customfield' )
{
	$json = array();

	nv_jsonOutput( $json );
}
elseif( ACTION_METHOD == 'save' )
{
	function checkUsernameReg( $login )
	{
		global $db, $lang_module, $global_users_config, $global_config;

		$error = nv_check_valid_login( $login, $global_config['nv_unickmax'], $global_config['nv_unickmin'] );
		if( $error != '' )
		{
			return preg_replace( '/\&(l|r)dquo\;/', '', strip_tags( $error ) );
		}
		if( "'" . $login . "'" != $db->quote( $login ) )
		{
			return sprintf( $lang_module['account_deny_name'], $login );
		}

		if( ! empty( $global_users_config['deny_name'] ) and preg_match( '/' . $global_users_config['deny_name'] . '/i', $login ) )
		{
			return sprintf( $lang_module['account_deny_name'], $login );
		}

		$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE md5username= :md5username' );
		$stmt->bindValue( ':md5username', nv_md5safe( $login ), PDO::PARAM_STR );
		$stmt->execute();
		if( $stmt->fetchColumn() )
		{
			return sprintf( $lang_module['account_registered_name'], $login );
		}

		$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE md5username= :md5username' );
		$stmt->bindValue( ':md5username', nv_md5safe( $login ), PDO::PARAM_STR );
		$stmt->execute();
		if( $stmt->fetchColumn() )
		{
			return sprintf( $lang_module['account_registered_name'], $login );
		}

		return '';
	}

	function checkEmailReg( $email )
	{
		global $db, $lang_module, $global_users_config;

		$error = nv_check_valid_email( $email );
		if( $error != '' )
		{
			return preg_replace( '/\&(l|r)dquo\;/', '', strip_tags( $error ) );
		}

		if( ! empty( $global_users_config['deny_email'] ) and preg_match( '/' . $global_users_config['deny_email'] . '/i', $email ) )
		{
			return sprintf( $lang_module['email_deny_name'], $email );
		}

		list( $left, $right ) = explode( '@', $email );
		$left = preg_replace( '/[\.]+/', '', $left );
		$pattern = str_split( $left );
		$pattern = implode( '.?', $pattern );
		$pattern = '^' . $pattern . '@' . $right . '$';

		$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE email RLIKE :pattern' );
		$stmt->bindParam( ':pattern', $pattern, PDO::PARAM_STR );
		$stmt->execute();
		if( $stmt->fetchColumn() )
		{
			return sprintf( $lang_module['email_registered_name'], $email );
		}

		$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE email RLIKE :pattern' );
		$stmt->bindParam( ':pattern', $pattern, PDO::PARAM_STR );
		$stmt->execute();
		if( $stmt->fetchColumn() )
		{
			return sprintf( $lang_module['email_registered_name'], $email );
		}

		$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE email RLIKE :pattern' );
		$stmt->bindParam( ':pattern', $pattern, PDO::PARAM_STR );
		$stmt->execute();
		if( $stmt->fetchColumn() )
		{
			return sprintf( $lang_module['email_registered_name'], $email );
		}

		return '';
	}

	$json = array();
 
	if( $globalUserid )
	{
		$json['redirect'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '='. $module_name .'&amp;' . NV_OP_VARIABLE . '=checkout', true );
				
	}
	
	$ProductCart = new NukeViet\Product\Cart( $productRegistry );

 
	// Validate cart has products and has stock.
	if( ( ! $ProductCart->hasProducts() && empty( $_SESSION[$module_data . '_vouchers'] ) ) || ( ! $ProductCart->hasStock() && ! $ProductGeneral->config['config_stock_checkout'] ) )
	{
		$json['redirect'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '='. $module_name .'&amp;' . NV_OP_VARIABLE . '=cart', true );
	
	}

	$getProducts = $ProductCart->getProducts();
 
	foreach( $getProducts as $product )
	{
		$product_total = 0;

		foreach( $getProducts as $product_2 )
		{
			if( $product_2['product_id'] == $product['product_id'] )
			{
				$product_total += $product_2['quantity'];
			}
		}

		if( $product['minimum'] > $product_total )
		{
			$json['redirect'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '='. $module_name .'&amp;' . NV_OP_VARIABLE . '=cart', true );

			break;
		}
	}	

	$dataContent['first_name'] = $nv_Request->get_title( 'firstname', 'post', '', 1 );
	$dataContent['last_name'] = $nv_Request->get_title( 'lastname', 'post', '', 1 );
	$dataContent['email'] = $nv_Request->get_title( 'email', 'post', '', 1 );
	$dataContent['username'] = $nv_Request->get_title( 'username', 'post', '', 1 );
	$dataContent['telephone'] = $nv_Request->get_title( 'telephone', 'post', '', 1 );
	$dataContent['fax'] = $nv_Request->get_title( 'fax', 'post', '', 1 );
	$dataContent['company'] = $nv_Request->get_title( 'company', 'post', '', 1 );
	$dataContent['address_1'] = $nv_Request->get_title( 'address_1', 'post', '', 1 );
	$dataContent['address_2'] = $nv_Request->get_title( 'address_2', 'post', '', 1 );
	$dataContent['password'] = $nv_Request->get_title( 'password', 'post', '', 1 );
	$dataContent['confirm'] = $nv_Request->get_title( 'confirm', 'post', '', 1 );
	$dataContent['city'] = $nv_Request->get_title( 'city', 'post', '', 1 );
	$dataContent['postcode'] = $nv_Request->get_title( 'postcode', 'post', '', 1 );
	$dataContent['shipping_address'] = $nv_Request->get_title( 'shipping_address', 'post', '', 1 );
	$dataContent['agree'] = $nv_Request->get_int( 'agree', 'post', 0 );
	$dataContent['newsletter'] = $nv_Request->get_int( 'newsletter', 'post', 0 );
	$dataContent['zone_id'] = $nv_Request->get_int( 'zone_id', 'post', 0 );
	$dataContent['country_id'] = $nv_Request->get_int( 'country_id', 'post', 0 );
	$dataContent['customer_group_id'] = $nv_Request->get_int( 'customer_group_id', 'post', 0 );
	$dataContent['sig'] = '';
	$dataContent['gender'] = '';
	$dataContent['question'] = '';
	$dataContent['answer'] = '';
	$dataContent['birthday'] = 0;
	$capcha = $nv_Request->get_title( 'capcha', 'post', '' );

	$capcha = ! $gfx_chk ? true : ( nv_capcha_txt( $capcha ) ? true : false );

	if( ! $capcha )
	{
		$json['error']['capcha'] = $lang_global['securitycodeincorrect'];
	}

	if( ( ( $check_login = checkUsernameReg( $dataContent['username'] ) ) ) != '' )
	{
		$json['error']['email'] = $check_login;
	}

	if( ( nv_strlen( $dataContent['password'] ) < 4 ) || ( nv_strlen( $dataContent['password'] ) > 20 ) )
	{
		$json['error']['password'] = $lang_ext['error_password'];
	}
	else
	{

		if( ( $check_pass = nv_check_valid_pass( $dataContent['password'], $global_config['nv_upassmax'], $global_config['nv_upassmin'] ) ) != '' )
		{
			$json['error']['password'] = $check_pass;
		}
	}

	if( ( nv_strlen( trim( $dataContent['first_name'] ) ) < 1 ) || ( nv_strlen( trim( $dataContent['first_name'] ) ) > 32 ) )
	{
		$json['error']['first_name'] = $lang_ext['error_first_name'];
	}

	if( ( nv_strlen( trim( $dataContent['last_name'] ) ) < 1 ) || ( nv_strlen( trim( $dataContent['last_name'] ) ) > 32 ) )
	{
		$json['error']['last_name'] = $lang_ext['error_last_name'];
	}

	if( ( nv_strlen( $dataContent['email'] ) > 96 ) || ! preg_match( '/^[^\@]+@.*.[a-z]{2,15}$/i', $dataContent['email'] ) )
	{
		$json['error']['email'] = $lang_ext['error_email'];
	}
	else
	{
		if( ( $check_email = checkEmailReg( $dataContent['email'] ) ) != '' )
		{
			$json['error']['email'] = $check_email;
		}
	}

	if( ( nv_strlen( $dataContent['telephone'] ) < 3 ) || ( nv_strlen( $dataContent['telephone'] ) > 32 ) )
	{
		$json['error']['telephone'] = $lang_ext['error_telephone'];
	}

	if( ( nv_strlen( trim( $dataContent['address_1'] ) ) < 3 ) || ( nv_strlen( trim( $dataContent['address_1'] ) ) > 128 ) )
	{
		$json['error']['address_1'] = $lang_ext['error_address_1'];
	}

	if( ( nv_strlen( trim( $dataContent['city'] ) ) < 2 ) || ( nv_strlen( trim( $dataContent['city'] ) ) > 128 ) )
	{
		$json['error']['city'] = $lang_ext['error_city'];
	}

	$getCountries = getCountries();

	if( $getCountries[$dataContent['country_id']] && $getCountries[$dataContent['country_id']]['postcode_required'] && ( nv_strlen( trim( $dataContent['postcode'] ) ) < 2 || nv_strlen( trim( $dataContent['postcode'] ) ) > 10 ) )
	{
		$json['error']['postcode'] = $lang_ext['error_postcode'];
	}

	if( $dataContent['country_id'] == '' )
	{
		$json['error']['country'] = $lang_ext['error_country'];
	}

	if( ! isset( $dataContent['zone_id'] ) || $dataContent['zone_id'] == '' )
	{
		$json['error']['zone'] = $lang_ext['error_zone'];
	}

	if( isset( $dataContent['customer_group_id'] ) && is_array( $ProductGeneral->config['config_customer_group_display'] ) && in_array( $dataContent['customer_group_id'], $ProductGeneral->config['config_customer_group_display'] ) )
	{
		$customer_group_id = $dataContent['customer_group_id'];
	}
	else
	{
		$customer_group_id = $ProductGeneral->config['config_customer_group_id'];
	}

	if( $dataContent['confirm'] != $dataContent['password'] )
	{
		$json['error']['confirm'] = $lang_ext['error_confirm'];
	}

	if( $ProductGeneral->config['config_account_id'] )
	{

		$getInformation = getInformation();

		$information_info = $getInformation[$ProductGeneral->config['config_account_id']];

		if( $information_info && ! isset( $dataContent['agree'] ) )
		{
			$json['error']['warning'] = sprintf( $lang_ext['error_agree'], $information_info['title'] );
		}
	}

	if( ! isset( $json['error'] ) )
	{
		$query_field = array( 'userid' => 0 );
		$userid = 0;
		$password = $crypt->hash_password( $array_register['password'], $global_config['hashprefix'] );
		$checknum = nv_genpass( 10 );
		$checknum = md5( $checknum );
		$your_question = '';
		$answer = '';
		if( ! defined( 'ACCESS_ADDUS' ) and ( $global_config['allowuserreg'] == 2 or $global_config['allowuserreg'] == 3 ) )
		{
			$sql = "INSERT INTO " . NV_USERS_GLOBALTABLE . "_reg (
				username, md5username, password, email, first_name, last_name, gender, birthday, sig, telephone, regdate, question, answer, checknum, users_info, newsletter
			) VALUES (
				:username,
				:md5username,
				:password,
				:email,
				:first_name,
				:last_name,
				:gender,
				:birthday,
				:sig,
				:telephone,
				" . NV_CURRENTTIME . ",
				:question,
				:answer,
				:checknum,
				:users_info,
				" . intval( $dataContent['newsletter'] ) . "
			)";

			$data_insert = array();
			$data_insert['username'] = $dataContent['username'];
			$data_insert['md5username'] = nv_md5safe( $dataContent['username'] );
			$data_insert['password'] = $password;
			$data_insert['email'] = $dataContent['email'];
			$data_insert['first_name'] = $dataContent['first_name'];
			$data_insert['last_name'] = $dataContent['last_name'];
			$data_insert['telephone'] = $dataContent['telephone'];
			$data_insert['gender'] = $dataContent['gender'];
			$data_insert['birthday'] = intval( $dataContent['birthday'] );
			$data_insert['sig'] = $dataContent['sig'];
			$data_insert['question'] = $dataContent['question'];
			$data_insert['answer'] = $dataContent['answer'];
			$data_insert['checknum'] = $checknum;
			$data_insert['users_info'] = nv_base64_encode( serialize( $query_field ) );

			$userid = $db->insert_id( $sql, 'userid', $data_insert );

			if( ! $userid )
			{

				$json['error']['warning'] = $lang_ext['error_save_user'];

			}
			else
			{
				if( $global_config['allowuserreg'] == 2 )
				{
					$register_active_time = isset( $global_users_config['register_active_time'] ) ? $global_users_config['register_active_time'] : 86400;
					
					$_full_name = nv_show_name_user( $dataContent['first_name'], $dataContent['last_name'], $dataContent['username'] );

					$sth = $db->prepare( 'INSERT INTO ' . NV_USERS_GLOBALTABLE . '_address SET 
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
						country_id = ' . intval( $dataContent['country_id'] ) . ', 
						zone_id = ' . intval( $dataContent['zone_id'] ) . ', 
						custom_field= :custom_field ' );

					if( ! isset( $dataContent['custom_field'] ) )
					{
						$dataContent['custom_field'] = array();
					}
					$sth->bindParam( ':first_name', $dataContent['first_name'], PDO::PARAM_STR );
					$sth->bindParam( ':last_name', $dataContent['last_name'], PDO::PARAM_STR );
					$sth->bindParam( ':company', $dataContent['company'], PDO::PARAM_STR );
					$sth->bindParam( ':address_1', $dataContent['address_1'], PDO::PARAM_STR );
					$sth->bindParam( ':address_2', $dataContent['address_2'], PDO::PARAM_STR );
					$sth->bindParam( ':city', $dataContent['city'], PDO::PARAM_STR );
					$sth->bindParam( ':postcode', $dataContent['postcode'], PDO::PARAM_STR );
					$sth->bindParam( ':custom_field', serialize( $dataContent['custom_field'] ), PDO::PARAM_STR );
					$sth->execute();
					$address_id = $db->lastInsertId();

					$db->query( 'UPDATE ' . NV_USERS_GLOBALTABLE . '_reg SET address_id = ' . ( int )$address_id . ' WHERE userid = ' . ( int )$userid );

					$subject = $lang_module['account_active'];
					$message = sprintf( $lang_module['account_active_info'], $_full_name, $global_config['site_name'], NV_MY_DOMAIN . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=active&userid=' . $userid . '&checknum=' . $checknum, $dataContent['username'], $dataContent['email'], nv_date( 'H:i d/m/Y', NV_CURRENTTIME + $register_active_time ) );
					$send = nv_sendmail( $global_config['site_email'], $dataContent['email'], $subject, $message );

					if( $send )
					{
						$info = $lang_ext['account_active_mess'];
					}
					else
					{
						$info = $lang_module['account_active_mess_error_mail'];
					}
				}
				else
				{
					$info = $lang_ext['account_register_to_admin'];
					nv_insert_notification( $module_name, 'contact_new', array( 'title' => $dataContent['username'] ), $userid, 0, 0, 1 );
				}

				$nv_redirect = '';

				$json['success'] = $info;
				$json['active'] = 0;

			}
		}
		else
		{

			function validUserLog( $user, $remember, $opid, $current_mode = 0 )
			{
				global $db, $global_config, $nv_Request, $lang_ext, $lang_module, $global_users_config, $module_name, $client_info;

				$remember = intval( $remember );
				$checknum = md5( nv_genpass( 10 ) );
				$user = array(
					'userid' => $user['userid'],
					'current_mode' => $current_mode,
					'checknum' => $checknum,
					'checkhash' => md5( $user['userid'] . $checknum . $global_config['sitekey'] . $client_info['browser']['key'] ),
					'current_agent' => NV_USER_AGENT,
					'last_agent' => $user['last_agent'],
					'current_ip' => NV_CLIENT_IP,
					'last_ip' => $user['last_ip'],
					'current_login' => NV_CURRENTTIME,
					'last_login' => intval( $user['last_login'] ),
					'last_openid' => $user['last_openid'],
					'current_openid' => $opid );

				$stmt = $db->prepare( "UPDATE " . NV_USERS_GLOBALTABLE . " SET
					checknum = :checknum,
					last_login = " . NV_CURRENTTIME . ",
					last_ip = :last_ip,
					last_agent = :last_agent,
					last_openid = :opid,
					remember = " . $remember . "
					WHERE userid=" . $user['userid'] );

				$stmt->bindValue( ':checknum', $checknum, PDO::PARAM_STR );
				$stmt->bindValue( ':last_ip', NV_CLIENT_IP, PDO::PARAM_STR );
				$stmt->bindValue( ':last_agent', NV_USER_AGENT, PDO::PARAM_STR );
				$stmt->bindValue( ':opid', $opid, PDO::PARAM_STR );
				$stmt->execute();
				$live_cookie_time = ( $remember ) ? NV_LIVE_COOKIE_TIME : 0;

				$nv_Request->set_Cookie( 'nvloginhash', serialize( $user ), $live_cookie_time );

				if( ! empty( $global_users_config['active_user_logs'] ) )
				{
					$log_message = $opid ? ( $lang_ext['userloginviaopt'] . ' ' . $opid ) : $lang_ext['st_login'];
					nv_insert_logs( NV_LANG_DATA, 'users', '[' . $user['username'] . '] ' . $log_message, ' Client IP:' . NV_CLIENT_IP, 0 );
				}
			}

			$group_id = 0;

			$global_users_config = array();
			$cacheFile = NV_LANG_DATA . '_users_config_' . NV_CACHE_PREFIX . '.cache';
			$cacheTTL = 3600;
			if( ( $cache = $nv_Cache->getItem( 'users', $cacheFile, $cacheTTL ) ) != false )
			{
				$global_users_config = unserialize( $cache );
			}
			else
			{
				$sql = "SELECT config, content FROM " . NV_USERS_GLOBALTABLE . "_config";
				$result = $db->query( $sql );
				while( $row = $result->fetch() )
				{
					$global_users_config[$row['config']] = $row['content'];
				}
				$cache = serialize( $global_users_config );
				$nv_Cache->setItem( 'users', $cacheFile, $cache, $cacheTTL );
			}

			$sql = "INSERT INTO " . NV_USERS_GLOBALTABLE . " (
				group_id, username, md5username, password, email, first_name, last_name, telephone, gender, photo, birthday, sig, regdate,
				question, answer, passlostkey, view_mail, remember, in_groups,
				active, checknum, last_login, last_ip, last_agent, last_openid, idsite, email_verification_time, newsletter
			) VALUES (
				" . ( defined( 'ACCESS_ADDUS' ) ? $group_id : ( $global_users_config['active_group_newusers'] ? 7 : 4 ) ) . ",
				:username,
				:md5username,
				:password,
				:email,
				:first_name,
				:last_name,
				:telephone,
				:gender
				, '',
				:birthday,
				:sig,
				 " . NV_CURRENTTIME . ",
				:question,
				:answer,
				'', 0, 1,
				'" . ( defined( 'ACCESS_ADDUS' ) ? $group_id : ( $global_users_config['active_group_newusers'] ? 7 : 4 ) ) . "',
				1, '', 0, '', '', '', " . $global_config['idsite'] . ", -1, " . intval( $dataContent['newsletter'] ) . "
			)";

			$data_insert = array();
			$data_insert['username'] = $dataContent['username'];
			$data_insert['md5username'] = nv_md5safe( $dataContent['username'] );
			$data_insert['password'] = $password;
			$data_insert['email'] = $dataContent['email'];
			$data_insert['first_name'] = $dataContent['first_name'];
			$data_insert['last_name'] = $dataContent['last_name'];
			$data_insert['telephone'] = $dataContent['telephone'];
			$data_insert['question'] = $dataContent['question'];
			$data_insert['answer'] = $dataContent['answer'];
			$data_insert['gender'] = $dataContent['gender'];
			$data_insert['birthday'] = intval( $dataContent['birthday'] );
			$data_insert['sig'] = $dataContent['sig'];

			$userid = $db->insert_id( $sql, 'userid', $data_insert );

			if( ! $userid )
			{
				$json['error']['warning'] = $lang_ext['error_save_user'];
			}
			else
			{
				$query_field['userid'] = $userid;
				$db->query( 'INSERT INTO ' . NV_USERS_GLOBALTABLE . '_info (' . implode( ', ', array_keys( $query_field ) ) . ') VALUES (' . implode( ', ', array_values( $query_field ) ) . ')' );

				if( defined( 'ACCESS_ADDUS' ) )
				{
					$db->query( 'INSERT INTO ' . NV_USERS_GLOBALTABLE . '_groups_users (group_id, userid, is_leader, approved, data) VALUES (' . $group_id . ',' . $userid . ', 0, 1, \'0\')' );
				}

				$db->query( 'UPDATE ' . NV_USERS_GLOBALTABLE . '_groups SET numbers = numbers+1 WHERE group_id=' . ( defined( 'ACCESS_ADDUS' ) ? $group_id : ( $global_users_config['active_group_newusers'] ? 7 : 4 ) ) );

				$sth = $db->prepare( 'INSERT INTO ' . NV_USERS_GLOBALTABLE . '_address SET 
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
					country_id = ' . intval( $dataContent['country_id'] ) . ', 
					zone_id = ' . intval( $dataContent['zone_id'] ) . ', 
					custom_field= :custom_field ' );

				if( ! isset( $dataContent['custom_field'] ) )
				{
					$dataContent['custom_field'] = array();
				}
				$sth->bindParam( ':first_name', $dataContent['first_name'], PDO::PARAM_STR );
				$sth->bindParam( ':last_name', $dataContent['last_name'], PDO::PARAM_STR );
				$sth->bindParam( ':company', $dataContent['company'], PDO::PARAM_STR );
				$sth->bindParam( ':address_1', $dataContent['address_1'], PDO::PARAM_STR );
				$sth->bindParam( ':address_2', $dataContent['address_2'], PDO::PARAM_STR );
				$sth->bindParam( ':city', $dataContent['city'], PDO::PARAM_STR );
				$sth->bindParam( ':postcode', $dataContent['postcode'], PDO::PARAM_STR );
				$sth->bindParam( ':custom_field', serialize( $dataContent['custom_field'] ), PDO::PARAM_STR );
				$sth->execute();
				$address_id = $db->lastInsertId();

				$db->query( 'UPDATE ' . NV_USERS_GLOBALTABLE . ' SET address_id = ' . ( int )$address_id . ' WHERE userid = ' . ( int )$userid );

				$subject = $lang_ext['success_account'];
				// $message = sprintf( $lang_module['account_register_info'], $dataContent['first_name'], $global_config['site_name'], $_url, $dataContent['username'] );
				// nv_sendmail( $global_config['site_email'], $dataContent['email'], $subject, $message );

				$_SESSION[$module_data . '_account'] = 'register';

				$customer_group_info = getCustomerGroup( $customer_group_id );

				if( $customer_group_info && ! $customer_group_info['approval'] && ! empty( $global_config['auto_login_after_reg'] ) )
				{
					
					// Auto login
					validUserLog( array(
						'userid' => $userid,
						'username' => $dataContent['username'],
						'last_agent' => '',
						'last_ip' => '',
						'last_login' => 0,
						'last_openid' => '' ), 1, '' );
 
					
					$_SESSION[$module_data . '_payment_address'] = getAddress( $userid, $address_id );

					if( isset( $_SESSION[$module_data . '_shipping_address'] ) && ! empty( $_SESSION[$module_data . '_shipping_address'] ) )
					{
						$_SESSION[$module_data . '_shipping_address'] = getAddress( $address_id );
					}
				}
				else
				{
					$json['redirect'] = nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=login', true );
				}

				unset( $_SESSION[$module_data . '_guest'] );
				unset( $_SESSION[$module_data . '_shipping_method'] );
				unset( $_SESSION[$module_data . '_shipping_methods'] );
				unset( $_SESSION[$module_data . '_payment_method'] );
				unset( $_SESSION[$module_data . '_payment_methods'] );

				$nv_Cache->delMod( 'users' );
				$nv_redirect = '';
				$json['success'] = $lang_ext['success_register'];
				$json['active'] = 1;

			}
		}
	}

	nv_jsonOutput( $json );
}

$xtpl = new XTemplate( 'ThemeCheckoutRegister.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file . '/checkout' );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LANGE', $lang_ext );
$xtpl->assign( 'TEMPLATE', $module_info['template'] );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );

$xtpl->parse( 'main' );
echo $xtpl->text( 'main' );
exit();
