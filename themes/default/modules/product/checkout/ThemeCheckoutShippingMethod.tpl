<!-- BEGIN: main -->
<!-- BEGIN: error_warning -->
<div class="alert alert-warning alert-dismissible"><i class="fa fa-exclamation-circle"></i> {ERROR_WARNING}</div>
<!-- END: error_warning -->
<p>Hãy chọn thức giao hàng ưu tiên để sử dụng cho đơn đặt hàng này.</p>
<!-- BEGIN: shipping -->
<p><strong>{SHIPPING.title}</strong></p>
<!-- BEGIN: quote -->
<div class="radio">
  <label><input type="radio" name="shipping_method" value="{QUOTE.code}" {QUOTE.checked} />{QUOTE.title} - {QUOTE.text}</label>
</div>
<!-- END: quote -->

<!-- BEGIN: error -->
<div class="alert alert-danger alert-dismissible">{SHIPPING.error}</div>
<!-- END: error -->

<!-- END: shipping -->
 
<p><strong>Thêm ghi chú về đơn hàng của bạn</strong></p>
<p>
  <textarea name="comment" rows="8" class="form-control"></textarea>
</p>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="Tiếp tục" id="button-shipping-method" data-loading-text="Đang tải..." class="btn btn-primary" />
  </div>
</div>

<!-- END: main -->
