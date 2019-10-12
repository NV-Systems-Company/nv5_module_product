<!-- BEGIN: main -->
<div id="shops-log">&nbsp;</div>
<div id="category" style="padding:3px;" itemscope itemtype="http://schema.org/Product">
	<!-- BEGIN: displays -->
	<div class="option-box" >
		{LANG.displays_product}
		<select name="sort" id="sort" onchange="nv_chang_price();" class="form-control input-sm">
			<!-- BEGIN: sorts -->
			<option value="{key}" {se}> {value}</option>
			<!-- END: sorts -->
		</select>
	</div>
	<!-- END: displays -->
	<div class="clear"></div>
	<h2 class="page_title">{CAT_NAME}</h2>
	<!-- BEGIN: image -->
	<img src="{IMAGE}" alt="{CAT_NAME}">
	<!-- END: image -->
	<!-- BEGIN: items -->
	<div class="list_rows clearfix">
		<div class="img clearfix">
			<a title="{TITLE}" href="{LINK}"> <img class="reflect" src="{IMG_LARGE}" alt="{TITLE}"  width="150"/> </a>
		</div>
		<p style="padding:5px">
			<strong>
				<a class="title-list" title="{TITLE}" href="{LINK}">{TITLE}</a>
				<!-- BEGIN: new -->
				<span class="newday">({LANG.newday})</span>
				<!-- END: new -->
			</strong>
			<br />
			<!-- BEGIN: product_code -->
			{LANG.product_code}: <strong>{PRODUCT_CODE}</strong>
			<br />
			<!-- END: product_code -->
			<span class="time_up">{publtime}</span>
			<br />
			<span>
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
				{LANG.detail_pro_price}: <span class="money">{LANG.price_contact}</span>
				<br />
				<!-- END: contact -->
				{intro} </span>
			<!-- BEGIN: compare -->
			<br />
			<span class="compare"> <input type="checkbox" value="{PRODUCT_ID}" {ch} onclick="nv_compare({PRODUCT_ID});" id="compare_{PRODUCT_ID}"/> <input type="button" value="{LANG.compare}" name="compare" class="bsss" onclick="nv_compare_result();"/> </span>
			<!-- END: compare -->
		</p>

		<div class="fr" style="margin-bottom:5px; width:140px" align="right">
			<!-- BEGIN: adminlink -->
			<!-- {ADMINLINK} -->
			<!-- END: adminlink -->
			<!-- BEGIN: order -->
			<a href="javascript:void(0)" id="{PRODUCT_ID}" title="{TITLE}" class="pro_order" onclick="cartorder(this)">{LANG.add_product}</a>
			<!-- END: order -->
			
			<!-- BEGIN: product_empty -->
			<a href="javascript:void(0)" class="pro_detail">{LANG.product_empty}</a>
			<!-- END: product_empty -->
			
			<!-- BEGIN: wishlist -->
			<a href="javascript:void(0)" title="{TITLE}" onclick="wishlist.add('{PRODUCT_ID}', '{TOKEN}')"  class="pro_detail">{LANG.wishlist}</a>
			<!-- END: wishlist -->
		</div>
		<div class="clear"></div>
	</div>
	
	<!-- END: items -->
	<div class="pages">
		{pages}
	</div>
</div>
<div class="msgshow" id="msgshow"></div>
<!-- END: main -->