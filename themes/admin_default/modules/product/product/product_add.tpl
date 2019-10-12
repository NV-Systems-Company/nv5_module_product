<!-- BEGIN: main -->
{AddMenu}
<div id="productcontent">
	<!-- BEGIN: error_warning -->
    <div class="alert alert-danger">
        <i class="fa fa-exclamation-circle"></i> {error_warning}<i class="fa fa-times"></i>
    </div>
    <!-- END: error_warning -->
	<div class="panel panel-default">
		<div class="panel-heading">
		<h3 class="panel-title" style="float:left"><i class="fa fa-pencil"></i> {CAPTION}</h3>
		<div class="pull-right">
			<button type="submit" form="form-stock" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANG.save}"><i class="fa fa-save"></i></button> 
			<a href="{BACK}" data-toggle="tooltip" class="btn btn-default btn-sm" title="{LANG.back}"><i class="fa fa-reply"></i></a> 
		</div>
		<div style="clear:both"></div>
		</div>
            <div class="panel-body">
                <form action="" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-general" data-toggle="tab">{LANG.general}</a> </li>

					    <li><a href="#tab-data" data-toggle="tab">{LANG.data}</a></li>
                        
						<li><a href="#tab-links" data-toggle="tab">{LANGE.entry_links}</a></li>
                        
						<li ><a href="#tab-attribute" data-toggle="tab">{LANGE.entry_attribute}</a></li>
						
						<li ><a href="#tab-option" data-toggle="tab">{LANGE.entry_option}</a></li>
                        
						<li ><a href="#tab-discount" data-toggle="tab">{LANGE.entry_discount}</a></li>
                        
						<li ><a href="#tab-special" data-toggle="tab">{LANGE.entry_special}</a></li>
                                              
						<li><a href="#tab-image" data-toggle="tab">{LANGE.entry_image}</a></li>
                        
						<li ><a href="#tab-video" data-toggle="tab">{LANGE.entry_video}</a></li>
						
						<li ><a href="#tab-extension" data-toggle="tab">{LANGE.entry_extension}</a></li>
							
						<li ><a href="#tab-reward" data-toggle="tab">{LANGE.entry_reward_points}</a></li>
                    </ul>
                    <div class="tab-content">
						
						<div class="tab-pane active" id="tab-general">
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
                                            <input type="text" name="product_description[{LANG_ID}][name]" value="{VALUE.name}" id="input-name{LANG_ID}" placeholder="{LANGE.entry_name}" class="form-control input-sm" />
											<!-- BEGIN: error_name --><div class="text-danger">{error_name}</div><!-- END: error_name -->
										</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-name1">{LANGE.entry_alias}</label>
                                        <div class="col-sm-20">
                                            <input type="text" name="product_description[{LANG_ID}][alias]" value="{VALUE.alias}" id="input-alias{LANG_ID}" placeholder="{LANGE.entry_alias}" class="form-control input-sm" />
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <label class="col-sm-4 control-label" for="input-description{LANG_ID}">{LANGE.entry_description}</label>
                                        <div class="col-sm-20">
                                            {VALUE.description}
											<!-- BEGIN: error_description --><div class="text-danger">{error_description}</div><!-- END: error_description -->
										
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-meta-title{LANG_ID}">{LANGE.entry_meta_title}</label>
                                        <div class="col-sm-20">
                                            <input type="text" name="product_description[{LANG_ID}][meta_title]" value="{VALUE.meta_title}" placeholder="{LANGE.entry_meta_title}" id="input-meta-title{LANG_ID}" class="form-control input-sm" />
											<!-- BEGIN: error_meta_title --><div class="text-danger">{error_meta_title}</div><!-- END: error_meta_title -->
											
										</div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-meta-description{LANG_ID}">{LANGE.entry_meta_description}</label>
                                        <div class="col-sm-20">
                                            <textarea name="product_description[{LANG_ID}][meta_description]" rows="3" placeholder="{LANGE.entry_meta_description}" id="input-meta-description{LANG_ID}" class="form-control input-sm">{VALUE.meta_description}</textarea>
                                        </div>
                                    </div>
 
                                    <div class="form-group">
                                        <label class="col-sm-4 control-label" for="input-tag{LANG_ID}"><span data-toggle="tooltip" title="{LANGE.help_tag}">{LANGE.entry_meta_keyword}</span>
                                        </label>
                                        <div class="col-sm-20">
											
											<input style="display: none;" class="textCtrl TagInput">
											<div style="height: 100%;" class="taggingInput textCtrl verticalShift">
												<!-- BEGIN: tags -->
												<span class="tag"><span>{TAGS} </span><a title="" href="#">x</a><input type="hidden" name="product_description[{LANG_ID}][tag][]" value="{KEYWORDS}"></span> 
												<!-- END: tags -->
												<div class="addTag"><input autocomplete="off" class="AcSingle" style="width: 100%;" id="GetTag" value="" data-value=""></div>
												<div class="clearfix"></div>
											</div>
											 
                                         </div>
                                    </div>
								</div>
								<!-- BEGIN: getalias -->
								<script type="text/javascript">
									$("#input-name{LANG_ID}").change(function() {
										 shops_get_alias({LANG_ID});
									});
								</script>
								<!-- END: getalias -->
								<!-- END: looplang -->
							</div>
						</div>
                        
						<div class="tab-pane" id="tab-data">
                            
							<div class="form-group">
                                <label class="col-sm-4 control-label" for="input-code">{LANGE.entry_model}</label>
                                <div class="col-sm-20">
                                    <input type="text" name="model" value="{DATA.model}" placeholder="{LANGE.entry_model}" id="input-code" class="form-control input-sm" />
									<!-- BEGIN: error_model --><div class="text-danger">{error_model}</div><!-- END: error_model -->
								</div>
                            </div>
                           <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-barcode">{LANGE.entry_barcode}</label>
                                <div class="col-sm-20">
                                    <input type="text" name="barcode" value="{DATA.barcode}" placeholder="{LANGE.entry_barcode}" id="input-barcode" class="form-control input-sm" />
									<!-- BEGIN: error_barcode --><div class="text-danger">{error_barcode}</div><!-- END: error_barcode -->
								</div>
                            </div>
                            <div class="form-group required">
                                <label class="col-sm-4 control-label" for="input-price">{LANGE.entry_price} </label>
                                <div class="col-sm-20">
                                    <input type="text" name="price" value="{DATA.price}" placeholder="{LANGE.entry_price}" id="input-price" class="form-control input-sm w100 pricenumber" style="display:inline-block;font-weight:bold"/> 
									<label><input type="checkbox" name="showprice" value="1" {ck_showprice} id="input-showprice" class="form-control input-sm" /> {LANGE.entry_showprice}</label>
									
									<!-- BEGIN: error_price --><div class="text-danger">{error_price}</div><!-- END: error_price -->
                                </div>
                            </div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-tax-class">{LANGE.entry_tax_class}</label>
								<div class="col-sm-20">
									<select name="tax_class_id" id="input-tax-class" class="form-control input-sm">
										<option value="0"> ========== </option>
										<!-- BEGIN: tax_class -->
										<option value="{TAX_CLASS.key}" {TAX_CLASS.selected}>{TAX_CLASS.name}</option>
										<!-- END: tax_class -->
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-minimum"><span data-toggle="tooltip" title="{LANGE.help_minimum}">{LANGE.entry_minimum}</span></label>
								<div class="col-sm-20">
									<input type="text" name="minimum" value="{DATA.minimum}" placeholder="{LANGE.entry_minimum}" id="input-minimum" class="form-control input-sm">
								</div>
							</div>
                            <div class="form-group required">
                                <label class="col-sm-4 control-label" for="input-quantity">
								{LANGE.entry_quantity}
								<!-- BEGIN: edit1 --><br> {DATA.quantity2}<!-- END: edit1 -->
								</label>
                                <div class="col-sm-20">
									<!-- BEGIN: edit -->
									<select name="quantity_prefix" class="form-control input-sm">
										<!-- BEGIN: quantity_prefix -->
										<option value="{QX.quantity_prefix}" {QX.selected}>{QX.quantity_prefix}</option>						
										<!-- END: quantity_prefix -->						
									</select>
								    <input type="text" name="quantity" value="{DATA.quantity}" placeholder="{LANGE.entry_quantity}" id="input-quantity" class="form-control input-sm" />
                                	<!-- END: edit -->
									<!-- BEGIN: add -->
										<input type="text" name="quantity" value="{DATA.quantity}" placeholder="{LANGE.entry_quantity}" id="input-quantity" class="form-control input-sm" />
                                	<!-- END: add -->
                                </div>
                            </div>
							<div class="form-group">
                                <label class="col-sm-4 control-label">{LANGE.entry_units}</label>
                                <div class="col-sm-20">
                                    <select class="form-control input-sm" name="units_id">
										<!-- BEGIN: units -->
										<option value="{UNITS.key}" {UNITS.selected}>{UNITS.name}</option>
										<!-- END: units -->
									</select>
                                    <!-- BEGIN: error_units --><div class="text-danger">{error_units}</div><!-- END: error_units -->
                                </div>
                            </div>						
							<div class="form-group">
                                <label class="col-sm-4 control-label" for="input-stock-status"><span data-toggle="tooltip" title="{LANGE.help_stock_status}">{LANGE.entry_stock_status}</span>
                                </label>
                                <div class="col-sm-20">
                                    <select name="stock_status_id" id="input-stock-status" class="form-control input-sm">
                                        <!-- BEGIN: stock_status -->
										<option value="{STOCK_STATUS.key}" {STOCK_STATUS.selected}>{STOCK_STATUS.name}</option>
										<!-- END: stock_status -->
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">{LANGE.entry_shipping}</label>
                                <div class="col-sm-20">
                                    <!-- BEGIN: shipping -->
									<label class="radio-inline">
                                        <input type="radio" name="shipping" value="{SHIPPING.key}" {SHIPPING.checked} /> {SHIPPING.name} 
									</label>
									<!-- END: shipping -->
                                </div>
                            </div>
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-length">{LANGE.entry_dimension}</label>
								<div class="col-sm-20">
									<div class="row">
										<div class="col-sm-4">
											<input type="text" name="length" value="{DATA.length}" placeholder="{LANGE.entry_length}" id="input-length" class="form-control input-sm">
										</div>
										<div class="col-sm-4">
											<input type="text" name="width" value="{DATA.width}" placeholder="{LANGE.entry_width}" id="input-width" class="form-control input-sm">
										</div>
										<div class="col-sm-4">
											<input type="text" name="height" value="{DATA.height}" placeholder="{LANGE.entry_height}" id="input-height" class="form-control input-sm">
										</div>
										<div class="col-sm-5">
											<select name="length_class_id" id="input-length-class" class="form-control input-sm">
												<!-- BEGIN: length_class -->
												<option value="{LENGTH.key}" {LENGTH.selected}>{LENGTH.name}</option>
												<!-- END: length_class -->
											</select>
										</div>
									</div>
								</div>
							</div> 
 
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-weight">{LANGE.entry_weight}</label>
								<div class="col-sm-20">
									<div class="row">
										<div class="col-sm-4">
											<input type="text" name="weight" value="{DATA.weight}" placeholder="{LANGE.entry_weight}" id="input-weight" class="form-control input-sm">
										</div>
										<div class="col-sm-4">
											<select name="weight_class_id" id="input-weight-class" class="form-control input-sm">
												<!-- BEGIN: weight_class -->
												<option value="{WEIGHT.key}" {WEIGHT.selected}>{WEIGHT.name}</option>
												<!-- END: weight_class -->
											</select>
										</div>
									</div>
								</div>
							</div>

							<div class="form-group">
                                <label class="col-sm-4 control-label"><span data-toggle="tooltip" title="{LANGE.help_subtract}">{LANGE.entry_subtract}</span> </label>
                                <div class="col-sm-20">
                                    <select class="form-control input-sm" name="subtract">
										<!-- BEGIN: subtract -->
										<option value="{SUBTRACT.key}" {SUBTRACT.selected}>{SUBTRACT.name}</option>
										<!-- END: subtract -->
									</select>
                                 </div>
                            </div>							
                            
							<div class="form-group">
                                <label class="col-sm-4 control-label" for="input-layout">{LANGE.entry_layout}</label>
                                <div class="col-sm-20">
									<select name="layout" id="input-layout" class="form-control input-sm">
										<option value=""> {LANG.default} </option>
										<!-- BEGIN: layout -->
										<option value="{LAYOUT.key}" {LAYOUT.selected}>{LAYOUT.key}</option>
										<!-- END: layout -->
									</select>
								</div>
							</div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-sort-order">{LANGE.entry_status}</label>
                                <div class="col-sm-20">
                                   <select class="form-control input-sm" name="status">
										<!-- BEGIN: status -->
										<option value="{STATUS.key}" {STATUS.selected} >{STATUS.name}</option>
										<!-- END: status -->	 
								   </select>
 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-sort-order">{LANGE.entry_sort_order}</label>
                                <div class="col-sm-20">
                                   <input type="text" value="{DATA.sort_order}" name="sort_order" id="input-sort-order" class="form-control input-sm"/>
 
                                </div>
                            </div>
                        </div>
						
					    <div class="tab-pane" id="tab-links">
                            <div class="form-group">
								<label class="col-sm-4 control-label" for="input-category"><span data-toggle="tooltip" title="(Autocomplete)">{LANGE.entry_category}</span></label>
								<div class="col-sm-20" style="position:relative">
								  <i class="fa fa-times clearCategory" aria-hidden="true"></i>
								  <input type="text" name="category" value="{DATA.category}" placeholder="{LANGE.entry_category}" id="input-category" class="form-control input-sm" />
								  <input type="hidden" name="category_id" value="{DATA.category_id}" id="input-category-id" class="form-control input-sm" />
								  <!-- BEGIN: error_category --><div class="text-danger">{error_category}</div><!-- END: error_category --> 
								</div>				
							</div>
							<div class="form-group">
                                <label class="col-sm-4 control-label" for="input-brand">{LANGE.entry_brand} </label>
                                <div class="col-sm-20">
                                    <select class="form-control input-sm" name="brand_id" >
										<option value="0" > ========== </option>
										<!-- BEGIN: brand -->
										<option value="{BRAND.key}" {BRAND.selected} >{BRAND.name}</option>
										<!-- END: brand -->
									</select>
                                </div>
                            </div>
							
							<div class="form-group">
								<label class="col-sm-4 control-label" for="input-block"><span data-toggle="tooltip" title="{LANGE.help_block}">{LANGE.entry_block}</span>
								</label>
								<div class="col-sm-20">
									<input type="text" name="block" value="" placeholder="{LANGE.entry_block}" id="input-block" class="form-control input-sm" />
									<div id="product-block" class="well well-sm" style="height: 100px; overflow: auto;">
										<!-- BEGIN: block -->
										<div id="product-block{BLOCK.filter_id}"><i class="fa fa-minus-circle"></i> {BLOCK.name}
										<input type="hidden" name="product_block[]" value="{BLOCK.block_id}"></div>
										<!-- END: block -->
									</div>
								</div>
							</div>
                            <div class="form-group">
								<label class="col-sm-4 control-label" for="input-filter"><span data-toggle="tooltip" title="{LANGE.help_filter}">{LANGE.entry_filter}</span>
								</label>
								<div class="col-sm-20">
									<input type="text" name="filter" value="" placeholder="{LANGE.entry_filter}" id="input-filter" class="form-control input-sm" />
									<div id="product-filter" class="well well-sm" style="height: 130px; overflow: auto;">
										<!-- BEGIN: filter -->
										<div id="product-filter{FILTER.filter_id}"><i class="fa fa-minus-circle"></i> {FILTER.name}
										<input type="hidden" name="product_filter[]" value="{FILTER.filter_id}"></div>
										<!-- END: filter -->
									</div>
								</div>
							</div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label" for="input-related"><span data-toggle="tooltip" title="{LANGE.help_related}">{LANGE.entry_related}</span>
                                </label>
                                <div class="col-sm-20">
                                    <input type="text" name="related" value="" placeholder="{LANGE.entry_related}" id="input-related" class="form-control input-sm" />
                                    <div id="product-related" class="well well-sm" style="height: 130px; overflow: auto;">
										<!-- BEGIN:related -->
										<div id="product-related{RELATED.related_id}"><i class="fa fa-minus-circle"></i> {RELATED.name}
										<input type="hidden" name="product_related[]" value="{RELATED.related_id}"></div>
										<!-- END:related -->
									</div>
                                </div>
                            </div>
                        </div>
                        
						<div class="tab-pane" id="tab-attribute">
							<div class="table-responsive">
								<table id="attribute" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left" style="min-width: 200px;">{LANGE.entry_attribute}</td>
											<td class="text-left">{LANGE.entry_text}</td>
											<td></td>
										</tr>
									</thead>
									<tbody>
										<!-- BEGIN: product_attribute -->
										<tr id="attribute-row{ATB.key}">
											<td class="text-left">
												<input type="text" name="product_attribute[{ATB.key}][name]" value="{ATB.name}" placeholder="{LANGE.entry_attribute}" class="form-control input-sm getAttribute" data-id="{ATB.key}" autocomplete="off">
												<input type="hidden" name="product_attribute[{ATB.key}][attribute_id]" value="{ATB.attribute_id}">
											</td>
											<td class="text-left">
												<!-- BEGIN: languages -->
												<div class="input-group"><span class="input-group-addon"><img src="{LANG_IMAGE}" title="{LANG_TITLE}"></span>
													<textarea name="product_attribute[{ATB.key}][product_attribute_description][{LANG_ID}][text]" rows="1" placeholder="{LANGE.entry_text}" class="form-control input-sm">{TEXT}</textarea>
												</div>
												<!-- END: languages -->
											</td>
											<td class="text-left">
												<button type="button" onclick="$('#attribute-row{ATB.key}').remove();" data-toggle="tooltip" title="{LANG.remove}" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button>
											</td>
										</tr>
										<!-- END: product_attribute -->
									</tbody>
									<tfoot>
										<tr>
											<td colspan="2"></td>
											<td class="text-left">
												<button type="button" onclick="addAttribute();" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANGE.entry_attribute_add}"><i class="fa fa-plus-circle"></i></button>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						
					    <div class="tab-pane" id="tab-option">
							<div class="row">
								<div class="col-sm-4">
									<ul class="nav nav-pills nav-stacked nav-shops" id="option">
										<!-- BEGIN:product_option1 -->
										<li class="{OPTION.active}" ><a href="#tab-option{OPTION.num}" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$('a[href=\'#tab-option{OPTION.num}\']').parent().remove(); $('#tab-option{OPTION.num}').remove(); $('#option a:first').tab('show');"></i> {OPTION.name}</a></li>
										<!-- END:product_option1 -->
										<li>
											<input type="text" name="option" value="" placeholder="{LANGE.entry_option}" id="input-option" class="form-control input-sm" />
										</li>
									</ul>
								</div>
								<div class="col-sm-20">
									<div class="tab-content">
										<!-- BEGIN: product_option2 -->
										<div class="tab-pane {OPTION.active}" id="tab-option{OPTION.num}">
											<input type="hidden" name="product_option[{OPTION.num}][product_option_id]" value="{OPTION.product_option_id}" />
											<input type="hidden" name="product_option[{OPTION.num}][name]" value="{OPTION.name}" />
											<input type="hidden" name="product_option[{OPTION.num}][option_id]" value="{OPTION.option_id}" />
											<input type="hidden" name="product_option[{OPTION.num}][type]" value="{OPTION.type}" />
											<div class="form-group">
												<label class="col-sm-4 control-label" for="input-required{OPTION.num}">{LANGE.entry_required}</label>
												<div class="col-sm-20">
													<select name="product_option[{OPTION.num}][required]" id="input-required{OPTION.num}" class="form-control input-sm">
														<!-- BEGIN:required -->
															<option value="{RQ.key}" {RQ.selected}>{RQ.required}</option>
														<!-- END:required -->
													</select>
												</div>
											</div>
											<!-- BEGIN:text -->
											<div class="form-group">
												<label class="col-sm-4 control-label" for="input-value{OPTION.num}">{LANGE.entry_option_value}</label>
												<div class="col-sm-20">
													<input type="text" name="product_option[{OPTION.num}][value]" value="" placeholder="{LANGE.entry_option_value}" id="input-value{OPTION.num}" class="form-control input-sm" />
												</div>
											</div>
											<!-- END:text -->
											
											<!-- BEGIN:textarea -->
											<div class="form-group">
												<label class="col-sm-4 control-label" for="input-value{OPTION.num}">{LANGE.entry_option_value}</label>
												<div class="col-sm-20">
													<textarea name="product_option[{OPTION.num}][value]" rows="5" placeholder="{LANGE.entry_option_value}" id="input-value{OPTION.num}" class="form-control input-sm"></textarea>
												</div>
											</div>
											<!-- END:textarea -->
											
											<!-- BEGIN:file -->
											<div class="form-group" style="none">
												<label class="col-sm-4 control-label" for="input-value{OPTION.num}">{LANGE.entry_option_value}</label>
												<div class="col-sm-20">
													<input type="text" name="product_option[{OPTION.num}][value]" value="" placeholder="{LANGE.entry_option_value}" id="input-value{OPTION.num}" class="form-control input-sm" />
												</div>
											</div>
											<!-- END:file -->
											
											<!-- BEGIN:date -->
											<div class="form-group">
												<label class="col-sm-4 control-label" for="input-value{OPTION.num}">{LANGE.entry_option_value}</label>
												<div class="col-sm-3">
													<div class="input-group date">
														<input type="text" name="product_option[{OPTION.num}][value]" value="2011-02-20" placeholder="{LANGE.entry_option_value}" data-format="YYYY-MM-DD" id="input-value{OPTION.num}" class="form-control input-sm" />
														<span class="input-group-btn">
															<button class="btn btn-default btn-sm" type="button"><i class="fa fa-calendar"></i></button>
														</span>
													</div>
												</div>
											</div>
											<!-- END:date -->
											
											<!-- BEGIN:time -->
											<div class="form-group">
												<label class="col-sm-4 control-label" for="input-value{OPTION.num}">{LANGE.entry_option_value}</label>
												<div class="col-sm-20">
													<div class="input-group time">
														<input type="text" name="product_option[{OPTION.num}][value]" value="" placeholder="{LANGE.entry_option_value}" data-format="HH:mm" id="input-value{OPTION.num}" class="form-control input-sm" />
														<span class="input-group-btn">
															<button type="button" class="btn btn-default btn-sm"><i class="fa fa-calendar"></i></button>
														</span>
													</div>
												</div>
											</div>
											<!-- END:time -->
											
											<!-- BEGIN:datetime -->
											<div class="form-group">
												<label class="col-sm-4 control-label" for="input-value{OPTION.num}">{LANGE.entry_option_value}</label>
												<div class="col-sm-20">
													<div class="input-group datetime">
														<input type="text" name="product_option[{OPTION.num}][value]" value="" placeholder="{LANGE.entry_option_value}" data-format="DD/MM/YYYY/ HH:mm" id="input-value{OPTION.num}" class="form-control input-sm" />
														<span class="input-group-btn">
															<button type="button" class="btn btn-default btn-sm"><i class="fa fa-calendar"></i></button>
														</span>
													</div>
												</div>
											</div>
											<!-- END:datetime -->
											
											<!-- BEGIN:select -->
											<div class="table-responsive">
												<table id="option-value{OPTION.num}" class="table table-striped table-bordered table-hover">
													<thead>
														<tr>
															<td class="text-left"><strong>{LANGE.entry_option_value}</strong></td>
															<td class="text-right"><strong>{LANGE.entry_quantity}</strong></td>
															<td class="text-left"><strong>{LANGE.entry_subtract}</strong></td>
															<td class="text-right"><strong>{LANGE.entry_price}</strong></td>
															<td class="text-right"><strong>{LANGE.entry_points}</strong></td>
															<td class="text-right"><strong>{LANGE.entry_weight}</strong></td>
															<td></td>
														</tr>
													</thead>
													<tbody>
													<!-- BEGIN: loop -->
														<tr id="option-value-row{OPTIONVALUE.option_value_row}">
															<td class="text-left">
																<select name="product_option[{OPTION.num}][product_option_value][{OPTIONVALUE.option_value_row}][option_value_id]" class="form-control input-sm">
																	<!-- BEGIN:option_values1 -->
																	<option value="{OPV1.option_value_id}" {OPV1.selected}>{OPV1.name}</option>
																	<!-- END:option_values1 -->
																</select>
																<input type="hidden" name="product_option[{OPTION.num}][product_option_value][{OPTIONVALUE.option_value_row}][product_option_value_id]" value="{OPTIONVALUE.product_option_value_id}" />
															</td>
															<td class="text-right">
																<input type="text" name="product_option[{OPTION.num}][product_option_value][{OPTIONVALUE.option_value_row}][quantity]" value="{OPTIONVALUE.quantity}" placeholder="{LANGE.entry_quantity}" class="form-control input-sm" />
															</td>
															<td class="text-left">
																<select name="product_option[{OPTION.num}][product_option_value][{OPTIONVALUE.option_value_row}][subtract]" class="form-control input-sm">
																	<!-- BEGIN:subtract -->
																	<option value="{SUT.key}" {SUT.selected}>{SUT.subtract}</option>
																	<!-- END:subtract -->
																</select>
															</td>
															<td class="text-right">
																<select name="product_option[{OPTION.num}][product_option_value][{OPTIONVALUE.option_value_row}][price_prefix]" class="form-control input-sm">
																	<!-- BEGIN:price_prefix -->
																	<option value="{PPX.price_prefix}" {PPX.selected}>{PPX.price_prefix}</option>
																	<!-- END:price_prefix -->
																</select>
																<input type="text" name="product_option[{OPTION.num}][product_option_value][{OPTIONVALUE.option_value_row}][price]" value="{OPTIONVALUE.price}" placeholder="{LANGE.entry_price}" class="form-control input-sm" />
															</td>
															<td class="text-right">
																<select name="product_option[{OPTION.num}][product_option_value][{OPTIONVALUE.option_value_row}][points_prefix]" class="form-control input-sm">
																	<!-- BEGIN:points_prefix -->
																	<option value="{PPOX.points_prefix}" {PPOX.selected}>{PPOX.points_prefix}</option>
																	<!-- END:points_prefix -->
																</select>
																<input type="text" name="product_option[{OPTION.num}][product_option_value][{OPTIONVALUE.option_value_row}][points]" value="{OPTIONVALUE.points}" placeholder="{LANGE.entry_points}" class="form-control input-sm" />
															</td>
															<td class="text-right">
																<select name="product_option[{OPTION.num}][product_option_value][{OPTIONVALUE.option_value_row}][weight_prefix]" class="form-control input-sm">
																	<!-- BEGIN:weight_prefix -->
																	<option value="{WPX.weight_prefix}" {WPX.selected}>{WPX.weight_prefix}</option>
																	<!-- END:weight_prefix -->
																</select>
																<input type="text" name="product_option[{OPTION.num}][product_option_value][{OPTIONVALUE.option_value_row}][weight]" value="{OPTIONVALUE.weight}" placeholder="{LANGE.entry_weight}" class="form-control input-sm" />
															</td>
															<td class="text-left">
																<button type="button" onclick="$('#option-value-row{OPTIONVALUE.option_value_row}').remove();" data-toggle="tooltip" title="{LANGE.entry_remove}" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i>
																</button>
															</td>
														</tr>
													<!-- END:loop -->
													</tbody>
													
													<tfoot>
														<tr>
															<td colspan="6"></td>
															<td class="text-left">
																<button type="button" onclick="addOptionValue('{OPTION.num}');$(this).tooltip('destroy');" data-toggle="tooltip" title="{LANGE.entry_add_option_value}" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i></button>
															</td>
														</tr>
													</tfoot>
												</table>
											</div>
											<select id="option-values{OPTION.num}" style="display: none;">
												<!-- BEGIN:option_values -->
												<option value="{OPV.option_value_id}">{OPV.name}</option>
												<!-- END:option_values -->
											</select>
											<!-- END:select -->
											
										</div>
										<!-- END:product_option2 -->
									</div>
								</div>
							</div>
						</div>
                        
						<div class="tab-pane" id="tab-discount">
                            <div class="table-responsive">
                                <table id="discount" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <td class="text-left"><strong>{LANGE.entry_customer_group}</strong></td>
                                            <td class="text-right"><strong>{LANGE.entry_quantity}</strong></td>
                                            <td class="text-right"><strong>{LANGE.entry_priority}</strong></td>
                                            <td class="text-right"><strong>{LANGE.entry_price}</strong></td>
                                            <td class="text-left"><strong>{LANGE.entry_date_start}</strong></td>
                                            <td class="text-left"><strong>{LANGE.entry_date_end}</strong></td>
                                            <td><strong>{LANGE.entry_action}</strong></td>
                                        </tr>
                                    </thead>
                                    <tbody>
										<!-- BEGIN: discount -->
										<tr id="discount-row{DISCOUNT.key}">
											<td class="text-left">
												<select name="product_discount[{DISCOUNT.key}][customer_group_id]" class="form-control input-sm">
													<!-- BEGIN: discount_groups -->
													<option value="{GROUPS.customer_group_id}" {GROUPS.selected}>{GROUPS.name}</option>
													<!-- END: discount_groups -->
												</select>
											</td>
											<td class="text-right">
												<input type="text" name="product_discount[{DISCOUNT.key}][quantity]" value="{DISCOUNT.value.quantity}" placeholder="{LANGE.entry_quantity}" class="form-control input-sm">
											</td>
											<td class="text-right">
												<input type="text" name="product_discount[{DISCOUNT.key}][priority]" value="{DISCOUNT.value.priority}" placeholder="{LANGE.entry_priority}" class="form-control input-sm">
											</td>
											<td class="text-right">
												<input type="text" name="product_discount[{DISCOUNT.key}][price]" value="{DISCOUNT.value.price}" placeholder="{LANGE.entry_price}" class="form-control input-sm">
											</td>
											<td class="text-left removeimage">
												<input type="text" id="date_start_{DISCOUNT.key}" name="product_discount[{DISCOUNT.key}][date_start]" value="{DISCOUNT.value.date_start}" placeholder="{LANGE.entry_date_start}" class="form-control input-sm date">
											</td>
											<td class="text-left removeimage">
												<input type="text" id="date_end_{DISCOUNT.key}" name="product_discount[{DISCOUNT.key}][date_end]" value="{DISCOUNT.value.date_end}" placeholder="{LANGE.entry_date_end}" class="form-control input-sm date">

											</td>
											<td class="text-left">
												<button type="button" onclick="$('#discount-row{DISCOUNT.key}').remove();" data-toggle="tooltip" title="{LANG.delete}" class="btn btn-danger btn-sm">
													<i class="fa fa-minus-circle"></i>
												</button>
											</td>
										</tr>
										<!-- END: discount -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6"></td>
                                            <td class="text-left">
                                                <button type="button" onclick="addDiscount();" data-toggle="tooltip" title="{LANGE.entry_add_discount}" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        
						<div class="tab-pane" id="tab-special">
							<div class="table-responsive">
								<table id="special" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left"><strong>{LANGE.entry_customer_group}</strong></td>
											<td class="text-right"><strong>{LANGE.entry_priority}</strong></td>
											<td class="text-right"><strong>{LANGE.entry_price}</strong></td>
											<td class="text-left"><strong>{LANGE.entry_date_start}</strong></td>
											<td class="text-left"><strong>{LANGE.entry_date_end}</strong></td>
											<td><strong>{LANGE.entry_action}</strong></td>
										</tr>
									</thead>
									<tbody>
										<!-- BEGIN: special -->
										<tr id="special-row{SPECIAL.key}">
											<td class="text-left">
												<select name="product_special[{SPECIAL.key}][customer_group_id]" class="form-control input-sm">
													<!-- BEGIN: special_groups -->
													<option value="{GROUPS.customer_group_id}" {GROUPS.selected}>{GROUPS.name}</option>
													<!-- END: special_groups -->
												</select>
											</td>
											<td class="text-right">
												<input type="text" name="product_special[{SPECIAL.key}][priority]" value="{SPECIAL.value.priority}" placeholder="{LANGE.entry_priority}" class="form-control input-sm">
											</td>
											<td class="text-right">
												<input type="text" name="product_special[{SPECIAL.key}][price]" value="{SPECIAL.value.price}" placeholder="{LANGE.entry_price}" class="form-control input-sm">
											</td>
											<td class="text-left removeimage">
												<input type="text" id="sdate_start_{SPECIAL.key}" name="product_special[{SPECIAL.key}][date_start]" value="{SPECIAL.value.date_start}" placeholder="{LANGE.entry_date_start}" class="form-control input-sm date"> 
											</td>
											<td class="text-left removeimage">
												<input type="text" id="sdate_end_{SPECIAL.key}" name="product_special[{SPECIAL.key}][date_end]" value="{SPECIAL.value.date_end}" placeholder="{LANGE.entry_date_end}" class="form-control input-sm date"> 
											</td>
											<td class="text-left">
												<button type="button" onclick="$('#special-row{SPECIAL.key}').remove();" data-toggle="tooltip" title="{LANG.delete}" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i>
												</button>
											</td>
										</tr>
										<!-- END: special -->
									</tbody>
									<tfoot>
										<tr>
											<td colspan="5"></td>
											<td class="text-left">
												<button type="button" onclick="addSpecial();" data-toggle="tooltip" title="{LANGE.entry_add_special}" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i>
												</button>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						
						<div class="tab-pane" id="tab-image">
 
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left">{LANGE.entry_image}</td>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td class="text-left">
												<a href="" id="thumb-image" data-toggle="image" rel="" class="productThumb" ><img src="{DATA.thumb}" alt="" title="" data-placeholder="{NV_BASE_SITEURL}themes/{THEME}/images/{MODULE_FILE}/no_image.png">
													<input type="hidden" name="image" value="{DATA.image}" data-old="{DATA.image}" id="input-image">											
												</a>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="table-responsive">
                                <table id="images" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <td class="text-left"><strong>{LANGE.entry_image_other}</strong></td>
                                            <td class="text-right"><strong>{LANGE.entry_sort_order}</strong></td>
                                            <td><strong>{LANGE.entry_action}</strong></td>
                                        </tr>
                                    </thead>
                                    <tbody>
										<!-- BEGIN: product_image -->
										<tr id="image-row{IMG.key}">
											<td class="text-left">
												<a href="#" id="thumb-image{IMG.key}" data-toggle="image" rel="{IMG.key}" class="productThumb"><img src="{IMG.value.thumb}" alt="" title="" data-placeholder="/themes/admin_default/images/product/no_image.png">
													<input type="hidden" name="product_image[{IMG.key}][image]" value="{IMG.value.image}" data-old="{IMG.value.image}" id="input-image{IMG.key}">
												</a>
											</td>
											<td class="text-right">
												<input type="text" name="product_image[{IMG.key}][sort_order]" value="{IMG.value.sort_order}" placeholder="{LANGE.entry_sort_order}" class="form-control input-sm">
											</td>
											<td class="text-left">
												<button type="button" onclick="$('#image-row{IMG.key}').remove();" data-toggle="tooltip" title="{LANG.delete}" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i>
												</button>
											</td>
										</tr>
										<!-- END: product_image -->
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2"></td>
                                            <td class="text-left">
                                                <button type="button" onclick="addImage();" data-toggle="tooltip" title="{LANGE.entry_add_image}" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>						
						
						<div class="tab-pane" id="tab-video">
							<div class="table-responsive">
								<table id="video" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left" style="min-width: 200px;">{LANGE.entry_video}</td>
											<td class="text-left">{LANGE.entry_video_url}</td>
											<td>{LANGE.entry_text}</td>
											<td></td>
										</tr>
									</thead>
									<tbody>
										<!-- BEGIN: product_video -->
										<tr id="video-row{VIDEO.key}">
											<td class="col-sm-1 text-center" id="ApplyVideo{VIDEO.key}"><img id="video-image{VIDEO.key}" onclick="showvideo({VIDEO.key})" style="max-width: 100%; cursor:pointer" src="{VIDEO.thumb}">
												<div id="frame{VIDEO.key}" class="video-container" style="display: none">
													<iframe width="560" height="315" src="//www.youtube.com/embed/{VIDEO.youtube_id}?rel=0" frameborder="0" allowfullscreen=""></iframe>
												</div>
											</td>
											<td class="col-sm-3 text-left">
												<input type="hidden" name="product_video[{VIDEO.key}][video_id]" value="{VIDEO.video_id}">
												<input type="hidden" id="thumbvideo{VIDEO.key}" name="product_video[{VIDEO.key}][thumb]" value="{VIDEO.thumb}">
												<input type="text" id="getvideo{VIDEO.key}" name="product_video[{VIDEO.key}][url]" value="{VIDEO.url}" placeholder="Video url" class="form-control input-sm"> 
												<a class="btn btn-primary btn-sm" onclick="get_info_video({VIDEO.key})" href="javascript:void(0);" >Lấy thông tin</a> 
											</td>
											<td class="col-sm-7 text-left">
												<!-- BEGIN: languages -->
												<div class="input-group"><span class="input-group-addon"><img src="{LANG_IMAGE}" title="{LANG_TITLE}"></span>
													<input type="text" id="video-title{VIDEO.key}" name="product_video[{VIDEO.key}][product_video_description][{LANG_ID}][name]" value="{NAME}" placeholder="Tiêu đề" class="form-control input-sm"> </div>
												<div class="input-group"><span class="input-group-addon"><img src="{LANG_IMAGE}" title="{LANG_TITLE}"></span>
													<textarea id="video-description{VIDEO.key}" name="product_video[{VIDEO.key}][product_video_description][{LANG_ID}][description]" rows="2" placeholder="Miêu tả" class="form-control input-sm">{DESCRIPTION}</textarea>
												</div>
												<!-- END: languages -->
											</td>
											<td class="col-sm-1 text-left">
												<button type="button" onclick="$('#video-row{VIDEO.key}').remove();" data-toggle="tooltip" title="" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i> </button>
											</td>
										</tr>
										<!-- END: product_video -->
									</tbody>
									<tfoot>
										<tr>
											<td colspan="3"></td>
											<td class="text-left">
												<button type="button" onclick="addVideo();" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANGE.entry_video_add}"><i class="fa fa-plus-circle"></i>
												</button>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						
						<div class="tab-pane" id="tab-extension">
							<div class="table-responsive">
								<table id="extension" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left">{LANGE.entry_info}</td>
											<td></td>
										</tr>
									</thead>
									<tbody>
										<!-- BEGIN: product_extension -->
										<tr id="extension-row{EXTENSION.key}">
											<td class="col-sm-11 text-left">
												<!-- BEGIN: languages -->
												<div class="input-group"><span class="input-group-addon"><img src="{LANG_IMAGE}" title="{LANG_TITLE}"></span>
													{INFO}
												</div>
												<!-- END: languages -->
											</td>
											<td class="col-sm-1 text-left" style="vertical-align: middle;">
												<button type="button" onclick="$('#extension-row{EXTENSION.key}').remove();" data-toggle="tooltip" title="{LANGE.delete}" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i> </button>
											</td>
										</tr>
										<!-- END: product_extension -->
 
										
									</tbody>
									<tfoot>
										<tr>
											<td colspan="1"></td>
											<td class="text-left">
												<button type="button" onclick="addExtension();" data-toggle="tooltip" class="btn btn-primary btn-sm" title="{LANGE.entry_extension_add}"><i class="fa fa-plus-circle"></i>
												</button>
											</td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
												
						<div class="tab-pane" id="tab-reward">
                            <div class="form-group">
                                <label class="col-lg-2 control-label" for="input-points"><span data-toggle="tooltip" title="{LANGE.help_points}">{LANGE.entry_points}</span>
                                </label>
                                <div class="col-lg-10">
                                    <input type="text" name="points" value="{DATA.points}" placeholder="{LANGE.entry_points}" id="input-points" class="form-control input-sm" />
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <td class="text-left"><strong>{LANGE.entry_customer_group}</strong></td>
                                            <td class="text-right"><strong>{LANGE.entry_reward_points}</strong></td>
                                        </tr>
                                    </thead>
                                    <tbody>
										<!-- BEGIN: customer_groups -->
                                        <tr>
                                            <td class="text-left">{GROUPS.name}</td>
                                            <td class="text-right">
                                                <input type="text" name="product_reward[{GROUPS.customer_group_id}][points]" value="{GROUPS.points}" class="form-control input-sm" />
                                            </td>
                                        </tr>
                                        <!-- END: customer_groups -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
 
						<div class="text-center" style="margin-top: 10px">
							<input type="hidden" value="1" name="save">
							<input name="product_id" type="hidden" value="{DATA.product_id}">
							<input class="btn btn-primary btn-sm" type="submit" value="{LANG.save}" /> 
							<a href="{BACK}"  class="btn btn-default btn-sm" title="{LANG.cancel}">{LANG.cancel} </a>						 
						</div>
                    </div>
			</form>
		</div>
	</div>
