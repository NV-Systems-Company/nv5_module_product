<!-- BEGIN: main -->
<style>
	.logo_bank {
		margin-left: 16px;
		margin-bottom: 5px;
		border: 1px solid white;
	}

	.credit-card{
		width: 415px;
	}

	.selected{
		border: 1px solid firebrick;
	}
</style>
<h2><?php echo $text_credit_card; ?></h2>
<div class="content" id="payment">
	<div class="credit-card">
		<?php $i = 0; foreach ($banks as $bank) { ?>
		<?php if($bank['payment_method_type'] == 1) { ?>
		<img src="<?php echo $bank['logo_url']  ?>" class='logo_bank' id="bank_<?php echo $bank['id'] ?>"/>
		<?php }  ?>
		<?php }  ?>
	</div>

	<input type="hidden" value="" id="baokim_bank_payment_method_id" name="baokim_bank_payment_method_id"/>
</div>
<div class="buttons">
	<div class="pull-right">
		<input type="submit" value="<?php echo $button_confirm; ?>" id="button-confirm"  class="btn btn-primary"/>
	</div>
</div>
<script type="text/javascript"><!--
	$('.logo_bank').click(function(){
		$('.logo_bank').each(function(){
			$("#"+this.id).removeClass('selected');
		});
		$("#"+this.id).addClass('selected');
		var el = (this.id).split("_");
		console.log(el[1]);
		$("#baokim_bank_payment_method_id").val(el[1]);
	});
	$('#button-confirm').on('click', function() {
		$.ajax({
			url: 'index.php?route=payment/baokim_pro/send',
			type: 'post',
			data: $('#payment :input'),
			dataType: 'json',
			beforeSend: function() {
				$('#button-confirm').attr('disabled', true);
				$('#payment').before('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
			},
			complete: function() {
				$('#button-confirm').attr('disabled', false);
				$('.attention').remove();
			},
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
				}

				if (json['next_action']) {
					location = json['redirect_url'] ? json['redirect_url'] : json['guide_url'];
				}
			}
		});
	});
	//--></script>
<!-- END: main -->