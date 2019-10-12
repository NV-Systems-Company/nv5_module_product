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
 
$lang_ext = getLangAdmin( 'product', 'product' );

$page_title = $lang_ext['text_list'];
 
if( ACTION_METHOD == 'copy' )
{ 
	$json = array();
	
	$token = $nv_Request->get_title( 'token', 'post', '' );

	$product_id = $nv_Request->get_int( 'product_id', 'post', 0 );
	
	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	
	if( $listid != '' && md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$copy_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] . session_id() . $product_id ) )
	{
		$copy_array = array( $product_id );
	}
	
	$array_block_cat_module = array();
	$sql = 'SELECT bc.block_id, adddefault, name FROM ' . TABLE_PRODUCT_NAME . '_block_cat bc 
	LEFT JOIN  ' . TABLE_PRODUCT_NAME . '_block_cat_description bn ON bc.block_id = bn.block_id 
	WHERE language_id = ' . $ProductGeneral->current_language_id . '
	ORDER BY weight ASC';
	$result = $db->query( $sql );
	while( list( $bid_i, $adddefault_i, $title_i ) = $result->fetch( 3 ) )
	{
		$array_block_cat_module[$bid_i] = $title_i;
		if( $adddefault_i )
		{
			$id_block_content[] = $bid_i;
		}
	}
	
	if( ! empty( $copy_array ) )
	{
		foreach( $copy_array as $product_id )
		{
			
			// product
			$data = $db->query( "SELECT * FROM " . TABLE_PRODUCT_NAME . "_product WHERE product_id=" . $product_id )->fetch();
			
			// product description
			$sql = 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_description WHERE product_id=' . $product_id;
			$result = $db->query( $sql );
			$extension_array = array();
			while( $rows = $result->fetch() )
			{
				if( !empty( $rows['info'] ) )
				{
					$extension_array[$rows['language_id']] = unserialize( $rows['info'] );
					
				}
				$tagids = explode( ',', $rows['tagids'] );
				$tagids = array_filter( $tagids );

				$array_keywords_old = array();
				if( ! empty( $tagids ) )
				{ 
					$_query = $db->query( 'SELECT keywords FROM ' . TABLE_PRODUCT_NAME . '_tags_description   
						WHERE tags_id IN ( ' . implode( ',', $tagids ) . ' ) AND language_id = ' . $rows['language_id'] . '
						ORDER BY alias ASC' );
 
					while( $row = $_query->fetch() )
					{
						$array_keywords_old[] = $row['keywords'];
					}
				}
				$data['keywords'][$rows['language_id']] = ! empty( $array_keywords_old ) ? implode( ',', $array_keywords_old ) : '';
				$data['keywords_old'][$rows['language_id']] = $data['keywords'][$rows['language_id']];
				$data['array_keywords_old'][$rows['language_id']] = $array_keywords_old;
				$rows['tag'] = $array_keywords_old;
				$data['product_description'][$rows['language_id']] = $rows;
			}
			$result->closeCursor();
			
			if( ! empty( $extension_array ) )
			{
				foreach( $extension_array as $language_id => $info )
				{
					if( is_array( $info ) && !empty( $info ) )
					{
						foreach( $info as $key => $value )
						{
							$data['product_extension'][$key][$language_id]['info'] = $value;
						}
					}
				}
			}
			
			// filter
			$result = $db->query('SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_filter WHERE product_id = ' . (int)$data['product_id'] );

			while( $row = $result->fetch() )
			{
				$product_filter_data[] = $row['filter_id'];
			}
			$data['product_filter'] = array();

			foreach( $product_filter_data as $filter_id )
			{
				$filter_info = $db->query('SELECT *, (SELECT name FROM ' . TABLE_PRODUCT_NAME . '_filter_group_description fgd 
					WHERE f.filter_group_id = fgd.filter_group_id 
					AND fgd.language_id = ' . (int)$ProductGeneral->current_language_id . ') group_name 
					FROM ' . TABLE_PRODUCT_NAME . '_filter f 
					LEFT JOIN ' . TABLE_PRODUCT_NAME . '_filter_description fd ON (f.filter_id = fd.filter_id) 
					WHERE f.filter_id = ' . (int)$filter_id . ' AND fd.language_id = ' . (int)$ProductGeneral->current_language_id )->fetch();

				if( $filter_info )
				{
					$data['product_filter'][] = array( 'filter_id' => $filter_info['filter_id'], 'name' => $filter_info['group_name'] . ' &gt; ' . $filter_info['name'] );
				}
			}
			
			// end filter
			
			// video
			$result = $db->query('SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_video WHERE product_id = ' . (int)$data['product_id'] );

			while( $row = $result->fetch() )
			{
				$query = $db->query('SELECT v.*, vd.* FROM ' . TABLE_PRODUCT_NAME . '_product_video v
				INNER JOIN ' . TABLE_PRODUCT_NAME . '_product_video_description vd ON ( v.video_id = vd.video_id )
				WHERE v.video_id = ' . (int)$row['video_id'] );
				
				$array_video = array();
				while( $video_info = $query->fetch() )
				{
					$array_video[$video_info['language_id']] = array( 'name'=> $video_info['name'], 'description'=> $video_info['description'] );
				}
				$data['product_video'][] = array( 
					'thumb' => $row['thumb'], 
					'url' => $row['url'], 
					'product_video_description' => $array_video );
				
			}
			// end video
			
			// Attribute
			$product_attributes = getProductAttributes( $data['product_id'] ) ;
			$getAttribute = getAttribute();
			$data['product_attribute'] = array();
			foreach ( $product_attributes as $product_attribute )
			{
		 
				if ( isset( $getAttribute[$product_attribute['attribute_id']] ) ) 
				{
					$data['product_attribute'][] = array(
						'attribute_id'                  => $product_attribute['attribute_id'],
						'name'                          => $getAttribute[$product_attribute['attribute_id']]['name'],
						'product_attribute_description' => $product_attribute['product_attribute_description']
					);
				}
			}

			
			// block
			$id_block_content = array();
			$sql = 'SELECT block_id FROM ' . TABLE_PRODUCT_NAME . '_block WHERE product_id=' . $product_id;
			$result = $db->query( $sql );

			while( list( $block_id_i ) = $result->fetch( 3 ) )
			{
				$id_block_content[] = $block_id_i;
			}
			$result->closeCursor();
			
			
			// discount
			$result = $db->query( "SELECT * FROM " . TABLE_PRODUCT_NAME . "_product_discount WHERE product_id=" . $product_id );
			while( $row = $result->fetch() )
			{
				$data['product_discount'][] = array(
					'product_id' => $row['product_id'],
					'customer_group_id' => $row['customer_group_id'],
					'quantity' => $row['quantity'],
					'priority' => $row['priority'],
					'price' => $row['price'],
					'date_start' => ! empty( $row['date_start'] ) ? date( 'd/m/Y', $row['date_start'] ) : '',
					'date_end' => ! empty( $row['date_start'] ) ? date( 'd/m/Y', $row['date_end'] ) : '',
					);
			}
			$result->closeCursor();

			$result = $db->query( "SELECT * FROM " . TABLE_PRODUCT_NAME . "_product_special WHERE product_id=" . $product_id );
			while( $row = $result->fetch() )
			{
				$data['product_special'][] = array(
					'product_id' => $row['product_id'],
					'customer_group_id' => $row['customer_group_id'],
					'priority' => $row['priority'],
					'price' => $row['price'],
					'date_start' => ! empty( $row['date_start'] ) ? date( 'd/m/Y', $row['date_start'] ) : '',
					'date_end' => ! empty( $row['date_start'] ) ? date( 'd/m/Y', $row['date_end'] ) : '',
					);
			}
			$result->closeCursor();

			// discount
			$result = $db->query( "SELECT t1.related_id, t2.name FROM " . TABLE_PRODUCT_NAME . "_product_related t1 
			LEFT JOIN " . TABLE_PRODUCT_NAME . "_product_description t2 ON ( t1.related_id = t2.product_id ) 
			WHERE t1.product_id=" . $product_id );

			while( $row = $result->fetch() )
			{
				$data['product_related'][] = array(
					'related_id' => $row['related_id'],
					'name' => $row['name'],
					);
			}
			$result->closeCursor();
			
			// image
			$result = $db->query( "SELECT * FROM " . TABLE_PRODUCT_NAME . "_product_image where product_id=" . $product_id );
			while( $row = $result->fetch() )
			{
				if( ! empty( $row['image'] ) and file_exists( NV_UPLOADS_REAL_DIR . '/' . $module_name . '/' . $row['image'] ) )
				{
					$row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $row['image'];
				}
				$data['product_image'][] = array(
					'product_id' => $row['product_id'],
					'image' => $row['image'],
					'sort_order' => $row['sort_order'] );
			}
			$result->closeCursor();
		
			// option
			$result = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_option po 
			LEFT JOIN ' . TABLE_PRODUCT_NAME . '_option o ON (po.option_id = o.option_id) 
			LEFT JOIN ' . TABLE_PRODUCT_NAME . '_option_description od ON (o.option_id = od.option_id) 
			WHERE po.product_id = ' . ( int )$product_id . ' AND od.language_id = ' . ( int )$ProductGeneral->current_language_id );
			while( $product_option = $result->fetch() )
			{
				$product_option_value_data = array();

				$result2 = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_option_value WHERE product_option_id = ' . ( int )$product_option['product_option_id'] );

				while( $product_option_value = $result2->fetch() )
				{
					$product_option_value_data[] = array(
						'product_option_value_id' => $product_option_value['product_option_value_id'],
						'option_value_id' => $product_option_value['option_value_id'],
						'quantity' => $product_option_value['quantity'],
						'subtract' => $product_option_value['subtract'],
						'price' => $product_option_value['price'],
						'price_prefix' => $product_option_value['price_prefix'],
						'points' => $product_option_value['points'],
						'points_prefix' => $product_option_value['points_prefix'],
						'weight' => $product_option_value['weight'],
						'weight_prefix' => $product_option_value['weight_prefix'] );
				}

				$data['product_option'][] = array(
					'product_option_id' => $product_option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id' => $product_option['option_id'],
					'name' => $product_option['name'],
					'type' => $product_option['type'],
					'value' => $product_option['value'],
					'required' => $product_option['required'] );

				$result2->closeCursor();
			}
			$result->closeCursor();
			
			
			// reward
			$result = $db->query( "SELECT * FROM " . TABLE_PRODUCT_NAME . "_product_reward where product_id=" . $product_id );
			while( $row = $result->fetch() )
			{
				$data['product_reward'][$row['customer_group_id']] = array(
					'product_id' => $row['product_id'],
					'customer_group_id' => $row['customer_group_id'],
					'points' => $row['points'] );
			}
			$result->closeCursor();
			
			
			$array_extension = array();
			if( !empty( $data['product_extension'] ) )
			{
				foreach( $data['product_extension'] as $key => $product_extension )
				{	
					foreach( $getLangModId as $language_id => $lang )
					{
						$array_extension[$language_id][] = $product_extension[$language_id]['info'];
					}
				}
					
			}
			
			$data['status']= 0;
			$data['product_id']= 0;
			
			// insert san pham
			$sql = "INSERT INTO " . TABLE_PRODUCT_NAME . "_product ( category_id, user_id, date_added, date_modified, 
			status, sort_order, shipping, points, model, minimum, quantity, price, units_id, tax_class_id,
			stock_status_id, subtract, weight, weight_class_id, length, width, height, length_class_id, brand_id, 
			image, thumb hitstotal, showprice, layout 
			)
				 VALUES ( 
				 :category_id,
				 " . intval( $data['user_id'] ) . ",
				 " . intval( NV_CURRENTTIME ) . ",
				 " . intval( NV_CURRENTTIME ) . ",
				 " . intval( $data['status'] ) . ",
				 " . intval( $data['sort_order'] ) . ",
				 " . intval( $data['shipping'] ) . ",
				 " . intval( $data['points'] ) . ",
				 :model,
 				 " . intval( $data['minimum'] ) . ",
				 " . intval( $data['quantity'] ) . ",
				 :price,
				 " . intval( $data['units_id'] ) . ",
				 " . intval( $data['tax_class_id'] ) . ",
				 " . intval( $data['stock_status_id'] ) . ",
				 " . intval( $data['subtract'] ) . ",
				 " . intval( $data['weight'] ) . ",
				 " . intval( $data['weight_class_id'] ) . ",
				 " . intval( $data['length'] ) . ",
				 " . intval( $data['width'] ) . ",
				 " . intval( $data['height'] ) . ",
				 " . intval( $data['length_class_id'] ) . ",
				 " . intval( $data['brand_id'] ) . ",
				 :image,
				 :thumb,
				 " . intval( $data['hitstotal'] ) . ",
				 " . intval( $data['showprice'] ) . ",
				 :layout
			)";
			$data_insert = array();
			$data_insert['category_id'] = $data['category_id'];
			$data_insert['model'] = '';
			$data_insert['price'] = $data['price'];
			$data_insert['image'] = $data['image'];
			$data_insert['thumb'] = $data['thumb'];
			$data_insert['homeimgalt'] = $data['homeimgalt'];
			$data_insert['layout'] = $data['layout'];
			$data['product_id'] = $db->insert_id( $sql, 'product_id', $data_insert );

			if( $data['product_id'] > 0 )
			{

				nv_insert_logs( NV_LANG_DATA, $module_name, 'Add A Product', 'product_id: ' . $data['product_id'], $admin_info['userid'] );
					
				$auto_model = '';
				if( ! empty( $ProductGeneral->config['config_format_code_id'] ) and empty( $data['model'] ) )
				{
					$i = 1;
					$auto_model = vsprintf( $ProductGeneral->config['config_format_code_id'], $data['product_id'] );

					$stmt = $db->prepare( 'SELECT product_id FROM ' . TABLE_PRODUCT_NAME . '_product WHERE model= :model' );
					$stmt->bindParam( ':model', $auto_model, PDO::PARAM_STR );
					$stmt->execute();
					if( $stmt->rowCount() )
					{
						$auto_model = vsprintf( $ProductGeneral->config['config_format_code_id'], ( $data['product_id'] + $i ) );
					}

					$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_product SET model= :model WHERE product_id=' . $data['product_id'] );
					$stmt->bindParam( ':model', $auto_model, PDO::PARAM_STR );
					$stmt->execute();
				}	
					
				foreach( $data['product_description'] as $language_id => $value )
				{
 
					$extension = isset( $array_extension[$language_id] ) ? serialize( $array_extension[$language_id] ) : '';
					
					$value['description'] = defined( 'NV_EDITOR' ) ? nv_nl2br( $value['description'], '' ) : nv_nl2br( nv_htmlspecialchars( strip_tags( $value['description'] ) ), '<br />' );

					$value['meta_description'] = nv_nl2br( nv_htmlspecialchars( strip_tags( $value['meta_description'] ) ), '<br />' );

					$value['alias'] = ( $value['alias'] == '' ) ? change_alias( $value['name'] ) : change_alias( $value['alias'] );

					$value['name'] = isset( $value['name'] ) ? $value['name'] : '';


					$value['meta_title'] = isset( $value['meta_title'] ) ? $value['meta_title'] : '';

					$value['meta_keyword'] = isset( $value['meta_keyword'] ) ? $value['meta_keyword'] : '';

					$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_description SET 
						product_id = ' . intval( $data['product_id'] ) . ', 
						language_id = ' . intval( $language_id ) . ', 
						name = :name,
						alias = :alias,
						description = :description,
						meta_title = :meta_title,
						meta_description = :meta_description,
						meta_keyword=:meta_keyword,
						info=:info' );

					$stmt->bindParam( ':name', $value['name'], PDO::PARAM_STR );
					$stmt->bindParam( ':alias', $value['alias'], PDO::PARAM_STR );
					$stmt->bindParam( ':description', $value['description'], PDO::PARAM_STR );
					$stmt->bindParam( ':meta_title', $value['meta_title'], PDO::PARAM_STR );
					$stmt->bindParam( ':meta_description', $value['meta_description'], PDO::PARAM_STR );
					$stmt->bindParam( ':meta_keyword', $value['meta_keyword'], PDO::PARAM_STR );
					$stmt->bindParam( ':info', $extension, PDO::PARAM_STR );
					$stmt->execute();
					$stmt->closeCursor();
					
					if( ! isset( $value['tag'] ) )
					{

						$keywords = ( $value['meta_keyword'] != '' ) ? $value['meta_keyword'] : $value['description'];
						$keywords = nv_get_keywords( $keywords, 100 );
						$keywords = explode( ',', $keywords );

						// Ưu tiên lọc từ khóa theo các từ khóa đã có trong tags thay vì đọc từ từ điển
						$keywords_return = array();
						$sth = $db->prepare( 'SELECT COUNT(*) FROM ' . TABLE_PRODUCT_NAME . '_tags_description WHERE keywords = :keywords AND language_id=' . $language_id );
						foreach( $keywords as $keyword_i )
						{
							$sth->bindParam( ':keywords', $keyword_i, PDO::PARAM_STR );
							$sth->execute();
							if( $sth->fetchColumn() )
							{
								$keywords_return[] = $keyword_i;
								if( sizeof( $keywords_return ) > 20 )
								{
									break;
								}
							}
						}

						if( sizeof( $keywords_return ) < 20 )
						{
							foreach( $keywords as $keyword_i )
							{
								if( ! in_array( $keyword_i, $keywords_return ) )
								{
									$keywords_return[] = $keyword_i;
									if( sizeof( $keywords_return ) > 20 )
									{
										break;
									}
								}
							}
						}
						$data['keywords'] = implode( ',', $keywords );
					}
					else
					{
						$data['keywords'] = implode( ',', $value['tag'] );
					}

					insert_tags( $data['keywords'], $data['product_id'], $language_id );

					
				}
				
				// begin insert product_filter
				if( isset( $data['product_filter'] ) )
				{
					$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_filter WHERE product_id = ' . ( int )$data['product_id'] );

					foreach( $data['product_filter'] as $key => $filter )
					{
						$db->query( 'INSERT IGNORE  INTO ' . TABLE_PRODUCT_NAME . '_product_filter SET product_id = ' . ( int )$data['product_id'] . ', filter_id = ' . ( int )$filter['filter_id'] );
					}
				}
 
				// end insert product_filter
				
				// begin insert product_video 
				if( ! empty( $data['product_video'] ) )
				{	
					foreach( $data['product_video'] as $product_video )
					{
						$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_video SET 
							product_id = ' . (int)$data['product_id'] . ', 
							thumb = :thumb,
							url = :url' );

						$sth->bindParam( ':thumb', $product_video['thumb'], PDO::PARAM_STR );
						$sth->bindParam( ':url', $product_video['url'], PDO::PARAM_STR );
						$sth->execute();
						$video_id = $db->lastInsertId();
						$sth->closeCursor();
					
						 
						if ( $video_id )
						{
							foreach ( $product_video['product_video_description'] as $language_id => $product_video_description ) 
							{
								$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_video_description SET 
									video_id = ' . $video_id . ', 
									language_id = ' . $language_id . ', 
									name = :name,
									description = :description' );

								$sth->bindParam( ':name', $product_video_description['name'], PDO::PARAM_STR );
								$sth->bindParam( ':description', $product_video_description['description'], PDO::PARAM_STR );
								$sth->execute();
								$sth->closeCursor();
							}
 	
						}

					}
				}
				// end insert product_video
				
				// begin insert product_attribute
				if( ! empty( $data['product_attribute'] ) )
				{	
					foreach( $data['product_attribute'] as $product_attribute )
					{
						if ( $product_attribute['attribute_id'] )
						{
							
							$db->query('DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_attribute WHERE product_id = ' . (int)$data['product_id'] . ' AND attribute_id = ' . (int)$product_attribute['attribute_id'] );

							foreach ( $product_attribute['product_attribute_description'] as $language_id => $product_attribute_description ) 
							{
								$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_attribute SET 
									product_id = ' . $data['product_id'] . ', 
									attribute_id = ' . $product_attribute['attribute_id'] . ', 
									language_id = ' . $language_id . ', 
									text = :text' );

								$sth->bindParam( ':text', $product_attribute_description['text'], PDO::PARAM_STR );
								$sth->execute();
								$sth->closeCursor();
							}
 	
						}

					}
				}
				// end insert product_attribute

				//begin insert product_related
				if( isset( $data['product_related'] ) )
				{
					foreach( $data['product_related'] as $related_id )
					{
						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_related WHERE product_id = ' . ( int )$data['product_id'] . ' AND related_id = ' . ( int )$related_id );
						$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_related SET product_id = ' . ( int )$data['product_id'] . ', related_id = ' . ( int )$related_id );
						$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_product_related WHERE product_id = ' . ( int )$data['product_id'] . ' AND related_id = ' . ( int )$data['product_id'] );
						$db->query( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_related SET product_id = ' . ( int )$data['product_id'] . ', related_id = ' . ( int )$data['product_id'] );
					}
				}
				// end insert product_related
				
				// begin insert product_reward
				foreach( $global_customer_group as $_group_id => $_g )
				{
					$points = isset( $data['product_reward'][$_group_id] ) ? $data['product_reward'][$_group_id]['points'] : 0;
					$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_reward SET product_id = ' . ( int )$data['product_id'] . ', customer_group_id = ' . ( int )$_group_id . ', points = ' . ( int )$points );
					$sth->execute();
					$sth->closeCursor();
				}
				// end insert product_reward

				// begin insert product_image
				if( ! empty( $data['product_image'] ) )
				{
					foreach( $data['product_image'] as $key => $value )
					{
						if( ! nv_is_url( $value['image'] ) and is_file( NV_DOCUMENT_ROOT . $value['image'] ) )
						{
							$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' );
							$value['image'] = substr( $value['image'], $lu );

							$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_image SET 
							product_id = ' . $data['product_id'] . ', 
							image = :image,
							sort_order = ' . ( int )$value['sort_order'] );

							$sth->bindParam( ':image', $value['image'], PDO::PARAM_STR );
							$sth->execute();
							$sth->closeCursor();
						}

					}
				}
				// end insert product_image

				// begin insert product_option
				if( ! empty( $data['product_option'] ) )
				{
					foreach( $data['product_option'] as $product_option )
					{
						if( $product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image' )
						{
							$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_option SET 
								product_id = ' . ( int )$data['product_id'] . ', 
								option_id = ' . ( int )$product_option['option_id'] . ', 
								required = ' . ( int )$product_option['required'] );
							$sth->execute();

							$product_option_id = $db->lastInsertId();
							$sth->closeCursor();

							foreach( $product_option['product_option_value'] as $product_option_value )
							{
								$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_option_value SET 
									product_option_id = ' . ( int )$product_option_id . ', 
									product_id = ' . ( int )$data['product_id'] . ', 
									option_id = ' . ( int )$product_option['option_id'] . ',
									option_value_id = ' . ( int )$product_option_value['option_value_id'] . ',
									quantity = ' . ( int )$product_option_value['quantity'] . ',
									subtract = ' . ( int )$product_option_value['subtract'] . ',
									price = ' . ( float )$product_option_value['price'] . ',
									price_prefix = :price_prefix,
									points = ' . ( int )$product_option_value['points'] . ',
									points_prefix = :points_prefix,
									weight = ' . ( float )$product_option_value['weight'] . ',
									weight_prefix = :weight_prefix ' );
								$sth->bindParam( ':price_prefix', $product_option_value['price_prefix'], PDO::PARAM_STR );
								$sth->bindParam( ':points_prefix', $product_option_value['points_prefix'], PDO::PARAM_STR );
								$sth->bindParam( ':weight_prefix', $product_option_value['weight_prefix'], PDO::PARAM_STR );
								$sth->execute();
								$sth->closeCursor();
							}

						}
					}
				}
				// end insert product_option

				// begin insert product_discount
				if( ! empty( $data['product_discount'] ) )
				{
					foreach( $data['product_discount'] as $key => $_value )
					{
						if( ! empty( $_value['date_start'] ) and ! preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_start'] ) ) $_value['date_start'] = '';
						if( ! empty( $_value['date_end'] ) and ! preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_end'] ) ) $_value['date_start'] = '';

						if( ! empty( $_value['date_start'] ) )
						{
							preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_start'], $m );
							$_value['date_start'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
						}
						else
						{
							$_value['date_start'] = 0;
						}
						unset( $m );
						if( ! empty( $_value['date_end'] ) )
						{
							preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_end'], $m );
							$_value['date_end'] = mktime( 23, 59, 59, $m[2], $m[1], $m[3] );
						}
						else
						{
							$_value['date_end'] = 0;
						}

						$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_discount SET 
						product_id = ' . ( int )$data['product_id'] . ', 
						customer_group_id = ' . ( int )$_value['customer_group_id'] . ', 
						quantity = ' . ( int )$_value['quantity'] . ',
						priority = ' . ( int )$_value['priority'] . ',
						price = ' . ( float )$_value['price'] . ',
						date_start = ' . ( int )$_value['date_start'] . ',
						date_end = ' . ( int )$_value['date_end'] );
						$sth->execute();
						$sth->closeCursor();
					}
				}
				// end insert product_discount

				// begin insert product_special
				if( ! empty( $data['product_special'] ) )
				{
					foreach( $data['product_special'] as $key => $_value )
					{
						if( ! empty( $_value['date_start'] ) and ! preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_start'] ) ) $_value['date_start'] = '';
						if( ! empty( $_value['date_end'] ) and ! preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_end'] ) ) $_value['date_start'] = '';

						if( ! empty( $_value['date_start'] ) )
						{
							preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_start'], $m );
							$_value['date_start'] = mktime( 0, 0, 0, $m[2], $m[1], $m[3] );
						}
						else
						{
							$_value['date_start'] = 0;
						}
						unset( $m );
						if( ! empty( $_value['date_end'] ) )
						{
							preg_match( '/^([0-9]{1,2})\/([0-9]{1,2})\/([0-9]{4})$/', $_value['date_end'], $m );
							$_value['date_end'] = mktime( 23, 59, 59, $m[2], $m[1], $m[3] );
						}
						else
						{
							$_value['date_end'] = 0;
						}

						$sth = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_special SET 
						product_id = ' . ( int )$data['product_id'] . ', 
						customer_group_id = ' . ( int )$_value['customer_group_id'] . ', 
						priority = ' . ( int )$_value['priority'] . ',
						price = ' . ( float )$_value['price'] . ',
						date_start = ' . ( int )$_value['date_start'] . ',
						date_end = ' . ( int )$_value['date_end'] );
						$sth->execute();
						$sth->closeCursor();
					}
				}
				//end insert product_special

				if( ! empty( $data['group_id'] ) )
				{
					$stmt = $db->prepare( 'INSERT INTO ' . TABLE_PRODUCT_NAME . '_items_group VALUES(' . $data['product_id'] . ', :group_id)' );
					foreach( $data['group_id'] as $group_id_i )
					{
						$stmt->bindParam( ':group_id', $group_id_i, PDO::PARAM_STR );
						$stmt->execute();
					}
				}
				//nv_fix_group_count( $data['group_id'] );

				$auto_model = '';
				if( ! empty( $ProductGeneral->config['config_format_code_id'] ) and empty( $data['model'] ) )
				{
					$i = 1;
					$auto_model = vsprintf( $ProductGeneral->config['config_format_code_id'], $data['product_id'] );

					$stmt = $db->prepare( 'SELECT product_id FROM ' . TABLE_PRODUCT_NAME . '_product WHERE model= :model' );
					$stmt->bindParam( ':model', $auto_model, PDO::PARAM_STR );
					$stmt->execute();
 
					if( $stmt->rowCount() )
					{
					 
						$auto_model = vsprintf( $ProductGeneral->config['config_format_code_id'], ( $data['product_id'] + $i ) );
						
					}

					$stmt = $db->prepare( 'UPDATE ' . TABLE_PRODUCT_NAME . '_product SET model= :model WHERE product_id=' . $data['product_id'] );
					$stmt->bindParam( ':model', $auto_model, PDO::PARAM_STR );
					$stmt->execute();
				}
				
				$db->query('INSERT INTO ' . TABLE_PRODUCT_NAME . '_product_to_store SET product_id = ' . intval( $product_id ) . ', store_id=' . ( int )$ProductGeneral->store_id );
				
				
				$db->query( 'DELETE FROM ' . TABLE_PRODUCT_NAME . '_block WHERE product_id = ' . $data['product_id'] );

				foreach( $id_block_content as $bid_i )
				{
					$db->query( "INSERT INTO " . TABLE_PRODUCT_NAME . "_block (block_id, product_id, weight) VALUES ('" . $bid_i . "', '" . $data['product_id'] . "', '0')" );
				}

				foreach( $array_block_cat_module as $bid_i )
				{
					nv_news_fix_block( $bid_i );
				}

				$ProductGeneral->deleteCache( 'product' );
				
				$json['success'] = 'Sao chép thành công';
			}
			else
			{
				$json['errorsave'] = $lang_module['errorsave'];
				$error_key[] = 'errorsave';
			}
			unset($data);
			sleep(1);
		}

	}
	
	nv_jsonOutput( $json );
 
}
elseif( ACTION_METHOD == 'product' )
{
	$name = $nv_Request->get_string( 'name', 'get', '' );
	$json = array();

	$and = '';
	if( ! empty( $name ) )
	{
		$and .= ' AND name LIKE :name';
	}

	$sql = 'SELECT product_id, name FROM ' . TABLE_PRODUCT_NAME . '_product_description  
	WHERE language_id = ' . $ProductGeneral->current_language_id . $and . '
	ORDER BY name DESC LIMIT 0, 5';

	$sth = $db->prepare( $sql );

	if( ! empty( $name ) )
	{
		$sth->bindValue( ':name', '%' . $name . '%' );
	}
	$sth->execute();
	while( list( $product_id, $name ) = $sth->fetch( 3 ) )
	{
		$json[] = array( 'product_id' => $product_id, 'name' => nv_htmlspecialchars( $name ) );
	}
	nv_jsonOutput( $json );
}
elseif( ACTION_METHOD == 'delete' )
{
	$json = array();
	$product_id = $nv_Request->get_int( 'product_id', 'post', 0 );
	$token = $nv_Request->get_title( 'token', 'post', '' );
	
	$listid = $nv_Request->get_string( 'listid', 'post', '' );
	if( $listid != '' and md5( $global_config['sitekey'] . session_id() ) == $token )
	{
		$del_array = array_map( 'intval', explode( ',', $listid ) );
	}
	elseif( $token == md5( $global_config['sitekey'] .  session_id() . $product_id ) )
	{
		$del_array = array( $product_id );
	}
	$_del_array = $no_del_array = array();
	if( ! empty( $del_array ) )
	{
		$a = 0;
		foreach( $del_array as $product_id )
		{
		
			delete_product( $product_id );
			
			$_del_array[] = $product_id;	
			$json['id'][$a] = $product_id;			
			++$a;
		}
		$count = sizeof( $del_array );
		if( $count )
		{
			nv_insert_logs( NV_LANG_DATA, $module_name, 'log_del_product', "product_id " . $listid, $admin_info['userid'] );
			
			$nv_Request->unset_request( $module_data . '_success', 'session' );
			
			$json['success'] = $lang_ext['success_delete'] ;
		}	 
		
		
	}else
	{
		$json['error'] = $lang_ext['error_delete'];
	}
	nv_jsonOutput( $json );
}
 
$base_url_sort = $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op;
 
 
// $category_id = $nv_Request->get_int( 'category_id', 'get', 0 );
$per_page_old = $nv_Request->get_int( 'per_page', 'cookie', 50 );
$per_page = $nv_Request->get_int( 'per_page', 'get', $per_page_old );
$page = $nv_Request->get_int( 'page', 'get', 1 );

if( $per_page < 1 and $per_page > 500 )
{
	$per_page = 50;
}

if( $per_page_old != $per_page )
{
	$nv_Request->set_Cookie( 'per_page', $per_page, NV_LIVE_COOKIE_TIME );
}

$sort = $nv_Request->get_string( 'sort', 'get', '' );
$order = $nv_Request->get_string( 'order', 'get' ) == 'asc' ? 'asc' : 'desc';

$sort_data = array(
	'b.name',
	'a.date_added',
	'a.status',
	'a.price',
	'a.quantity' );

$data['name'] = $nv_Request->get_string( 'name', 'get', '' );
$data['status'] = $nv_Request->get_string( 'status', 'get', '*' );
$data['price'] = $nv_Request->get_string( 'price', 'get', '' );
$data['quantity'] = $nv_Request->get_string( 'quantity', 'get', '' );
$data['category_id'] = $nv_Request->get_int( 'category_id', 'get', 0 );

$from = TABLE_PRODUCT_NAME . '_product a 
LEFT JOIN ' . TABLE_PRODUCT_NAME . '_product_description b ON a.product_id = b.product_id 
LEFT JOIN ' . NV_USERS_GLOBALTABLE . ' u ON a.user_id = u.userid';

$where = ' WHERE b.language_id = ' . $ProductGeneral->current_language_id;

$and = '';

if( ! empty( $data['name'] ) )
{
	$and .= ' AND b.name LIKE :name';
}
if( ! empty( $data['category_id'] ) )
{
	$and .= ' AND a.category_id=' . ( int )$data['category_id'];
}
if( is_numeric( $data['status'] ) )
{
	$and .= ' AND a.status=' . ( int )$data['status'];
}
if( ! empty( $data['price'] ) )
{
	$and .= ' AND a.price= :price';
}
if( ! empty( $data['quantity'] ) )
{
	$and .= ' AND a.quantity= :quantity';
}

$sql = $from . $where . $and;

$db->sqlreset()->select( 'COUNT(*)' )->from( $sql );

$sth = $db->prepare( $db->sql() );

if( ! empty( $data['name'] ) )
{
	$sth->bindValue( ':name', '%' . $data['name'] . '%' );
}
if( ! empty( $data['price'] ) )
{
	$sth->bindValue( ':price', $data['price'] );
}
if( ! empty( $data['quantity'] ) )
{
	$sth->bindValue( ':quantity', $data['quantity'] );
}

$sth->execute();

$num_items = $sth->fetchColumn();

if( isset( $sort ) && in_array( $sort, $sort_data ) )
{

	$sql .= " ORDER BY " . $sort;
}
else
{
	$sql .= " ORDER BY date_added";
}

if( isset( $order ) && ( $order == 'desc' ) )
{
	$sql .= " DESC";
}
else
{
	$sql .= " ASC";
}

$q = nv_substr( $nv_Request->get_title( 'q', 'get', '' ), 0, NV_MAX_SEARCH_LENGTH );

$checkss = $nv_Request->get_string( 'checkss', 'get', '' );

if( $checkss == md5( session_id() ) )
{
	// Tim theo tu khoa
	if( $stype == 'product_code' )
	{
		$from .= " WHERE product_code LIKE '%" . $db->dblikeescape( $q ) . "%' ";
	}
	elseif( in_array( $stype, $array_in_rows ) and ! empty( $q ) )
	{
		$from .= " WHERE " . NV_LANG_DATA . "_" . $stype . " LIKE '%" . $db->dblikeescape( $q ) . "%' ";
	}
	elseif( $stype == 'admin_id' and ! empty( $q ) )
	{
		$sql = "SELECT userid FROM " . NV_USERS_GLOBALTABLE . " WHERE userid IN (SELECT admin_id FROM " . NV_AUTHORS_GLOBALTABLE . ") AND username LIKE '%" . $db->dblikeescape( $q ) . "%' OR full_name LIKE '%" . $db->dblikeescape( $q ) . "%'";
		$result = $db->query( $sql );
		$array_admin_id = array();
		while( list( $admin_id ) = $result->fetch( 3 ) )
		{
			$array_admin_id[] = $admin_id;
		}
		$from .= " WHERE user_id IN (0," . ( ! empty( $array_admin_id ) ? implode( ",", $array_admin_id ) : 0 ) . ",0)";
	}
	elseif( ! empty( $q ) )
	{
		$sql = "SELECT userid FROM " . NV_USERS_GLOBALTABLE . " WHERE userid IN (SELECT admin_id FROM " . NV_AUTHORS_GLOBALTABLE . ") AND username LIKE '%" . $db->dblikeescape( $q ) . "%' OR full_name LIKE '%" . $db->dblikeescape( $q ) . "%'";
		$result = $db->query( $sql );

		$array_admin_id = array();
		while( list( $admin_id ) = $result->fetch( 3 ) )
		{
			$array_admin_id[] = $admin_id;
		}

		$arr_from = array();
		$arr_from[] = "(product_code LIKE '%" . $db->dblikeescape( $q ) . "%')";
		foreach( $array_in_rows as $val )
		{
			$arr_from[] = "(" . NV_LANG_DATA . "_" . $val . " LIKE '%" . $db->dblikeescape( $q ) . "%')";
		}
		$from .= " WHERE ( " . implode( " OR ", $arr_from );
		if( ! empty( $array_admin_id ) )
		{
			$from .= ' OR (user_id IN (0,' . implode( ',', $array_admin_id ) . ',0))';
		}
		$from .= ' )';
	}

	// Tim theo loai san pham
	if( ! empty( $category_id ) )
	{
		if( empty( $q ) )
		{
			$from .= ' WHERE';
		}
		else
		{
			$from .= ' AND';
		}

		if( $productCategory[$category_id]['numsubcat'] == 0 )
		{
			$from .= ' listcategory_id=' . $category_id;
		}
		else
		{
			$array_cat = array();
			$array_cat = getCatidInParent( $category_id );
			$from .= ' category_id IN (' . implode( ',', $array_cat ) . ')';
		}
	}
}


$base_url .='&sort=' . $sort . '&order=' . $order . '&per_page=' . $per_page;

$order2 = ( $order == 'asc' ) ? 'desc' : 'asc';


$xtpl = new XTemplate( 'items.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file . '/product' );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LANGE', $lang_ext );
$xtpl->assign( 'AddMenu', AddMenu( ) );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_FILE', $module_file );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'DATA', $data );
$xtpl->assign( 'TOKEN', md5( $global_config['sitekey'] . session_id() ) );
$xtpl->assign( 'ADD_NEW', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=product&action=add' );
$xtpl->assign( 'URL_SEARCH', NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '='. $op .'&action=product' );

 
$xtpl->assign( 'URL_NAME', $base_url_sort . '&amp;sort=b.name&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_DATE_ADDED', $base_url_sort . '&amp;sort=a.date_added&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_STATUS', $base_url_sort . '&amp;sort=a.status&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_PRICE', $base_url_sort . '&amp;sort=a.price&amp;order=' . $order2 . '&amp;per_page=' . $per_page );
$xtpl->assign( 'URL_QUANTITY', $base_url_sort . '&amp;sort=a.quantity&amp;order=' . $order2 . '&amp;per_page=' . $per_page );

$xtpl->assign( 'NAME_ORDER', ( $sort == 'b.name' ) ? 'class="' . $order2 . '"' : '' );
$xtpl->assign( 'DATE_ADDED_ORDER', ( $sort == 'a.date_added' ) ? 'class="' . $order2 . '"' : '' );
$xtpl->assign( 'STATUS_ORDER', ( $sort == 'a.status' ) ? 'class="' . $order2 . '"' : '' );
$xtpl->assign( 'PRICE_ORDER', ( $sort== 'a.price' ) ? 'class="' . $order2 . '"' : '' );
$xtpl->assign( 'QUANTITY_ORDER', ( $sort == 'a.quantity' ) ? 'class="' . $order2 . '"' : '' );
 

if( $nv_Request->get_string( $module_data . '_success', 'session' ) )
{
	$xtpl->assign( 'SUCCESS', $nv_Request->get_string( $module_data . '_success', 'session' ) );

	$xtpl->parse( 'main.success' );

	$nv_Request->unset_request( $module_data . '_success', 'session' );

}

foreach( $productArrayStatus as $status => $val )
{

	$selected = ( ( $status == $data['status'] ) && is_numeric( $data['status'] ) ) ? 'selected="selected"' : '';

	$xtpl->assign( 'STATUS', array(
		'selected' => $selected,
		'status' => $status,
		'name' => $val ) );
	$xtpl->parse( 'main.status' );
}
// Loai san pham
foreach( $productCategory as $cat )
{
	if( $cat['category_id'] > 0 )
	{
		$xtitle_i = '';
		if( $cat['lev'] > 0 )
		{
			for( $i = 1; $i <= $cat['lev']; $i++ )
			{
				$xtitle_i .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
			}
		}
		$cat['name'] = $xtitle_i . $cat['name'];
		$cat['selected'] = $cat['category_id'] == $data['category_id'] ? ' selected="selected"' : '';

		$xtpl->assign( 'CAT', $cat );
		$xtpl->parse( 'main.category' );
	}
}

$db->select( 'a.product_id, a.category_id, a.user_id, a.image, a.thumb, a.status, a.date_added, a.date_modified, a.quantity, a.price, a.tax_class_id, b.name, b.alias, u.username' )->from( $sql )->limit( $per_page )->offset( ( $page - 1 ) * $per_page );
 
$sth = $db->prepare( $db->sql() );

if( ! empty( $data['name'] ) )
{
	$sth->bindValue( ':name', '%' . $data['name'] . '%' );
}
if( ! empty( $data['category_id'] ) )
{
	$and .= ' AND a.category_id=' . ( int )$data['category_id'];
}
if( is_numeric( $data['status'] ) )
{
	$and .= ' AND a.status=' . ( int )$data['status'];
}
if( ! empty( $data['price'] ) )
{
	$sth->bindValue( ':price', $data['price'] );
}
if( ! empty( $data['quantity'] ) )
{
	$sth->bindValue( ':quantity', $data['quantity'] );
}
$sth->execute();

$theme = $site_mods[$module_name]['theme'] ? $site_mods[$module_name]['theme'] : $global_config['site_theme'];
$a = 0;

while( list( $product_id, $category_id, $admin_id, $image, $thumb, $status, $date_modified, $date_added, $quantity, $price, $tax_class_id, $name, $alias, $username ) = $sth->fetch( 3 ) )
{
	$date_added = nv_date( 'H:i d/m/y', $date_added );
	$date_modified = nv_date( 'H:i d/m/y', $date_modified );
	$name = nv_clean60( $name, 80 );

	$category_id_i = 0;
	if( $category_id > 0 )
	{
		$category_id_i = $category_id;
	}
	else
	{
		$category_id_i = $category_id_i;
	}

	// Xac dinh anh nho
	if( $thumb == 1 ) //image thumb
	{
		$thumb = NV_BASE_SITEURL . NV_FILES_DIR . '/' . $module_name . '/' . $image;
		$imghome = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $image;
	}
	elseif( $thumb == 2 ) //image file
	{
		$imghome = $thumb = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_name . '/' . $image;
	}
	elseif( $thumb == 3 ) //image url
	{
		$imghome = $thumb = $image;
	}
	elseif( file_exists( NV_ROOTDIR . '/themes/' . $theme . '/images/' . $module_file . '/no-image.jpg' ) )
	{
		$imghome = $thumb = NV_BASE_SITEURL . 'themes/' . $theme . '/images/' . $module_file . '/no-image.jpg';
	}
	else
	{
		$imghome = $thumb = NV_BASE_SITEURL . 'themes/default/images/' . $module_file . '/no-image.jpg';
	}

	if( $quantity == 0 )
	{
		$quantity_label = 'warning';
	}
	elseif( $quantity >= 0 && $quantity <= 5 )
	{
		$quantity_label = 'danger';
	}
	else
	{
		$quantity_label = 'success';
	}

	$special = 0;
	$query = $db->query( 'SELECT * FROM ' . TABLE_PRODUCT_NAME . '_product_special WHERE product_id = ' . ( int )$product_id . ' ORDER BY priority, price' );
	while( $product_special = $query->fetch() )
	{
		if( ( $product_special['date_start'] == 0 || $product_special['date_start'] < NV_CURRENTTIME ) && ( $product_special['date_end'] == 0 || $product_special['date_end'] > NV_CURRENTTIME ) )
		{
			$special = $product_special['price'];
			break;
		}
	}
	$token = md5( $global_config['sitekey'] . session_id() . $product_id );
 	$xtpl->assign( 'LOOP', array(
		'product_id' => $product_id,
		'link' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $productCategory[$category_id_i]['alias'] . '/' . $alias . $global_config['rewrite_exturl'],
		'edit' => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=product&action=edit&token=" . $token . "&product_id=" . $product_id,
		'name' => $name,
		'date_added' => $date_added,
		'date_modified' => $date_modified,
		'status' => $lang_module['status_' . $status],
		'admin_id' => ! empty( $username ) ? $username : '',
		'quantity' => $quantity,
		'quantity_label' => $quantity_label,
		'price' => $ProductCurrency->format( $ProductTax->calculate( $price, $tax_class_id, $ProductGeneral->config['config_tax'] ), $nv_Request->get_string( $module_data . '_currency', 'session' ) ),
		'special' => $ProductCurrency->format( $ProductTax->calculate( $special, $tax_class_id, $ProductGeneral->config['config_tax'] ), $nv_Request->get_string( $module_data . '_currency', 'session' ) ),
		'thumb' => $thumb,
		'token' => $token ) );

	if( $special )
	{
		$xtpl->parse( 'main.loop.special' );
	}
	else
	{
		$xtpl->parse( 'main.loop.price' );
	}

	$xtpl->parse( 'main.loop' );

	++$a;
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
