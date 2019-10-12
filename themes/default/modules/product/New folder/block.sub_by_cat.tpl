<!-- BEGIN: tree -->
<li class="level sub">
	<a title="{MENUTREE.name}" href="{MENUTREE.link}">{MENUTREE.name}</a>
	<!-- BEGIN: tree_content -->
	<ul class="navigation">
		{TREE_CONTENT}
	</ul>
	<!-- END: tree_content -->
</li>
<!-- END: tree -->
<!-- BEGIN: main -->
<!-- BEGIN: cat -->
<div class="verticalmenu">
    <ul id="vertical">
		<!-- BEGIN: loopcat1 -->
        <li class="sub">
            <a href="{CAT.link}"><span>{CAT.name}</span></a>
            <!-- BEGIN: cat2 -->
			<ul class="navigation">
				{HTML_CONTENT}
			</ul>
			<!-- END: cat2 -->
        </li>
	    <!-- END: loopcat1 --> 
	</ul>
</div>
<!-- END: cat -->
<!-- END: main -->