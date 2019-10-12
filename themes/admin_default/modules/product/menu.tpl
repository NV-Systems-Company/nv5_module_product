<!-- BEGIN: main -->
<div style="margin-bottom: 10px;position: relative;z-index: 1;">
    <div class="navbar fixh navbar-defaults navbar-static-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu-horizontal2">
                    <span class="sr-only">&nbsp;</span>
                    <span class="icon-bar">&nbsp;</span>
                    <span class="icon-bar">&nbsp;</span>
                    <span class="icon-bar">&nbsp;</span>
                </button>
            </div>
            <div class="collapse navbar-collapse" id="menu-horizontal2">
                <ul class="nav navbar-nav">
					<!-- BEGIN: cat -->
					<li class="dropdown {CAT.active}">
						<a href="{CAT.link}">{CAT.title}
							<!-- BEGIN: checksub -->
							<strong class="caret">&nbsp;</strong>
							<!-- END: checksub -->
						</a>
						<!-- BEGIN: subcat -->
						<ul class="dropdown-menu">
							<!-- BEGIN: loop -->
							<li class="{SUBCAT.active}"><a href="{SUBCAT.link}" title="{SUBCAT.title}">{SUBCAT.title}</a>  </li>
							<!-- END: loop -->
						</ul>
						<!-- END: subcat -->
					</li>
					<!-- END: cat -->
                </ul>
             </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#menu-horizontal2 .dropdown').hover(function(){
		NV.openMenu(this);
	}, function(){
		NV.closeMenu(this);
	});
});
</script> 
 
<!-- END: main -->