<!-- BEGIN: main -->
<div class="panel panel-default">
    <div class="panel-heading">
        <h4 class="panel-title"><a href="#collapse-voucher" data-toggle="collapse" data-parent="#accordion" class="accordion-toggle">{LANGE.heading_title} <i class="fa fa-caret-down"></i></a></h4>
    </div>
    <div id="collapse-voucher" class="panel-collapse collapse">
        <div class="panel-body">
            <label class="col-sm-4 control-label" for="input-voucher">{LANGE.entry_voucher}</label>
            <div class="input-group">
                <input type="text" name="voucher" value="{VOUCHER}" placeholder="{LANGE.entry_voucher}" id="input-voucher" class="form-control input-sm" />
                <span class="input-group-btn">
						<input type="submit" value="{LANGE.entry_button_voucher}" id="button-voucher" data-loading-text="Đang tải..."  class="btn btn-primary" />
						</span>
            </div>
            <script type="text/javascript">
                $('#button-voucher').on('click', function() {
                    var voucher = $('#input-voucher').val();
                    if (voucher == '') {
                        alert('{LANGE.error_empty}');
                    } else {
                        $.ajax({
                            url: nv_siteroot + 'index.php?' + nv_lang_variable + '=' + nv_sitelang + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax&nocache=' + new Date().getTime(),
                            type: 'post',
                            data: 'check_voucher=1&voucher=' + encodeURIComponent($('input[name=\'voucher\']').val()),
                            dataType: 'json',
                            beforeSend: function() {
                                $('#button-voucher').button('loading');

                            },
                            complete: function() {
                                $('#button-voucher').button('reset');
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