<!-- BEGIN: main -->
<link rel="stylesheet" type="text/css" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/css/sildeproductdetail.css">
<link rel='stylesheet' id='jquery.fancybox-css' href='{NV_BASE_SITEURL}js/fancybox/jquery.fancybox.css' type='text/css' media='all' />
<link type="text/css" href="{NV_BASE_SITEURL}js/magiczoom/magiczoom.css" rel="stylesheet" media="screen" />

<script type='text/javascript' src='{NV_BASE_SITEURL}js/fancyBox/jquery.fancybox.js'></script>
<script type='text/javascript' src='{NV_BASE_SITEURL}js/fancybox/jquery.mousewheel-3.0.6.pack.js'></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/magiczoom/magiczoom.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}js/magiczoom/magictoolbox.utils.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/shop_detail.js"></script>
<div id="shops-log"></div>

<div class="Product1" itemscope itemtype="http://schema.org/Product">
	<div class="bentrai">
		<!-- BEGIN: othersimg -->
		<div class="center" align="center">
			<a href="{HOMEIMG}" class="MagicZoom fancybox" rel="group" id="zoom" title=""><img  height="450" src="{HOMEIMG}" class="attachment-mycustomsize wp-post-image" alt="{BASENAMEHOME}" style="opacity: 1;"> <div class="MagicZoomPup"></div>
				<div class="MagicZoomHint" >
					{LANG.zoom}
				</div> 
			</a>
		</div>
		<div class="clear"></div>
		<div class="wrap-carosel-box" align="center">
			<div class="list_carousel">
				<div class="caroufredsel_wrapper" style="position: relative; overflow: hidden;width: 400px; height: 52px;">
					<ul id="Carousel-wrap" style="position: absolute; width: 400px; height: 52px;">
						<!-- BEGIN: loop -->
						<li>
							<a href="{IMG_SRC_OTHER}" title="{BASENAME}" rel="group" class="fancybox"> <img itemprop="image" src="{IMG_SRC_OTHER}" alt="{BASENAME}" class="imgThumbZoom" style="border: 1px solid rgb(233, 233, 233);"> </a>
						</li>
						<!-- END: loop -->
					</ul>
				</div>
			</div>
		</div>
		<!-- END: othersimg -->
	</div>
	<div class="ProductMain" id="send-input">
		<div  class="colleft">
			<div class="ProductDetailsGrid">
				<div class="tensp">
					<h1 itemprop="name">{TITLE}</h1>
				</div>
				<!-- BEGIN: allowed_rating -->
				<div class="starbox small ghosting"> </div>
				<div style="text-align: left; font-size: 12px; display: inline-block; vertical-align:top" itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
					<span id="vote_score" itemprop="ratingValue">{RATINGVALUE} </span>/5 (<span id="vote_count" itemprop="reviewCount">{RATINGCOUNT}</span> đánh giá)
				</div>

				<div class="clear"></div>
				<!-- END: allowed_rating -->
			</div>
			<div class="clear"></div>
			<ul class="list-unstyled">

				<!-- BEGIN: manufacturer -->
				<li>
					{LANG.text_manufacturer}: <a href="{MANUFACTURER_LINK}">{MANUFACTURER_NAME}</a>
				</li>
				<!-- END: manufacturer -->

				<!-- BEGIN: model -->
				<li>
					{LANG.text_model}: {MODEL}
				</li>
				<!-- END: model -->

				<!-- BEGIN: reward -->
				<li>
					{LANG.text_reward}: {REWARD}
				</li>
				<!-- END: reward -->

				<li>
					{LANG.text_stock}: {STOCK}
				</li>
			</ul>

			<!-- BEGIN: option -->

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
						</button> </span>
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

			<!-- END: option -->

		</div>
		<div class="colright" >
			<!-- BEGIN: price -->
			<div class="detail_price_around" style="margin-bottom: 10px;" itemprop="offers" itemscope="" itemtype="http://schema.org/Offer">
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
						<div class="price-lang">{LANG.detail_pro_price}: </div>
						<div class="price-new"><span itemprop="price">{PRICE_NEW}</span></div>
						<meta itemprop="priceCurrency" content="{CURRENCY}">
						<link itemprop="availability" href="http://schema.org/InStock">
						<div class="clear"></div>
					</li>
					<li>
						<div class="price-lang old">{LANG.price_old}: </div>
						<div class="price-old"><span class="price-old" style="text-decoration: line-through;">{PRICE}</span></div>
						<div class="clear"></div>	
					</li> 

					<li>
						<div class="percent-lang">{LANG.text_percent}: </div>
						<div class="percent"><span class="percents">-{PERCENT}%</span></div>
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

					<!-- BEGIN: order -->
					<li>

						<input type="hidden" name="product_id" value="{PRODUCT_ID}" >
						<input type="text" name="quantity" value="1" id="quantity" class="form-control input-sm">
						<a class="dt_add_cart" id="button-cart" href="javascript:void(0)" title="Babydoll Maxi Cobalt">{LANG.add_to_cart}</a>
						<div class="clear"></div>
					</li>
					<!-- END: order -->

					<!-- BEGIN: product_empty -->
					<li>
						<div class="fr" style="margin-top: 6px">
							<button class="button disabled">
								{LANG.product_empty}
							</button>
						</div>
						<div class="clear"></div>
					</li>
					<!-- END: product_empty -->

					<!-- BEGIN: discount -->
					<li>
						{DISCOUNT_QUANTITY} {LANG.text_discount} {DISCOUNT_PRICE}
					</li>
					<!-- END: discount -->
				</ul>
			</div>
			<!-- END: price -->

			<!-- BEGIN: contact -->
			{LANG.price_contact}
			<!-- END: contact -->
		</div>

		<div class="clear"></div>
		<div role="tabpanel">

			<!-- Nav tabs -->
			<ul class="nav nav-tabs" role="tablist">
				<li role="presentation" class="active">
					<a href="#detail" aria-controls="detail" role="tab" data-toggle="tab">{LANG.product_info}</a>
				</li>
				<li role="presentation">
					<a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">{LANG.text_manufacturer}</a>
				</li>
