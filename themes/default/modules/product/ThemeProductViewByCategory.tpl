<!-- BEGIN: main -->
<div id="ProductContent">
	<div id="byCategory" itemscope="" itemtype="http://schema.org/Product">
		<!-- BEGIN: category -->
		<div class="boxCategory">
			<div class="boxProduct">        
				<h2><a href="{CATEGORY_LINK}" title="{CATEGORY_NAME}">{CATEGORY_NAME}</a>({PRODUCT_TOTAL})</h2>
				<div class="clear"></div>
			</div>
			<div class="row">
				<!-- BEGIN: product -->
				<div class="col-md-6 col-sm-8 col-xs-12 fixGrid">
					<div class="product-hover">					
						<div class="ProductImage lazyload">
							<a href="{PRODUCT.link}" title="{PRODUCT.name}"> <img class="lazy" data-original="{PRODUCT.thumb}" alt="{PRODUCT.name}"> </a>
							<!-- BEGIN: percent -->
							<div class="saleFlag iconSprite">{PERCENT}<span>%</span> </div>  
							<!-- END: percent --> 
						</div>
						<div class="ProductPrice">
							<div class="ProductDetails"><a href="{PRODUCT.link}" title="{PRODUCT.name}">{PRODUCT.name_short}</a></div>				
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
							<button type="button" onclick="cart.add('{PRODUCT.product_id}', '{PRODUCT.token}')"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md">{LANG.add_product}</span></button>
							<!-- END: order -->
							<!-- BEGIN: wishlist -->
							<button type="button" data-toggle="tooltip" onclick="wishlist.add('{PRODUCT.product_id}', '{PRODUCT.token}')" title="{LANG.wishlist}"><i class="fa fa-heart"></i></button>
							<!-- END: wishlist -->
							<!-- BEGIN: compare -->
							<button type="button" data-toggle="tooltip" onclick="compare.add('{PRODUCT.product_id}', '{PRODUCT.token}');" title="{LANG.compare}"><i class="fa fa-exchange"></i></button>
							<!-- END: compare -->
						</div>
					</div>
				</div>
				<!-- END: product -->
			</div>			
			<div class="clearfix"></div>		
		</div>  	
		<!-- END: category -->	
	</div> 
</div> 

<!-- END: main -->
