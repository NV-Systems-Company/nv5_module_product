<!-- BEGIN: main -->
<div class="sub-list-box">
	<!-- BEGIN: cat -->
	<h2 class="sub_title"><a href="{CAT.link}" class="cat_link">{CAT.name}</a></h2>
	
	<!-- BEGIN: subcat -->								
	<a href="{SUBCAT.link}" class="sub_link">{SUBCAT.name}</a>
	<!-- END: subcat -->	
	
	<!-- END: cat -->							
</div>
<!-- END: main -->
<!-- BEGIN: config  -->
<tr>
    <td>Danh mục chính</td>
    <td>
		<select name="config_category_id" id="config_category_id" class="form-control input-sm">
			<option value="0">Chọn danh mục chính</option>
			<!-- BEGIN: category  -->
			<option value="{CATEGORY.key}" {CATEGORY.selected} >{CATEGORY.name}</option>
		 	<!-- END: category  -->
	    	
	    </select>
    </td>
</tr>
<tr>
    <td>Danh mục con</td>
    <td id="getcat">
		<!-- BEGIN: parent -->
			<input class="form-control input-sm" type="checkbox" name="config_parent_id[]" value="{PARENT.key}" {PARENT.checked}>{PARENT.name}<br>
		<!-- END: parent -->
    </td>
</tr>
<tr>
    <td>Giới hạn danh mục</td>
    <td>
       <select name="config_target" class="form-control input-sm">
			<!-- BEGIN: target  -->
			<option value="{TARGET.key}" {TARGET.selected} >{TARGET.name}</option>
			<!-- END: target  -->
	   </select>
    </td>
</tr>
<script type="text/javascript">
$(document).on('change', '#config_category_id', function() {

	category_id = $('#config_category_id').val();
	$.ajax({
		url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '={mod_name}&' + nv_fc_variable + '=ajax&nocache=' + new Date().getTime(),
		type: 'post',
		data: 'get_cat_sub=1&category_id='+category_id,
		dataType: 'json',
		success: function(json) 
		{
			if ( json['info'] ) 
			{
				var loop = '';
				$.each( json['info'], function(i, item) {
					loop+='<input class="form-control input-sm" type="checkbox" name="config_parent_id[]" value="'+item.category_id+'" /> '+item.name+'<br>';
				});
				$('#getcat').html( loop ); 

			}
		}
	});
});
 
</script>
<!-- END: config -->