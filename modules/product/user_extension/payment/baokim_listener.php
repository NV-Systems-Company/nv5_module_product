<?php

/**
 * BAOKIM PAYMENT NOTIFICATION
 * Script này có chức năng như sau:
 * - Nhận thông báo giao dịch từ Bảo Kim (BPN)
 * - Gọi ngược thông tin nhận được trên BPN về Bảo Kim để xác minh thông tin
 * - Ghi log BPN nhận được
 * - Nếu xác minh thông tin trên BPN thành công, cập nhật (hoàn thành) đơn hàng
 *
 * Copy Right by BaoKim, Jsc 2013
 * @author hieunn
 */

class BaokimListener extends shops_global
{
	/**
	 * CẤU HÌNH HỆ THỐNG
	 * @const DIR_LOG   Đường dẫn file log. Thư mục mặc định là baokim_listener
	 * @const FILE_NAME Tên file log.
	 *
	 */
	const DIR_LOG = 'log/';
	const FILE_NAME = 'bpn'; //Phần mở rộng của file là .log

	//trạng thái giao dịch trên bảo kim: hoàn thành
	const BAOKIM_TRANSACTION_STATUS_COMPLETED = 4;

	//trạng thái giao dịch trên bảo kim: đang tạm giữ
	const BAOKIM_TRANSACTION_STATUS_TEMP_HOLDING = 13;

