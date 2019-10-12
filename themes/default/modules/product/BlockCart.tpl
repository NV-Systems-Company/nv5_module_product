<!-- BEGIN: main --> 
<style type="text/css">
#product-cart {
    margin-bottom: 10px;
}
#product-cart > .btn {
    font-size: 12px;
    line-height: 18px;
    color: #FFF;
}
#product-cart .btn-inverse {
    color: #ffffff;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
    background-color: #25aae2;
    background-image: linear-gradient(to bottom, #25aae2, #1489ba);
    background-repeat: repeat-x;
    border-color: #0c678d #4a97b8 #22a3da;
}
#product-cart .dropdown-menu {
    background: #eee;
    z-index: 1001;
}
#product-cart .dropdown-menu {
    min-width: 100%;
}
#product-cart .dropdown-menu li > div {
    min-width: 427px;
    padding: 0 10px;
}
#product-cart > ul > li a.thumb{
	height:50px;
	width:50px;
	display:block;
	overflow:hidden
}
#product-cart > ul > li a > img {
	max-height:50px;
	width:100%
}
.btn-group > .btn, .btn-group > .dropdown-menu, .btn-group > .popover {
    font-size: 12px;
}
#product-cart > button > i{font-size:16px}

</style>
<div id="product-cart" class="btn-group btn-block">
	<button id="product-block-cart" type="button" data-toggle="dropdown" class="btn btn-inverse btn-block btn-lg dropdown-toggle">
		<i class="fa fa-shopping-cart"></i> 
		<span id="cart-total">{TEXT_ITEMS}</span>
	</button>
	<ul class="dropdown-menu pull-right">
 				
	<!-- BEGIN: data -->
	 <li>
      <table class="table table-striped">
		
		<!-- BEGIN: product -->
        <tr rel="{PRODUCT.cart_id}">
			<td class="text-center">
				<!-- BEGIN: thumb -->
				<a href="{PRODUCT.link}" class="thumb"><img src="{PRODUCT.thumb}" alt="{PRODUCT.name}" title="{PRODUCT.name}" class="img-thumbnail" /></a>
				<!-- END: thumb -->
			</td>
			<td class="text-left"><a href="{PRODUCT.link}">{PRODUCT.name}</a>
				<!-- BEGIN: option -->
				<br /> - <small>{OPTION.name} {OPTION.value}</small>
				<!-- END: option -->
            </td>
          <td class="text-right">x {PRODUCT.quantity}</td>
          <td class="text-right">{PRODUCT.total}</td>
          <td class="text-center"><button type="button" onclick="cart.remove('{PRODUCT.cart_id}');" title="{LANG.cart_button_remove}" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></td>
        </tr>
		<!-- END: product -->
		
		<!-- BEGIN: vouchers -->
        <tr>
          <td class="text-center"></td>
          <td class="text-left">{VOUCHERS.description}</td>
          <td class="text-right">x&nbsp;1</td>
          <td class="text-right">{VOUCHERS.amount}</td>
          <td class="text-center text-danger"><button type="button" onclick="voucher.remove('{VOUCHERS.key}');" title="{LANG.cart_button_remove}" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></td>
        </tr>
        <!-- END: vouchers -->
		
      </table>
    </li>
	<li>
      <div>
        <table class="table table-bordered">
         <!--  BEGIN: total -->
          <tr>
            <td class="text-right"><strong>{TOTAL.title}</strong></td>
            <td class="text-right">{TOTAL.text}</td>
          </tr>
         <!--  END: total -->
        </table>
        <p class="text-right"><a href="{LINK_CART}"><strong><i class="fa fa-shopping-cart"></i> {LANG.cart_viewcart}</strong></a>&nbsp;&nbsp;&nbsp;<a href="{LINK_CHECKOUT}"><strong><i class="fa fa-share"></i> {LANG.cart_checkout}</strong></a></p>
      </div>
	</li> 
 
	<!-- END: data -->
	
	<!-- BEGIN: empty -->
	<li>
		<p class="text-center">{LANG.cart_empty}</p>
	</li> 
	<!-- END: empty -->
 
	</ul>									
</div> 
<script type="text/javascript">var product_mod = '{MOD_NAME}';</script>	
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/product_cart.js"></script>
<!-- END: main -->

<!-- BEGIN: loadcart -->

<div id="product-cart" class="btn-group btn-block">
	<button type="button" data-toggle="dropdown" class="btn btn-inverse btn-block btn-lg dropdown-toggle">
		<i class="fa fa-shopping-cart"></i> 
		<span id="cart-total">{TEXT_ITEMS}</span>
	</button>
	<ul class="dropdown-menu pull-right">
 					
	<!-- BEGIN: data -->
	 <li>
      <table class="table table-striped">
		
		<!-- BEGIN: product -->
        <tr rel="{PRODUCT.cart_id}">
			<td class="text-center">
				<!-- BEGIN: thumb -->
				<a href="{PRODUCT.link}" class="thumb"><img src="{PRODUCT.thumb}" alt="{PRODUCT.name}" title="{PRODUCT.name}" class="img-thumbnail" /></a>
				<!-- END: thumb -->
			</td>
			<td class="text-left"><a href="{PRODUCT.link}">{PRODUCT.name}</a>
				<!-- BEGIN: option -->
				<br /> - <small>{OPTION.name} {OPTION.value}</small>
				<!-- END: option -->
            </td>
          <td class="text-right">x {PRODUCT.quantity}</td>
          <td class="text-right">{PRODUCT.total}</td>
          <td class="text-center"><button type="button" onclick="cart.remove('{PRODUCT.cart_id}');" title="{LANG.cart_button_remove}" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></td>
        </tr>
		<!-- END: product -->
		
		<!-- BEGIN: vouchers -->
        <tr>
          <td class="text-center"></td>
          <td class="text-left">{VOUCHERS.description}</td>
          <td class="text-right">x&nbsp;1</td>
          <td class="text-right">{VOUCHERS.amount}</td>
          <td class="text-center text-danger"><button type="button" onclick="voucher.remove('{VOUCHERS.key}');" title="{LANG.cart_button_remove}" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></td>
        </tr>
        <!-- END: vouchers -->
		
      </table>
    </li>
	<li>
      <div>
        <table class="table table-bordered">
         <!--  BEGIN: total -->
          <tr>
            <td class="text-right"><strong>{TOTAL.title}</strong></td>
            <td class="text-right">{TOTAL.text}</td>
          </tr>
         <!--  END: total -->
        </table>
        <p class="text-right"><a href="{LINK_CART}"><strong><i class="fa fa-shopping-cart"></i> {LANG.cart_viewcart}</strong></a>&nbsp;&nbsp;&nbsp;<a href="{LINK_CHECKOUT}"><strong><i class="fa fa-share"></i> {LANG.cart_checkout}</strong></a></p>
      </div>
	</li> 
	<!-- END: data -->
	
	<!-- BEGIN: empty -->
	<li>
		<p class="text-center">{LANG.cart_empty}</p>
	</li> 
	<!-- END: empty -->
	</ul>									
</div>  
<!-- END: loadcart -->

