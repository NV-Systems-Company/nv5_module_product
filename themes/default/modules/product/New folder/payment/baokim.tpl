<!-- BEGIN: main -->
<div class="buttons">
	<div class="pull-right">
		<input id="button-confirm" type="submit" value="{LANG.button_confirm}" class="btn btn-primary" />
	</div>
</div>
<script type="text/javascript">
<!--
$('#button-confirm').on('click', function() {
	$.ajax({ 
		type: 'GET',
		url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=payment&action=confirm&method=baokim&nocache=' + new Date().getTime(),
		success: function() 
		{
			location = '{DATA.continue}';
		}		
	});
});
//-->
</script>
<!-- END: main -->
