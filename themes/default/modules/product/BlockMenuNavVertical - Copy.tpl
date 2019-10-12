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
<style type="text/css">
.product-menu-vertical {margin-bottom: 10px;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;background: #fff;border: 1px #eee solid;}
.product-menu-vertical ul{padding:0;margin:0;list-style:none}
.product-menu-vertical ul li{list-style:none; border-bottom: 1px #eee solid;}
.product-menu-vertical ul li.nav-menu a{padding: 8px 0 8px 10px}
.product-menu-vertical ul li.nav-menu a.menu-item{display: block;}
.product-menu-vertical ul li.nav-menu a.active{background: #eee;width: 100%;display: block;   color: #000;}
.product-menu-vertical ul li.nav-menu a:hover{}
.product-menu-vertical ul ul.submenu{padding:0;margin:0;list-style:none}
.product-menu-vertical ul ul.submenu li{list-style:none;}
.product-menu-vertical ul ul.submenu li a {padding: 6px 0px 6px 10px;display: block;}
.product-menu-vertical ul ul.submenu li a:hover{}
.product-menu-vertical ul ul.submenu li a i{font-size:10px;font-weight:400}
.product-menu-vertical ul ul.submenu li:last-child{border-bottom:none}
</style>
<div class="product-menu-vertical">
    <ul>
        <!-- BEGIN: category -->
		<li class="nav-menu">
			<a href="{CATEGORY.link}" class="menu-item {CATEGORY.active}">{CATEGORY.name}({CATEGORY.product_total})
			</a> 
            <!-- BEGIN: subcat -->
			{SUBCAT}	
			<!-- END: subcat -->
        </li>
		<!-- END: category -->         
    </ul>
</div>
<!-- END: main -->

