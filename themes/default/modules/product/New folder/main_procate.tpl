<!-- BEGIN: main -->
<div class="row fixed">
	<div id="products" itemscope itemtype="http://schema.org/Product">
	<!-- BEGIN: category -->
	<div class="BoxCateItem multi-columns-row">
		<div class="sanpham">
			<div class="cate">
				<h2><a href="{CATEGORY_LINK}" title="{CATEGORY_NAME}">{CATEGORY_NAME} ({NUM_PRO})</a></h2>
			</div>

			<div class="clear"></div> 
		</div>
		<div class="col-md-4 blockcategory">
			[BODYCATEGORY{CATEGORY_ID}]
		</div>
		<div class="col-md-20">
			<div class="rows">
			<!-- BEGIN: items -->
			<div class="col-xs-6 col-sm-4 col-md-6 {CLASS}">
				<div class="product-hover">
					
					<div class="ProductImage lazyload">
						<a href="{LINK}"> <img src="{IMG_LARGE}" class="lazy" data-src="{IMG_LARGE}"> </a>
						<!-- BEGIN: percent -->
						<div class="saleFlag iconSprite "> {PERCENT}<span>%</span> </div>  
						<!-- END: percent --> 
					</div>
					<div class="ProductPrice">
						<div class="ProductDetails"> <strong><a href="{LINK}"> {TITLE0}</a></strong> </div>
					
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
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<!-- END: category -->
	</div>
</div>
 
<!-- END: main -->