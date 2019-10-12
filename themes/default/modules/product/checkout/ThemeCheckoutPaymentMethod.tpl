<!-- BEGIN: main -->
<p>Chọn hình thức thanh toán cho đơn hàng này.</p>
<!-- BEGIN: payment -->
<div class="radio">
	<label><input type="radio" name="payment_method" value="{PAYMENT.code}" {PAYMENT.checked} />{PAYMENT.title}</label>
</div>
<!-- BEGIN: terms -->
{DATA.terms}
<!-- END: terms -->
<!-- END: payment -->
<p><strong>Ghi chú</strong></p>
<p>
  <textarea name="comment" rows="8" class="form-control"></textarea>
</p>
<div class="buttons">
	<div class="pull-right">Tôi đã đọc và đồng ý với<a href="#" class="agree"><b> Quy định và điều khoản của cửa hàng</b></a>
		<input type="checkbox" name="agree" value="1" {AGREE_CHECKED} />
		&nbsp;
		<input type="button" value="Tiếp tục" id="button-payment-method" data-loading-text="Đang tải..." class="btn btn-primary" />
	</div>
</div> 
<!-- END: main -->
