<!-- BEGIN: main -->
{AddMenu} 

<div id="widget">
	<div id="columnleft">
		<ul id="menu-help">
 			<li><a href="#">Hướng dẫn sử dụng</a>
					<ul>
						<li><a href="#">Bắt đầu</a></li> 
						<li><a href="#">Nơi để tải về</a></li> 
						<li><a href="#">Yêu cầu máy chủ/Hosting</a></li> 
						<li><a href="#">Giao diện cửa hàng</a></li> 
						<li><a href="#">Giao diện quản trị</a></li> 
					</ul>
			</li>		
			<li><a href="#">Mục lục</a></li>
			<li><a href="#">Phần mở rộng</a></li>
			<li><a href="#">Bán hàng</a></li>
			<li><a href="#">Báo cáo</a></li>
			<li><a href="#">Hệ thống</a></li>
			<li><a href="#">Trợ giúp</a></li>
			<li><a href="#">Vấn đề khác</a></li>
			<li><a href="#">Hệ thống hướng dẫn quản trị</a>
				<ul>
					<li><a href="#">Thêm nhiều ngôn ngữ</a></li> 
					<li><a href="#">Tạo một cửa hàng nhiều chi nhánh</a></li> 
					<li><a href="#">Di chuyển Website đến một hosting mới</a></li> 
					<li><a href="#">Từ khóa SEO</a></li> 
					<li><a href="#">Các vấn đề bảo mật</a></li> 
				</ul>
			</li>
			<li><a href="#">Hướng dẫn phát triển</a></li>
			<li><a href="#">Hướng dẫn thiết kế</a></li>
 
 		</ul>

	</div> 
  <div id="columnright">
		<div class="help-content">
			<h1>Dữ liệu đang được cập nhật</h1>
		</div>
  </div>
</div>
<link href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.treeview.css" rel="stylesheet"/> 
<script src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.treeview.min.js"></script>
<script src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery/jquery.cookie.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	var h = parseInt($('#productcontentmod').height() ) - 500;
	
	$('#widget').width('100%').height(h).split({orientation:'vertical', limit:0, position:'20%'});
	
	$('#columnright').split({orientation:'horizontal', limit:0, invisible: true});
	
	$("#menu-help").treeview({
		collapsed : true,
		unique : true,
		persist : 'location'
	});
});
 
</script>
<!-- END: main -->