</div>

<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/i18n/{NV_LANG_INTERFACE}.js"></script>

<script type="text/javascript">
if( $('#language li').length == 1 ) {$('#language').hide()}
$(document).ready(function() {
	$('#language a:first').tab('show');
});
</script> 
<!-- BEGIN: languages_tag -->
<script type="text/javascript">
$('#GetTag').autofill({
	'source': function(request, response) {
 
		if( $('#GetTag').val().length > 1 )
		{	 
			$.ajax({
				url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=product&action=getTags&tag=' +  encodeURIComponent(request) + '&nocache=' + new Date().getTime(),
				dataType: 'json',
				success: function(json) {
					 
					response($.map(json, function( item ) {
					
						return {
							label: item['tag'],
							value: item['tag']
						}
					}));
				}
			});
		}
	},
	'select': function(item) {
 
		$('.addTag').before('<span class="tag"><span>'+ item['value'].replace(',', '') +' </span><a title="" href="#">x</a></span>');	 
		$('#GetTag').val('').focus();
	}
}); 

$('body').on('click','span.tag a', function(e) {	
	$(this).parent().remove();	
	e.preventDefault(); 	  
});

$('#GetTag').on('keypress, keydown, keyup', function(event) {
	
	if ( event.which != 188 ) 
	{
		$(this).attr('data-value', $(this).val() ); 
	}
	if ( event.which == 188 ) 
	{
		$('ul.dropdown-menu.template').empty().hide();
		var tag = $(this).attr('data-value');
		if( tag.length > 1 )
		{
			tag = tag.replace(',', '');
			$('.addTag').before('<span class="tag"><span>'+ tag +' </span><a title="" href="#">x</a><input type="hidden" name="product_description[{LANG_ID}][tag][]" value="'+ tag +'"></span>');	
			
		}
		$('#GetTag').val('').focus();
	} 
});
</script> 
<!-- END: languages_tag -->
<script type="text/javascript">
$('input[name=\'category\']').autofill({
	'source': function(request, response) {
		$.ajax({
			url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=product&action=getCategory&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['category_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'category\']').val(item['label']);
		$('input[name=\'category_id\']').val(item['value']);
		$('.clearCategory').show();
	}
});
$('.clearCategory').on('click', function(){
	$('input[name=\'category\']').val('');
	$('input[name=\'category_id\']').val(0);
	$(this).hide();
})
</script> 
<script type="text/javascript">
// Block
$('input[name=\'block\']').autofill({
    'source': function(request, response) {
        $.ajax({
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=product&action=getBlock&block_name=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['block_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'block\']').val('');

        $('#product-block' + item['value']).remove();

        $('#product-block').append('<div id="product-block' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_block[]" value="' + item['value'] + '" /></div>');
    }
});

$('#product-block').delegate('.fa-minus-circle', 'click', function() {
    $(this).parent().remove();
});
</script>
<script type="text/javascript">
// Filter
$('input[name=\'filter\']').autofill({
    'source': function(request, response) {
        $.ajax({
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=product&action=getFilter&filter_name=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['filter_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('input[name=\'filter\']').val('');

        $('#product-filter' + item['value']).remove();

        $('#product-filter').append('<div id="product-filter' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_filter[]" value="' + item['value'] + '" /></div>');
    }
});

$('#product-filter').delegate('.fa-minus-circle', 'click', function() {
    $(this).parent().remove();
});
</script>
  
<script type="text/javascript">
// Related
$('input[name=\'related\']').autofill({
	'source': function(request, response) {
		$.ajax({
			url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=product&action=getRelated&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'related\']').val('');
		
		$('#product-related' + item['value']).remove();
		
		$('#product-related').append('<div id="product-related' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_related[]" value="' + item['value'] + '" /></div>');	
	}	
});

$('#product-related').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});	
</script>
<script type="text/javascript">	
var option_row = {option_row};
$('input[name=\'option\']').autofill({
    'source': function(request, response) {
        $.ajax({
            url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=product&action=getOption&filter_name=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        category: item['category'],
                        label: item['name'],
                        value: item['option_id'],
                        type: item['type'],
                        option_value: item['option_value']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        html = '<div class="tab-pane" id="tab-option' + option_row + '">';
        html += '	<input type="hidden" name="product_option[' + option_row + '][product_option_id]" value="" />';
        html += '	<input type="hidden" name="product_option[' + option_row + '][name]" value="' + item['label'] + '" />';
        html += '	<input type="hidden" name="product_option[' + option_row + '][option_id]" value="' + item['value'] + '" />';
        html += '	<input type="hidden" name="product_option[' + option_row + '][type]" value="' + item['type'] + '" />';

        html += '	<div class="form-group">';
        html += '	  <label class="col-sm-4 control-label" for="input-required' + option_row + '">{LANGE.entry_required}</label>';
        html += '	  <div class="col-sm-20"><select name="product_option[' + option_row + '][required]" id="input-required' + option_row + '" class="form-control input-sm">';
        html += '	      <option value="1">{LANG.yes}</option>';
        html += '	      <option value="0">{LANG.no}</option>';
        html += '	  </select></div>';
        html += '	</div>';

        if (item['type'] == 'text') {
            html += '	<div class="form-group">';
            html += '	  <label class="col-sm-4 control-label" for="input-value' + option_row + '">{LANGE.entry_option_value}</label>';
            html += '	  <div class="col-sm-20"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="{LANGE.entry_option_value}" id="input-value' + option_row + '" class="form-control input-sm" /></div>';
            html += '	</div>';
        }

        if (item['type'] == 'textarea') {
            html += '	<div class="form-group">';
            html += '	  <label class="col-sm-4 control-label" for="input-value' + option_row + '">{LANGE.entry_option_value}</label>';
            html += '	  <div class="col-sm-20"><textarea name="product_option[' + option_row + '][value]" rows="5" placeholder="{LANGE.entry_option_value}" id="input-value' + option_row + '" class="form-control input-sm"></textarea></div>';
            html += '	</div>';
        }

        if (item['type'] == 'file') {
            html += '	<div class="form-group" style="display: none;">';
            html += '	  <label class="col-sm-4 control-label" for="input-value' + option_row + '">{LANGE.entry_option_value}</label>';
            html += '	  <div class="col-sm-20"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="{LANGE.entry_option_value}" id="input-value' + option_row + '" class="form-control input-sm" /></div>';
            html += '	</div>';
        }

        if (item['type'] == 'date') {
            html += '	<div class="form-group">';
            html += '	  <label class="col-sm-4 control-label" for="input-value' + option_row + '">{LANGE.entry_option_value}</label>';
            html += '	  <div class="col-sm-3"><div class="input-group date"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="{LANGE.entry_option_value}" id="input-value' + option_row + '" class="form-control input-sm" /><span class="input-group-btn"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-calendar"></i></button></span></div></div>';
            html += '	</div>';
        }

        if (item['type'] == 'time') {
            html += '	<div class="form-group">';
            html += '	  <label class="col-sm-4 control-label" for="input-value' + option_row + '">{LANGE.entry_option_value}</label>';
            html += '	  <div class="col-sm-20"><div class="input-group time"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="{LANGE.entry_option_value}" id="input-value' + option_row + '" class="form-control input-sm" /><span class="input-group-btn"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-calendar"></i></button></span></div></div>';
            html += '	</div>';
        }

        if (item['type'] == 'datetime') {
            html += '	<div class="form-group">';
            html += '	  <label class="col-sm-4 control-label" for="input-value' + option_row + '">{LANGE.entry_option_value}</label>';
            html += '	  <div class="col-sm-20"><div class="input-group datetime"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="{LANGE.entry_option_value}" id="input-value' + option_row + '" class="form-control input-sm" /><span class="input-group-btn"><button type="button" class="btn btn-default btn-sm"><i class="fa fa-calendar"></i></button></span></div></div>';
            html += '	</div>';
        }

        if (item['type'] == 'select' || item['type'] == 'radio' || item['type'] == 'checkbox' || item['type'] == 'image') {
            html += '<div class="table-responsive">';
            html += '  <table id="option-value' + option_row + '" class="table table-striped table-bordered table-hover">';
            html += '  	 <thead>';
            html += '      <tr>';
            html += '        <td class="text-left"><strong>{LANGE.entry_option_value}</strong></td>';
            html += '        <td class="text-right"><strong>{LANGE.entry_quantity}</strong></td>';
            html += '        <td class="text-left"><strong>{LANGE.entry_subtract}</strong></td>';
            html += '        <td class="text-right"><strong>{LANGE.entry_price}</strong></td>';
            html += '        <td class="text-right"><strong>{LANGE.entry_points}</strong></td>';
            html += '        <td class="text-right"><strong>{LANGE.entry_weight}</strong></td>';
            html += '        <td></td>';
            html += '      </tr>';
            html += '  	 </thead>';
            html += '  	 <tbody>';
            html += '    </tbody>';
            html += '    <tfoot>';
            html += '      <tr>';
            html += '        <td colspan="6"></td>';
            html += '        <td class="text-left"><button type="button" onclick="addOptionValue(' + option_row + ');" data-toggle="tooltip" title="{LANGE.entry_add_option_value}" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i></button></td>';
            html += '      </tr>';
            html += '    </tfoot>';
            html += '  </table>';
            html += '</div>';

            html += '  <select id="option-values' + option_row + '" style="display: none;">';

            for (i = 0; i < item['option_value'].length; i++) {
                html += '  <option value="' + item['option_value'][i]['option_value_id'] + '">' + item['option_value'][i]['name'] + '</option>';
            }

            html += '  </select>';
            html += '</div>';
        }

        $('#tab-option .tab-content').append(html);

        $('#option > li:last-child').before('<li><a href="#tab-option' + option_row + '" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$(\'a[href=\\\\\'#tab-option' + option_row + '\\\\\']\').parent().remove(); $(\'#tab-option' + option_row + '\').remove(); $(\'#option a:first\').tab(\'show\')"></i> ' + item['label'] + '</li>');

        $('#option a[href=\'#tab-option' + option_row + '\']').tab('show');
		
		if( (item['type'] == 'date') || (item['type'] == 'time') || (item['type'] == 'datetime')  )
		{
			$("#input-value" + option_row+"" ).datepicker({
				showOn : "both",
				dateFormat : "dd/mm/yy",
				changeMonth : true,
				changeYear : true,
				showOtherMonths : true,
				buttonImage : nv_base_siteurl + "{NV_ASSETS_DIR}/images/calendar.gif",
				buttonImageOnly : true
			});
		}
        option_row++;
    }
});

var option_value_row = {option_value_row};

function addOptionValue(option_row) {
    html = '<tr id="option-value-row' + option_value_row + '">';
    html += '  <td class="text-left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value_id]" class="form-control input-sm">';
    html += $('#option-values' + option_row).html();
    html += '  </select><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][product_option_value_id]" value="" /></td>';
    html += '  <td class="text-right"><input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][quantity]" value="" placeholder="{LANGE.entry_quantity}" class="form-control input-sm" /></td>';
    html += '  <td class="text-left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][subtract]" class="form-control input-sm">';
    html += '    <option value="1">{LANG.yes}</option>';
    html += '    <option value="0">{LANG.no}</option>';
    html += '  </select></td>';
    html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price_prefix]" class="form-control input-sm">';
    html += '    <option value="+">+</option>';
    html += '    <option value="-">-</option>';
    html += '  </select>';
    html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price]" value="" placeholder="{LANGE.entry_price}" class="form-control input-sm" /> </td>';
    html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points_prefix]" class="form-control input-sm">';
    html += '    <option value="+">+</option>';
    html += '    <option value="-">-</option>';
    html += '  </select>';
    html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points]" value="" placeholder="{LANGE.entry_points}" class="form-control input-sm" /></td>';
    html += '  <td class="text-right"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight_prefix]" class="form-control input-sm">';
    html += '    <option value="+">+</option>';
    html += '    <option value="-">-</option>';
    html += '  </select>';
    html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight]" value="" placeholder="{LANGE.entry_weight}" class="form-control input-sm" /></td>';
    html += '  <td class="text-left"><button type="button" onclick="$(this).tooltip(\'destroy\');$(\'#option-value-row' + option_value_row + '\').remove();" data-toggle="tooltip" title="{LANG.delete}" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button></td>';
    html += '</tr>';

    $('#option-value' + option_row + ' tbody').append(html);

    option_value_row++;
}
</script>
<script type="text/javascript">
	var discount_row = {discount_row};

	function addDiscount() {
		html = '<tr id="discount-row' + discount_row + '">';
		html += '  <td class="text-left"><select name="product_discount[' + discount_row + '][customer_group_id]" class="form-control input-sm">';
		<!-- BEGIN: customer_group -->
		html += '    <option value="{GROUP.customer_group_id}">{GROUP.name}</option>';
		<!-- END: customer_group -->
		html += '  </select></td>';
		html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][quantity]" value="" placeholder="{LANGE.entry_quantity}" class="form-control input-sm" /></td>';
		html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][priority]" value="" placeholder="{LANGE.entry_priority}" class="form-control input-sm" /></td>';
		html += '  <td class="text-right"><input type="text" name="product_discount[' + discount_row + '][price]" value="" placeholder="{LANGE.entry_price}" class="form-control input-sm" /></td>';
		html += '  <td class="text-left removeimage"><input type="text" id="date_start_' + discount_row + '" name="product_discount[' + discount_row + '][date_start]" value="" placeholder="{LANGE.entry_date_start}" class="form-control input-sm date" /></td>';
		html += '  <td class="text-left removeimage"><input type="text" id="date_end_' + discount_row + '" name="product_discount[' + discount_row + '][date_end]" value="" placeholder="{LANGE.entry_date_end}" class="form-control input-sm date" /></td>';
		html += '  <td class="text-left"><button type="button" onclick="$(\'#discount-row' + discount_row + '\').remove();" data-toggle="tooltip" title="{LANG.delete}" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button></td>';
		html += '</tr>';

		$('#discount tbody').append(html);
		$(".date").datepicker({
			showOn : "both",
			dateFormat : "dd/mm/yy",
			changeMonth : true,
			changeYear : true,
			showOtherMonths : true,
			buttonImage : nv_base_siteurl + "{NV_ASSETS_DIR}/images/calendar.gif",
			buttonImageOnly : true
		});
		discount_row++;
		
	}
</script>
<script type="text/javascript">
var special_row = {special_row};
function addSpecial() {
	html  = '<tr id="special-row' + special_row + '">'; 
    html += '  <td class="text-left"><select name="product_special[' + special_row + '][customer_group_id]" class="form-control input-sm">';
        <!-- BEGIN: customer_group2 -->
		html += '    <option value="{GROUP.customer_group_id}">{GROUP.name}</option>';
		<!-- END: customer_group2 -->
        html += '  </select></td>';		
    html += '  <td class="text-right"><input type="text" name="product_special[' + special_row + '][priority]" value="" placeholder="{LANGE.entry_priority}" class="form-control input-sm" /></td>';
	html += '  <td class="text-right"><input type="text" name="product_special[' + special_row + '][price]" value="" placeholder="{LANGE.entry_price}" class="form-control input-sm" /></td>';
    html += '  <td class="text-left removeimage"><input type="text" id="sdate_start_' + special_row+'" name="product_special[' + special_row + '][date_start]" value="" placeholder="{LANGE.entry_date_start}" class="form-control input-sm date" /></td>';
	html += '  <td class="text-left removeimage"><input type="text" id="sdate_end_' + special_row+'" name="product_special[' + special_row + '][date_end]" value="" placeholder="{LANGE.entry_date_end}" class="form-control input-sm date" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#special-row' + special_row + '\').remove();" data-toggle="tooltip" title="{LANGE.entry_remove}" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#special tbody').append(html);

	$(".date").datepicker({
		showOn : "both",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
		buttonImage : nv_base_siteurl + "{NV_ASSETS_DIR}/images/calendar.gif",
		buttonImageOnly : true
	});
		
	special_row++;
}
</script> 
<script type="text/javascript">
	var image_row = {image_row};
	function addImage() {
		html = 	'<tr id="image-row' + image_row + '">'; 
		html += '	<td class="text-left">';
		html += '		<a href="" id="thumb-image' + image_row + '" data-toggle="image" rel="' + image_row + '" class="productThumb" ><img src="{NV_BASE_SITEURL}themes/{THEME}/images/{MODULE_FILE}/no_image.png" alt="" title="" data-placeholder="{NV_BASE_SITEURL}themes/{THEME}/images/{MODULE_FILE}/no_image.png">';
		html += '			<input type="hidden" name="product_image[' + image_row + '][image]" value="" data-old="" id="input-image' + image_row + '">';											
		html += '		</a>';
		html += '	</td>';
		html += '	<td class="text-right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="" placeholder="{LANGE.entry_sort_order}" class="form-control input-sm" /></td>';
		html += '	<td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row + '\').remove();" data-toggle="tooltip" title="{LANG.delete}" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button></td>';
		html += '</tr>';
		$('#images tbody').append(html);
		image_row++;
	}
</script>
<script type="text/javascript">
    var attribute_row = {attribute_row};

    function addAttribute() {
        html = '<tr id="attribute-row' + attribute_row + '">';
        html += '  <td class="text-left"><input type="text" name="product_attribute[' + attribute_row + '][name]" value="" data-id="' + attribute_row + '" placeholder="{LANGE.entry_attribute}" class="form-control input-sm getAttribute" /><input type="hidden" name="product_attribute[' + attribute_row + '][attribute_id]" value="" /></td>';
        html += '  <td class="text-left">';
		<!-- BEGIN: languages_attribute -->
        html += '<div class="input-group"><span class="input-group-addon"><img src="{LANG_IMAGE}" title="{LANG_TITLE}" /></span><textarea name="product_attribute[' + attribute_row + '][product_attribute_description][{LANG_ID}][text]" rows="1" placeholder="{LANGE.entry_text}" class="form-control input-sm"></textarea></div>';
        <!-- END: languages_attribute -->
         
		html += '  </td>';
        html += '  <td class="text-left"><button type="button" onclick="$(\'#attribute-row' + attribute_row + '\').remove();" data-toggle="tooltip" title="{LANG.remove}" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#attribute tbody').append(html);
 
        ++attribute_row;
    }

    function getAttribute(attribute_row) {
        $('input[name=\'product_attribute[' + attribute_row + '][name]\']').autofill({
            'source': function(request, response) {
                $.ajax({
                    url: script_name + '?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=product&action=getAttribute&filter_name=' + encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                category: item.attribute_group,
                                label: item.name,
                                value: item.attribute_id
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                $('input[name=\'product_attribute[' + attribute_row + '][name]\']').val(item['label']);
                $('input[name=\'product_attribute[' + attribute_row + '][attribute_id]\']').val(item['value']);
                $('#attribute-row' + attribute_row ).find('ul.dropdown-menu').remove();
            }
        });
    }
	$(document).on('focus', '.getAttribute', function(e){
		var id = $(this).attr('data-id');
		getAttribute(id);
	})

     
</script>
<script type="text/javascript">
var video_row = {video_row};

function addVideo() {
		html = '<tr id="video-row' + video_row + '">';
		html += '    <td class="col-sm-1 text-center" id="ApplyVideo' + video_row + '">';	
		html += '        <img style="max-width: 100%" src="{NV_BASE_SITEURL}themes/{THEME}/images/{MODULE_FILE}/no_image.png" alt="" title="" data-placeholder="{NV_BASE_SITEURL}themes/{THEME}/images/{MODULE_FILE}/no_image.png">';
		html += '    </td>';
		html += '    <td class="col-sm-3 text-left">';
		html += '        <input type="hidden" name="product_video[' + video_row + '][video_id]" value="">';
		html += '        <input type="hidden" id="thumbvideo' + video_row + '" name="product_video[' + video_row + '][thumb]" value="">';
		html += '        <input type="text" id="getvideo' + video_row + '" name="product_video[' + video_row + '][url]" value="" placeholder="Video url" class="form-control input-sm">';
		html += '        <a class="btn btn-primary btn-sm" onclick="get_info_video(' + video_row + ')" href="javascript:void(0);" >Lấy thông tin</a>';
		html += '    </td>';
		html += '    <td class="col-sm-7 text-left">';
		<!-- BEGIN: languages_video -->
		html += '        <div class="input-group"><span class="input-group-addon"><img src="{LANG_IMAGE}" title="{LANG_TITLE}" /></span>';
		html += '            <input type="text" id="video-title' + video_row + '" name="product_video[' + video_row + '][product_video_description][{LANG_ID}][name]" value="" placeholder="Tiêu đề" class="form-control input-sm">';
		html += '        </div>';
		html += '        <div class="input-group"><span class="input-group-addon"><img src="{LANG_IMAGE}" title="{LANG_TITLE}" /></span>';
		html += '            <textarea id="video-description' + video_row + '" name="product_video[' + video_row + '][product_video_description][{LANG_ID}][description]" rows="2" placeholder="Miêu tả" class="form-control input-sm"></textarea>';
		html += '        </div>';
		<!-- END: languages_video -->
		html += '    </td>';
		html += '    <td class="col-sm-1 text-left">';
		html += '        <button type="button" onclick="$(\'#video-row' + video_row + '\').remove();" data-toggle="tooltip" title="" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i>';
		html += '        </button>';
		html += '    </td>';
		html += '</tr>'; 
       
	   $('#video tbody').append(html);

       video_row++;
}

function get_info_video( key )
{
	var url = $('#getvideo'+ key).val(); 
	if( youtube( url ) )
	{
		var id = youtube(url);
 
		var url = "http://gdata.youtube.com/feeds/api/videos?q="+id+"&max-results=1&v=2&alt=jsonc";
		var title;
		var description;
		$.getJSON(url,
			function(response){
				title = response.data.items[0].title;
				duration = response.data.items[0].duration;
				description = response.data.items[0].description;
				thumbnail = response.data.items[0].thumbnail['hqDefault'];
				urlnew = response.data.items[0].content['5'];
				var frame = '<img id="video-image'+key+'" onclick="showvideo('+key+')" style="max-width: 100%; cursor:pointer" src="'+thumbnail+'">';
				frame += '<div id="frame'+key+'" class="video-container" style="display: none"><iframe width="560" height="315" src="//www.youtube.com/embed/'+id+'?rel=0" frameborder="0" allowfullscreen></iframe></div>';
				
				$('#ApplyVideo' + key ).html(frame);
				$('#video-title' + key).val(title);
				$('#thumbvideo' + key).val(thumbnail);
				$('#video-description' + key).val(description);
 

		});
	}
 
}

function showvideo(key)
{
	$('#video-image'+key).hide();
	$('#frame'+key).show();
	
}

function youtube(url){
    var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
    var match = url.match(regExp);
    if (match&&match[7].length==11){
        return match[7];
    }else{
        return false;
    }
}
</script>
<script type="text/javascript">
    var extension_row = {extension_row};

    function addExtension() {
		html = '<tr id="extension-row' + extension_row + '">';
		html += '    <td class="col-sm-11 text-left">';
		<!-- BEGIN: languages_extension -->
		html += '        <div class="input-group"><span class="input-group-addon"><img src="{LANG_IMAGE}" title="{LANG_TITLE}" /></span>';
		html += '            <textarea type="text" id="extension-info' + extension_row + '" name="product_extension[' + extension_row + '][{LANG_ID}][info]" value="" placeholder="{LANGE.entry_extension_info}" class="form-control input-sm"></textarea>';
		html += '        </div>';
		<!-- END: languages_extension -->
		html += '    </td>';
		html += '    <td class="col-sm-1 text-left" style="vertical-align: middle;">';
		html += '        <button type="button" onclick="$(\'#extension-row' + extension_row + '\').remove();" data-toggle="tooltip" title="{LANG.delete}" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i>';
		html += '        </button>';
		html += '    </td>';
		html += '</tr>'; 
        $('#extension tbody').append(html);
	   addEditor( 'extension-info' + extension_row );
	   
        extension_row++;
    }
	function addEditor( id )
	{
		//$( id ).ckeditor();
		CKEDITOR.disableAutoInline = true;

		CKEDITOR.replace( id, { 
			width: '100%',
			height: '50px',
			removeButtons: '',
			removePlugins: 'elementspath',
			
			toolbar: [
				[ 'Cut', 'Paste', 'PasteText', '-', 'Undo', 'Redo' ], 
				{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', ] },
				{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
				{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
				{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
			]
			 
		});
	}
	
</script>
<script type="text/javascript">
 
	$(".date").datepicker({
		showOn : "both",
		dateFormat : "dd/mm/yy",
		changeMonth : true,
		changeYear : true,
		showOtherMonths : true,
	});
 
	var inputnumber = '{LANGE.entry_error_inputnumber}';
	var nv_base_adminurl = '{NV_BASE_ADMINURL}';
	var file_dir = '{NV_UPLOADS_DIR}/{MODULE_NAME}';
	var currentpath = "{CURRENT}";
	var clear = '';
	function getImage ( id )
	{
		var area = 'input-image' + id;
		var path = "{NV_UPLOADS_DIR}/{MODULE_NAME}";
		var currentpath = "{CURRENT}";
		var type = "image";
		nv_open_browse("{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}=upload&popup=1&area=" + area + "&path=" + path + "&type=" + type + "&currentpath=" + currentpath, "NVImg", 850, 500, "resizable=no,scrollbars=no,toolbar=no,location=no,status=no");
		window.clear = setInterval(imageRefresh, 1000, id);
	}
	function imageRefresh( id )
	{
		var img = $('#input-image' + id ).val();
		var old = $('#input-image' + id ).attr('data-old');											
		if( img != '' && old != img){
			$('#input-image' + id).attr('data-old', img);	
			var thumb = img.replace('{NV_BASE_SITEURL}{NV_UPLOADS_DIR}/', '{NV_BASE_SITEURL}{NV_ASSETS_DIR}/');
			$('#thumb-image' + id + ' img').attr('src', thumb);	
			clearInterval(window.clear);
		}		
	}
 
</script>
<script type="text/javascript">
function shops_get_alias( key ) {		
	var title = strip_tags(document.getElementById('input-name'+key+'').value);
	if (title != '') {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=alias&nocache=' + new Date().getTime(), 'title=' + encodeURIComponent(title), function(res) {
			if (res != "") {
				document.getElementById('input-alias'+key+'').value = res;
		 	} else {
		 		document.getElementById('input-alias'+key+'').value = '';
		 	}
		});
	}
	return false;
}
</script>
<!-- END:main -->