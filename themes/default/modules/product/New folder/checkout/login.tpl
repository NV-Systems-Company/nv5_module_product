<!-- BEGIN: main -->
<div class="row">
    <div class="col-sm-12">
        <h2>{LANGE.text_new_customer}</h2>
        <p>{LANGE.text_checkout_option}:</p>
        <div class="radio">
            <label>
                <input type="radio" name="account" value="register" checked="checked" /> {LANGE.text_register}
			</label>
        </div>
        <div class="radio">
            <label>
                <input type="radio" name="account" value="guest" /> {LANGE.text_guest}
			</label>
        </div>
        <p>{LANGE.text_register_account_help}</p>
        <input type="button" value="{LANG.button_continue}" id="button-account" data-loading-text="{LANG.button_loading}" class="btn btn-primary" />
    </div>
    <div class="col-sm-12">
        <h2>{LANGE.text_returning_customer}</h2>
        <p>{LANGE.text_i_am_returning_customer}</p>
        <div class="form-group">
            <label class="control-label" for="input-email">{LANGE.entry_email}</label>
            <input type="text" name="nv_login" value="" placeholder="{LANGE.entry_email}" id="input-email" class="form-control input-sm" autocomplete="off"/>
        </div>
        <div class="form-group">
            <label class="control-label" for="input-password">{LANGE.entry_password}</label>
            <input type="password" name="nv_password" value="" placeholder="{LANGE.entry_password}" id="input-password" class="form-control input-sm" autocomplete="off"/>
            <a href="#">{LANGE.text_forgotten}</a>
        </div>
        <input type="button" value="{LANGE.text_login}" id="button-login" data-loading-text="{LANG.button_loading}" class="btn btn-primary" />
    </div>
</div>
<!-- END: main -->

