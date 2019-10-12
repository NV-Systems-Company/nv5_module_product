<!-- BEGIN: main -->

<!-- BEGIN: warning -->
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>{WARNING}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<!-- END: warning -->

 
<p>{LANGE.text_shipping_method}</p> 

<!-- BEGIN: shipping --> 

<p><strong>{SHIPING.title}</strong></p>

<!-- BEGIN: shipping_method --> 
<div class="radio">
  <label>
	<input type="radio" name="shipping_method" value="{LOOP.code}" {LOOP.checked} />
		{LOOP.title} - {LOOP.text}
	</label>
</div>
<!-- END: shipping_method -->

<!-- BEGIN: shipping_error --> 
<div class="alert alert-danger">{SHIPING.error}</div> 
<!-- END: shipping_error -->

<!-- END: shipping --> 
<p><strong>{LANGE.text_comments}</strong></p>
<p>
  <textarea name="comment" rows="8" class="form-control input-sm">{DATA.comment}</textarea>
</p>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="{LANG.button_continue}" id="button-shipping-method" data-loading-text="{LANG.button_loading}" class="btn btn-primary" />
  </div>
</div> 
<!-- END: main --> 
