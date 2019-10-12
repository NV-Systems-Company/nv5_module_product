<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/jcarousel/jcarousel.vertical.css"> 
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/magiczoom/magiczoom.css"> 
 
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/jcarousel/jquery.jcarousel.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/jcarousel/jquery.jcarousel-core.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/jcarousel/jquery.jcarousel-control.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/magiczoom/magiczoom.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/magiczoom/magictoolbox.utils.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/getboostrap.modal.js"></script>

<script type="text/javascript">
function change_image_zoom(obj)
{
	var id = $(obj).attr('rel');
	var url = $(obj).attr('rev');
	
	$('#MagicZoomPlusImage{PRODUCT_ID}').attr('href', url);
	$('#MagicZoomPlusImage{PRODUCT_ID} img').attr('src', url);
 
} 
MagicZoomPlus.options = {
	'hint-text' : 'Click vào ảnh để phóng to',
	'zoom-width' : 600,
	'zoom-height' : 400,
	'loading-msg' : 'Đang tải...',
	'selectors-change' : 'mouseover'
}
</script>

 
<div class="Product1" itemscope itemtype="http://schema.org/Product">
	<meta itemprop="url" content="{LINK}">
	<div class="containers">
		<div id="send-input" class="row product-box"> 
                       
			<div class="col-xs-24 col-sm-10 col-md-10 fix-detail">
				<script type="text/javascript">
				$(function() {
					$('.jcarousel').jcarousel({
						vertical: true
					});

					$('.jcarousel-prev').jcarouselControl({target: '-=5'});
					$('.jcarousel-next').jcarouselControl({target: '+=5'});
				});
				</script>
				<div class="MagicToolboxContainer selectorsLeft clearfix">
					<!-- BEGIN: othersimg -->
					<div id="MagicToolboxSelectors{PRODUCT_ID}" class="item-thumbnail MagicToolboxSelectorsContainer">
						<div class="jcarousel-skin-default">
							<a href="#" class="jcarousel-prev">Prev</a>
							<div class="jcarousel jcarousel-vertical">
								<ul>
									<!-- BEGIN: loop -->
									<li>
										<a onmouseover="change_image_zoom(this);" href="{IMG_SRC_OTHER}" rel="zoom-id: MagicZoomPlusImage{PRODUCT_ID};zoom-width: 800;caption-source: a:title;" rev="{IMG_SRC_OTHER}"><img itemprop="image" src="{IMG_SRC_OTHER}" alt="{BASENAME}" /></a>
									</li>
									<!-- END: loop --> 
								</ul>
							</div>
							<a href="#" class="jcarousel-next">Next</a>
						</div>
					</div>
					<div class="item-main-image MagicToolboxMainContainer clearfix">
						<div class="item-watermark clearfix"> </div>
						<a class="MagicZoomPlus nobook" id="MagicZoomPlusImage{PRODUCT_ID}" href="{HOMEIMG}"><img itemprop="image" src="{HOMEIMG}" alt="{TITLE}" /></a> 
					</div>
					<!-- END: othersimg -->
					 <div class="facebook_like">
							<div class="fb-like fb_iframe_widget" data-href="{SELFURL}" data-width="375" data-show-faces="false" data-send="true" fb-xfbml-state="rendered" fb-iframe-plugin-query="app_id=&amp;href=http%3A%2F%2Fmanvn.net%2Fsan-pham%2Fcap-da-nam-cong-so-bvp-cao-cap-khoa-ma-so%2F&amp;locale=vi_VN&amp;sdk=joey&amp;send=true&amp;show_faces=false&amp;width=375">
							</div>
					</div> 

				</div>
			</div>
			<div class="col-xs-24 col-sm-14 col-md-14 fix-full-detail"> 
				<h1 class="item-name" itemprop="name">{TITLE}</h1>
				<!-- <p class="bestseller">
					<img src="/images/beseller-product.png" alt="Top 10 bán chạy tuần này">
					<strong>Đứng số 2</strong> trong <a href="/bestsellers/">Top 10 bán chạy tuần này</a>
				</p> -->
 
				<div class="item-box clearfix">
					<div class="item-box-left left" >
						<!-- BEGIN: brand -->
						<div class="clearfix brand-box">
							<a class="brand-logo" target="_blank" href="{BRAND.link}">
								<img height="35" title="{BRAND.name}" src="{BRAND.logo}">
							</a>
							<ul class="brand-list">
								<li>Thương hiệu</li>
								<li>
									<a target="_blank" href="{BRAND.link}"> {BRAND.name} </a>
								</li>
							</ul>
						</div>
						<!-- END: brand -->
						<!-- BEGIN: price -->
						<div class="detail_price_around" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
							<ul class="list-unstyled">

								<!-- BEGIN: no_special -->
								<li>
									<div class="price-lang">{LANG.detail_pro_price}: </div>
									<div class="price-new"><span itemprop="price">{PRICE}</span></div>
									<meta itemprop="priceCurrency" content="{CURRENCY}">
									<link itemprop="availability" href="http://schema.org/InStock">
									<div class="clear"></div>
								</li>
								<!-- END: no_special -->

								<!-- BEGIN: special -->
								<li>
									<div class="price-lang old">{LANG.price_old}: </div>
									<div class="price-old"><span class="price-old" style="text-decoration: line-through;">{PRICE}</span></div>
									<div class="clear"></div>	
								</li> 
								<li>
									<div class="price-lang special">{LANG.detail_special_price}: </div>
									<div class="price-new"><span itemprop="price">{PRICE_NEW} </span><span class="special-time">({SPECIAL_TIME})</span></div>
									<meta itemprop="priceCurrency" content="{CURRENCY}">
									<link itemprop="availability" href="http://schema.org/InStock">
									<div class="clear"></div>
								</li>
								
								<li>
									<div class="percent-lang">{LANG.text_percent}: </div>
									<div class="percent"><span class="percents">{SALE} ({PERCENT}%)</span></div>
									<div class="clear"></div>	
									
								</li>
								<!-- END: special -->

								<!-- BEGIN: tax -->
								<li>
									{LANG.text_tax}: {TAX}
								</li>
								<!-- END: tax -->

								<!-- BEGIN: points -->
								<li>
									{LANG.text_points}: {POINTS}
								</li>
								<!-- END: points -->
 
								<!-- BEGIN: discount -->
								<li class="discount">
									SL >= {DISCOUNT_QUANTITY} giá {DISCOUNT_PRICE}
								</li>
								<!-- END: discount -->
							</ul>
							<div class="clear"></div>
						</div>
						<!-- END: price -->

					</div>

					<div class="item-box-right left" style="min-height: 120px;">

						<div class="item-review-social clearfix ratings">
							<div class="item-review-info" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
								<div class="rating-box">
									<div class="rating" style="width:80%"></div>
									<a href="#reviewShowArea" title="{REVIEWS_TOTAL} đánh giá">(<span itemprop="ratingCount" content="{REVIEWS_TOTAL}">{REVIEWS_TOTAL}</span> đánh giá)</a>
									<meta itemprop="ratingValue" content="{REVIEWS_TOTAL}">
								</div>
							</div>
							<div class="item-review-now">
								<a href="#reviewSubmitArea" class="writereview_small">
									Viết nhận xét để nhận điểm thưởng
								</a>
							</div>
						</div>

						
						<div class="item-benefit">
							<!-- BEGIN: info -->
							<p>{INFO}</strong> </p>
							 <!-- END: info -->
						</div>
					</div>
				</div>
 
				<!-- BEGIN: option -->
				<fieldset class="product-options" id="product-options-wrapper">
				
					<div class="item-select-box">
					
						<div class="step-box clearfix">
							<div class="step-number">1</div>
							<div class="step-lable">Tùy chọn:</div>
						</div>
						
						<!-- BEGIN: select -->
						<div class="form-group {OPTION.required}">
							<label class="control-label" for="input-option{OPTION.product_option_id}">{OPTION.name}</label>
							<select name="option[{OPTION.product_option_id}]" id="input-option{OPTION.product_option_id}" class="form-control input-sm fixcolor">
								<option value="">{LANG.option_select}</option>
								<!-- BEGIN: option_value -->
								<option value="{OPTION_VALUE.product_option_value_id}">{OPTION_VALUE.name}
									<!-- BEGIN: option_value_price -->
									({OPTION_VALUE.price_prefix}{OPTION_VALUE.price})
									<!-- END: option_value_price -->
								</option>
								<!-- END: option_value -->
							</select>
						</div>
						<!-- END: select -->

						<!-- BEGIN: radio -->
						<div class="form-group {OPTION.required}">
							<label class="control-label">{OPTION.name}</label>
							<div id="input-option{OPTION.product_option_id}">
								<!-- BEGIN: option_value -->
								<div class="radio">
									<label> <input type="radio" name="option[{OPTION.product_option_id}]" value="{OPTION_VALUE.product_option_value_id}" /> {OPTION_VALUE.name}
										<!-- BEGIN: option_value_price -->
										({OPTION_VALUE.price_prefix}{OPTION_VALUE.price})
										<!-- END: option_value_price -->
									</label>
								</div>
								<!-- END: option_value -->
							</div>
						</div>
						<!-- END: radio -->
						<!-- BEGIN: checkbox -->
						<div class="form-group {OPTION.required}">
							<label class="control-label">{OPTION.name}</label>
							<div id="input-option{OPTION.product_option_id}">
								<!-- BEGIN: option_value -->
								<div class="checkbox">
									<label> <input type="checkbox" name="option[{OPTION.product_option_id}][]" value="{OPTION_VALUE.product_option_value_id}" /> {OPTION.name}
										<!-- BEGIN: option_value_price -->
											({OPTION_VALUE.price_prefix}{OPTION_VALUE.price})
										<!-- END: option_value_price -->
									</label>
								</div>
								<!-- END: option_value -->
							</div>
						</div>
						<!-- END: checkbox -->

						<!-- BEGIN: image -->
						<div class="form-group {OPTION.required}">
							<label class="control-label">{OPTION.name}</label>
							<div id="input-option{OPTION.product_option_id}">
								<!-- BEGIN: option_value -->
								<div class="radio">
									<label> <input type="radio" name="option[{OPTION.product_option_id}]" value="{OPTION_VALUE.product_option_value_id}" /> <img src="{OPTION_VALUE.image}" alt="{OPTION_VALUE.alt}" class="img-thumbnail" /> {OPTION_VALUE.name}
										<!-- BEGIN: option_value_price -->
										({OPTION_VALUE.price_prefix}{OPTION_VALUE.price})
										<!-- END: option_value_price -->
									</label>
								</div>
								<!-- END: option_value -->
							</div>
						</div>
						<!-- END: image -->

						<!-- BEGIN: text -->
						<div class="form-group {OPTION.required}">
							<label class="control-label" for="input-option{OPTION.product_option_id}">{OPTION.name}</label>
							<input type="text" name="option[{OPTION.product_option_id}]" value="{OPTION.value}" placeholder="{OPTION.name}" id="input-option{OPTION.product_option_id}" class="form-control input-sm" />
						</div>
						<!-- END: text -->

						<!-- BEGIN: textarea -->
						<div class="form-group {OPTION.required}">
							<label class="control-label" for="input-option{OPTION.product_option_id}">{OPTION.name}</label>
							<textarea name="option[{OPTION.product_option_id}]" rows="5" placeholder="{OPTION.name}" id="input-option{OPTION.product_option_id}" class="form-control input-sm">{OPTION.value}</textarea>
						</div>
						<!-- END: textarea -->

						<!-- BEGIN: file -->
						<div class="form-group {OPTION.required}">
							<label class="control-label">{OPTION.name}</label>
							<button type="button" id="button-upload{OPTION.product_option_id}" data-loading-text="{LANG.option_loading}" class="btn btn-default btn-block">
								<i class="fa fa-upload"></i> {LANG.option_button_upload}
							</button>
							<input type="hidden" name="option[{OPTION.product_option_id}]" value="" id="input-option{OPTION.product_option_id}" />
						</div>
						<!-- END: file -->

						<!-- BEGIN: date -->
						<div class="form-group {OPTION.required}">
							<label class="control-label" for="input-option{OPTION.product_option_id}">{OPTION.name}</label>
							<div class="input-group date">
								<input type="text" name="option[{OPTION.product_option_id}]" value="{OPTION.value}" data-format="YYYY-MM-DD" id="input-option{OPTION.product_option_id}" class="form-control input-sm" />
								<span class="input-group-btn">
									<button class="btn btn-default" type="button">
										<i class="fa fa-calendar"></i>
									</button> </span>
							</div>
						</div>
						<!-- END: date -->

						<!-- BEGIN: datetime -->
						<div class="form-group {OPTION.required}">
							<label class="control-label" for="input-option{OPTION.product_option_id}">{OPTION.name}</label>
							<div class="input-group datetime">
								<input type="text" name="option[{OPTION.product_option_id}]" value="{OPTION.value}" data-format="YYYY-MM-DD HH:mm" id="input-option{OPTION.product_option_id}" class="form-control input-sm" />
								<span class="input-group-btn">
									<button type="button" class="btn btn-default">
										<i class="fa fa-calendar"></i>
									</button> 
								</span>
							</div>
						</div>
						<!-- END: datetime -->

						<!-- BEGIN: time -->
						<div class="form-group {OPTION.required}">
							<label class="control-label" for="input-option{OPTION.product_option_id}">{OPTION.name}</label>
							<div class="input-group time">
								<input type="text" name="option[{OPTION.product_option_id}]" value="{OPTION.value}" data-format="HH:mm" id="input-option{OPTION.product_option_id}" class="form-control input-sm" />
								<span class="input-group-btn">
									<button type="button" class="btn btn-default">
										<i class="fa fa-calendar"></i>
									</button> </span>
							</div>
						</div>
						<!-- END: time -->	
					 
				</fieldset>
				<!-- END: option -->
				<!-- BEGIN: order -->
				<div class="add-to-holder">
				<script type="text/javascript">
					function upQtyClick() {
						var qty = $(".tbQty").val();
						if (qty < 20 && qty < 97) {
							$(".tbQty").css("color", "black");
							$(".tbQty").val(parseInt(qty) + 1);
						} else {
							$(".tbQty").css("color", "red");
						}
					}

					function downQtyClick() {
						var qty = $(".tbQty").val();
						if (qty > 1) {
							$(".tbQty").css("color", "black");
							$(".tbQty").val(parseInt(qty) - 1);
						} else {
							$(".tbQty").css("color", "red");
						}
					}

					function KeyPressQty(e) {
						var unicode = e.charCode ? e.charCode : e.keyCode
						if (unicode != 8) { //if the key isn't the backspace key (which we should allow)
							if (unicode < 48 || unicode > 57) //if not a number
								return false; //disable key press
						}
					}
				</script>
 
				<div class="product-options-bottom">
					<div class="item-select-box">
						<div class="step-box clearfix" >
							<div class="step-number">2</div>
							<div class="step-lable">Số lượng:</div>
							<div class="select-qty" >
								<span class="bgArrowQty">
									<input type="hidden" name="product_id" value="{PRODUCT_ID}" >
									<input id="quantity" type="text" name="quantity" class="tbQty qty-input" onkeypress="return KeyPressQty(event)" value="1">
									<span class="arrowBlock">
										<a href="javascript: upQtyClick();" class="upQty"></a>
										<a href="javascript: downQtyClick();" class="downQty"></a>
									</span>
								</span>
							</div>

							<div class="step-box-button clearfix">
								<button type="button" class="left add-to-cart-new" onclick="order_cart('{PRODUCT_ID}', '{TOKEN}')" >{LANG.add_to_cart}</button>

								<button type="button" data-toggle="tooltip" title="Thêm vào danh sách ưa thích" onclick="wishlist.add('{PRODUCT_ID}', '{TOKEN}')" class="add-to-wl-new not_login">
									Thêm vào danh sách ưa thích 
								</button>

							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END: order -->	
			<!-- BEGIN: product_empty -->
			<div class="fr" style="margin-top: 6px">
				<button class="button disabled">
					{LANG.product_empty}
				</button>
			</div>
			<div class="clear"></div>
			<!-- END: product_empty -->
			</div>
			</div>
 	
			
		</div>
		<div class="product-content">

			<ul class="nav nav-tabs product-tabs">
				<li class="active"><a href="#gioi-thieu">Mô Tả Sản Phẩm<span class="active-arrow"></span></a>
				</li>
				<li><a href="#chi-tiet">Thông Tin Chi Tiết <span class="active-arrow"></span></a>
				</li>
				<li><a id="tabvideo" href="#video" style="display:none">Video Giới Thiệu <span class="active-arrow"></span></a>
				</li>
				<li><a href="#discuss-faq">Hỏi đáp<span class="active-arrow"></span></a>
				</li>
				<li><a href="#nhan-xet">Khách Hàng Nhận Xét ({REVIEWS_TOTAL}) <span class="active-arrow"></span></a>
				</li>
			</ul>


			<div class="tab-content product-tabs-detail">

				<section class="description-content clearfix" id="gioi-thieu">
					<div class="product-content-area">
						<!-- BEGIN: brand_detail -->
						<div class="authorContent fr clearfix">
							<a class="fl" target="_blank" href="{BRAND.link}">
								<img width="100" title="{BRAND.name}" src="{BRAND.image}" />
							</a>
							 {BRAND.description}
							<a title="Xem các sản phẩm của {BRAND.name}" class="show-detail" target="_blank" href="{BRAND.link}">
								Xem các sản phẩm của {BRAND.name}                  
							</a>
						</div>
						<!-- END: brand_detail -->
						<div class="product-description" itemprop="description">
							{DETAIL}
						</div>
 	 
					</div>
 
					<div class="show-more hides"><a title="Xem Thêm Nội Dung"><span>Xem Thêm Nội Dung</span></a> </div>
					<div class="show-less hides"><a title="Thu Gọn Nội Dung"><span>Thu Gọn Nội Dung</span></a> </div>

				</section>
				
				<!-- Thông Tin Chi Tiết -->
				<section class="additional-content">

					<h3 class="section-title">Thông Tin Chi Tiết</h3>

					<div class="collateral-box attribute-specs" id="chi-tiet">
						<table cellspacing="0" class="table table-bordered table-detail table-striped" id="product-attribute-specs-table">
							<col width="25%" />
							<col />

							<!-- <tr>
								<td>Có thuế VAT</td>
								<td>Có</td>
							</tr> -->
							<!-- BEGIN: brand_info -->
							<tr>
								<td>Thương hiệu</td>
								<td><a target="_blank" title="{BRAND.name}" href="{BRAND.link}">{BRAND.name}</a>
								</td>
							</tr>
							<!-- END: brand_info -->
							<!-- BEGIN: category -->
							<tr>
								<td>Danh mục</td>
								<td><a target="_blank" title="{CATEGORY.name}" href="{CATEGORY.link}">{CATEGORY.name}</a>
								</td>
							</tr>
							<!-- END: category -->
 
							<tr>
								<td>Nhà sản xuất</td>
								<td><span itemprop='manufacturer'>Samsung</span>
								</td>
							</tr>
							<tr>
								<td>Số hiệu sản phẩm</td>
								<td><span itemprop='model'>{MODEL}</span>
								</td>
							</tr>
							<!-- BEGIN: attribute -->
							<tr>
								<td>{ATTRIBUTE.name}</td>
								<td>{ATTRIBUTE.text}</td>
							</tr>
							<!-- END: attribute -->
					 
						</table>
 
					</div>
				</section>


				<!-- Video content -->
				<section class="video-content" id="video-content" style="display:none">
				
				</section>



				<div class="action-repeat">
					<button class="add-to-cart-rp btn-submit-configurable" onclick="$('html, body').animate({scrollTop: 0 }, 'slow');" type="button">Thêm vào giỏ hàng</button>
				</div>


				<div id="product_view_block_upsell"> </div>
 
				<div id="product_view_block_same_brand"> </div>
 
				<div class="collateral-box">
					<div id="discuss-faq">
						<h3 class="dis-title">Hỏi, đáp về sản phẩm</h3>
						<div class="discuss-container" id="discuss-container">
						
						</div>

						<div class="dicuss-item last-discuss">
							<div class="discuss-content">

								<div class="messages">
									 
										<div class="alert alert-danger">
											<i class="fa fa-exclamation-circle"></i> Cảnh báo: Hãy kiểm tra cẩn thận các hình thức cho các lỗi!
											<button type="button" class="close" data-dismiss="alert">×</button>
											<br>
										</div>
									 
								</div>
								<div id="question-form" class="aw-pq2-form" method="post">
									<div class="ask-form">
										<textarea class="required-entry" name="question" placeholder="Hãy đặt câu hỏi liên quan đến sản phẩm..."></textarea>
										<button onclick="send_question()" id="add-question-button" type="submit" class="btn btn-primary not_login">
											<span>Gửi câu hỏi</span>
										</button>
									</div>

									<input type="hidden" name="product_id" value="{PRODUCT_ID}" />
									<input type="hidden" name="faq_id" value="0" />
									<input type="hidden" name="parent_id" value="0" />
									<input type="hidden" name="tokenkey" value="{TOKENKEY}" />
									<input type="hidden" name="send_question" value="1" />
									<input type="hidden" name="faq_error_question" value="{LANG.faq_error_question}" />
									<input type="hidden" name="referer_ajax" value="{BASEURL}" />
								</div>

 
								<div class="aw-pq2-list__no-questions">
									<p style="margin-bottom:0">Các câu hỏi thường gặp về sản phẩm:</p>
									<ul>
										<li>- Sản phẩm này có bền không?</li>
										<li>- Kích thước sản phẩm này?</li>
										<li>- Sản phẩm này có dễ dùng không?</li>
									</ul>
									<p style="margin-top:10px;color:#888">Các câu hỏi liên quan đến vấn đề có hàng, thời gian giao hàng, bảo hành, v.v... vui lòng gửi đến <a href="http://chomongcaionline.vn/contact/" target="_blank">http://chomongcaionline.vn/contact/</a>
									</p>
								</div>
							</div>
						</div>
 
						
					</div>
 
				</div>

				
				
				<script type="text/javascript" src="{NV_BASE_SITEURL}js/star-rating/jquery.rating.pack.js"></script>
				<script src="{NV_BASE_SITEURL}js/star-rating/jquery.MetaData.js" type="text/javascript"></script>
				<link href="{NV_BASE_SITEURL}js/star-rating/jquery.rating.css" type="text/css" rel="stylesheet"/>
				<section class="review-content">
					<div class="review-summary" id="reviewSubmitArea">
						<h3 class="review-title">
							Khách Hàng Nhận Xét
						</h3>
						
						<div class="review-sum-box" id="nhan-xet">
							<ul class="clearfix">
								<li class="review-sum-total">
									<h4>Đánh Giá Trung Bình</h4>

									<p class="total-review-point">
										{REVIEWS_AVG}/5
									</p>

									<div class="rating-box">
										<div class="rating" style="width:{REVIEWS_AVG_PERCENT}%"></div>
									</div>

									<p>{REVIEWS_TOTAL} nhận xét</p>
								</li>

								<li class="review-progress-bar">

									<ul class="review-percent-detail">
										<!-- BEGIN: rating -->
										<li class="clearfix">
											<span class="rating-num">{RATE.rating} sao</span>

											<div class="progress">
												<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{RATE.percent}%" aria-valuemin="0" aria-valuemax="100" style="width: {RATE.percent}%" title="{RATE.percent}% đánh giá {RATE.rating} sao">
													<span class="sr-only">{RATE.percent}% Complete</span>
												</div>
											</div>

											<span class="rating-num-total" >{RATE.total}</span>
										</li>
										<!-- END: rating -->
									</ul>
								</li>

								<li class="review-submit">
									<h4>Chia sẻ nhận xét về sản phẩm</h4>
									<button type="button" class="btn btn-primary submit-your-review" onclick="show_form_review()">
										<i class="icon-pencil icon-white"></i> Viết nhận xét của bạn
									</button>
								</li>
							</ul>

							<div class="review-form-submit inside-form hides"> 
								<div class="writeReviewBox" id="reviewform">
									<h3 class="section-title review-form-title">Gửi nhận xét của bạn</h3>
									<div class="WriteReviewForm clearfix">
										<div class="col-xs-12 col-sm-6 col-md-6">
											<input type="hidden" id="review_error_ratings" value="{LANG.review_error_ratings}">
											<input type="hidden" id="review_error_title" value="{LANG.review_error_title}">
											<input type="hidden" id="review_error_detail" value="{LANG.review_error_detail}">
											<div id="review-messages"></div>
											<div class="reviewForm">
												<div id="review-form">
													<ul>
														<li>
															<div id="product-review-table" class="clearfix rating-star">

																<div class="rating-star-n"> <span class="review-step">1.</span> Đánh giá của bạn về sản phẩm này:</div>

																<div class="rating-star-i">
																	<input type="radio" class="star" name="review_ratings" id="Đánh giá_1" value="1" />
																	<input type="radio" class="star" name="review_ratings" id="Đánh giá_2" value="2" />
																	<input type="radio" class="star" name="review_ratings" id="Đánh giá_3" value="3" />
																	<input type="radio" class="star" name="review_ratings" id="Đánh giá_4" value="4" />
																	<input type="radio" class="star" name="review_ratings" id="Đánh giá_5" value="5" />
																</div>

															</div>
															<input type="hidden" name="review_review_id" value="0" />
 															<input type="hidden" name="review_parent_id" value="0" />
 															<input type="hidden" name="review_product_id" value="{PRODUCT_ID}" />
 															<input type="hidden" name="review_send" value="1" />
 															<input type="hidden" name="review_token" value="{REVIEWTOKEN}" />
 														</li>

														<li>
															<label><span class="review-step">2.</span> Tiêu đề của nhận xét:</label>
															<input type="text" tabindex="1" name="review_title" id="summary_field" class="form-control input-sm required-entry ReviewTitleInput" value="" placeholder="Tiêu đề của nhận xét">
														</li>

														<li>
															<label><span class="review-step">3.</span> Viết nhận xét của bạn vào bên dưới:</label>
															<textarea rows="3" class="form-control input-sm ReviewTextArea" name="review_detail" id="review_field" placeholder="Nhập nội dung nhận xét tại đây. Tối thiểu 100 từ, tối đa 2000 từ"></textarea>

															<div class="button-review clearfix">
																<div class="left review-confirm">
																	<div class="facebook-check">
																		<input type="checkbox" value="1" name="review_post_facebook" id="postFacebook" checked="checked" />
																		<label for="postFacebook"> Đăng nhận xét lên <span class="post-to-fb">facebook</span></label>
																	</div> 

																	<div class="public-check">
																		<input type="checkbox" value="1" name="review_show_information" id="show_information" checked="checked" />
																		<label for="show_information"> Hiển thị thông tin mua hàng trong phần nhận xét</label>
																	</div>
																</div>

																<div class="right">
																	<button onclick="send_review()" id="send-review" type="submit" class="btn btn-primary">
																		<span>Gửi nhận xét</span>
																	</button>
																</div>
															</div>
														</li>
													</ul>
												</div>
											</div>
										</div>
										<div class="col-xs-12 col-sm-6 col-md-6">
										
											<div class="reviewRules">
												<ul>
													<li><b>NHẬN NGAY <a target="_blank" title="Hướng dẫn về Điểm thưởng" href="#">Điểm thưởng</a> KHI CHIA SẺ NHẬN XÉT HAY</b>
													</li>
													<li>- Điểm thưởng là hệ thống điểm thưởng (giá trị quy đổi <span class="price"><b>2,000 Điểm thưởng</b></span> tương ứng <span class="price"><b>10,000 đồng</b></span>) được dùng khi mua hàng tại ChoMongCaiOnline.vn.</li>
													<li>- 1 nhận xét được duyệt sẽ được tặng xu như sau: <span class="price"><b>400 Điểm thưởng</b></span> cho khách hàng đã từng mua hàng thành công tại ChoMongCaiOnline.vn, và <span class="price"><b>200 Điểm thưởng</b></span> cho khách hàng chưa từng mua hàng thành công.</li>
													<li>- Tiêu chí duyệt nhận xét:
														<ul>
															<li>• Tối thiểu 100 từ đối với sản phẩm sách, 50 từ đối với sản phẩm các ngành hàng khác.</li>
															<li>• Được viết bằng tiếng Việt chuẩn, có dấu.</li>
															<li>• Hữu ích đối với người đọc, nêu rõ điểm tốt/chưa tốt của sản phẩm.</li>
															<li>• Nội dung chưa từng được đăng trên các website khác và do chính người gửi nhận xét viết.</li>
															<li>• Không mang tính quảng cáo, kêu gọi mua sản phẩm một cách không cần thiết.</li>
															<li>• Không nhận xét ác ý, cố tình bôi xấu sản phẩm.</li>
														</ul>
													</li>
													<li><a target="_blank" title="Thông tin thêm về Điểm thưởng" href="#"><b>Thông tin thêm</b></a>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
 
					<div class="review-show" id="reviewShowArea">
						<ul class="nav nav-tabs review-tabs">
							<li class="active"><a href="#moi-nhat" data-toggle="tab">Nhận Xét Mới Nhất<span class="active-arrow"></span></a>
							</li>
							<li><a href="#yeu-thich" data-toggle="tab">Nhận Xét Được Yêu Thích<span class="active-arrow"></span></a>
							</li>
							<li class="fb-thaoluan">
								<a id="fb-cm" title="Thảo luận về sản phẩm" href="#facebook-comment" data-toggle="tab">
								Thảo Luận
								(<span class="inline fb-comments-count" data-href="{SELFURL}">10</span>)
								<span class="active-arrow"></span>
							</a>
							</li>
							<li class="supportArea">
								<a target="_blank" id="btt-support" href="#" title="Hỗ trợ nhanh từ ChoMongCaiOnline">Hỗ trợ nhanh từ CMC</a>
							</li>
						</ul>


						<div class="tab-content">

							<div class="tab-pane active review-list" id="moi-nhat">
								<ul class="list-items">
									<!-- BEGIN: reviews -->
									<li class="clearfix" itemprop="reviews" itemscope itemtype="http://schema.org/Review">
										<div class="review-name">
											<img width="65" height="65" alt="{REVIEWS.customer_name}" src="{REVIEWS.photo}" title="{REVIEWS.customer_name}">
											<p class="name" itemprop="author">{REVIEWS.customer_name}</p>
											<p class="_from"><span class="from-locate">đến từ </span>{REVIEWS.customer_address}</p>
											<p class="review-date">
												<time class="date" datetime="{REVIEWS.datetime}">({REVIEWS.date_string})</time>
											</p>
										</div>

										<div class="review-item-detail">
											<div class="rating-name clearfix">
												<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
													<meta itemprop="ratingValue" content="3">
													<div class="rating-box">
														<div class="rating" style="width: {REVIEWS.rating_percent}%"></div>
													</div>
												</div>
												<div class="reating-title" itemprop="name">
													<a href="#">
														{REVIEWS.title}
													</a>
												</div>
											</div>
											<div class="review-item-content" itemprop="reviewBody">
												<div class="content-inline" id="inline-content-146872_1">
													{REVIEWS.detail}
												</div>
											</div>


											<div class="btt-content clearfix">
												<div class="fr">
													<a class="review-report not_login">Báo xấu</a>
												</div>

												<div class="fr show-comment-area">
													<a class="show-comment not_login" id="r146872_1">Gửi trả lời</a>
												</div>

												<div class="left thanks-total">

													<div class="review-btts vote_buttom_146872_1">

														<span class="thanks-box"><span id="review_thank_146872_1">2 người</span> đã cảm ơn nhận xét này.</span> <span class="useful-rv">
															Nhận xét này hữu ích với bạn? 
														</span>
														<button type="button" class="btn btn-primary review-thank not_login">
															<i class="fa fa-thumbs-o-up tk-i__like"></i> Cảm ơn
														</button>
													</div>
												</div>
											</div>
											<div class="comment-box" id="commnet_field_146872_1">
												<textarea placeholder="Nhập nội dung trả lời tại đây. Tối thiểu 100 từ, tối đa 1500 từ." maxlength="1500" id="comment_detail_146872_1" name="comment_detail_146872_1" class="form-control input-sm"></textarea>
												<div class="btt-submit-review">
													<button class="button not_login" type="button"><span>Gửi trả lời của bạn</span>
													</button>
													<button class="button cancel-reply" type="button"><span>Hủy bỏ</span>
													</button>
												</div>
											</div>

											 

											<ul class="Rv_feedbacks user-reply-content" id="commentReview_146872_1"></ul>

										</div>
									</li>
									<!-- END: reviews -->
								</ul>
							</div>

							<!-- Danh sách đánh giá sản phẩm -->
							<div class="tab-pane review-list" id="yeu-thich">
								<ul>
								 
									<li class="clearfix">
										<div class="review-name">
											<img width="65" height="65" alt="Ng Ncn" src="http://graph.facebook.com/100003098023608/picture?width=65&height=65" title="Ng Ncn">
											<p class="name">Ng Ncn</p>
											<p class="_from"><span class="from-locate">đến từ </span>Cà Mau</p>
											<p class="review-date">
												<time class="date" datetime="2015-01-05">(5 ngày trước)</time>
											</p>
										</div>

										<div class="review-item-detail">
											<div class="rating-name clearfix">
												<div>
													<div class="rating-box">
														<div class="rating" style="width: 60%"></div>
													</div>
												</div>
												<div class="reating-title">
													<a href="#"> 	Thiết kế ổn, màn hình tệ </a>
												</div>
											</div>




											<div class="review-item-content">
												<div class="content-inline" id="inline-content-146872_6">Là 1 sản phẩm mới của Samsung nhưng lại không quá nổi bật trong các dòng anh em của mình với khá nhiều khuyết điểm.
													<br /> _Thiết kế nhìn chung không thay đổi nhiều ngoại trừ những phần góc cạnh được bo nhẹ tạo cảm giác mạnh mẽ, nam tính hơn tí, khung viền kim loại khá sang trọng, phần lưng nhựa bóng để bám vân tay nhưng lại được thiết kế liền khối, khung camera lồi khá xấu vì máy khá mỏng.
													<br /> _Màn hình to những 5.5&quot; nhưng chất lượng khá tệ, độ phân giải khá thấp chưa đạt tới HD - một chuẩn mà các dòng máy trung cấp đã đáp ứng đa số, nhiều người không thích cảm giác công nghệ màn hình Super AMOLED cho lắm nhất là khi ở sản phẩm này rất dễ xuất hiện tình trạng rỗ ở hình ảnh.
													<br /> _Cấu hình thì khả quan hơn hẳn, mạnh mẽ với lượng RAM vừa đủ, đáp ứng tốt hầu hết các game hay ứng dụng nặng hiện nay trên Android, tất nhiên là các thao tác cơ bản rất mượt mà và ổn định khiến trải nghiệm rất trơn tru.
													<br /> _Camera chất lượng cũng rất ổn, cả CAM trước và sau đều khá tốt, cho chất lượng cao và đẹp, Cam trước 5.0 cũng thích hợp cho nhu cầu tự sướng của giới trẻ hiện nay. Lượng Pin cũng ở mức trung bình khi phải gánh màn hình khá lớn và cấu hình khủng, vẫn đủ dùng trong ngày.</div>
											</div>


											<div class="btt-content clearfix">
												<div class="right">
													<a class="review-report not_login">Báo xấu</a>
												</div>

												<div class="right show-comment-area">
													<a class="show-comment not_login" id="r146872_6">Gửi trả lời</a>
												</div>

												<div class="left thanks-total">

													<div class="review-btts vote_buttom_146872_6">

														<span class="thanks-box"><span id="review_thank_146872_6">2 người</span> đã cảm ơn nhận xét này.</span> <span class="useful-rv">
															Nhận xét này hữu ích với bạn? 
														</span>
														<button type="button" class="review-thank not_login">
															<i class="fa fa-thumbs-o-up tk-i__like"></i> Cảm ơn
														</button>
													</div>
												</div>
											</div>
											<div class="comment-box" id="commnet_field_146872_6">
												<textarea placeholder="Nhập nội dung trả lời tại đây. Tối thiểu 100 từ, tối đa 1500 từ." maxlength="1500" id="comment_detail_146872_6" name="comment_detail_146872_6" class="required-entry ReviewTextArea"></textarea>
												<div class="btt-submit-review">
													<button class="button not_login" type="button"><span>Gửi trả lời của bạn</span>
													</button>
													<button class="button cancel-reply" type="button"><span>Hủy bỏ</span>
													</button>
												</div>
											</div>
 
											<ul class="Rv_feedbacks user-reply-content" id="commentReview_146872_6"></ul>

										</div>
									</li>
								</ul>
							</div>
							

							 <div class="tab-pane " id="facebook-comment">   
								 <fb:comments href="{SELFURL}" width="980" data-colorscheme="light"></fb:comments> 
							</div> 
						</div>
					</div>
 
				</section>
			</div>

		</div>
		<div class="clear"></div>
			<div class="chitietsanpham">
				<div class="sanphamcungloai">

					<!-- BEGIN: other -->
					{OTHER}
					<!-- END: other -->
				</div>
				<div class="post_excerpt">

					<div class="clear"></div>
					<!-- BEGIN: other_view -->
					<div class="cate">
						<h2>{LANG.detail_others_view}</h2>
					</div>
					{OTHER_VIEW}
					<!-- END: other_view -->
				</div>
				

			</div>
	</div>
