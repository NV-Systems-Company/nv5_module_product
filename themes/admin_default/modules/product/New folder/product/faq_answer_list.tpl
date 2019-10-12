<!-- BEGIN: main -->
  {AddMenu}
<div id="productcontent">
    <!-- BEGIN:  error_warning -->
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-circle"></i> {error_warning}
        <i class="fa fa-times"></i>
        <br>
    </div>
    <!-- END: error_warning -->
    
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title" style="float:left"><i class="fa fa-pencil"></i> {CAPTION}</h3>
                <div class="pull-right">
                    <!-- <button type="submit" form="form-stock" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.save}"><i class="fa fa-save"></i></button>  -->
					<a href="{CANCEL}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.cancel}"><i class="fa fa-reply"></i></a>
                </div>
                <div style="clear:both"></div>
            </div>
            <div class="panel-body">
				<h3>Câu hỏi</h3>
				<div class="highlight"> {DATA.question} </div>
				<h3>Trả lời</h3>
				<!-- BEGIN: answer -->
				<div class="highlight" id="group_{ANSWER.answer_id}"> 
					<div class="form-inline"> 
						<div class="form-group pull-left"> 
							{ANSWER.answer}
						</div>	
						<div class="form-group pull-right"> 					
							
							<a href="{ANSWER.edit_answer}" data-toggle="tooltip" title="{LANG.edit}" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a> 
 
							<a href="javascript:void(0);" onclick="delete_answer('{ANSWER.faq_id}', '{ANSWER.answer_id}', '{ANSWER.token}')" data-toggle="tooltip" title="{LANG.delete}" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i></a> 
						
						</div>
						
					</div>
					<div class="clearfix"></div>
				</div>
				<!-- END: answer -->
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/content.js"></script>
  
 
<script type="text/javascript">
  
function delete_answer(faq_id, answer_id, token) {
	if(confirm('{LANG.confirm}')) {
		$.ajax({
			url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=faq&action=delete_answer&nocache=' + new Date().getTime(),
			type: 'post',
			dataType: 'json',
			data: 'faq_id=' + faq_id + '&answer_id=' + answer_id + '&token=' + token,
			beforeSend: function() {
				$('#button-delete i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
				$('#button-delete').prop('disabled', true);
			},	
			complete: function() {
				$('#button-delete i').replaceWith('<i class="fa fa-trash-o"></i>');
				$('#button-delete').prop('disabled', false);
			},
			success: function(json) {
				$('.alert').remove();

				if (json['error']) {
					$('#productcontent').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
				}
				
				if (json['success']) {
					$('#productcontent').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
					 $.each(json['id'], function(i, id) {
						$('#group_' + id ).remove();
					});
				}		
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}
</script>
<!-- END: main -->