<!-- BEGIN: main -->
<div class="row fixed"> 
	<div id="products" itemscope itemtype="http://schema.org/Product">
 	<div class="BoxCateItem multi-columns-row">
		<div class="sanpham">
			<div class="cate">
				<h2>Giảm giá sốc</h2>
			</div>
			<div class="btn-group fr fixb">
				<div class="btn-group grid-list">
					<a href="#" id="list" class="btn btn-default btn-sm">
					<i class="fa fa-th-list"> </i> List</a> 
					<a href="#" id="grid" class="btn btn-default btn-sm">
					<i class="fa fa-th"> </i> Grid</a>
				</div>
				<!-- BEGIN: displays -->
				<div class="btn-group option-box" >
					<select name="sort" id="sort" onchange="nv_chang_price();" class="form-control input-sm">
						<!-- BEGIN: sorts -->
						<option value="{key}" {se}> {value}</option>
						<!-- END: sorts -->
					</select>
				</div>
				<!-- END: displays -->
			</div>
			
			<div class="clear"></div> 
		</div>
		<!-- BEGIN: items -->
		<div class="item col-xs-6 col-sm-4 col-md-6 grid-group-item {CLASS}">
			<div class="product-hover">
				
				<div class="ProductImage lazyload">
					<a href="{LINK}"> <img src="{IMG_LARGE}" class="lazy" data-src="{IMG_LARGE}"> </a>
					<!-- BEGIN: percent -->
					<div class="saleFlag iconSprite "> {PERCENT}<span>%</span> </div>  
					<!-- END: percent --> 
				</div>
						
				
				<div class="ProductPrice">
					<div class="ProductDetails"> <strong><a href="{LINK}"> {TITLE}</a></strong> </div>
					<!-- BEGIN: price -->
					<p class="price">
						<!-- BEGIN: discounts -->
						<span class="money">{PRICE_NEW}</span>
						<span class="discounts_money">{PRICE}</span>
						<!-- END: discounts -->
								
						<!-- BEGIN: no_discounts -->
						<span class="money">{PRICE}</span>
						<!-- END: no_discounts -->
					</p>
					<!-- END: price -->
							
					<!-- BEGIN: contact -->
					<p class="content_price">
						{LANG.detail_pro_price}: <span class="money">{LANG.price_contact}</span>
					</p>
					<!-- END: contact -->
					 
				</div>
				<div class="button-group">
					<!-- BEGIN: order -->
					<button type="button" onclick="cart.add('{PRODUCT_ID}', '{TOKEN}')"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md">{LANG.add_product}</span></button>
					<!-- END: order -->
					<!-- BEGIN: wishlist -->
					<button type="button" data-toggle="tooltip" onclick="wishlist.add('{PRODUCT_ID}', '{TOKEN}')" title="{LANG.wishlist}"><i class="fa fa-heart"></i></button>
					<!-- END: wishlist -->
					<!-- BEGIN: compare -->
					<button type="button" data-toggle="tooltip" onclick="compare.add('{PRODUCT_ID}', '{TOKEN}');" title="{LANG.compare}"><i class="fa fa-exchange"></i></button>
					<!-- END: compare -->
				</div>
			</div>
		</div>
		<!-- END: items -->
		<div class="clear"></div>
	</div>
 	</div>
</div>
<div class="pages" align="center">
	{generate_page}
</div>
<script type="text/javascript">
$(document).ready(function() {
     $('#list').click(function(event){event.preventDefault();$('#products .item').addClass('list-group-item');});
    $('#grid').click(function(event){event.preventDefault();$('#products .item').removeClass('list-group-item');$('#products .item').addClass('grid-group-item');});
});
</script>
<!-- END: main -->