<!-- 				<li role="presentation">
					<a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Vật liệu & chăm sóc</a>
				</li> -->

			</ul>

			<!-- Tab panes -->
			<div class="tab-content">
				<div role="tabpanel" class="tab-pane active" id="detail" itemprop="description">
					{DETAIL}
				</div>
				<div role="tabpanel" class="tab-pane" id="profile">
					{MANUFACTURER_DESCRIPTION}

				</div>
				<!-- <div role="tabpanel" class="tab-pane" id="messages"></div> -->

			</div>

		</div>

		<div class="facebook_like">
			<div class="fb-like fb_iframe_widget" data-href="{SELFURL}" data-width="375" data-show-faces="false" data-send="true" fb-xfbml-state="rendered" fb-iframe-plugin-query="app_id=&amp;href=http%3A%2F%2Fmanvn.net%2Fsan-pham%2Fcap-da-nam-cong-so-bvp-cao-cap-khoa-ma-so%2F&amp;locale=vi_VN&amp;sdk=joey&amp;send=true&amp;show_faces=false&amp;width=375">
				<span style="vertical-align: bottom; width: 375px; height: 27px;"><iframe name="f9811e7f4" width="375px" height="1000px" frameborder="0" allowtransparency="true" scrolling="no" title="fb:like Facebook Social Plugin" src="http://www.facebook.com/v2.0/plugins/like.php?app_id=&amp;channel=http%3A%2F%2Fstatic.ak.facebook.com%2Fconnect%2Fxd_arbiter%2FehazDpFPEnK.js%3Fversion%3D41%23cb%3Df554e17ec%26domain%3Dmanvn.net%26origin%3Dhttp%253A%252F%252Fmanvn.net%252Ff266423c5%26relation%3Dparent.parent&amp;href=http%3A%2F%2Fmanvn.net%2Fsan-pham%2Fcap-da-nam-cong-so-bvp-cao-cap-khoa-ma-so%2F&amp;locale=vi_VN&amp;sdk=joey&amp;send=true&amp;show_faces=false&amp;width=375" style="border: none; visibility: visible; width: 375px; height: 27px;" class=""></iframe></span>
			</div>
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
		<div class="fb-comments fb_iframe_widget" data-href="{SELFURL}" data-width="1000" data-numposts="15" data-colorscheme="light" fb-xfbml-state="rendered">
			<span style="height: 111px; width: 1000;"><iframe id="f31d0a4bb8" name="f225da9164" scrolling="no" title="Facebook Social Plugin" class="fb_ltr" src="https://www.facebook.com/plugins/comments.php?api_key=&amp;channel_url=http%3A%2F%2Fstatic.ak.facebook.com%2Fconnect%2Fxd_arbiter%2FehazDpFPEnK.js%3Fversion%3D41%23cb%3Df3693ef544%26domain%3Dmanvn.net%26origin%3Dhttp%253A%252F%252Fmanvn.net%252Ff266423c5%26relation%3Dparent.parent&amp;colorscheme=light&amp;href=http%3A%2F%2Fmanvn.net%2Fsan-pham%2Fcap-da-nam-cong-so-bvp-cao-cap-khoa-ma-so%2F&amp;locale=vi_VN&amp;numposts=15&amp;sdk=joey&amp;skin=light&amp;width=1000" style="border: none; overflow: hidden; height: 111px; width: 1000px;"></iframe></span>
		</div>

	</div>
	<div class="msgshow" id="msgshow"></div>
