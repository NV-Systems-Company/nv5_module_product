<!-- BEGIN: subcat -->
<ul class="sub_cat">
	<!-- BEGIN: loop -->
	<li>
		<span class="icon_toggle"></span>
		<a href="{SUBCAT.link}">
			<span class="cat_name">{SUBCAT.name}
		</span> 
		</a>
	</li>
	<!-- END: loop -->
</ul>
<!-- END: subcat -->
<!-- BEGIN: main -->
<div id="left-sidebar" class="widget-container wd_widget_product_categories">
	<div class="wd_product_categories" id="wd_product_categories_545">
		<ul class="hover_mode">
			<!-- BEGIN: category -->
			<li class="cat_item"><span class="icon_toggle"></span><a href="{CATEGORY.link}"><span class="cat_name">{CATEGORY.name}</span></a>
				<!-- BEGIN: subcat -->
				{SUBCAT}
				<!-- END: subcat -->
			</li>
			<!-- END: category -->
			 
		</ul>
		<div class="clear"></div>
	</div>
</div>
<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/jquery.hoverIntent.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	"use strict";
	
	var _random_id = 'wd_product_categories_545';
	wd_widget_product_categories(_random_id);
	wd_widget_product_categories_dropdown(_random_id);
	
	if( !$('#'+_random_id+' ul:first').hasClass( 'is_dropdown' ) ){
		$(window).bind('resize',$.debounce( 250, function(){
			wd_widget_product_categories_update(_random_id);
		}) );
	}
	else{
		$('#'+_random_id+' a.current').parents('ul.sub_cat').siblings('.icon_toggle').trigger('click');
	}
});
function wd_widget_product_categories_update(_random_id){
	// unbind event
	$("#"+_random_id+" ul li").unbind("mouseenter").unbind("mouseleave");
	$("#"+_random_id+" ul li .icon_toggle").unbind("click");
	
	var _width = $(window).width();
	if( _width <= 1024 ){
		$("#"+_random_id+" ul:first").addClass('dropdown_mode').removeClass('hover_mode');
		wd_widget_product_categories_dropdown(_random_id);
		$("#"+_random_id+" ul li ul").css({'opacity':1});
	}
	else{
		$("#"+_random_id+" ul:first").removeClass('dropdown_mode').addClass('hover_mode');
		wd_widget_product_categories(_random_id);
		$("#"+_random_id+" ul li ul").css({'opacity':0});
	}
}
function wd_widget_product_categories(_random_id){
	var _parent_li = $("#"+_random_id+" ul.hover_mode li.cat_item ul.sub_cat").parent("li");
	_parent_li.addClass("has_sub");
	
	_parent_li.hoverIntent(
	function(){
		if( $(this).css('opacity') != 1 )
			return;
		var _child_ul = $(this).find("ul.sub_cat:first");
		var _is_left_sidebar = $(this).parents("#left-sidebar").length == 1;
		if( $(this).parents('.header-category').length > 0 ){
			_is_left_sidebar = !$('body').hasClass('rtl');
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
		var _child_ul = $(this).find("ul.sub_cat");
		var _is_left_sidebar = $(this).parents("#left-sidebar").length == 1;
		if( $(this).parents('.header-category').length > 0 ){
			_is_left_sidebar = !$('body').hasClass('rtl');
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
	var _parent_li = $("#"+_random_id+" ul.dropdown_mode li.cat_item ul.sub_cat").parent("li");
	_parent_li.addClass("has_sub");
	
	_parent_li.find('.icon_toggle').bind('click',function(){
		var parent_li = $(this).parent('li.has_sub');
		if( !$(this).hasClass('active') ){
			parent_li.find('ul.sub_cat:first').slideDown();
			$(this).addClass('active');
		}
		else{
			parent_li.find('ul.sub_cat').slideUp();
			$(this).removeClass('active');
			parent_li.find('.icon_toggle').removeClass('active');
		}
	});
}			
</script>
<!-- END: main -->

