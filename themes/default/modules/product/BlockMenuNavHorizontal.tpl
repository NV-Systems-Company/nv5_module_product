<!-- BEGIN: subcat -->
<ul class="list-unstyled">
	<!-- BEGIN: loopx -->
	<li>
		<a href="{SUBCAT.link}">{SUBCAT.name} ({SUBCAT.product_total})</a>	
	</li>
	<!-- BEGIN: clear --></ul><ul class="list-unstyled"><!-- END: clear -->	
	<!-- END: loopx -->
</ul>
<!-- END: subcat -->
<!-- BEGIN: main -->
<div class="navbar-header"><span id="category" class="visible-xs">Danh mục</span>
    <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
</div>
<div class="collapse navbar-collapse navbar-ex1-collapse">
    <ul class="nav navbar-nav">
        <!-- BEGIN: category -->
		<li {DROPDOWN}>
			<a href="{CATEGORY.link}" class="dropdown-toggle" data-toggle="dropdown">{CATEGORY.name}
				<!-- BEGIN: caret -->
				<span class="caret"></span>
				<!-- END: caret -->
			</a> 
            <!-- BEGIN: subcat -->
			<div class="dropdown-menu">
                <div class="dropdown-inner">                    
				{SUBCAT}	
                </div>
			</div>
			<!-- END: subcat -->
        </li>
		<!-- END: category -->         
    </ul>
</div>
<script type="text/javascript">
$('#menu .dropdown-menu').each(function() {
	var menu = $('#menu').offset();
	var dropdown = $(this).parent().offset();
	var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());	
	if (i > 0) {
		$(this).css('margin-left', '-' + (i + 10) + 'px');
	}
});
</script>   
<!-- END: main -->