</div>
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/rating.js"></script>

<script type="text/javascript">
$(document).ready(function() {
 
        $('#button-cart').on('click', function() {
            var error = "";
            product_id = parseInt($(this).attr("id"));
 
            if (error != "") {
                alert(error);
                return false;
            } else {
                $.ajax({
                    url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=setcart&nocache=' + new Date().getTime(),
                    type: 'post',
                    data: $('#send-input input[type=\'text\'], #send-input input[type=\'hidden\'], #send-input input[type=\'radio\']:checked, #send-input input[type=\'checkbox\']:checked, #send-input select, #send-input textarea'),
                    dataType: 'json',
                    beforeSend: function() {
                        //$('#button-cart').button('loading');
                    },
                    complete: function() {
                        //$('#button-cart').button('reset');
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
                            if (json['error']['product']) {
                                $('#shops-log').after('<div class="alert alert-danger">' + json['error']['product'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                            }
                            if (json['error']['recurring']) {
                                $('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
                            }

                            // Highlight any found errors
                            $('.text-danger').parent().addClass('has-error');
                        }

                        if (json['success']) {
                            $('#shops-log').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                            $('#cart-total').html(json['total']);

                            $('html, body').animate({
                                scrollTop: 0
                            }, 'slow');

                            $('#cart > ul').load(nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=loadcart');
                        }
                    }
                });
            }

        });
    });
</script>

<script type="text/javascript">
    $("body").on("click", '.close', function() {
        $('#shops-log .alert').remove();
    });

    $(function() {
        <!-- BEGIN: allowed_print_js -->
        $('#click_print').click(function(event) {
            var href = $(this).attr("href");
            event.preventDefault();
            nv_open_browse(href, '', 640, 500, 'resizable=no,scrollbars=yes,toolbar=no,location=no,status=no');
            return false;
        });
        <!-- END: allowed_print_js -->

        <!-- BEGIN: allowed_rating_js -->
        $(function() {
			 $('.starbox').starbox({
				average: {RATINGWIDTH},
				stars: 5,
				buttons: 5,
				changeable: 'once',
				autoUpdateAverage: false,
				ghosting: true
			}).bind('starbox-value-changed', function(event, value) {
				$.ajax({
                    url: '{LINK_RATE}&nocache=' + new Date().getTime(),
                    type: 'post',
                    data: {rating: value},
                    dataType: 'json',
					success: function(json) { 
						if (json['success']) 
						{
							$('.starbox').starbox('setOption', 'average', json.width);
							$('#vote_score').html( json['ratingValue'] );
							$('#vote_count').html( json['reviewCount'] );
							
							alert(json['success']);
						}
						if (json['error']) 
						{
							alert(json['error']);
						}
					}
 
				});
			});
		}); 
        <!-- END: allowed_rating_js -->
    });
</script>
<!-- END: main -->