</div>
<!--  login_form -->
<div class="modal fade" id="login-form" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
<!-- login_form -->
 
<script type="text/javascript">
$(document).ready(function(){
 
	$.ajax({
		url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax&nocache=' + new Date().getTime(),
		type: 'post',
		data: 'check_video=1&product_id={PRODUCT_ID}&token={TOKEN}',
		dataType: 'json',
		success: function(json) 
		{
			if ( json['info'] ) 
			{
				$('#tabvideo').show();
				$('#video-content').html(json['info']).show();

			}
		}
	}); 
 
	$.ajax({
		url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax&nocache=' + new Date().getTime(),
		type: 'post',
		data: 'check_faq=1&product_id={PRODUCT_ID}&alias={PRODUCT_ALIAS}&token={TOKEN}',
		dataType: 'json',
		success: function(json) 
		{
			if ( json['info'] ) 
			{
				 
				$('#discuss-container').html(json['info']).show();

			}
		}
	}); 
	 
});
</script>
<script type="text/javascript">
var product_height_limit = 720;
var scroll_time = 500;
if ($('.body-detail ul.product-tabs').length > 0) {
    var tabs = $('.body-detail div.product-tabs-detail');

    $('.body-detail .product-content-area').css({
        'position': 'absolute',
        'visibility': 'hidden',
        'display': 'block'
    });
    var height = $('.body-detail .product-content-area').outerHeight();
    $('.body-detail .product-content-area').removeAttr('style');

    if (height >= product_height_limit) {
        $('.show-more', tabs).show();
    }

    $('.show-more', tabs).click(function() {
        $('.product-content-area', tabs).css('max-height', 'none');
        $('.show-more', tabs).hide();
        $('.show-less', tabs).show();
    });

    $('.show-less', tabs).click(function() {
        $('.product-content-area', tabs).css('max-height', product_height_limit + 'px');
        $('.show-less', tabs).hide();
        $('.show-more', tabs).show();
        $('html, body').animate({
            scrollTop: $('#gioi-thieu').offset().top - 50
        }, scroll_time);
    });

    // menu fixed position
    var nav = $('.product-tabs');
    var recentviewHeight = ($('.recentview').length > 0) ? $('.recentview').height() : 0;
    var repeatActionHeight = $('.action-repeat').outerHeight(true);

    var stickyNav = function() {
        var height = $('.b-header-3').outerHeight() + $('.b-main').outerHeight() - $('.product-content').outerHeight() - recentviewHeight - nav.outerHeight(true);
        var reviewHeight = $('.b-header-').outerHeight() + $('.b-main').outerHeight() - $('.review-content').outerHeight() - recentviewHeight - nav.outerHeight(true) - repeatActionHeight + 200;
        var scrollTop = $(window).scrollTop();

        if (scrollTop >= height) {
            if (scrollTop <= reviewHeight) {
                nav.css('visibility', 'visible');
                nav.addClass('sticky');
                //jQuery("#header").removeClass('b-header-3_fixed');
				$('#menu-top').hide();
                if ($('.video-content', tabs).length > 0) {
                    if (scrollTop >= $('.review-content', tabs).position().top - 50 - repeatActionHeight) {
                        enableTab('nhan-xet');
                    } else if (scrollTop >= $('#discuss-faq', tabs).position().top - 90) {
                        enableTab('discuss-faq');
                    } else if (scrollTop >= $('.video-content', tabs).position().top - 90) {
                        enableTab('video');
                    } else if (scrollTop >= $('.additional-content', tabs).position().top - 70) {
                        enableTab('chi-tiet');
                    } else {
                        enableTab('gioi-thieu');
                    }
                } else {
                    if (scrollTop >= $('.review-content', tabs).position().top - 50 - repeatActionHeight) {
                        enableTab('nhan-xet');
                    } else if (scrollTop >= $('#discuss-faq', tabs).position().top - 90) {
                        enableTab('discuss-faq');
                    } else if (scrollTop >= $('.additional-content', tabs).position().top - 70) {
                        enableTab('chi-tiet');
                    } else {
                        enableTab('gioi-thieu');
                    }
                }
            } else {
                nav.css('visibility', 'hidden');
				$('#menu-top').show();
            }
        } else {
            nav.removeClass('sticky');
			$('#menu-top').show();
        }

    };

    stickyNav();

    $(window).scroll(function() {
		 
        stickyNav();
    });

    // click on product tab menu
    var extra = 0;
    $('.body-detail ul.product-tabs a').click(function() {
        var href = $(this).attr("href");
        extra = (href == '#nhan-xet' && $('.outside-form').length > 0) ? 20 : 60;
        if (nav.hasClass('sticky')) {
            $("html, body").animate({
                scrollTop: $(href).position().top - extra
            }, scroll_time);
        } else {
            $("html, body").animate({
                scrollTop: $(href).position().top - nav.outerHeight(true) - extra
            }, scroll_time);
        }
        return false;
    });

    // Product Description : replace pre by div tag
    $(".product-description pre").wrapInner('<div>').find('div').unwrap();
}
</script>
<!-- END: main -->
<li id="rating-star-{STAR}" class="clearfix">
											<span class="rating-num">{STAR} sao</span>

											<div class="progress">
												<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="100%" aria-valuemin="0" aria-valuemax="100" style="width: 100%" title="100% đánh giá {STAR} sao">
													<span class="sr-only">100% Complete</span>
												</div>
											</div>

											<span class="rating-num-total" >1</span>
										</li>	