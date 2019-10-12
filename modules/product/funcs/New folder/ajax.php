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

if( ! defined( 'NV_IS_MOD_PRODUCT' ) ) die( 'Stop!!!' );
 
if( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
  
if( $nv_Request->isset_request( 'payment_zone', 'post' ) )
{
	$country_id = $nv_Request->get_int( 'country_id', 'post', 0 );
	$zone_id = $nv_Request->get_int( 'zone_id', 'post', 0 );
	$contents = "<option value=\"0\">" . $lang_module['not_select_region'] . "</option>\n";
	if( $country_id > 0 )
	{
		$sql='SELECT zone_id, name FROM ' . TABLE_PRODUCT_NAME . '_zone WHERE country_id = ' . intval( $country_id );
		
		$result = $db->query( $sql );
		   
		while( list( $_zone_id, $name ) = $result->fetch(3)) 
		{
			$select = ( $zone_id == $_zone_id ) ? "selected='selected'" : "";
			$contents .= "<option value=\"" . $_zone_id . "\"  " . $select . ">" . nv_htmlspecialchars( $name ) . "</option>\n";
		}
		$result->closeCursor();
	}

	echo $contents;
	exit();
}

if( $nv_Request->isset_request( 'shipping_zone', 'post' ) )
{
	$country_id = $nv_Request->get_int( 'country_id', 'post', 0 );
	$zone_id = $nv_Request->get_int( 'zone_id', 'post', 0 );
	$contents = "<option value=\"0\">" . $lang_module['not_select_region'] . "</option>\n";
	if( $country_id > 0 )
	{
		$sql='SELECT zone_id,  name FROM ' . TABLE_PRODUCT_NAME . '_zone WHERE country_id = ' . intval( $country_id );
		
		$result = $db->query( $sql );
		   
		while( list( $_zone_id, $name ) = $result->fetch(3)) 
		{
			$select = ( $zone_id == $_zone_id ) ? "selected='selected'" : "";
			$contents .= "<option value=\"" . $_zone_id . "\"  " . $select . ">" . nv_htmlspecialchars( $name ) . "</option>\n";
		}
		$result->closeCursor();
	}

	echo $contents;
	exit();
}

if( $nv_Request->isset_request( 'zone', 'get,post' ) )
{
	
	$getCountry = getCountry();
	
	$json = array();
	$country_id = $nv_Request->get_int( 'country_id', 'get, post', 0 );

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

	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( $nv_Request->isset_request( 'check_coupon', 'post' ) )
{

	$coupon = $nv_Request->get_string( 'coupon', 'post', '' );

	$json = array();
	
	$shops_coupon = new shops_coupon( $productRegistry );
	
	$coupon_info = $shops_coupon->getCoupon( $coupon );
	
	$shops_coupon->clear();
	
	if( empty( $coupon ) )
	{
		$json['error'] = $lang_module['coupon_error_empty'];
	}
	elseif( $coupon_info )
	{

		$_SESSION[$module_data . '_coupon'] = $coupon;
		$_SESSION[$module_data . '_success'] = $lang_module['coupon_text_success'];

		$link_cart =  NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart', true );
		
		$json['redirect'] = str_replace( '&amp;', '&', $link_cart );

	}
	else
	{
		$json['error'] = $lang_module['coupon_error_coupon'];
	}

	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( $nv_Request->isset_request( 'check_voucher', 'post' ) )
{
	
	$voucher = $nv_Request->get_string( 'voucher', 'post', '' );

	$json = array();
	
	$shops_voucher = new shops_voucher( $productRegistry );
	
	$voucher_info = $shops_voucher->getVoucher( $voucher );

	if( empty( $voucher ) )
	{
		$json['error'] = $lang_module['voucher_error_empty'];
	}
	elseif( $voucher_info )
	{
		$_SESSION[$module_data . '_voucher'] = $voucher;
		$_SESSION[$module_data . '_success'] = $lang_module['voucher_text_success'];
 
		
		$link_cart = NV_MY_DOMAIN . nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cart', true );

		$json['redirect'] = str_replace( '&amp;', '&', $link_cart );
	}
	else
	{
		$json['error'] = $lang_module['voucher_error_voucher'];
	}

	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( $nv_Request->isset_request( 'check_video', 'post' ) )
{
 
	$json = array();
	
	$data = array();
	
	$token = $nv_Request->get_string( 'token', 'post', '' );
	
	$product_id = $nv_Request->get_int( 'product_id', 'post', 0 );
	
	if(  $token == md5( $product_id . $global_config['sitekey'] . session_id() ) )
	{
		$result = $db->query('SELECT v.*, vd.* FROM ' . TABLE_PRODUCT_NAME . '_product_video v
		INNER JOIN ' . TABLE_PRODUCT_NAME . '_product_video_description vd ON ( v.video_id = vd.video_id )
		WHERE vd.language_id='. $ProductGeneral->current_language_id .' AND v.product_id = ' . (int)$product_id );
 
		while( $video_info = $result->fetch() )
		{
			$data[] = $video_info;
		}
		$result->closeCursor();
		
		$json['info'] = theme_product_video( $data );
	}
	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( $nv_Request->isset_request( 'check_faq', 'post' ) )
{
 
	$json = array();
	
	$data = array();
	
	$token = $nv_Request->get_string( 'token', 'post', '' );
	$alias = $nv_Request->get_string( 'alias', 'post', '' );
 
	$product_id = $nv_Request->get_int( 'product_id', 'post', 0 );
	
	$page = $nv_Request->get_int( 'page', 'post', 0 );
	
	$per_page = $nv_Request->get_int( 'page', 'post', 10 );
	
	if(  $token == md5( $product_id . $global_config['sitekey'] . session_id() ) )
	{ 
		$result = $db->query('SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_faq 
			WHERE product_id = ' . (int)$product_id .' 
			ORDER BY date_added DESC 
			LIMIT '. $page .', '. $per_page );
 
		while( $faq_info = $result->fetch() )
		{
 
			$data[] = $faq_info;
		}
		$result->closeCursor();
		
		$json['info'] = theme_faq_list( $data, $product_id, $alias );
	}
	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( $nv_Request->isset_request( 'users_likes', 'post' ) )
{
 
	$json = array();
	
	$referer_ajax = $nv_Request->get_string( 'referer_ajax', 'post', '' );
	
	if( ! defined( 'NV_IS_USER' ) )
	{
		$json['login'] = theme_login_form( $referer_ajax );
		header( 'Content-Type: application/json' );
		echo json_encode( $json );
		exit();
	}
 
	$token = $nv_Request->get_string( 'token', 'post', '' );
	$product_id = $nv_Request->get_int( 'product_id', 'post', 0 );
	$faq_id = $nv_Request->get_int( 'faq_id', 'post', 0 );
	$total = 0; 
 
	if(  $token == md5( $product_id . $faq_id . $global_config['sitekey'] . session_id() ) )  
	{ 
		if( $db->query( 'SELECT COUNT(*) FROM ' . TABLE_PRODUCT_NAME . '_product_faq_likes WHERE faq_id = '.intval( $faq_id ) . ' AND userid = '.intval( $user_info['userid'] ) )->fetchColumn() )
		{
			$json['error'] = 'Bạn đã thích câu hỏi này trước đó !';
		}else
		{
			$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_faq_likes SET 
				faq_id = ' . ( int )$faq_id .',
				userid = ' . ( int )$user_info['userid'] . ',
				date_added = ' . ( int )NV_CURRENTTIME );
 
			if( $stmt->execute() )
			{
				$total = $db->query( 'SELECT COUNT(*) FROM ' . TABLE_PRODUCT_NAME . '_product_faq_likes  WHERE faq_id = '.intval( $faq_id ) )->fetchColumn();
	 
				$db->query('UPDATE ' . TABLE_PRODUCT_NAME . '_product_faq SET likes = '. intval( $total ) .' WHERE faq_id = ' . intval( $faq_id ) );
	 
			}
			
			$json['total'] = $total;
			$json['success'] = 'Đã thích';
		}
		
	}
	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( $nv_Request->isset_request( 'send_question', 'post' ) )
{
 
	$referer_ajax = $nv_Request->get_string( 'referer_ajax', 'post', '' );
	
	if( ! defined( 'NV_IS_USER' ) )
	{
		$json['login'] = theme_login_form( $referer_ajax );
		header( 'Content-Type: application/json' );
		echo json_encode( $json );
		exit();
	}
 
	$question = $nv_Request->get_string( 'question', 'post', '' );
	
	$tokenkey = $nv_Request->get_string( 'tokenkey', 'post', '' );
	
	$product_id = $nv_Request->get_int( 'product_id', 'post', 0 );
	$faq_id = $nv_Request->get_int( 'faq_id', 'post', 0 );
 	
	if(  $tokenkey ==  md5( $product_id . $faq_id . $parent_id . $global_config['sitekey'] . session_id() ) )
	{
		$difftimeout = 300;
		$timeout = $nv_Request->get_int( $module_name . '_question_timeout', 'cookie', 0 );
		
		if( empty( $question ) )
		{
			$json['error'] = $lang_module['faq_error_question'];
		}elseif( NV_CURRENTTIME - $timeout < $difftimeout )
		{
			$timeout = ceil( ( $difftimeout - NV_CURRENTTIME + $timeout ) / 60 );
			$json['error'] = sprintf( $lang_module['faq_error_timeout'], $timeout );
		}
		else
		{
			if ( !empty( $user_info ) )
			{
				$customer_name = '';
				$customer_email = '';
			}
 
			$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_faq SET 
 				product_id = ' . ( int )$product_id . ', 
				date_added = ' . ( int )NV_CURRENTTIME . ', 
				userid = ' . ( int )$user_info['userid'] . ', 
				customer_name = :customer_name,
				customer_email = :customer_email,
				customer_ip = :customer_ip,
				question = :question,
				status = 0' );
			
			$user_info['full_name'] = !empty( $user_info['full_name'] ) ? $user_info['full_name'] : 'guest';
			
			$sth->bindParam( ':customer_name', $user_info['full_name'], PDO::PARAM_STR );
			$sth->bindParam( ':customer_email', $user_info['email'], PDO::PARAM_STR );
			$sth->bindParam( ':customer_ip', $client_info['ip'], PDO::PARAM_STR );
			$sth->bindParam( ':question', $question, PDO::PARAM_STR );
			$sth->execute();
			$faq_id = $db->lastInsertId();
			$sth->closeCursor();
	
			if( $faq_id )
			{
				$json['success'] = $lang_module['faq_success_send'];
				
				$nv_Request->set_Cookie( $module_name . '_question_timeout', NV_CURRENTTIME );
			}
		}
		
		$json['referer'] = base64_decode( $referer_ajax );
	}else
	{
		$json['error'] = $lang_module['faq_error_security'];
	}
	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( $nv_Request->isset_request( 'review_send', 'post' ) )
{
 
	$json = array();
	
	if( ! defined( 'NV_IS_USER' ) )
	{
		$json['login'] = theme_login_form();
	
	}
 
 
	$review_title = $nv_Request->get_string( 'review_title', 'post', '' );
	$review_token = $nv_Request->get_string( 'review_token', 'post', '' );
	$review_detail = $nv_Request->get_string( 'review_detail', 'post', '' );
	$referer_ajax = $nv_Request->get_string( 'referer_ajax', 'post', '' );
 	
	$review_product_id = $nv_Request->get_int( 'review_product_id', 'post', 0 );
	$review_ratings = $nv_Request->get_int( 'review_ratings', 'post', 0 );
	$review_parent_id = $nv_Request->get_int( 'review_parent_id', 'post', 0 );
	$review_id = $nv_Request->get_int( 'review_review_id', 'post', 0 );
 	$review_post_facebook = $nv_Request->get_int( 'review_post_facebook', 'post', 0 );
 	$review_show_information = $nv_Request->get_int( 'review_show_information', 'post', 0 );
 	
 
	if(  $review_token ==  md5( $review_product_id . $review_id . $review_parent_id . $global_config['sitekey'] . session_id() ) )
	{	
		$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_review SET 
			parent_id = ' . ( int )$review_parent_id . ', 
			product_id = ' . ( int )$review_product_id . ', 
			rating = ' . ( int )$review_ratings . ', 
			post_facebook = ' . ( int )$review_post_facebook . ', 
			show_information = ' . ( int )$review_show_information . ', 
			userid = ' . ( int )$user_info['userid'] . ', 
			customer_name = :customer_name,
			customer_email = :customer_email,
			customer_ip = :customer_ip,
			customer_address = :customer_address,
			title = :title,
			detail = :detail,
			date_added = ' . ( int )NV_CURRENTTIME . ', 
			status = 0' );
		$user_info['full_name'] = !empty( $user_info['full_name'] ) ? $user_info['full_name'] : 'User';
		$sth->bindParam( ':customer_name', $user_info['full_name'], PDO::PARAM_STR );
		$sth->bindParam( ':customer_email', $user_info['email'], PDO::PARAM_STR );
		$sth->bindParam( ':customer_ip', $client_info['ip'], PDO::PARAM_STR );
		$sth->bindParam( ':customer_address', $user_info['city'], PDO::PARAM_STR );
		$sth->bindParam( ':title', $review_title, PDO::PARAM_STR );
		$sth->bindParam( ':detail', $review_detail, PDO::PARAM_STR );
		$sth->execute();
		$review_id = $db->lastInsertId();
		$sth->closeCursor();

		if( $review_id )
		{
			 
			$json['success'] = $lang_module['review_success_send'];
		}else
		{
			$json['error'] = $lang_module['review_error_save'];
		}

		
		$json['referer'] = base64_decode( $referer_ajax );
	}else
	{
		$json['error'] = $lang_module['review_error_security'];
	}
	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( $nv_Request->isset_request( 'get_rating', 'post' ) )
{
	$json = array();
	$product_id = $nv_Request->get_int( 'product_id', 'post', 0 );
	$total_rating = $nv_Request->get_int( 'total_rating', 'post', 0 );
	$result = $db->query( 'SELECT rating, COUNT(*) total FROM ' . TABLE_PRODUCT_NAME . '_product_review WHERE product_id = ' . ( int )$product_id . ' GROUP BY rating ASC' );
	$array_rating = array();
 
	while( list( $rating, $total ) = $result->fetch( 3 ) )
	{
		
		$array_rating[$rating] = array(
			'rating' => $rating,
			'percent' =>  ( $total/$total_rating ) * 100,
			'total' => $total );
 
	}
 
	$json['rating'] = $array_rating;

	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( $nv_Request->isset_request( 'get_cat_sub', 'post' ) )
{
	$json = array();
	$array_cat = array();
	$category_id = $nv_Request->get_int( 'category_id', 'post', 0 );
	if( isset( $productCategory[$category_id] ) )
	{	
		$subcatid = explode(',', $productCategory[$category_id]['subcatid'] );
		foreach( $subcatid as $_category_id )
		{
			if( $productCategory[$_category_id]['inhome'] == '1' )
			{
				$array_cat[] = array( 'category_id'=> $_category_id, 'name'=> $productCategory[$_category_id]['name']);	
			}
		}
		
		
	}
	$json['info'] = $array_cat;

	header( 'Content-Type: application/json' );
	echo json_encode( $json );
	exit();
}

if( ACTION_METHOD == 'get_product' )
{
	
	$info = array();
	
	$name = $nv_Request->get_string( 'name', 'get', '' );
	
	if( !empty ( $name ) )
	{
		function convertLikeToRegex( $command )
		{
			return "/^" . str_replace( '%', '(.*?)', $command ) . "$/si";
		}
		function get_keywords( )
		{
			global $module_name, $module_data, $db;
			
			$data = array();
			$cache_file = NV_LANG_DATA . '_search_tags_' . NV_CACHE_PREFIX . '.cache';
			if( ( $cache = nv_get_cache( $module_name, $cache_file ) ) != false )
			{
				$data = unserialize( $cache );
			}
			else 
			{
				$result = $db->query('SELECT DISTINCT alias, keywords FROM ' . TABLE_PRODUCT_NAME . '_tags_description ORDER BY numpro DESC LIMIT 5000' );
				while( $rows = $result->fetch ( ) )
				{
					$rows['alias'] = str_replace( '-', ' ', $rows['alias'] );
					$data[] = $rows;
				}
				$result->closeCursor();$rows = null;$result = null;
				$cache = serialize( $data );
				nv_set_cache( $module_name, $cache_file, $cache );
			} 
			return $data;
		}
		$content = get_keywords( );	
		
		$likeClauses = array(
		'%' . $name . '',
		'' . $name . '%',
		'%' . $name . '%' );
		$array_tags = array();
		foreach( $content as $value )
		{
			foreach( $likeClauses as $search )
			{
				
				if( preg_match( convertLikeToRegex( $search ), $value['keywords'] ) || preg_match( convertLikeToRegex( $search ), $value['alias'] ) ) 
				{
					$array_tags[] = $value['keywords'];
				}
			}
		} 
		$array_tags = array_unique ( array_filter( $array_tags ) ) ;
		$and = '';
		if( ! empty( $name ) )
		{
			$and .= ' AND pd.name LIKE :name';
		}
 
		// truy vấn giảm giá đặc biệt
		$special = '(SELECT price
			FROM ' . TABLE_PRODUCT_NAME . '_product_special ps
			WHERE ps.product_id = p.product_id
				AND ps.customer_group_id = ' . intval( $ProductGeneral->config['config_customer_group_id'] ) . '
				AND ((ps.date_start = 0
					OR ps.date_start < ' . NV_CURRENTTIME . ')
					AND (ps.date_end = 0
					   OR ps.date_end > ' . NV_CURRENTTIME . '))
			ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) special';

		$sql = 'SELECT p.product_id, p.category_id, p.showprice, p.image, p.thumb, pd.name, pd.alias, price, '.$special.' FROM ' . TABLE_PRODUCT_NAME . '_product p LEFT JOIN 
		' . TABLE_PRODUCT_NAME . '_product_description pd ON (p.product_id = pd.product_id)
		WHERE pd.language_id = ' . $ProductGeneral->current_language_id . $and . '
		ORDER BY pd.name DESC LIMIT 0, 5';
 
		$sth = $db->prepare( $sql );

		if( ! empty( $name ) )
		{
			$sth->bindValue( ':name', '%' . $name . '%' );
		}
		$sth->execute();
		$data = array();
		while( $rows = $sth->fetch( ) )
		{
			$rows['link'] =  nv_url_rewrite( NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $productCategory[$rows['category_id']]['alias'] . '/' . $rows['alias'] . $global_config['rewrite_exturl'], true );
			
			$data[] = $rows;
			 
		}
		$info['template'] = ajax_search_box( $data, $array_tags );
		
	}
 
	header( 'Content-Type: application/json' );
	echo json_encode( $info );
	exit();
}
