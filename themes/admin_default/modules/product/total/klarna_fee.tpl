<!-- BEGIN: main -->
{AddMenu}
<div id="productcontent">
    <!-- BEGIN: warning -->
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-circle"></i> {WARNING}<i class="fa fa-times"></i>
    </div>
    <!-- END: warning -->
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title" style="float:left"><i class="fa fa-pencil"></i> {LANGP.text_edit}</h3>
			<div class="pull-right">
				<button type="submit" form="form-stock" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.save}"><i class="fa fa-save"></i>
				</button> <a href="{BACK}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.back}"><i class="fa fa-reply"></i></a>
			</div>
			<div style="clear:both"></div>
        </div>
        <div class="panel-body">
			<form action="" method="post" enctype="multipart/form-data" id="form-flat" class="form-horizontal">
				<input type="hidden" name="save" value="1">
				<div class="form-group">
					<label class="col-sm-4 control-label" for="input-status">{LANGP.entry_status}</label>
					<div class="col-sm-20">
						<select name="klarna_fee_status" id="input-status" class="form-control input-sm">
							<!-- BEGIN: klarna_fee_status -->
							<option value="{STATUS.key}" {STATUS.selected}>{STATUS.name}</option>
							<!-- END: klarna_fee_status -->
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 control-label" for="input-sort-order">{LANGP.entry_sort_order}</label>
					<div class="col-sm-20">
						<input type="text" name="klarna_fee_sort_order" value="{DATA.klarna_fee_sort_order}" placeholder="{LANGP.entry_sort_order}" id="input-sort-order" class="form-control input-sm">	
					</div>
				</div>
			</form>
		</div>
	</div> 
</div>
<!-- END: main -->