<!-- BEGIN: subcat -->
<ul class="submenu">
	<!-- BEGIN: loop -->
	<li>
		<a class="sub-item {SUBCAT.active}" href="{SUBCAT.link}"><i class="fa fa-chevron-right" aria-hidden="true"></i> {SUBCAT.name} ({SUBCAT.product_total})</a>	
		<!-- BEGIN: sub -->
		{SUB}
		<!-- END: sub -->
	</li>
	<!-- END: loop -->
</ul>
<!-- END: subcat -->
<!-- BEGIN: main -->
<ul class="xoxo">
	<li id="wd_product_categories-3" class="widget-container wd_widget_product_categories">
		<div class="widget_title_wrapper">
			<a class="block-control" href="javascript:void(0)"></a>
			<h3 class="widget-title heading-title">Danh mục sản phẩm</h3>
		</div>
		<div class="wd_product_categories" id="wd_product_categories_545">
			<ul class="hover_mode">
				<li class="cat_item"><span class="icon_toggle"></span><a href="product-category/khoa-dien-tu/default.htm"
						class=""><span class="cat_name">Khóa cửa điện tử, vân tay chính hãng</span></a>
					<ul
						class="sub_cat">
						<li><span class="icon_toggle"></span><a href="product-category/khoa-dien-tu/khoa-sam-sung/default.htm"
								class=""><span class="cat_name">Khóa điện tử, vân tay samsung</span></a></li>
						<li><span class="icon_toggle"></span><a href="product-category/khoa-dien-tu/yale/default.htm"
								class=""><span class="cat_name">Khóa điện tử, vân tay YALE</span></a></li>
						<li><span class="icon_toggle"></span><a href="product-category/khoa-dien-tu/adel/default.htm"
								class=""><span class="cat_name">Khóa cửa vân tay ADEL</span></a></li>
						<li><span class="icon_toggle"></span><a href="product-category/khoa-dien-tu/epic/default.htm"
								class=""><span class="cat_name">Khóa điện tử, vân tay EPIC</span></a></li>
						<li><span class="icon_toggle"></span><a href="product-category/khoa-dien-tu/gateman/default.htm"
								class=""><span class="cat_name">Khóa điện tử, vân tay GATEMAN</span></a></li>
						<li><span class="icon_toggle"></span><a href="product-category/khoa-dien-tu/glass/default.htm"
								class=""><span class="cat_name">Khóa điện tử cửa kính</span></a></li>
			</ul>
			</li>
			<li class="cat_item"><span class="icon_toggle"></span><a href="product-category/khoa-van-tay-mat-ma/default.htm"
					class=""><span class="cat_name">Khóa cửa vân tay mật mã</span></a></li>
			<li
				class="cat_item"><span class="icon_toggle"></span><a href="product-category/khoa-the-tu-mat-ma/default.htm"
					class=""><span class="cat_name">Khóa thẻ từ mật mã</span></a></li>
	<li
		class="cat_item"><span class="icon_toggle"></span><a href="product-category/khoa-cua-kinh-thuy-luc/default.htm"
			class=""><span class="cat_name">Khóa cửa kính thủy lực</span></a></li>
		<li
			class="cat_item"><span class="icon_toggle"></span><a href="product-category/khoa-tu/default.htm"
				class=""><span class="cat_name">Khóa tủ đồ</span></a></li>
			<li class="cat_item"><span class="icon_toggle"></span><a href="product-category/khoa-khach-san/default.htm"
					class=""><span class="cat_name">Khóa khách sạn</span></a></li>
			<li class="cat_item"><span class="icon_toggle"></span><a href="product-category/chuong-hinh/default.htm"
					class=""><span class="cat_name">Chuông cửa có hình</span></a>
				<ul class="sub_cat">
					<li><span class="icon_toggle"></span><a href="product-category/chuong-hinh/chuong-aiphone/default.htm"
							class=""><span class="cat_name">Chuông hình AIPHONE</span></a></li>
					<li><span class="icon_toggle"></span><a href="product-category/chuong-hinh/chuong-samsung/default.htm"
							class=""><span class="cat_name">Chuông hình SAMSUNG</span></a></li>
				</ul>
			</li>
			<li class="cat_item"><span class="icon_toggle"></span><a href="product-category/kiem-soat-vao-ra/default.htm"
					class=""><span class="cat_name">Kiểm soát vào - ra</span></a></li>
			<li
				class="cat_item"><span class="icon_toggle"></span><a href="product-category/camera/default.htm"
					class=""><span class="cat_name">Camera quan sát</span></a></li>
				<li
					class="cat_item"><span class="icon_toggle"></span><a href="product-category/phu-kien/default.htm"
						class=""><span class="cat_name">Phụ kiện</span></a></li>
