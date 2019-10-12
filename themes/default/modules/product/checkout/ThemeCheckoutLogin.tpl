<!-- BEGIN: main -->
<div class="row">
  <div class="col-sm-12">
    <h2>Khách hàng mới</h2>
    <p>Lựa chọn thanh toán:</p>
    <div class="radio">
      <label><input type="radio" name="account" value="register" checked="checked" />Đăng ký tài khoản mới</label>
    </div>
	<div class="radio">
      <label><input type="radio" name="account" value="guest" /> Thanh toán ngay không cần đăng ký</label>
    </div>
	<p>Khi tạo một tài khoản, bạn sẽ có thể mua sắm nhanh hơn, được cập nhật về trạng thái của đơn đặt hàng và theo dõi các đơn đặt hàng bạn đã đặt trước đó.</p>
    <input type="button" value="Tiếp tục" id="button-account" data-loading-text="Đang tải..." class="btn btn-primary" />
  </div>
  <div class="col-sm-12">
    <h2>Khách hàng đã có tài khoản</h2>
    <p>Đăng nhập nếu bạn đã có tài khoản</p>
    <div class="form-group">
      <label class="control-label" for="input-email">E-Mail</label>
      <input type="text" name="email" value="" placeholder="E-Mail" id="input-email" class="form-control" />
    </div>
    <div class="form-group">
      <label class="control-label" for="input-password">Mật khẩu</label>
      <input type="password" name="password" value="" placeholder="Password" id="input-password" class="form-control" />
      <a href="#">Quên mật khẩu</a></div>
    <input type="button" value="Đăng nhập" id="button-login" data-loading-text="Đang tải..." class="btn btn-primary" />
  </div>
</div> 
<!-- END: main -->
