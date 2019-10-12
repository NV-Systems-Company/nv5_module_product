<!-- BEGIN: main -->
<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Đăng nhập vào ChomongcaiOnline</h4>
      </div>
      <div class="modal-body">
		<form action="{USER_LOGIN}" method="post" id="logins-form">
			<div class="login-box login-ajax">
				<div class="registered-users">
					<div class="content">
						<ul class="form-list">
							<li>
								<label for="email">Địa chỉ email</label>
								<br>
								<input placeholder="Địa chỉ email" id="block_login_iavim" name="nv_login" type="text" class="form-control input-sm" Autocomplete="off">
							</li>
							<li>
								<label for="pass">Mật khẩu</label>
								<br>
								<input type="password" class="form-control input-sm"  id="block_password_iavim" name="nv_password" Autocomplete="off">
							</li>
							<!-- BEGIN: captcha -->
							<li>
								<div class="form-group text-right">
									<img id="block_vimg" src="{NV_BASE_SITEURL}index.php?scaptcha=captcha" width="{GFX_WIDTH}" height="{GFX_HEIGHT}" alt="{N_CAPTCHA}" />
									&nbsp;<em class="fa fa-pointer fa-refresh fa-lg" onclick="nv_change_captcha('block_vimg','block_seccode_iavim');">&nbsp;</em>
								</div>
								<div class="form-group">
									<div class="input-group">
										<span class="input-group-addon"><em class="fa fa-shield fa-lg fa-fix">&nbsp;</em></span>
										<input id="block_seccode_iavim" name="nv_seccode" type="text" class="form-control input-sm" maxlength="{GFX_MAXLENGTH}" placeholder="{LANG.securitycode}"/>
									</div>
								</div>
							</li>
							<!-- END: captcha -->
							<li>
								<input type="checkbox" name="login[remember]" id="keepme">
								<label for="keepme">Nhớ mật khẩu. Tự động đăng nhập vào lần sau</label>
							</li>
						</ul>

						<div class="btt-group" style="margin-top: 20px;">
							<input name="nv_redirect" value="{REDIRECT}" type="hidden" />
							<input name="nv_header" value="" type="hidden" />
 
							<button class="btn btn-primary" type="submit" name="send" id="send2"><span>Đăng nhập</span> </button>

							<span class="or-login">hoặc </span>

							<button type="button" class="btn btt_fb" onclick="openPopup('facebook', 'aHR0cDovL3Rpa2kudm4vby1jdW5nLWRpLWRvbmctd2QtbXktcGFzc3BvcnQtdWx0cmEtdXNiLTMtMC0xdGItcDg5MDg4Lmh0bWw,');">
								<i class="fa fa-facebook-square tk-i-fb" style="margin-right:6px;"></i> Đăng nhập bằng Facebook
							</button>

						</div>
							<!-- BEGIN: openid -->
							<hr />
							<p class="text-center">
								<img alt="{LANG.openid_login}" src="{OPENID_IMG_SRC}" width="{OPENID_IMG_WIDTH}" height="{OPENID_IMG_HEIGHT}" /> {LANG.openid_login} 
							</p>
							<div class="text-center">
								<!-- BEGIN: server -->
								<a title="{OPENID.title}" href="{OPENID.href}">
									&nbsp;<img alt="{OPENID.title}" src="{OPENID.img_src}" width="{OPENID.img_width}" height="{OPENID.img_height}" data-toggle="tooltip" data-placement="top" title="{OPENID.title}"/>&nbsp;
								</a>
								<!-- END: server -->
							</div>
							<!-- END: openid -->
					</div>

					<div class="login-button-set">
						<a target="_blank" class="btnForgetPw" href="#">Quên mật khẩu?</a>

						<a target="_blank" class="btnRegister" href="#">Tạo tài khoản mới</a>
					</div>
				</div>
			</div>
		</div>
      </div>
    </div>
</div>
 
<!-- END: main -->
