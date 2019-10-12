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
if( ! defined( 'NV_IS_FILE_ADMIN' ) ) die( 'Stop!!!' );

$lang_ext = getLangAdmin( $op, 'sale' );

$page_title = $lang_ext['heading_title'];
 
$getCountry = getCountry();
$getCustomerGroup = getCustomerGroup();

if( ACTION_METHOD == 'zone' )
{
	$json = array();
	$country_id = $nv_Request->get_int( 'country_id', 'get', 0 );

	$json = $getCountry[$country_id];

	$sql = 'SELECT zone_id, code, status, name FROM ' . TABLE_PRODUCT_NAME . '_zone WHERE country_id=' . $country_id;
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

	getOutputJon( $json );
}
elseif( ACTION_METHOD == 'delete' )
{
	$info = array();
	$userid = $nv_Request->get_int( 'userid', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );

	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] . session_id() . $userid ) )
	{
		$del_array = array( $userid );
	}

	if( ! empty( $del_array ) )
	{

		$sql = 'SELECT userid,  CONCAT(first_name, " ", last_name) name FROM ' . TABLE_PRODUCT_NAME . '_customer WHERE userid IN (' . implode( ',', $del_array ) . ')';
		$result = $db->query( $sql );
		$del_array = $no_del_array = array();
		$artitle = array();
		$a = 0;
		while( list( $userid, $name ) = $result->fetch( 3 ) )
		{

			if( $db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_customer WHERE userid = ' . ( int )$userid ) )
			{
				// $db->query( 'DELETE FROM ' . $db_config['prefix'] . '_groups_users WHERE userid = ' . ( int )$userid );
				// $db->query( 'DELETE FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = ' . ( int )$userid );
				$db->query( 'DELETE FROM ' . NV_USERS_GLOBALTABLE . ' WHERE userid = ' . ( int )$userid );
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_customer WHERE userid = ' . ( int )$userid );
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_customer_reward WHERE userid = ' . ( int )$userid );
				//$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_customer_transaction WHERE userid = ' . ( int )$userid );
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_customer_ip WHERE userid = ' . ( int )$userid );
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_customer_address WHERE userid = ' . ( int )$userid );

				$info['id'][$a] = $userid;
				$del_array[] = $userid;
				$artitle[] = $name;
				++$a;
			}
			else
			{
				$no_del_array[] = $userid;
			}
		}

		$count = sizeof( $del_array );
		if( $count )
		{

			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_customer', implode( ', ', $artitle ), $admin_info['userid'] );

			$nv_Request->unset_request( $module_data . '_success', 'session' );

			$info['success'] = $lang_ext['text_success'];

			$ProductGeneral->deleteCache( 'customer' );
		}
		if( ! empty( $no_del_array ) )
		{

			$info['error'] = $lang_ext['no_del_customer'] . ': ' . implode( ', ', $no_del_array );
		}

	}
	else
	{
		$info['error'] = $lang_ext['no_del_customer'];
	}
	header( 'Content-Type: application/json' );
	echo json_encode( $info );
	exit();
}
elseif( ACTION_METHOD == 'add' || ACTION_METHOD == 'edit' )
{

	$data = array(
		'userid' => 0,
		'customer_group_id' => 0,
		'first_name' => '',
		'last_name' => '',
		'email' => '',
		'telephone' => '',
		'password' => '',
		'confirm' => '',
		'gender' => '',
		'birthday' => '',
		'photo' => '',
		'newsletter' => '',
		'active' => 1,
		'address_id' => 0,
		'address' => array(),
	);
	$error = array();
	$array_address = array();

	$data['userid'] = $nv_Request->get_int( 'userid', 'get,post', 0 );

	if( $data['userid'] > 0 )
	{
		$data = $db->query( 'SELECT * FROM ' . NV_USERS_GLOBALTABLE . ' u LEFT JOIN  ' . TABLE_PRODUCT_NAME . '_group_users gu ON (u.userid = gu.customer_id) WHERE u.userid=' . intval( $data['userid'] ) )->fetch();

		$data['confirm'] = $data['old_password'] = $data['password'];

		$sql = 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_address WHERE userid=' . intval( $data['userid'] );

		$result = $db->query( $sql );

		while( $rows = $result->fetch() )
		{
			$data['address'][] = $rows;
		}
		$result->closeCursor();
		
		$caption = $lang_ext['text_edit'];

	}
	else
	{
		$caption = $lang_ext['text_add'];
	}

	if( $nv_Request->get_int( 'save', 'post' ) == 1 )
	{

		$data['userid'] = $nv_Request->get_int( 'userid', 'post', 0 );
		$data['customer_group_id'] = $nv_Request->get_int( 'customer_group_id', 'post', 0 );
		$data['first_name'] = nv_substr( $nv_Request->get_title( 'first_name', 'post', '', 1 ), 0, 32 );
		$data['last_name'] = nv_substr( $nv_Request->get_title( 'last_name', 'post', '', 1 ), 0, 32 );
		$data['username'] = $nv_Request->get_title( 'username', 'post', '', 1 );
		$data['gender'] = nv_substr( $nv_Request->get_title( 'gender', 'post', '', 1 ), 0, 1 );
		$data['birthday'] = $nv_Request->get_title( 'birthday', 'post' );
		$data['photo'] = nv_substr( $nv_Request->get_title( 'photo', 'post', '', 1 ), 0, 255 );
		$data['email'] = $nv_Request->get_title( 'email', 'post', '', 1 );
		$data['telephone'] = $nv_Request->get_title( 'telephone', 'post', '' );
		$data['password'] = $nv_Request->get_title( 'password', 'post', '' );
		$data['confirm'] = $nv_Request->get_title( 'confirm', 'post', '' );
		$data['newsletter'] = $nv_Request->get_int( 'newsletter', 'post', 0 );
		$data['active'] = $nv_Request->get_int( 'active', 'post', 0 );
		$data['address'] = $nv_Request->get_typed_array( 'address', 'post', array() );

		$md5username = nv_md5safe( $data['username'] );

		if( $data['userid'] > 0 )
		{
			// Thực hiện câu truy vấn để kiểm tra username đã tồn tại chưa.
			$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE md5username= :md5username AND userid != ' . $data['userid'] );
			$stmt->bindParam( ':md5username', $md5username, PDO::PARAM_STR );
			$stmt->execute();
			$query_error_username = $stmt->fetchColumn();
 
			// Thực hiện câu truy vấn để kiểm tra email đã tồn tại chưa.
			$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE email= :email AND userid != ' . $data['userid'] );
			$stmt->bindParam( ':email', $data['email'], PDO::PARAM_STR );
			$stmt->execute();
			$query_error_email = $stmt->fetchColumn();

			// Thực hiện câu truy vấn để kiểm tra email đã tồn tại trong NV_USERS_GLOBALTABLE_reg  chưa.
			$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE email= :email AND userid != ' . $data['userid'] );
			$stmt->bindParam( ':email', $data['email'], PDO::PARAM_STR );
			$stmt->execute();
			$query_error_email_reg = $stmt->fetchColumn();

			// Thực hiện câu truy vấn để kiểm tra email đã tồn tại trong NV_USERS_GLOBALTABLE_openid chưa.
			$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE email= :email AND userid != ' . $data['userid'] );
			$stmt->bindParam( ':email', $data['email'], PDO::PARAM_STR );
			$stmt->execute();
			$query_error_email_openid = $stmt->fetchColumn();
		}
		else
		{
			// Thực hiện câu truy vấn để kiểm tra username đã tồn tại chưa.
			$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE md5username= :md5username' );
			$stmt->bindParam( ':md5username', $md5username, PDO::PARAM_STR );
			$stmt->execute();
			$query_error_username = $stmt->fetchColumn();

			// Thực hiện câu truy vấn để kiểm tra email đã tồn tại chưa.
			$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . ' WHERE email= :email' );
			$stmt->bindParam( ':email', $data['email'], PDO::PARAM_STR );
			$stmt->execute();
			$query_error_email = $stmt->fetchColumn();

			// Thực hiện câu truy vấn để kiểm tra email đã tồn tại trong NV_USERS_GLOBALTABLE_reg  chưa.
			$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_reg WHERE email= :email' );
			$stmt->bindParam( ':email', $data['email'], PDO::PARAM_STR );
			$stmt->execute();
			$query_error_email_reg = $stmt->fetchColumn();

			// Thực hiện câu truy vấn để kiểm tra email đã tồn tại trong NV_USERS_GLOBALTABLE_openid chưa.
			$stmt = $db->prepare( 'SELECT userid FROM ' . NV_USERS_GLOBALTABLE . '_openid WHERE email= :email' );
			$stmt->bindParam( ':email', $data['email'], PDO::PARAM_STR );
			$stmt->execute();
			$query_error_email_openid = $stmt->fetchColumn();

		}

		if( strlen( $data['first_name'] ) < 3 || strlen( $data['first_name'] ) > 32 )
		{
			$error['first_name'] = $lang_ext['error_first_name'];

		}
		if( strlen( $data['last_name'] ) < 3 || strlen( $data['last_name'] ) > 32 )
		{
			$error['last_name'] = $lang_ext['error_last_name'];

		}

		if( ( $error_xemail = nv_check_valid_email( $data['email'] ) ) != '' )
		{
			$error['email'] = $lang_ext['error_email'];

		}
		elseif( $query_error_email )
		{
			$error['email'] = $lang_ext['error_email_exists'];

		}
		elseif( $query_error_email_reg )
		{
			$error['email'] = $lang_ext['error_email_exists'];
		}
		elseif( $query_error_email_openid )
		{
			$error['email'] = $lang_ext['error_email_exists'];
		}

		if( strlen( $data['telephone'] ) < 6 || strlen( $data['telephone'] ) > 32 )
		{
			$error['telephone'] = $lang_ext['error_telephone'];
		}
		if( ( $error_username = nv_check_valid_login( $data['username'], NV_UNICKMAX, NV_UNICKMIN ) ) != '' )
		{
			$error['username'] = $error_username;
		}
		elseif( "'" . $data['username'] . "'" != $db->quote( $data['username'] ) )
		{
			$error['username'] = sprintf( $lang_module['account_deny_name'], '<strong>' . $data['username'] . '</strong>' );

		}
		elseif( $query_error_username )
		{
			$error['username'] = $lang_ext['error_username_exist'];

		}

		if( $data['userid'] > 0 )
		{
			if( ! empty( $data['password'] ) )
			{
				if( ( $check_pass = nv_check_valid_pass( $data['password'], NV_UPASSMAX, NV_UPASSMIN ) ) != '' )
				{
					$error['password'] = $check_pass;
				}
				elseif( $data['password'] != $data['confirm'] )
				{
					$error['confirm'] = $lang_ext['error_confirm'];
				}
			}

		}
		else
		{
			if( ( $check_pass = nv_check_valid_pass( $data['password'], NV_UPASSMAX, NV_UPASSMIN ) ) != '' )
			{
				$error['password'] = $check_pass;
			}
			elseif( $data['password'] != $data['confirm'] )
			{
				$error['confirm'] = $lang_module['error_confirm_customer'];
			}

		}

		if( isset( $data['address'] ) )
		{
			foreach( $data['address'] as $key => $value )
			{
				if( ( strlen( $value['first_name'] ) < 1 ) || ( strlen( $value['first_name'] ) > 32 ) )
				{
					$error['address'][$key]['first_name'] = $lang_ext['error_first_name'];
				}

				if( ( strlen( $value['last_name'] ) < 1 ) || ( strlen( $value['last_name'] ) > 32 ) )
				{
					$error['address'][$key]['last_name'] = $lang_ext['error_last_name'];
				}

				if( ( strlen( $value['address_1'] ) < 3 ) || ( strlen( $value['address_1'] ) > 128 ) )
				{
					$error['address'][$key]['address_1'] = $lang_ext['error_address_1'];
				}

				if( ( strlen( $value['city'] ) < 2 ) || ( strlen( $value['city'] ) > 128 ) )
				{
					$error['address'][$key]['city'] = $lang_ext['error_city'];
				}

				$country_info = isset( $getCountry[$value['country_id']] ) ? $getCountry[$value['country_id']] : array();

				if( $country_info && $country_info['postcode_required'] && ( strlen( $value['postcode'] ) < 2 || strlen( $value['postcode'] ) > 10 ) )
				{
					$error['address'][$key]['postcode'] = $lang_ext['error_postcode'];
				}

				if( $value['country_id'] == '' )
				{
					$error['address'][$key]['country'] = $lang_ext['error_country'];
				}

				if( ! isset( $value['zone_id'] ) || $value['zone_id'] == '' )
				{
					$error['address'][$key]['zone'] = $lang_ext['error_zone'];
				}

			}
		}

		if( ! empty( $error ) && ! isset( $error['warning'] ) )
		{
			$error['warning'] = $lang_ext['error_warning'];
		}

		if( empty( $error ) )
		{
			if( $data['userid'] == 0 )
			{

				$stmt = $db->prepare( 'INSERT INTO ' . NV_USERS_GLOBALTABLE . ' SET 
					username=:username, 
					md5username=:md5username, 
					password=:password, 
					email=:email, 
					first_name=:first_name, 
					last_name=:last_name, 
					birthday = 0, 
					telephone = :telephone, 
					regdate = ' . NV_CURRENTTIME . ', 
					active = ' . intval( $data['newsletter'] ) . ',
					newsletter = ' . intval( $data['newsletter'] ) );

				$crypt_password = $crypt->hash( $data['password'] );
				$stmt->bindParam( ':username', $data['username'], PDO::PARAM_STR );
				$stmt->bindParam( ':first_name', $data['first_name'], PDO::PARAM_STR );
				$stmt->bindParam( ':last_name', $data['last_name'], PDO::PARAM_STR );
				$stmt->bindParam( ':md5username', $md5username, PDO::PARAM_STR );
				$stmt->bindParam( ':password', $crypt_password, PDO::PARAM_STR );
				$stmt->bindParam( ':email', $data['email'], PDO::PARAM_STR );
				$stmt->bindParam( ':telephone', $data['telephone'], PDO::PARAM_STR );
				$stmt->execute();

				if( $data['userid'] = $db->lastInsertId() > 0 )
				{
					$stmt->closeCursor();
					unset( $stmt );

					if( ! empty( $data['address'] ) )
					{

						foreach( $data['address'] as $address )
						{

							$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_address SET 
								userid = ' . $data['userid'] . ', 
								first_name = :first_name,
								last_name = :last_name, 
								company = :company, 
								address_1 = :address_1, 
								address_2= :address_2, 
								city= :city, 
								postcode= :postcode, 
								country_id = ' . intval( $address['country_id'] ) . ', 
								zone_id = ' . intval( $address['zone_id'] ) . ', 
								custom_field= :custom_field ' );

							if( ! isset( $address['custom_field'] ) )
							{
								$address['custom_field'] = array();
							}
							$sth->bindParam( ':first_name', $address['first_name'], PDO::PARAM_STR );
							$sth->bindParam( ':last_name', $address['last_name'], PDO::PARAM_STR );
							$sth->bindParam( ':company', $address['company'], PDO::PARAM_STR );
							$sth->bindParam( ':address_1', $address['address_1'], PDO::PARAM_STR );
							$sth->bindParam( ':address_2', $address['address_2'], PDO::PARAM_STR );
							$sth->bindParam( ':city', $address['city'], PDO::PARAM_STR );
							$sth->bindParam( ':postcode', $address['postcode'], PDO::PARAM_STR );
							$sth->bindParam( ':custom_field', serialize( $address['custom_field'] ), PDO::PARAM_STR );
							$sth->execute();
							$address_id = $db->lastInsertId();
							if( isset( $address['default'] ) )
							{ 

								$db->query( 'UPDATE ' . NV_USERS_GLOBALTABLE . ' SET address_id = ' . ( int )$address_id . ' WHERE userid = ' . ( int )$data['userid'] );
							}
						}
					}

					nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A Customer', 'userid: ' . $data['userid'], $admin_info['userid'] );

				}
				else
				{
					$error[] = $lang_ext['error_save'];

				}
			}
			else
			{

				$stmt = $db->prepare( 'UPDATE ' . NV_USERS_GLOBALTABLE . ' SET 
					username = :username, 
					md5username = :md5username, 
					email = :email, 
					first_name= :first_name,  
					last_name= :last_name,
					telephone= :telephone,
					customer_group_id= ' . intval( $data['customer_group_id'] ) . ', 						
					newsletter= ' . intval( $data['newsletter'] ) . ', 						
					active= ' . intval( $data['active'] ) . ' 						
					WHERE userid = ' . $data['userid'] );

				$stmt->bindParam( ':username', $data['username'], PDO::PARAM_STR );
				$stmt->bindParam( ':md5username', $md5username, PDO::PARAM_STR );
				$stmt->bindParam( ':email', $data['email'], PDO::PARAM_STR );
				$stmt->bindParam( ':first_name', $data['first_name'], PDO::PARAM_STR );
				$stmt->bindParam( ':last_name', $data['last_name'], PDO::PARAM_STR );
				$stmt->bindParam( ':telephone', $data['telephone'], PDO::PARAM_STR );
				$stmt->execute();
				$stmt->closeCursor();

				if( ! empty( $data['password'] ) )
				{
					$db->query( 'UPDATE ' . NV_USERS_GLOBALTABLE . ' SET password = ' . $db->quote( $crypt->hash( $data['password'] ) ) . ' WHERE userid = ' . $data['userid'] );
				}

				if( ! empty( $data['address'] ) )
				{
					$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_address WHERE userid = ' . ( int )$data['userid'] );

					unset( $sth );
					foreach( $data['address'] as $address )
					{

						$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_address SET 
									userid = ' . $data['userid'] . ', 
									first_name = :first_name,
									last_name = :last_name, 
									company = :company, 
									address_1 = :address_1, 
									address_2 = :address_2, 
									city = :city, 
									postcode = :postcode, 
									country_id = ' . intval( $address['country_id'] ) . ', 
									zone_id = ' . intval( $address['zone_id'] ) . ', 
									custom_field= :custom_field ' );

						if( ! isset( $address['custom_field'] ) )
						{
							$address['custom_field'] = array();
						}
						$sth->bindParam( ':first_name', $address['first_name'], PDO::PARAM_STR );
						$sth->bindParam( ':last_name', $address['last_name'], PDO::PARAM_STR );
						$sth->bindParam( ':company', $address['company'], PDO::PARAM_STR );
						$sth->bindParam( ':address_1', $address['address_1'], PDO::PARAM_STR );
						$sth->bindParam( ':address_2', $address['address_2'], PDO::PARAM_STR );
						$sth->bindParam( ':city', $address['city'], PDO::PARAM_STR );
						$sth->bindParam( ':postcode', $address['postcode'], PDO::PARAM_STR );
						$sth->bindParam( ':custom_field', serialize( $address['custom_field'] ), PDO::PARAM_STR );
						$sth->execute();
						$address_id = $db->lastInsertId();
						if( isset( $address['default'] ) )
						{

							$db->query( 'UPDATE ' . NV_USERS_GLOBALTABLE . ' SET address_id = ' . ( int )$address_id . ' WHERE userid = ' . ( int )$data['userid'] );
						}
					}
				}

				nv_insert_logs( NV_LANG_DATA, $module_name, 'Edit A Customer', 'userid: ' . $data['userid'], $admin_info['userid'] );

				$stmt->closeCursor();

			}

		}
		
		if( empty( $error ) )
		{
			
			Header( 'Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=customer' );
			die();
		}

	}

	$xtpl = new XTemplate( 'customer_add.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/sale' );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'LANGE', $lang_ext );
	$xtpl->assign( 'AddMenu', AddMenu() );
	$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
	$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'TEMPLATE', $global_config['site_theme'] );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'CAPTION', $caption );
	$xtpl->assign( 'DATA', $data );
	$xtpl->assign( 'GET_LINK', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=customer" );
	$xtpl->assign( 'BACK', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=customer" );

	foreach( $getCustomerGroup as $key => $item )
	{
		$xtpl->assign( 'CUSTOMER_GROUP', array( 'key'=> $key, 'name'=> $item['name'], 'selected'=> ( $key == $data['customer_group_id'] ) ? 'selected="selected"' : ''  ) );
		$xtpl->parse( 'main.customer_group' );
	}

	foreach( $productArrayGender as $key => $name )
	{
 
		$xtpl->assign( 'GENDER', array( 'key'=> $key, 'name'=> $name, 'selected'=> ( $key == $data['gender'] ) ? 'selected="selected"' : ''  ) );
		$xtpl->parse( 'main.gender' );
	}

	foreach( $productArrayYesNo as $key => $name )
	{
 
		$xtpl->assign( 'NEWSLETTER', array( 'key'=> $key, 'name'=> $name, 'selected'=> ( $key == $data['newsletter'] ) ? 'selected="selected"' : ''  ) );
		$xtpl->parse( 'main.newsletter' );
	}
	
	foreach( $productArrayStatus as $key => $name )
	{
		$xtpl->assign( 'ACTIVE', array( 'key'=> $key, 'name'=> $name, 'selected'=> ( $key == $data['active'] ) ? 'selected="selected"' : ''  ) );
		$xtpl->parse( 'main.active' );
	}
 
	$stt = 1;
	if( ! empty( $data['address'] ) )
	{

		foreach( $data['address'] as $_key => $value )
		{
			$xtpl->assign( 'stt', $stt );
			$xtpl->parse( 'main.address1' );

			$value['address_checked'] = ( isset( $value['default'] ) || ( $data['address_id'] == $value['address_id'] ) ) ? 'checked="checked"' : '';
			$xtpl->assign( 'LOOP', $value );

			foreach( $getCountry as $_country_id => $_value )
			{
				$xtpl->assign( 'country_selected', ( $_country_id == $value['country_id'] ) ? 'selected="selected"' : '' );
				$xtpl->assign( 'country_name', nv_htmlspecialchars( $_value['name'] ) );
				$xtpl->assign( 'country_id', $_country_id );
				$xtpl->parse( 'main.address2.loopcountry' );
			}

			if( isset( $error['address'] ) )
			{
				if( isset( $error['address'][$_key]['first_name'] ) )
				{
					$xtpl->assign( 'error_first_name', $error['address'][$_key]['first_name'] );
					$xtpl->parse( 'main.address2.error_first_name' );
				}
			}
			if( isset( $error['address'] ) )
			{
				if( isset( $error['address'][$_key]['last_name'] ) )
				{
					$xtpl->assign( 'error_last_name', $error['address'][$_key]['last_name'] );
					$xtpl->parse( 'main.address2.error_last_name' );
				}
			}
			if( isset( $error['address'] ) )
			{
				if( isset( $error['address'][$_key]['address_1'] ) )
				{
					$xtpl->assign( 'error_address_1', $error['address'][$_key]['address_1'] );
					$xtpl->parse( 'main.address2.error_address_1' );
				}
			}
			if( isset( $error['address'] ) )
			{
				if( isset( $error['address'][$_key]['city'] ) )
				{
					$xtpl->assign( 'error_city', $error['address'][$_key]['city'] );
					$xtpl->parse( 'main.address2.error_city' );
				}
			}
			if( isset( $error['address'] ) )
			{
				if( isset( $error['address'][$_key]['postcode'] ) )
				{
					$xtpl->assign( 'error_postcode', $error['address'][$_key]['postcode'] );
					$xtpl->parse( 'main.address2.error_postcode' );
				}
			}
			if( isset( $error['address'] ) )
			{
				if( isset( $error['address'][$_key]['country'] ) )
				{
					$xtpl->assign( 'error_country', $error['address'][$_key]['country'] );
					$xtpl->parse( 'main.address2.error_country' );
				}
			}
			if( isset( $error['address'] ) )
			{
				if( isset( $error['address'][$_key]['zone'] ) )
				{
					$xtpl->assign( 'error_zone', $error['address'][$_key]['zone'] );
					$xtpl->parse( 'main.address2.error_zone' );
				}
			}

			$xtpl->parse( 'main.address2' );
			++$stt;
		}

	}

	$xtpl->assign( 'NUM', $stt );

	foreach( $getCountry as $getcountry_id => $_value )
	{
		$xtpl->assign( 'getcountry_name', nv_htmlspecialchars( $_value['name'] ) );
		$xtpl->assign( 'getcountry_id', $getcountry_id );
		$xtpl->parse( 'main.getcountry' );
	}

	if( $data['userid'] > 0 )
	{

		$xtpl->parse( 'main.loadctab' );
		$xtpl->parse( 'main.loadcontent' );
		$xtpl->parse( 'main.loadscript' );
	}

	if( isset( $error['first_name'] ) )
	{
		$xtpl->assign( 'error_first_name', $error['first_name'] );
		$xtpl->parse( 'main.error_first_name' );
	}

	if( isset( $error['last_name'] ) )
	{
		$xtpl->assign( 'error_last_name', $error['last_name'] );
		$xtpl->parse( 'main.error_last_name' );
	}
	if( isset( $error['email'] ) )
	{
		$xtpl->assign( 'error_email', $error['email'] );
		$xtpl->parse( 'main.error_email' );
	}
	if( isset( $error['telephone'] ) )
	{
		$xtpl->assign( 'error_telephone', $error['telephone'] );
		$xtpl->parse( 'main.error_telephone' );
	}
	if( isset( $error['username'] ) )
	{
		$xtpl->assign( 'error_username', $error['username'] );
		$xtpl->parse( 'main.error_username' );
	}
	if( isset( $error['password'] ) )
	{
		$xtpl->assign( 'error_password', $error['password'] );
		$xtpl->parse( 'main.error_password' );
	}
	if( isset( $error['confirm'] ) )
	{
		$xtpl->assign( 'error_confirm', $error['confirm'] );
		$xtpl->parse( 'main.error_confirm' );
	}

	if( isset( $error['warning'] ) )
	{
		$xtpl->assign( 'WARNING', $error['warning'] );
		$xtpl->parse( 'main.warning' );
	}

	$xtpl->parse( 'main' );
	$contents = $xtpl->text( 'main' );
	include NV_ROOTDIR . '/includes/header.php';
	echo nv_admin_theme( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
}

/*show list customer*/

// $db->query( 'UPDATE ' . NV_USERS_GLOBALTABLE . ' SET customer_group_id = ' . ( int )$ProductGeneral->config['config_customer_group_id'] . ' WHERE customer_group_id = 0' );

$per_page = 50;
$page = $nv_Request->get_int( 'page', 'get', 1 );

$sql = TABLE_PRODUCT_NAME . '_customer';

$data['name'] = $nv_Request->get_string( 'name', 'get', '' );
$data['email'] = $nv_Request->get_string( 'email', 'get', '' );
$data['customer_group_id'] = $nv_Request->get_int( 'customer_group_id', 'get', 0 );
$data['approved'] = $nv_Request->get_string( 'approved', 'get', '' );
$data['active'] = $nv_Request->get_string( 'active', 'get', '' );
$data['date_added'] = $nv_Request->get_string( 'date_added', 'get', '' );
$data['email'] = $nv_Request->get_string( 'email', 'get', '' );
$data['status'] = $nv_Request->get_string( 'status', 'get', '' );
$data['ip'] = $nv_Request->get_string( 'ip', 'get', '' );
$data['sort'] = $nv_Request->get_string( 'sort', 'get', '' );
$data['order'] = $nv_Request->get_string( 'order', 'get' ) == 'desc' ? 'desc' : 'asc';

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=customer&amp;sort=' . $data['sort'] . '&amp;order=' . $data['order'] . '&amp;per_page=' . $per_page;

$sql = NV_USERS_GLOBALTABLE . ' u 
LEFT JOIN ' . TABLE_PRODUCT_NAME . '_group_users gu ON (u.userid = gu.customer_id)
LEFT JOIN ' . TABLE_PRODUCT_NAME . '_group_description gd ON (gu.customer_group_id = gd.customer_group_id)';

$sql .= ' WHERE gd.language_id = ' . $ProductGeneral->current_language_id;
$implode = array();

if( ! empty( $data['name'] ) )
{
	$implode[] = "CONCAT(u.first_name, ' ', u.last_name) LIKE '%" . $db->dblikeescape( $data['name'] ) . "%'";
}

if( ! empty( $data['email'] ) )
{
	$implode[] = "u.email LIKE '" . $db->dblikeescape( $data['email'] ) . "%'";
}

if( ! empty( $data['customer_group_id'] ) )
{
	$implode[] = "u.customer_group_id = '" . ( int )$data['customer_group_id'] . "'";
}

if( ! empty( $data['last_ip'] ) )
{
	$implode[] = 'u.last_ip = ' . $db->quote( $data['last_ip'] );
}

if( is_numeric( $data['active'] ) )
{
	$implode[] = 'u.active = ' . ( int )$data['active'];
}

if( ! empty( $data['regdate'] ) )
{
	$implode[] = "DATE(u.regdate) = DATE('" . $db->dblikeescape( $data['regdate'] ) . "')";
}

if( $implode )
{
	$sql .= " AND " . implode( " AND ", $implode );
}
 
$num_items = $db->query( 'SELECT COUNT(*) FROM ' . $sql )->fetchColumn();

$sort_data = array(
	'name',
	'u.email',
	'customer_group',
	'u.active',
	'u.last_ip',
	'u.regdate' );

if( isset( $data['sort'] ) && in_array( $data['sort'], $sort_data ) )
{
	$sql .= " ORDER BY " . $data['sort'];
}
else
{
	$sql .= " ORDER BY name";
}

if( isset( $data['order'] ) && ( $data['order'] == 'DESC' ) )
{
	$sql .= " DESC";
}
else
{
	$sql .= " ASC";
}

$db->sqlreset()->select( '*, CONCAT(u.first_name, \' \', u.last_name) name, gd.name customer_group' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );

$result = $db->query( $db->sql() );

$dataContent = array();

while( $rows = $result->fetch() )
{
	$dataContent[] = $rows;

}

$xtpl = new XTemplate( 'customer.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/sale' );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LANGE', $lang_ext );
$xtpl->assign( 'AddMenu', AddMenu() );
$xtpl->assign( 'NV_LANG_VARIABLE', NV_LANG_VARIABLE );
$xtpl->assign( 'NV_LANG_DATA', NV_LANG_DATA );
$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
$xtpl->assign( 'TEMPLATE', $global_config['site_theme'] );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'TOKEN', md5( $global_config['sitekey'] . session_id() ) );
$xtpl->assign( 'DATA', $data );

$order2 = ( $data['order'] == 'asc' ) ? 'desc' : 'asc';
$xtpl->assign( 'URL_NAME', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=name&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_EMAIL', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=email&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_CUSTOMER_GROUP', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=customer_group&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_ACTIVE', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=active&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_IP', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=last_ip&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_DATE_ADDED', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . '&amp;sort=date_added&amp;order=' . $order2 . '&amp;per_page=' . $per_page );

$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&action=add" );

foreach( $getCustomerGroup as $key => $item )
{
	$xtpl->assign( 'CUSTOMER_GROUP', array( 'key'=> $key, 'name'=> $item['name'], 'selected'=> ( $key == $data['customer_group_id'] ) ? 'selected="selected"' : ''  ) );
	$xtpl->parse( 'main.customer_group' );
}

foreach( $productArrayStatus as $valuekey => $status_title )
{
	$xtpl->assign( 'key_selected', ( $valuekey == $data['status'] && $data['status'] != '' ) ? 'selected="selected"' : '' );
	$xtpl->assign( 'valuekey', $valuekey );
	$xtpl->assign( 'status_title', $status_title );
	$xtpl->parse( 'main.status' );

}

if( ! empty( $dataContent ) )
{
	foreach( $dataContent as $item )
	{

		$item['customer_group'] = $getCustomerGroup[$item['customer_group_id']]['name'];
		$item['status'] = $productArrayStatus[$item['active']];
		$item['regdate'] = date( 'd/m/Y', $item['regdate'] );
		$item['token'] = md5( $global_config['sitekey'] . session_id() . $item['userid'] );

		$item['edit'] = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=customer&action=edit&token=" . $item['token'] . "&userid=" . $item['userid'];
		$xtpl->assign( 'LOOP', $item );

		$xtpl->parse( 'main.loop' );
	}

}
else
{
	$xtpl->parse( 'main.no_results' );
}

$generate_page = nv_generate_page( $base_url, $num_items, $per_page, $page );
if( ! empty( $generate_page ) )
{
	$xtpl->assign( 'GENERATE_PAGE', $generate_page );
	$xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );
include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';
