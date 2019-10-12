<!-- BEGIN: main -->
<form id="search_mini_form" action="" method="get" role="search">
    <div class="b-header-3__search-input">
        <div class="input-group">
            <div class="input-group-btn b-header-3__search-select">
                <button type="button" class="btn btn-default dropdown-toggle b-header-3__search-all" data-toggle="dropdown" aria-expanded="true">
                    Tất cả <span class="caret"></span>
                </button>
                <ul class="dropdown-menu search-dropdown">
                    <li><a class="pointer">Tất cả</a> </li>
                    <!-- BEGIN: cat -->
                    <li><a class="pointer" data="{CAT.category_id}">{CAT.name}</a> </li>
					<!-- END: cat -->
                </ul>
            </div>
            <input type="text" id="search" autocomplete="off" class="form-control input-sm" name="q" value="" />
            <input type="hidden" name="cat" value="" />
            <span class="input-group-btn">
				<button class="btn btn-default" type="submit">Tìm</button>
			</span>
        </div>
        <div id="search_autocomplete" class="search-autocomplete"></div>
    </div>
</form>
<script type="text/javascript" src="{NV_BASE_SITEURL}modules/{MODULE_FILE}/js/autofill.js"></script>
<script type="text/javascript">
$('input[name=\'q\']').autofill({
	'source': function(request, response) {
		if( $('#search').val().length > 2 )
		{
			var cat = $('input[name="cat"]').val();
			$.ajax({
				url: '{JSON_PRODUCT}&name=' +  encodeURIComponent(request) + '&cat=' +  encodeURIComponent(cat) + '&nocache=' + new Date().getTime(),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return item;
					}), 'template');
				}
			});
		}
	}
});
 
$('body').on('click', '.dropdown-menu.template li', function(){
	$('#search').val($(this).attr('title'));
	if( $(this).find('a').length ) window.location.href = $( this ).find( 'a' ).attr('href');
 
});
$('body').on('click', '.search-dropdown li a.pointer', function(){
	$('span.caret').parent().html(  $(this).text() + ' <span class="caret"></span>' );
	$('input[name=\'cat\']').val($(this).attr('data') ); 	
});
</script> 
<!-- END: main -->

<!-- BEGIN: search_content -->
 
  
    <!-- BEGIN: tags -->
	  <li title="{TAGS}" class="item"><b>{TAGS}</b></li>
    <!-- END: tags -->
	<!-- BEGIN: data -->
	<h3 class="quicklinks">Sản phẩm gợi ý</h3>
    <!-- BEGIN: loop -->
	<li title="{DATA.name}" class="item">
        <a class="clearfix" title="{DATA.name}" href="{DATA.link}">
            <img width="40" height="40" src="{DATA.thumb}" />
            <div class="product_suggestion">
                <p class="instant-search-title">{DATA.name}</p>
				<!-- BEGIN: price -->
					<!-- BEGIN: discounts -->
					<p class="instant-search-price">{PRICE_NEW}<span>{PRICE}</span></p>
					<!-- END: discounts -->
					<!-- BEGIN: no_discounts -->
					<p class="instant-search-price">{PRICE}</p>
					<!-- END: no_discounts -->
				<!-- END: price -->
                <!-- BEGIN: contact -->
				<p class="instant-search-price">Liên hệ</p>
                <!-- END: contact -->
 
            </div>
        </a>
    </li>
	<!-- END: loop -->
	<!-- END: data -->
 
<!-- END: search_content -->

