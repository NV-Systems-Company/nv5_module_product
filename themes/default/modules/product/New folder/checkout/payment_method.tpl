<!-- BEGIN: main -->

<!-- BEGIN: warning -->
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>{WARNING}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<!-- END: warning -->
<p>{LANGE.text_payment_method}</p>

<!-- BEGIN: payment_method --> 
<div class="radio">
	<label>
		<input type="radio" name="payment_method" value="{LOOP.code}" {LOOP.checked} />
		{LOOP.title} 
	</label>
</div>
<!-- END: payment_method -->


<p><strong>{LANGE.text_comments}</strong> </p>
<p>
    <textarea name="comment" rows="8" class="form-control input-sm">{DATA.comment}</textarea>
</p>
<!-- BEGIN: text_agree -->
<div class="buttons">
    <div class="pull-right">{DATA.text_agree}
        <input type="checkbox" name="agree" value="1" {DATA.agree}/> &nbsp;
        <input type="button" value="{LANG.button_continue}" id="button-payment-method" data-loading-text="{LANG.button_loading}" class="btn btn-primary" />
    </div>
</div>
<!-- END: text_agree -->

<!-- BEGIN: text_noagree -->
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="{LANG.button_continue}" id="button-payment-method" data-loading-text="{LANG.button_loading}" class="btn btn-primary" />
  </div>
</div>
<!-- END: text_noagree -->

<!-- END: main --> 