</ul>
<div class="clear"></div>
</div>

</li>
<script type="text/javascript">
				jQuery(document).ready(function(){
					"use strict";
					
					var _random_id = 'wd_product_categories_545';
					wd_widget_product_categories(_random_id);
					wd_widget_product_categories_dropdown(_random_id);
					
					if( !jQuery('#'+_random_id+' ul:first').hasClass( 'is_dropdown' ) ){
						jQuery(window).bind('resize',jQuery.debounce( 250, function(){
							wd_widget_product_categories_update(_random_id);
						}) );
					}
					else{
						jQuery('#'+_random_id+' a.current').parents('ul.sub_cat').siblings('.icon_toggle').trigger('click');
					}
				});
				function wd_widget_product_categories_update(_random_id){
					// unbind event
					jQuery("#"+_random_id+" ul li").unbind("mouseenter").unbind("mouseleave");
					jQuery("#"+_random_id+" ul li .icon_toggle").unbind("click");
					
					var _width = jQuery(window).width();
					if( _width <= 1024 ){
						jQuery("#"+_random_id+" ul:first").addClass('dropdown_mode').removeClass('hover_mode');
						wd_widget_product_categories_dropdown(_random_id);
						jQuery("#"+_random_id+" ul li ul").css({'opacity':1});
					}
					else{
						jQuery("#"+_random_id+" ul:first").removeClass('dropdown_mode').addClass('hover_mode');
						wd_widget_product_categories(_random_id);
						jQuery("#"+_random_id+" ul li ul").css({'opacity':0});
					}
				}
				function wd_widget_product_categories(_random_id){
					var _parent_li = jQuery("#"+_random_id+" ul.hover_mode li.cat_item ul.sub_cat").parent("li");
					_parent_li.addClass("has_sub");
					
					_parent_li.hoverIntent(
					function(){
						if( jQuery(this).css('opacity') != 1 )
							return;
						var _child_ul = jQuery(this).find("ul.sub_cat:first");
						var _is_left_sidebar = jQuery(this).parents("#left-sidebar").length == 1;
						if( jQuery(this).parents('.header-category').length > 0 ){
							_is_left_sidebar = !jQuery('body').hasClass('rtl');
						}
						
						if( _is_left_sidebar ){
							_child_ul.css({'opacity':0,'left': '50%'}).show();
							_child_ul.animate({'opacity':1,'left': '100%'},200);
						}
						else{
							_child_ul.css({'opacity':0,'right': '50%','left':'auto'}).show();
							_child_ul.animate({'opacity':1,'right': '100%','left':'auto'},200);
						}
					},
					function(){
						var _child_ul = jQuery(this).find("ul.sub_cat");
						var _is_left_sidebar = jQuery(this).parents("#left-sidebar").length == 1;
						if( jQuery(this).parents('.header-category').length > 0 ){
							_is_left_sidebar = !jQuery('body').hasClass('rtl');
						}
						
						if( _is_left_sidebar ){
							_child_ul.animate({'opacity':0,'left': '50%'},200,function(){_child_ul.hide().css('left','100%');});
						}
						else{
							_child_ul.animate({'opacity':0,'right': '50%','left':'auto'},200,function(){_child_ul.hide().css({'right':'100%','left':'auto'});});
						}
					});
				}
				function wd_widget_product_categories_dropdown(_random_id){
					var _parent_li = jQuery("#"+_random_id+" ul.dropdown_mode li.cat_item ul.sub_cat").parent("li");
					_parent_li.addClass("has_sub");
					
					_parent_li.find('.icon_toggle').bind('click',function(){
						var parent_li = jQuery(this).parent('li.has_sub');
						if( !jQuery(this).hasClass('active') ){
							parent_li.find('ul.sub_cat:first').slideDown();
							jQuery(this).addClass('active');
						}
						else{
							parent_li.find('ul.sub_cat').slideUp();
							jQuery(this).removeClass('active');
							parent_li.find('.icon_toggle').removeClass('active');
						}
					});
				}
				
			</script>
<!-- END: main -->

