<!-- BEGIN: main -->
<link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/cloudzoom/cloudzoom.css" type="text/css" rel="stylesheet" />
<link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/cloudzoom/thumbelina.css" type="text/css" rel="stylesheet" />
<style tye="text/css">
img.cloudzoom {
	width:100%;
}
#slider1 {
	margin-left:16px;
	margin-right:16px;
	height:60px;
	border-top:1px solid #aaa;
	border-bottom:1px solid #aaa;
	position:relative;
	margin-top: 6px;
}
</style>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/cloudzoom/cloudzoom.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/cloudzoom/thumbelina.js"></script>
<script type = "text/javascript">
CloudZoom.quickStart();
$(function(){
	$('#slider1').Thumbelina({
		$bwdBut:$('#slider1 .left'), 
		$fwdBut:$('#slider1 .right')
	});
});                             
</script>
<div id="ProductContent">
	<div id="viewDetail" itemscope="" itemtype="http://schema.org/Product">
		<div class="boxDetail grid">
			<div class="action-sub">
				<div class="booth-image-content">
					<div class="booth-image">
						<img class="cloudzoom" alt ="{PRODUCT.name}" id ="zoom1" src="{IMAGE.image}" data-cloudzoom='zoomSizeMode:"image",autoInside: 550'>
						<div id="slider1">
							<div class="thumbelina-but horiz left">&#706;</div>
							<ul>
								<!-- BEGIN: image -->
								<li>
									<img class='cloudzoom-gallery' src="{IMAGE.thumb}" 
										 data-cloudzoom ="useZoom:'.cloudzoom', image:'{IMAGE.image}' "><span class="arrow"></span>
								</li>
								<!-- END: image -->							
							</ul>
							<div class="thumbelina-but horiz right">&#707;</div>
						</div>
					</div>  
				</div> 
				<div class="clearfix"></div>
			</div>
			<div id="product" class="action-main">
				<div class="action-main-wrap">
					<div class="widget-main-action widget-main-action-ws">
						<h1 class="ma-title" itemprop="name" title="{PRODUCT.name}">		
							{PRODUCT.name}
						</h1>
						<p class="brand">
							<span style="color:#999">Thương hiệu: </span><a href="{PRODUCT.brand.link}" id="providerUrl" class="cl-blue">{PRODUCT.brand.name}</a>
							<span class="delimiter">|</span>
							<span style="color:#999">Mã hàng: <span id="model" style="color: #222">{PRODUCT.model}</span></span>
						</p>
						<div class="ma-main">
							
							<!-- BEGIN: price -->
							
							<div class="price">
	 
								<!-- BEGIN: no_special -->
								<p class="money cost">Giá bán: <span>{PRICE}</span></p>
								<!-- END: no_special -->		
								<!-- BEGIN: special -->
								<p class="money discount">Giá bán: <span>{PRICE_NEW}</span> <span class="cost">{PRICE}</span></p> 
								<!-- END: special -->
								<!-- BEGIN: tax -->
								<p class="money tax">Giá chưa thuế: <span>{TAX}</span></p> 
								<!-- END: tax -->
							</div>
							<!-- BEGIN: discount -->
							<div class="ma-price-wrap">
								<ul class="list-unstyled">	 
									<!-- BEGIN: loop -->
									<li>{DISCOUNT.quantity} hoặc hơn {DISCOUNT.price}</li>
									<!-- END: loop -->
								</ul>
							</div>
							<!-- END: discount -->
	 
							<!-- END: price -->
							
							
							<!-- BEGIN: options -->
							<div class="ma-price-wrap">	
								<!-- BEGIN: checkbox -->
								<div class="form-group {OPTION.required}">							
									<label class="control-label">{OPTION.name}</label>								
									<div id="input-option{OPTION.product_option_id}">	
										<!-- BEGIN: loop -->
										<div class="checkbox">
											<label>
												<input type="checkbox" name="option[{OPTION.product_option_id}][]" value="{LOOP.product_option_value_id}" data-quantity="{LOOP.quantity}" data-price="{LOOP.price}" data-prefix="{LOOP.price_prefix}">{LOOP.name}({LOOP.price_prefix}{LOOP.price_sale})
												<!-- BEGIN: image -->
												<img src="{LOOP.image}" alt="{LOOP.name} {LOOP.price_prefix}{LOOP.price}" class="img-thumbnail" />
												<!-- END: image -->
											</label>
										</div>
										<!-- END: loop -->	
									</div>									
								</div>
								<!-- END: checkbox -->
								<!-- BEGIN: radio -->
								<div class="form-group {OPTION.required}">							
									<label class="control-label">{OPTION.name}</label>
									
									<div id="input-option{OPTION.product_option_id}">								 
										<!-- BEGIN: loop -->
										<div class="radio">
											<label>
												<input type="radio" name="option[{OPTION.product_option_id}][]" value="{LOOP.product_option_value_id}" data-quantity="{LOOP.quantity}" data-price="{LOOP.price}" data-prefix="{LOOP.price_prefix}" >{LOOP.name}({LOOP.price_prefix}{LOOP.price_sale})
												<!-- BEGIN: image -->
												<img src="{LOOP.image}" alt="{LOOP.name} {LOOP.price_prefix}{LOOP.price}" class="img-thumbnail" />
												<!-- END: image -->
											</label>
										</div>
										<!-- END: loop -->				
									</div>
									
								</div>
								<!-- END: radio -->
								<!-- BEGIN: select -->
								<div class="form-group {OPTION.required}">							
									<label class="control-label"  for="input-option{OPTION.product_option_id}">{OPTION.name}</label>
									<select name="option[{OPTION.product_option_id}]" id="input-option{OPTION.product_option_id}" class="form-control">
										<option value=""> --- {OPTION.name} --- </option>
										<!-- BEGIN: loop -->
										<option value="{LOOP.product_option_value_id}" data-quantity="{LOOP.quantity}" data-price="{LOOP.price}" data-prefix="{LOOP.price_prefix}">{LOOP.name}({LOOP.price_prefix}{LOOP.price_sale})</option>
										<!-- END: loop -->
									</select>
								</div>
								<!-- END: select -->
							</div>
							<!-- END: options -->
							
							
							<!-- BEGIN: contact -->
							<div class="contact">Liên hệ </div>
							<!-- END: contact -->
							<!-- BEGIN: show_order1 -->
							<div class="ma-brief-list ma-main-brief-list">
								
								<div id="validate-quantity"></div>

								<dl class="product-quantity clearfix">
									<dt class="name">Số lượng:</dt>
									<dd class="value">
										<div data-quantity="true" class="quantity-value">
											<a class="btn-quantity quantity-down" data-type="-" data-min="{PRODUCT.minimum}" href="javascript:void(0);" ><b>-</b></a>
											<input type="text" id="quantity" name="quantity" value="{PRODUCT.minimum}" maxlength="6" class="ui-textfield ui-textfield-system numberonly" >
											<input type="hidden" name="product_id" value="{PRODUCT.product_id}">
											<input type="hidden" name="token" value="{PRODUCT.token}">
											<a class="btn-quantity quantity-up" data-type="+" data-max="{PRODUCT.quantity}"  href="javascript:void(0);"><b>+</b></a>
											<span class="product-unit-bar">	
												<span data-role="product-inventory" class="stock-partition product-inventory">
												Còn
												<span data-role="inventory-count">{PRODUCT.stock}</span>
												<span class="product-unit" title="Pieces">Chiếc</span>
												</span>
											</span>
										</div>
									</dd>
								</dl>
								<script>
								$('[data-quantity="true"] a').hover(function(){
									$('#quantity').addClass('quantity-mark');		
									}, function(){
									$('#quantity').removeClass('quantity-mark');
								});	
								$('[data-quantity="true"] a').on('click', function(){
									var type = $(this).attr('data-type');
									var min = parseInt($(this).attr('data-min'));
									var max = parseInt($(this).attr('data-max'));
									var quantity = parseInt($('#quantity').val());
									$('[data-quantity="true"] a').removeAttr('style');
									if( type == '-' )
									{	
										if( quantity > min )
										{
											var qty = quantity - 1;
											$('#quantity').val(qty);
											$(this).css({
												'cursor':'cursor',
												'background-color':'rgba(255, 255, 255, 0.72)'
											});
										}else{
											$('#quantity').val(min);		
											$(this).css({
												'cursor':'not-allowed',
												'background-color':'transparent'
											});
										}
	 
									}else
									{
										if( quantity < max )
										{
											var qty = parseInt( quantity ) + 1;
											$('#quantity').val(qty);
											$(this).css({
												'cursor':'cursor',
												'background-color':'rgba(255, 255, 255, 0.72)'
											});
										}else{
											$('#quantity').val(max);
											$(this).css({
												'cursor':'not-allowed',
												'background-color':'transparent',
											});
										}	
									}
								});
								</script>
							</div>
							<!-- END: show_order1 -->
						</div>
						<div class="ma-operate">       
							<div class="ma-main-operate">
								<!-- BEGIN: show_order2 -->
								<button id="button-order" class="btn btn-primary btn-buy" data-redirect="1">Mua ngay</button>
								<!-- END: show_order2 -->
								<div class="btn-group">
									<!-- BEGIN: wishlist -->
									<button type="button" data-toggle="tooltip" class="btn btn-default" title="Yêu thích" onclick="wishlist.add('{PRODUCT.product_id}', '{PRODUCT.token}');"><i class="fa fa-heart"></i></button>
									<!-- END: wishlist -->
									<!-- BEGIN: compare -->
									<button type="button" data-toggle="tooltip" class="btn btn-default" title="So sánh sản phẩm" onclick="compare.add('{PRODUCT.product_id}', '{PRODUCT.token}');" ><i class="fa fa-exchange"></i></button>
									<!-- END: compare -->
								</div>
							</div>
						</div>
					
						<div class="ma-sub-operate">
							<a href="javascript:;" class="atm link-default atm-online">
								<i class="fa fa-comments" aria-hidden="true"></i>
								<span title="Chat with me">Liên hệ nhanh!</span>
							</a>
							<!-- BEGIN: show_order3 -->
							<a id="button-cart" class="link-default btn-shopping-cart" href="javascript:void(0);" style="position: relative;" data-redirect="0"><i class="fa fa-shopping-cart" aria-hidden="true"></i>Thêm vào giỏ hàng</a>
							<!-- END: show_order3 -->
						</div>
						<!-- BEGIN: info -->
						<div class="ma-sub-infox">
							<!-- BEGIN: loop -->
							<p><i class="fa fa-check-square-o" aria-hidden="true"></i> {INFO}</p>
							<!-- END: loop -->
						</div>
						<!-- END: info -->
						<div class="ma-tag-wrap"> <a href="#"><i class="fa fa-tags" aria-hidden="true"></i> Điều hòa</a>, <a href="#"><i class="fa fa-tags" aria-hidden="true"></i> Điều hòa cầm tay</a></div> 
					</div>
				</div> 
			</div> 
		</div> 
		<div class="bodycontent">
			<div class="tabbable-panel">
				<div class="tabbable-line tabs-below">
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#tab_product_1" data-toggle="tab"> Chi tiết sản phẩm </a>
						</li>
						<li class="">
							<a href="#tab_product_2" data-toggle="tab"> Thông số kỹ thuật </a>
						</li>
						<li>
							<a href="#tab_product_3" data-toggle="tab"> Video </a>
						</li>
						<!-- BEGIN: faq_tab -->
						<li>
							<a href="#tab_product_faq" data-toggle="tab"> Hỏi đáp </a>
						</li>				
						<!-- END: faq_tab -->		
						<!-- BEGIN: review_tab -->
						<li>
							<a href="#tab_product_review" data-toggle="tab"> Nhận xét </a>
						</li>
						<!-- END: review_tab -->
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab_product_1">
							<div class="description">{PRODUCT.description}</div>
						</div>
						<div class="tab-pane" id="tab_product_2">
							<table cellspacing="0" class="table table-bordered table-detail table-striped" id="product-attribute">
								<tbody>
									<tr>
										<td>Danh mục</td>
										<td>
											<!-- BEGIN: cat -->
											<a class="cat-title" target="_blank" title="{CAT.title}" href="{CAT.link}">{CAT.title}</a>
											<span class="cat-arrow">></span> 
											<!-- END: cat -->
										</td>
									</tr>
									<tr>
										<td>Nhà sản xuất</td>
										<td><span itemprop="manufacturer">{PRODUCT.brand.name}</span>
										</td>
									</tr>
									<tr>
										<td>Số hiệu sản phẩm</td>
										<td><span itemprop="model">{PRODUCT.model}</span></td>
									</tr>
									<!-- BEGIN: attribute -->
									<tr>
										<td>{ATTRIBUTE.name}</td>
										<td>{ATTRIBUTE.text}</td>
									</tr>	
									<!-- END: attribute -->
									
								</tbody>
							</table>						
						</div>
						<div class="tab-pane" id="tab_product_3">
							Hiện tại chưa có video về sản phẩm này
						</div>
						<!-- BEGIN: faq_content -->	
						<div class="tab-pane" id="tab_product_faq">
							<div id="discuss-faq">
								<div class="discuss-container" id="discuss-container">
									<!-- BEGIN: question -->
									<div class="dicuss-item helpfulness" id="faqcontent{QUESTION.faq_id}">
										<div class="ask-votes">
											<p class="ask-votes-number" id="total-likes{QUESTION.faq_id}"> 0 </p>
											<span class="helpfulness-progress" style="display:none;"></span> lượt thích
										</div>
										<div class="discuss-content">
											<a href="{QUESTION.link}">
												<p class="discuss-owner">{QUESTION.question}</p>
											</a>

											<div class="dis-control">
												<a class="likes" href="javascript:void(0);" data-user="0" onclick="users_likes( this, '{QUESTION.faq_id}', '{QUESTION.product_id}', '{QUESTION.token}' )" id="likes{QUESTION.faq_id}"> Thích</a>
												<a href="{QUESTION.link}">Trả lời</a>
												<a href="{QUESTION.link}">Xem tất cả  câu trả lời</a>
											</div>
										</div>
									</div>
									<!-- END: question -->
									<div class="dicuss-item last-discuss">
										<div class="discuss-content">
											<div class="messages">
												 
											</div>
											<div id="question-form" class="aw-pq2-form" method="post">
												<div class="ask-form">
													<textarea class="form-control" name="question" placeholder="Hãy đặt câu hỏi liên quan đến sản phẩm..." ></textarea>
													<button onclick="send_question()" id="add-question-button" type="submit" class="btn btn-primary not_login">
															<span>Gửi câu hỏi</span>
													</button>
												</div>
												<input type="hidden" name="product_id" value="{PRODUCT.product_id}">
												<input type="hidden" name="faq_id" value="0">
												<input type="hidden" name="parent_id" value="0">
												<input type="hidden" name="token" value="{PRODUCT.token}">
												<input type="hidden" name="send_question" value="1">	 
												<input type="hidden" name="redirect" value="{REDIRECT}">
											</div>
											<div class="no-questions">
												<p style="margin-bottom:0">Các câu hỏi thường gặp về sản phẩm:</p>
												<ul>
													<li>- Sản phẩm này có bền không?</li>
													<li>- Kích thước sản phẩm này?</li>
													<li>- Sản phẩm này có dễ dùng không?</li>
												</ul>
												 
											</div>
										</div>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
						<!-- END: faq_content -->	
						<!-- BEGIN: review_content -->
						<div class="tab-pane" id="tab_product_review">
							<div class="review-sum-box" id="reviewcomment">
								<div class="boxComment clearfix">
									<div class="col-md-8 col-sm-12 col-xs-24 box1">
											<h4>Đánh Giá Trung Bình</h4>
											<p class="total-review-point">5/5</p>
											<div class="rating-box">
												<div class="rating" style="width:98%"></div>
											</div>
											<div class="clearfix"></div>
											<p class="comm">(6 nhận xét)</p>
									</div>
									<div class="col-md-8 col-sm-12 col-xs-24 box2">
										<div class="item">
											<span class="rating-num">5 sao</span>
											<div class="progress">
												<div class="progress-bar progress-bar-success" style="width: 83%;">
													<span class="sr-only">83% Complete</span>
												</div>
											</div>
											<span class="rating-num-total">5</span>
										</div>
										<div class="item">
											<span class="rating-num">4 sao</span>
											<div class="progress">
												<div class="progress-bar progress-bar-success" style="width: 17%;">
													<span class="sr-only">17% Complete</span>
												</div>
											</div>
											<span class="rating-num-total">1</span>
										</div>
										<div class="item">
											<span class="rating-num">3 sao</span>
											<div class="progress">
												<div class="progress-bar progress-bar-success" style="width: 0%;">
													<span class="sr-only">0% Complete</span>
												</div>
											</div>
											<span class="rating-num-total">0</span>
										</div>
										<div class="item">
											<span class="rating-num">2 sao</span>
											<div class="progress">
												<div class="progress-bar progress-bar-success" style="width: 0%;">
													<span class="sr-only">0% Complete</span>
												</div>
											</div>
											<span class="rating-num-total">0</span>
										</div>
										<div class="item">
											<span class="rating-num">1 sao</span>
											<div class="progress">
												<div class="progress-bar progress-bar-success" style="width: 0%;">
													<span class="sr-only">0% Complete</span>
												</div>
											</div>
											<span class="rating-num-total">0</span>
										</div>
									</div>
									<div class="col-md-8 col-sm-24 col-xs-24 box3">
										<div class="boxreview">
											<h4>Chia sẻ nhận xét về sản phẩm</h4>
											<button type="button" class="btn btn-primary submit-your-review" onclick="show_form_review()">
												<i class="icon-pencil icon-white"></i> Viết nhận xét của bạn
											</button>
										</div>
									</div>		 
								</div>

								<div class="review-form-submit inside-form">
									<div class="writeReviewBox" id="reviewform">
										<h3 class="review-form-title">Gửi nhận xét của bạn</h3>
										<div class="clearfix">
											<div class="col-xs-24 col-sm-12 col-md-12">
												<form action="" method="post">
													<input type="hidden" name="entity_pk_value" value="122782" id="entity_pk_value">
													<input type="hidden" name="productset_id" value="85" id="productset_id">
													<div class="rate" id="rating_wrapper">
														<label>1. Đánh giá của bạn về sản phẩm này:</label>
														<div class="rating-input"><i class="glyphicon glyphicon-star-empty" data-value="1"></i><i class="glyphicon glyphicon-star-empty" data-value="2"></i><i class="glyphicon glyphicon-star-empty" data-value="3"></i><i class="glyphicon glyphicon-star-empty" data-value="4"></i><i class="glyphicon glyphicon-star-empty" data-value="5"></i> <a class="rating-clear" style="display:none;" href="javascript:void"><span class="glyphicon glyphicon-remove"></span> xóa</a><input type="number" id="rating_star" data-clearable="xóa" class="rating hidden" data-min="1" data-max="5"></div>
														<span class="help-block" id="rating_error"></span>
													</div>

													<div class="title" id="title_wrapper">
														<label for="review_title">2. Tiêu đề của nhận xét:</label>
														<input type="text" placeholder="Nhập tiêu đề nhận xét" name="title" id="review_title" class="form-control input-sm" required="required">
														<span class="help-block" id="title_error"></span>
													</div>

													<div class="text" id="detail_wrapper">
														<label for="review_detail">3. Viết nhận xét của bạn vào bên dưới:</label>
														<textarea placeholder="Nhập nội dung nhận xét tại đây. Tối thiểu 100 từ đối với sản phẩm sách, 50 từ đối với sản phẩm các ngành hàng khác, tối đa 2000 từ." class="form-control" name="detail" id="review_detail" cols="30" rows="10"></textarea>
														<span class="help-block" id="detail_error"></span>
													</div>
													
													<div class="action">
														<div class="word-counter"></div>
														<div class="checkbox" style="display:none;">
															<label>
																 <input id="show_information" type="checkbox" checked="" value="1"> Hiển thị thông tin mua hàng trong phần nhận xét
															</label>
														</div>
														<button id="send_review" type="button" class="btn btn-primary">Gửi nhận xét</button>
													</div>												
												</form>											
											</div>
											<div class="col-xs-24 col-sm-12 col-md-12">
												<div class="reviewRules">
													[POINT]
												</div>
											</div>
										</div>
									</div>
								</div>						
								
							</div>
							<div id="reviewcontent">
								<h3><i class="fa fa-comments-o" aria-hidden="true"></i> Nhận xét mới </h3>
								<div id="review-list" class="review-list">
									
									<div class="item" itemprop="review" itemtype="http://schema.org/Review">
										<div itemprop="itemReviewed" itemtype="http://schema.org/Product">
											&nbsp;&nbsp;&nbsp;&nbsp;
											<span itemprop="name" content=""></span>
										</div>
										<div class="col-md-6 col-sm-24 col-xs-24 avatar">
											<p class="image">
												<a><img class="img-responsive" width="65" height="65" src="/themes/nukevnshops/images/users/no_avatar.png" style="display: block;"></a>
											</p>
											<p class="name" itemprop="author">Nguyen Hoang</p>
											<p class="from"><span>đến từ Hồ Chí Minh</span></p>
											<p class="days">một năm trước</p>
											<div class="clearfix"></div>
										</div>
										<div class="col-md-18 col-sm-24 col-xs-24">
											<div class="infomation">
												<div class="rating">
													<div itemprop="reviewRating" itemtype="http://schema.org/Rating">
														<meta itemprop="ratingValue" content="5">
													</div><span class="rating-content"><i class="star"></i><i class="star"></i><i class="star"></i><i class="star"></i><i class="star"></i><span style="width: 100%;"><i class="star"></i><i class="star"></i><i class="star"></i><i class="star"></i><i class="star"></i></span></span>
												</div>
												<p class="review" itemprop="name">{PRODUCT.name}</p>
												<p class="buy-already">
													<i class="fa fa-check-square-o" aria-hidden="true"></i> Đã mua sản phẩm này từ 2 năm trước
												</p>
												<div class="description js-description"><span class="review_detail" itemprop="reviewBody">Đây là một trong những màn hình xuất sắc của Dell. Mình chỉ hơi buồn vì nó thiếu cổng DVI nên buộc mình phải kiếm mua dây chuyển từ HDMI qua DVI, hơi cực xíu nhưng bù lại, nó không làm mình thất vọng. Khả năng lên màu tươi sáng, chi tiết và độ phân giải cao, hỗ trợ tới 2 cổng HDMI và 2 Display Port thời thượng. Cạnh màn hình rất mỏng và không bị hở sáng như các kết quả review trên mạng. Cũng cám ơn tiki đã gói gém sản phẩm rất kỹ càng.</span></div>
												<div class="link"><span class="text-success"><strong>6 người<!-- /react-text --></strong><!-- react-text: 46 --> đã cảm ơn nhận xét này<!-- /react-text --></span><span>Nhận xét này hữu ích với bạn?</span><button type="button" class="btn btn-primary btn-sm thank-review" data-review-id="246987" data-product-id="117390"><i class="fa fa-thumbs-o-up"></i> Cảm ơn</button>
													<p class="review_action"><a href="#" class="js-quick-reply">Gửi trả lời</a></p>
												</div>
												<div class="quick-reply"><textarea class="form-control review_comment" placeholder="Nhập nội dung trả lời tại đây. Tối đa 1500 từ" id=""></textarea><span class="help-block text-left"></span>
													<button type="button" class="btn btn-primary btn-sm btn_add_comment" data-review-id="246987">Gửi trả lời của bạn</button>
													<button type="button" class="btn btn-default btn-sm js-quick-reply-hide">Hủy bỏ</button>
												</div>
											</div>
											<div class="replies">
												<div class="replies-item">
													<p class="replies-image">
														<a><img class="img-responsive" width="45" height="45" src="/themes/nukevnshops/images/users/no_avatar.png" style="display: block;"></a>
													</p>
													<p class="replies-name">
														dung
														<span> đã trả lời:</span></p>
													<p class="replies-text">Các bạn tạo tài khoản mới và dùng mã TF-F4XJ2 khi mua sản phẩm sẽ được giảm giá thêm 50.000đ. Mã giảm giá này có thể kết hợp với mã giảm giá khác nhé</p>
													<p class="replies-report"><a href="#" class="report_bad_comment" data-comment-id="21240">Báo xấu</a></p>
												</div>
											</div>
										</div>
									</div>
									
									<div class="clearfix"></div>
								</div>						
							</div>						
						</div>
						<!-- END: review_content -->
					</div>	
				</div>
			</div>
		</div>
	</div> 