	/**
	 * @param $server_baokim  Trang thực hiện thanh toán
	 */
	public function index()
	{
		//Danh sách các trạng thái giao dịch có thể coi là thành công (có thể giao hàng)
		$success_transaction_status = array( self::BAOKIM_TRANSACTION_STATUS_COMPLETED, self::BAOKIM_TRANSACTION_STATUS_TEMP_HOLDING );
		$this->load->model( 'checkout/order' );

		$req = '';
		$err = true;
		$myFile = self::DIR_LOG . self::FILE_NAME . "-" . date( "d-m" ) . ".log";
		$this->isFileORDirExist( self::DIR_LOG, $myFile );
		$server_baokim = $this->config->get( 'baokim_server' );
		if( strpos( $server_baokim, 'sandbox' ) === false )
		{
			$baokim_url = 'https://www.baokim.vn/bpn/verify';
		}
		else
		{
			$baokim_url = 'http://sandbox.baokim.vn/bpn/verify';
		}

		if( $this->_isCurl() )
		{
			if( ! empty( $_POST ) )
			{
				foreach( $_POST as $key => $value )
				{
					$value = urlencode( stripslashes( $value ) );
					$req .= "&$key=$value";
				}
				$err = false;
			}
			else
			{
				$req = "=> Khong nhan duoc du lieu tu BaoKim";
				$err = true;
			}
		}
		else
		{
			$req = "=> Bạn cần chắc chắn đã bật cURL trên máy chủ";
			$err = true;
		}
		$fh = fopen( $myFile, 'a' ) or die( "can't open file" );
		fwrite( $fh, "\r\n" . "---------------------------------------------------" );
		fwrite( $fh, "\r\n" . date( "Y-m-d H:i:s", time() ) . " --- | --- " . $req );
		if( ! $err )
		{
			/**
			 * Gửi dữ liệu về Bảo Kim. Kiểm tra tính chính xác của dữ liệu
			 * @param $result Kết quả xác thực thông tin trả về.
			 * @paran $status Mã trạng thái trả về.
			 * @error $error  Lỗi được ghi vào file bpn.log
			 */
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $baokim_url );
			curl_setopt( $ch, CURLOPT_VERBOSE, 1 );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_POST, 1 );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $req );
			$result = curl_exec( $ch );
			$status = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
			$error = curl_error( $ch );
			//$result != '' && strstr($result, 'VERIFIED') && $status == 200
			if( $result != '' && strstr( $result, 'VERIFIED' ) && $status == 200 )
			{
				fwrite( $fh, ' => VERIFIED' );
				$str_id = explode( "-", $_POST['order_id'] );
				$order_id = $str_id[1];
				$transaction_id = isset( $_POST['transaction_id'] ) ? $_POST['transaction_id'] : '';
				$transaction_status = isset( $_POST['transaction_status'] ) ? $_POST['transaction_status'] : '';
				$total_amount = isset( $_POST['total_amount'] ) ? $_POST['total_amount'] : '';
				$net_amount = isset( $_POST['net_amount'] ) ? $_POST['net_amount'] : '';
				$fee_amount = isset( $_POST['fee_amount'] ) ? $_POST['fee_amount'] : '';
				$customer_name = isset( $_POST['customer_name'] ) ? $_POST['customer_name'] : '';
				$customer_address = isset( $_POST['customer_address'] ) ? $_POST['customer_address'] : '';
				$confirm = '';

				/**
				 * Kiểm tra thông tin đơn hàng và đối soát với thông tin trên BPN gồm:
				 * - Mã đơn hàng.
				 * - Số tiền giao dịch
				 * - Trạng thái giao dịch
				 *  $transaction_status 4 : giao dịch hoàn thành ; 13 : Giao dịch tạm giữ
				 */
				if( in_array( $transaction_status, $success_transaction_status ) )
				{

					//Lấy thông tin order
					$order_info = $this->model_checkout_order->getOrder( $order_id );

					//Kiểm tra sự tồn tại của đơn hàng
					if( empty( $order_info ) )
					{
						$confirm .= "\r\n" . ' Don hang khong ton tai voi ma don hang : ' . $order_id;
					}

					//Kiểm tra số tiền đã thanh toán phải >= giá trị đơn hàng
					$order_total_amount = $this->currency->format( $order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false );
					if( $total_amount < $order_total_amount )
					{
						$confirm .= "\r\n" . ' So tien thanh toan: ' . $total_amount . ' nho hon gia tri don hang : ' . $order_id;
					}

				}
				else
				{
					$confirm .= "\r\n" . ' Trang thai giao dich:' . $transaction_status . ' chua thanh cong ung voi don hang : ' . $order_id;
				}
				//TODO: trường hợp đối soát thông tin BPN thành công => hoàn thành đơn hàng (website merchant có thể edit lại phần này theo yêu cầu)
				if( $confirm == '' )
				{
					/**
					 * Trạng thái giao dịch là 4 : Cập nhật đơn hàng về trạng thái thành công
					 * Trạng thái giao dịch là 13 : Thanh toán thành công đang trong trạng thái tạm giữ
					 * Có thể thực hiện giao hàng với hai trạng thái này
					 */

					switch( $transaction_status )
					{

						case 4:
							$this->model_checkout_order->confirm( $order_id, $this->config->get( 'config_complete_status_id' ) );
							$this->model_checkout_order->update( $order_id, $this->config->get( 'config_complete_status_id' ), "Nhận BPN từ Bảo Kim, thực hiện cập nhật đơn hàng thành công", true );
							break;
						case 13:
							$this->model_checkout_order->confirm( $order_id, $this->config->get( 'baokim_order_status_id' ) );
							$this->model_checkout_order->update( $order_id, $this->config->get( 'baokim_order_status_id' ), "Nhận BPN từ Bảo Kim, thực hiện cập nhật đơn hàng thành công", true );
							break;
					}
				}
				else
				{
					//Đối soát thông tin bpn không thành công: log lỗi
					fwrite( $fh, $confirm );
					die;
				}
			}
			else
			{
				fwrite( $fh, ' => INVALID' );
			}
			if( $error )
			{
				fwrite( $fh, " | ERROR: $error" );
			}
			fwrite( $fh, "\r\n" );
			fclose( $fh );
		}
	}

	/**
	 * Hàm kiểm tra sự tồn tại của file log. Thực hiện tạo mới nếu file không tồn tại
	 * @param $dir      Tên thư mục
	 * @param $fileName Tên file
	 */
	function isFileORDirExist( $dir, $fileName )
	{
		if( $dir != '' )
		{
			if( ! is_dir( $dir ) )
			{
				mkdir( $dir );
			}
		}
		if( $fileName != '' )
		{
			if( ! file_exists( $fileName ) )
			{
				$ourFileHandle = fopen( $fileName, 'w' ) or die( "can't open file" );
				fclose( $ourFileHandle );
			}
		}
		else
		{
			die;
		}
	}

	/**
	 * Kiểm tra thư viện cURL chắc chắn được cài đặt trên máy chủ
	 * @return bool
	 */
	function _isCurl()
	{
		return function_exists( 'curl_version' );
	}

}
