<!-- BEGIN: main -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title"><a href="#collapse-coupon" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">{LANGE.heading_title} <i class="fa fa-caret-down"></i></a></h4>
    </div>
    <div id="collapse-coupon" class="panel-collapse collapse">
        <div class="panel-body">
            <label class="col-sm-2 control-label" for="input-coupon">{LANGE.entry_coupon}</label>
            <div class="input-group">
                <input type="text" name="coupon" value="{COUPON}" placeholder="{LANGE.entry_coupon}" id="input-coupon" class="form-control input-sm" />
                <span class="input-group-btn">
						<input type="button" value="{LANGE.entry_button_coupon}" id="button-coupon" data-loading-text="Đang tải..."  class="btn btn-primary" />
						</span>
            </div>
            <script type="text/javascript">
                $('#button-coupon').on('click', function() {
                    var coupon = $('#input-coupon').val();
                    if (coupon == '') {
                        alert('{LANGE.error_empty}');
                    } else {
                        $.ajax({
                            url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax&nocache=' + new Date().getTime(),
                            type: 'post',
                            data: 'check_coupon=1&coupon=' + encodeURIComponent($('input[name=\'coupon\']').val()),
                            dataType: 'json',
                            beforeSend: function() {
                                $('#button-coupon').button('loading');

                            },
                            complete: function() {
                                $('#button-coupon').button('reset');
                            },
                            success: function(json) {

                                $('.alert').remove();

                                if (json['error']) {
                                    $('.breadcrumb').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                                    $('html, body').animate({
                                        scrollTop: 0
                                    }, 'slow');

                                }

                                if (json['redirect']) {
                                    location = json['redirect'];
                                }
                            }
                        });
                    }
                    return false;
                });
            </script>
        </div>
    </div>
</div>
<!-- END: main -->