</div>
<script type="text/javascript">

$('#button-cart, #button-order').on('click', function() {
	var redirect = parseInt( $(this).attr('data-redirect') );
	$.ajax({
		url: nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=setcart&action=add&nocache=' + new Date().getTime(),
		type: 'post',
		data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-cart i').replaceWith('<i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i>');
		},
		complete: function() {
			$('#button-cart i').replaceWith('<i class="fa fa-shopping-cart" aria-hidden="true"></i>');
		},
		success: function(json) {
			$('.alert, .text-danger').remove();
			$('.form-group').removeClass('has-error');

			if (json['error']) {
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						var element = $('#input-option' + i.replace('_', '-'));

						if (element.parent().hasClass('input-group')) {
							element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						} else {
							element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						}
					}
				}

				if (json['error']['recurring']) {
					$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
				}

				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');
			}
			
			if (json['success']) {
				$('#ProductContent').prepend('<div class="alert alert-success">' + json['success'] + '<i class="fa fa-times"></i></div>');

				$('#product-cart button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');

				$('html, body').animate({ scrollTop: 0 }, 'slow');

				$('#product-cart > ul').load(nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=loadcart ul li');
				
				if( redirect )
				{
					
					location = json['link_cart'];
				}
				
			}
		},
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
	});
});
</script>

<!-- END: main -->
