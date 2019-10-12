<!-- BEGIN: main -->
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>{DATA.title}</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #000000;">
<div style="width: 680px;"><a href="{DATA.store_url}" title="{DATA.store_name}"><img src="{DATA.logo}" alt="{DATA.store_name}" style="margin-bottom: 20px; border: none;" /></a>
  <p style="margin-top: 0px; margin-bottom: 20px;">{DATA.text_greeting}</p>
  <!-- BEGIN: userid -->
  <p style="margin-top: 0px; margin-bottom: 20px;">{DATA.text_link}</p>
  <p style="margin-top: 0px; margin-bottom: 20px;"><a href="{DATA.link}">{DATA.link} </a></p>
  <!-- END: userid -->
 <!-- BEGIN: download -->
  <p style="margin-top: 0px; margin-bottom: 20px;">{DATA.text_download} </p>
  <p style="margin-top: 0px; margin-bottom: 20px;"><a href="{DATA.download}">{DATA.download}</a></p>
  <!-- END: download -->
  <table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
    <thead>
      <tr>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;" colspan="2">{DATA.text_order_detail}</td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><b>{DATA.text_order_id}</b> {DATA.order_id}<br />
          <b>{DATA.text_date_added}</b> {DATA.date_added} <br />
          <b>{DATA.text_payment_method}</b> {DATA.payment_method} <br />
          
		 <!-- BEGIN: shipping_method -->
          <b> {DATA.text_shipping_method} </b> {DATA.shipping_method} 
          <!-- END: shipping_method -->
		  
		</td>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"><b>{DATA.text_email}</b> {DATA.email}<br />
          <b>{DATA.text_telephone} </b> {DATA.telephone}<br />
          <b>{DATA.text_ip} </b> {DATA.ip}<br />
          <b>{DATA.text_order_status} </b> {DATA.order_status}<br />
		</td>
      </tr>
    </tbody>
  </table>
  <!-- BEGIN: comment -->
  <table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
    <thead>
      <tr>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;">{DATA.text_instruction}</td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">{DATA.comment}</td>
      </tr>
    </tbody>
  </table>
  <!-- END: comment --> 
  <table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
    <thead>
      <tr>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;">{DATA.text_payment_address} </td>
         <!-- BEGIN: shipping_address0 -->
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;">{DATA.text_shipping_address} </td>
        <!-- END: shipping_address0 --> 
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">{DATA.payment_address}</td>
        <!-- BEGIN: shipping_address1 --> 
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">{DATA.shipping_address}</td>
        <!-- END: shipping_address1 --> 
      </tr>
    </tbody>
  </table>
  <table style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
    <thead>
      <tr>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;">{DATA.text_product}</td>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;">{DATA.text_model}</td>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;">{DATA.text_quantity}</td>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;">{DATA.text_price}</td>
        <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;">{DATA.text_total}</td>
      </tr>
    </thead>
    <tbody>
      <!-- BEGIN: product --> 
      <tr>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">{PRODUCT.name}
			<!-- BEGIN: option -->		
			<br /> &nbsp;
			<small> - {OPTION.name}: {OPTION.value}</small>
			<!-- END: option -->	
			<!-- BEGIN: display_group -->
			<!-- BEGIN: group -->
			<br /> &nbsp;
			<small> - {group}</small>
			<!-- END: group -->
			<!-- END: display_group -->
		</td>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">{PRODUCT.model}</td>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;">{PRODUCT.quantity}</td>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;">{PRODUCT.price}</td>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;">{PRODUCT.total}</td>
      </tr>
      <!-- END: product -->
       <!-- BEGIN: voucher --> 
      <tr>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">{VOUCHER.description} </td>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"></td>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;">1</td>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;">{VOUCHER.amount}</td>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;">{VOUCHER.amount}</td>
      </tr>
       <!-- END: voucher -->
    </tbody>
    <tfoot>
     <!-- BEGIN: total -->
      <tr>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;" colspan="4"><b>{TOTAL.title}:</b></td>
        <td style="font-size: 12px;	border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;">{TOTAL.text}</td>
      </tr>
      <!-- END: total -->
    </tfoot>
  </table>
  <p style="margin-top: 0px; margin-bottom: 20px;">{DATA.text_footer}</p>
</div>
</body>
</html>
<!-- END: main -->