<!-- BEGIN: tree -->

<!-- END: tree -->
<!-- BEGIN: main -->
<nav class="header-nav" role="navigation">
    <div class="header-nav-box {absolute}" id="menu-product">
        <a href="javascript:void(0)" class="menu-all" title="Tất cả Danh Mục">
            <i class="fa fa-bars tk-i-all"></i>
            <h2 class="dropdown-toggle">Tất Cả Danh Mục</h2>
        </a>
        <ul class="menu-nav-list">
            <!-- BEGIN: cat -->
            <li data-submenu-id="submenu-{CAT.category_id}">
                <a class="title-menu" href="{CAT.link}">
                    <div class="nav-item-box">
                        <span class="main-text">{CAT.name}</span><i class="fa fa-angle-right tk-i-nav"></i>
                        <span class="sub-tag">{CAT.description}</span>
                    </div>
                </a>
                
                <div id="submenu-{CAT.category_id}" class="submenuover">
                    
					<ul class="subcat">
						<!-- BEGIN: loop -->
						<li class="subcat-item">
							{CATAGORY}
							
						</li>
						<!-- END: loop -->
					</ul>
					
                </div>
            </li>
            <!-- END: cat -->
        </ul>
        <div class="clear"></div>
    </div>
</nav>
<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/js/menu.js"></script>
<script type="text/javascript" >
    var $menu = $(".menu-nav-list");
    $menu.menuAim({
        activate: activateSubmenu,
        deactivate: deactivateSubmenu,
        exitMenu: function() {
            jQuery(".submenuover").css("display", "none");
            return true;
        }
    });
    function activateSubmenu(row) {
        var $row = $(row),
            submenuId = $row.data("submenuId"),
            $submenu = $("#" + submenuId),
            height = $menu.outerHeight(),
            width = $menu.outerWidth();
        $submenu.css({
            display: "block",
            top: -1,
            left: width - 3, 
            height: height - 4
        });

        $row.find("a").addClass("maintainHover");
    }
    function deactivateSubmenu(row) {
        var $row = $(row),
            submenuId = $row.data("submenuId"),
            $submenu = $("#" + submenuId);
        $submenu.css("display", "none");
        $row.find("a").removeClass("maintainHover");
    }
	
    $(document).click(function() {
        $(".submenuover").css("display", "none");
        $("a.maintainHover").removeClass("maintainHover");
    });
	$(document).ready(function(){
		var $mediaw = $(window).width();
		$(window).resize(function(){	
			$mediaw = $(this).width();	 
		});
		//$('.menu-nav-list').hide();
		//$('a.menu-all').hover(function() 
		//{ 
		///	$('.menu-nav-list').show();
		//});
		//$( "#menu-product" ).mouseleave(function() {
		//	if( $mediaw < 1200) $('.menu-nav-list').hide();
		//});
		$( "a.menu-all" ).click(function() {
			$( "ul.menu-nav-list" ).toggle();
		});		
	});
</script>
<!-- END: main -->