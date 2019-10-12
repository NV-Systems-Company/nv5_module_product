<!-- BEGIN: main -->
 {AddMenu}
<div id="productcontent">
    <!-- BEGIN: units_error_warning -->
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-circle"></i> {WARNING}
        <i class="fa fa-times"></i>
        <br>
    </div>
    <!-- END: units_error_warning -->
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title" style="float:left"><i class="fa fa-pencil"></i> {CAPTION}</h3>
                <div class="pull-right">
                    <button type="submit" form="form-stock" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.save}"><i class="fa fa-save"></i>
                    </button> <a href="{CANCEL}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.cancel}"><i class="fa fa-reply"></i></a>
                </div>
                <div style="clear:both"></div>
            </div>
            <div class="panel-body">
                <form action="" method="post"  enctype="multipart/form-data" id="form-units" class="form-horizontal">
					<input type="hidden" name ="units_id" value="{DATA.units_id}" />
 					<input name="save" type="hidden" value="1" />
 
                    <div class="tab-content">
                        <div class="tab-pane active in" id="tab-general">
                            <ul class="nav nav-tabs" id="language">
                                <!-- BEGIN: looplangtab -->
                                <li>
                                    <a href="#language{LANG_KEY}" data-toggle="tab"><img src="{LANG_TITLE.image}" title="{LANG_TITLE.name}" /> {LANG_TITLE.name}</a>
                                </li>
                                <!-- END: looplangtab -->

                            </ul>
                            <div class="tab-content">
                                <!-- BEGIN: looplang -->
                                <div class="tab-pane" id="language{LANG_ID}">
                                    <div class="form-group required">
                                        <label class="col-sm-4 control-label" for="input-name{LANG_ID}">{LANGE.entry_name}</label>
                                        <div class="col-sm-20">
                                            <input type="text" name="units_description[{LANG_ID}][name]" value="{VALUE.name}" placeholder="{LANGE.name}" id="input-name{LANG_ID}" class="form-control input-sm" />
											<!-- BEGIN: units_error_name --><div class="text-danger">{units_error_name}</div><!-- END: units_error_name -->
                                        
										</div>
										
                                    </div>
 
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-description{LANG_ID}">{LANGE.entry_description} </label>
                                        <div class="col-sm-20">
                                            <textarea name="units_description[{LANG_ID}][description]" placeholder="{LANGE.entry_description}" id="input-description{LANG_ID}" class="form-control input-sm">{VALUE.description}</textarea>
                                         
                                        </div>
                                    </div>
     
                                    
                                </div>
								 
                                <!-- END: looplang -->
                            </div>
                        </div>
 
						<div align="center">
							<input class="btn btn-primary btn-sm" type="submit" value="{LANG.save}">
							<a class="btn btn-primary btn-sm" href="{CANCEL}" title="{LANG.cancel}">{LANG.cancel}</a> 
						</div>
                    </div>
                </form>
            </div>
        </div>
    </div>
  

<script type="text/javascript">
if( $('#language li').length == 1 ) 
{
	$('#language').hide();
	$('.tab-content').css('padding-top', '0px');
}
$(document).ready(function() {
	$('#language a:first').tab('show');	
});
</script>
</div>
<!-- END: main -->