(function($) {
	function Autofill(element, options) {
		this.element = element;
		this.options = options;
		this.timer = null;
		this.items = new Array();

		$(element).attr('autocomplete', 'off');
		$(element).on('focus', $.proxy(this.focus, this));
		$(element).on('blur', $.proxy(this.blur, this));
		$(element).on('keydown', $.proxy(this.keydown, this));

		$(element).after('<ul class="dropdown-menu template scrollable-menu" role="menu"></ul>');
		$(element).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));
	}

	Autofill.prototype = {
		focus: function() {
			this.request();
			 
		},
		blur: function() {
			setTimeout(function(object) {
				object.hide();
			}, 200, this);
		},
		click: function(event) {
			event.preventDefault();

			value = $(event.target).parent().attr('data-value');

			if (value && this.items[value]) {
				this.options.select(this.items[value]);
			}
			this.hide();
			
		},
		keydown: function(event) {
 
			switch(event.keyCode) {
				case 27: // escape
					this.hide();
					break;
				case 188: // comma
					break;
				default:
					this.request();
					break;
			}
		},
		show: function() {
 
			var pos = $(this.element).position();

			$(this.element).siblings('ul.dropdown-menu').css({
				top: pos.top + $(this.element).outerHeight(),
				left: pos.left
			});

			$(this.element).siblings('ul.dropdown-menu').show();
		},
		hide: function() {
 
			$(this.element).siblings('ul.dropdown-menu').hide();
		},
		request: function() {
 
			clearTimeout(this.timer);

			this.timer = setTimeout(function(object) {
				object.options.source($(object.element).val(), $.proxy(object.response, object));
			}, 200, this);
		},
		response: function(json) {
	 
			html = '';
			if ( json.length ) {
				for (i = 0; i < json.length; i++) {
					this.items[json[i]['value']] = json[i];
				}

				for (i = 0; i < json.length; i++) {
					if (!json[i]['category']) {	
						var content = json[i]['label'].replace(new RegExp(this.element.value, "gi"), '<strong>$&</strong>');	
						html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + content + '</a></li>';
					}
				}

				// Get all the ones with a categories
				var category = new Array();

				for (i = 0; i < json.length; i++) {
					if (json[i]['category']) {
						if (!category[json[i]['category']]) {
							category[json[i]['category']] = new Array();
							category[json[i]['category']]['name'] = json[i]['category'];
							category[json[i]['category']]['item'] = new Array();
						}

						category[json[i]['category']]['item'].push(json[i]);
					}
				}

				for (i in category) {
					html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';

					for (j = 0; j < category[i]['item'].length; j++) {
						html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
					}
				}
				 
			}

			if (html) {
				this.show();
			} else {
				this.hide();
			}

			$(this.element).siblings('ul.dropdown-menu').html(html);
		}
	};

	$.fn.autofill = function(option) {
		return this.each(function() {
			var data = $(this).data('autofill');

			if (!data) {
				data = new  Autofill(this, option);

				$(this).data('autofill', data);
			}
		});
	}
})(window.jQuery);  

(function($, undefined) {
    var count = 0;
    var splitter_id = null;
    var splitters = [];
    var current_splitter = null;
    $.fn.split = function(options) {
        var data = this.data('splitter');
        if (data) {
            return data;
        }
        var panel_1;
        var panel_2;
        var settings = $.extend({
            limit: 100,
            orientation: 'horizontal',
            position: '50%',
            invisible: false,
            onDragStart: $.noop,
            onDragEnd: $.noop,
            onDrag: $.noop
        }, options || {});
        this.settings = settings;
        var cls;
        var children = this.children();
        if (settings.orientation == 'vertical') {
            panel_1 = children.first().addClass('left_panel');
            panel_2 = panel_1.next().addClass('right_panel');
            cls = 'vsplitter';
        } else if (settings.orientation == 'horizontal') {
            panel_1 = children.first().addClass('top_panel')
            panel_2 = panel_1.next().addClass('bottom_panel');
            cls = 'hsplitter';
        }
        if (settings.invisible) {
            cls += ' splitter-invisible';
        }
        var width = this.width();
        var height = this.height();
        var id = count++;
        this.addClass('splitter_panel');
        var splitter = $('<div/>').addClass(cls).bind('mouseenter touchstart', function() {
            splitter_id = id;
        }).bind('mouseleave touchend', function() {
            splitter_id = null;
        }).insertAfter(panel_1);
        var position;

        function get_position(position) {
            if (typeof position === 'number') {
                return position;
            } else if (typeof position === 'string') {
                var match = position.match(/^([0-9]+)(px|%)$/);
                if (match) {
                    if (match[2] == 'px') {
                        return +match[1];
                    } else {
                        if (settings.orientation == 'vertical') {
                            return (width * +match[1]) / 100;
                        } else if (settings.orientation == 'horizontal') {
                            return (height * +match[1]) / 100;
                        }
                    }
                } else {
                    //throw position + ' is invalid value';
                }
            } else {
                //throw 'position have invalid type';
            }
        }

        var self = $.extend(this, {
            refresh: function() {
                var new_width = this.width();
                var new_height = this.height();
                if (width != new_width || height != new_height) {
                    width = this.width();
                    height = this.height();
                    self.position(position);
                }
            },
            position: (function() {
                if (settings.orientation == 'vertical') {
                    return function(n, silent) {
                        if (n === undefined) {
                            return position;
                        } else {
                            position = get_position(n);
                            var sw = splitter.width();
                            var sw2 = sw/2;
                            if (settings.invisible) {
                                var pw = panel_1.width(position).outerWidth();
                                panel_2.width(self.width()-pw);
                                splitter.css('left', pw-sw2);
                            } else {
                                var pw = panel_1.width(position-sw2).outerWidth();
                                panel_2.width(self.width()-pw-sw);
                                splitter.css('left', pw);
                            }
                        }
                        if (!silent) {
                            self.find('.splitter_panel').trigger('splitter.resize');
                        }
                        return self;
                    };
                } else if (settings.orientation == 'horizontal') {
                    return function(n, silent) {
                        if (n === undefined) {
                            return position;
                        } else {
                            position = get_position(n);
                            var sw = splitter.height();
                            var sw2 = sw/2;
                            if (settings.invisible) {
                                var pw = panel_1.height(position).outerHeight();
                                panel_2.height(self.height()-pw);
                                splitter.css('top', pw-sw2);
                            } else {
                                var pw = panel_1.height(position-sw2).outerHeight();
                                panel_2.height(self.height()-pw-sw);
                                splitter.css('top', pw);
                            }
                        }
                        if (!silent) {
                            self.find('.splitter_panel').trigger('splitter.resize');
                        }
                        return self;
                    };
                } else {
                    return $.noop;
                }
            })(),
            orientation: settings.orientation,
            limit: settings.limit,
            isActive: function() {
                return splitter_id === id;
            },
            destroy: function() {
                self.removeClass('splitter_panel');
                splitter.unbind('mouseenter');
                splitter.unbind('mouseleave');
                splitter.unbind('touchstart');
                splitter.unbind('touchmove');
                splitter.unbind('touchend');
                splitter.unbind('touchleave');
                splitter.unbind('touchcancel');
                if (settings.orientation == 'vertical') {
                    panel_1.removeClass('left_panel');
                    panel_2.removeClass('right_panel');
                } else if (settings.orientation == 'horizontal') {
                    panel_1.removeClass('top_panel');
                    panel_2.removeClass('bottom_panel');
                }
                self.unbind('splitter.resize');
                self.find('.splitter_panel').trigger('splitter.resize');
                splitters[id] = null;
                splitter.remove();
                var not_null = false;
                for (var i=splitters.length; i--;) {
                    if (splitters[i] !== null) {
                        not_null = true;
                        break;
                    }
                }
                //remove document events when no splitters
                if (!not_null) {
                    $(document.documentElement).unbind('.splitter');
                    $(window).unbind('resize.splitter');
                    self.data('splitter', null);
                    splitters = [];
                    count = 0;
                }
            }
        });
        self.bind('splitter.resize', function(e) {
            var pos = self.position();
            if (self.orientation == 'vertical' &&
                pos > self.width()) {
                pos = self.width() - self.limit-1;
            } else if (self.orientation == 'horizontal' &&
                       pos > self.height()) {
                pos = self.height() - self.limit-1;
            }
            if (pos < self.limit) {
                pos = self.limit + 1;
            }
            self.position(pos, true);
        });
        //inital position of splitter
        var pos;
        if (settings.orientation == 'vertical') {
            if (pos > width-settings.limit) {
                pos = width-settings.limit;
            } else {
                pos = get_position(settings.position);
            }
        } else if (settings.orientation == 'horizontal') {
            //position = height/2;
            if (pos > height-settings.limit) {
                pos = height-settings.limit;
            } else {
                pos = get_position(settings.position);
            }
        }
        if (pos < settings.limit) {
            pos = settings.limit;
        }
        self.position(pos, true);
        if (splitters.length == 0) { // first time bind events to document
            $(window).bind('resize.splitter', function() {
                $.each(splitters, function(i, splitter) {
                    splitter.refresh();
                });
            });
            $(document.documentElement).bind('mousedown.splitter touchstart.splitter', function(e) {
                if (splitter_id !== null) {
                    current_splitter = splitters[splitter_id];
                    $('<div class="splitterMask"></div>').css('cursor', splitter.css('cursor')).insertAfter(current_splitter);
                    current_splitter.settings.onDragStart(e);
                    return false;
                }
            }).bind('mouseup.splitter touchend.splitter touchleave.splitter touchcancel.splitter', function(e) {
                if (current_splitter) {
                    $('.splitterMask').remove();
                    current_splitter.settings.onDragEnd(e);
                    current_splitter = null;
                }
            }).bind('mousemove.splitter touchmove.splitter', function(e) {
                if (current_splitter !== null) {
                    var limit = current_splitter.limit;
                    var offset = current_splitter.offset();
                    if (current_splitter.orientation == 'vertical') {
                        var pageX = e.pageX;
                        if(e.originalEvent && e.originalEvent.changedTouches){
                          pageX = e.originalEvent.changedTouches[0].pageX;
                        }
                        var x = pageX - offset.left;
                        if (x <= current_splitter.limit) {
                            x = current_splitter.limit + 1;
                        } else if (x >= current_splitter.width() - limit) {
                            x = current_splitter.width() - limit - 1;
                        }
                        if (x > current_splitter.limit &&
                            x < current_splitter.width()-limit) {
                            current_splitter.position(x, true);
                            current_splitter.find('.splitter_panel').
                                trigger('splitter.resize');
                            e.preventDefault();
                        }
                    } else if (current_splitter.orientation == 'horizontal') {
                        var pageY = e.pageY;
                        if(e.originalEvent && e.originalEvent.changedTouches){
                          pageY = e.originalEvent.changedTouches[0].pageY;
                        }
                        var y = pageY-offset.top;
                        if (y <= current_splitter.limit) {
                            y = current_splitter.limit + 1;
                        } else if (y >= current_splitter.height() - limit) {
                            y = current_splitter.height() - limit - 1;
                        }
                        if (y > current_splitter.limit &&
                            y < current_splitter.height()-limit) {
                            current_splitter.position(y, true);
                            current_splitter.find('.splitter_panel').
                                trigger('splitter.resize');
                            e.preventDefault();
                        }
                    }
                    current_splitter.settings.onDrag(e);
                }
            });
        }
        splitters.push(self);
        self.data('splitter', self);
        return self;
    };
})(jQuery);


function getData(b){var c={},d=/^data\-(.+)$/;$.each(b.get(0).attributes,function(b,a){if(d.test(a.nodeName)){var e=a.nodeName.match(d)[1];c[e]=a.nodeValue}});return c};
 
function get_alias( mod, id ) {
	var title = strip_tags(document.getElementById('input-title').value);
	if (title != '') {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=alias&nocache=' + new Date().getTime(), 'title=' + encodeURIComponent(title) + '&mod=' + mod + '&id=' + id, function(res) {
			if (res != "") {
				document.getElementById('input-alias').value = res;
			} else {
				document.getElementById('input-alias').value = '';
			}
		});
	}
	return false;
}
$.fn.centerDiv  = function() {
 
    this.css({
        'position': 'absolute',
        'left': '50%',
        'top': '50%'
    });
    this.css({
        'margin-left': -this.outerWidth() / 2 + 'px',
        'margin-top':  -this.outerHeight() / 2 + 'px'
    });

    return this;
}
$.fn.center  = function() {
 
    this.css({
        'position': 'absolute',
        'left': '50%',
        'top': '50%'
    });
    this.css({
        'margin-left': -this.outerWidth() / 2 + 'px',
        'margin-top': -( $(window).height() / 2 + 100 ) + 'px'
    });

    return this;
}

function createEditor(element) {
	CKEDITOR.replace( element, {
		width: '100%',
		height: '100px',
		toolbarGroups:[
			{ name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
			{ name: 'forms', groups: [ 'forms' ] },
			{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
			{ name: 'links', groups: [ 'links' ] },
			{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'paragraph' ] },
			{ name: 'insert', groups: [ 'insert' ] },
			{ name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
			{ name: 'styles', groups: [ 'styles' ] },
			{ name: 'colors', groups: [ 'colors' ] },
			{ name: 'tools', groups: [ 'tools' ] },
			{ name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
			{ name: 'others', groups: [ 'others' ] },
			{ name: 'about', groups: [ 'about' ] }
		],
		removePlugins: 'autosave,gg,switchbar',
		removeButtons: 'Templates,Googledocs,Sourse,NewPage,Preview,Print,Save,Find,Replace,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Blockquote,Flash,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,Maximize,About,Anchor,BidiRtl,CreateDiv,Indent,BulletedList,NumberedList,Outdent,ShowBlocks,Youtube,Video' 
			
	});
	CKEDITOR.add;
	
	
}

function isNumberOnly(a, c) {
    var b = a.which ? a.which : a.keyCode;
    return 47 < b && 58 > b ? !0 : !1
};
function isNumber(a,c){var b=a.which?a.which:a.keyCode;return 47<b&&58>b||46==b&&-1==$(c).val().indexOf('.')?!0:!1};
 
$('.number').on('keypress', function(e){
	return isNumber(e, this);
});
$('.numberonly').on('keypress', function(e){
	return isNumberOnly(e, this);
});
 

$('.pricenumber').keyup(function(event) {

  // skip for arrow keys
  if(event.which >= 58 && event.which <= 47) return;

  // format number
  $(this).val(function(index, value) {
    return value
    .replace(/\D/g, "")
    .replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    ;
  });
});


$(document).delegate('a[data-toggle=\'image\']', 'click', function(e) {
	e.preventDefault();	
	var element = this;
		var rel = $(this).attr('rel');	
		$(element).popover({
			html: true,
			placement: 'right',
			trigger: 'manual',
			content: function() {
				return '<button type="button" onclick="getImage( \''+ rel +'\' )" class="btn btn-primary "><i class="fa fa-pencil rmbutton" id="button-close"></i></button> <button type="button" id="button-clear" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>';
			}
		});
	
		$(element).popover('toggle');		
 
		$('#button-close').on('click', function() {
			$(element).popover('hide');
		});
		$('#button-clear').on('click', function() {
			$(element).find('img').attr('src', $(element).find('img').attr('data-placeholder'));
			
			$(element).parent().find('input').attr('value', '');
			$(element).parent().find('input').attr('data-old', '');
	
			$(element).popover('hide');
		});
		
});
$(document).ready(function() {
	
	$('body').on('click', 'a.analyzes', function(e){
		var question_id = $(this).attr('data-question_id');
		var token = $(this).attr('data-token');
		var obj = $(this);
		
		if( $('#analyzesList-' + question_id).hasClass('show') ) 
		{
			$('#analyzesList-' + question_id).removeClass('show').addClass('hide');
			
		}else  
		{
			$('#analyzesList-' + question_id).addClass('show').removeClass('hide');
		}
 
		e.preventDefault() ;
 
	})
	
	$('body').on('click', 'a.comment', function(e){
		var question_id = $(this).attr('data-question_id');
		var token = $(this).attr('data-token');
		var obj = $(this);
		
		if( obj.hasClass('disabled') && $('#commentList-' + question_id).hasClass('hide')) 
		{
			return false;
		}else if( $('#commentList-' + question_id).hasClass('show') ) 
		{
			$('#commentList-' + question_id).removeClass('show').addClass('hide');
			return false;
		}else if( $('#commentList-' + question_id).hasClass('isload')  && $('#commentList-' + question_id).hasClass('hide') ) 
		{
			$('#commentList-' + question_id).addClass('show').removeClass('hide');
			return false;
		}

		$.ajax({
			url: nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=comment&nocache=' + new Date().getTime(),
			type: 'post',
			dataType: 'json',
			data: { action:'getComment', question_id : question_id, token : token } ,
			beforeSend: function() {
				obj.find('i').show();
				obj.prop('disabled', true).addClass('disabled');

			},
			complete: function() {
				obj.find('i').hide();
				setTimeout(function() {
					obj.prop('disabled', false).removeClass('disabled');
				}, 5000);
			},
			success: function(json) {
				
				if( json['comment'] )
				{
					$('#commentList-' + question_id ).append( json['comment'] ).removeClass('hide').addClass('show isload');
					createEditor('comment-'+question_id); 
					 
				}else if( json['error'] )
				{
					alert( json['error'] );
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				obj.prop('disabled', false);
			}
		});
		e.preventDefault() ;
		
		
		
	})
		$(document).on('click', '.insertComment', function(e){
		
		var obj = $(this).parent().parent();
		var id = obj.attr('id');
		var question_id = obj.attr('data-id');
		var comment =  CKEDITOR.instances['comment-'+question_id+''].getData();  
		if( strip_tags( comment, '<img>' ).length < 10 )
		{
			alert('Nội dung bình luận quá ngắn');
			return false;
		}
		$.ajax({
			url: nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=comment&nocache=' + new Date().getTime(),
			type: 'post',
			dataType: 'json',
			data :{
				action: 'insertComment',
				comment_id: $('#' + id + ' input[name="comment_id"]').val(),
				question_id: $('#' + id + ' input[name="question_id"]').val(),
				token: $('#' + id + ' input[name="token"]').val(),
				lastcomment: $('#' + id + ' input[name="lastcomment"]').val(),
				comment: comment 	
			},
			beforeSend: function() {
				obj.find('i').show();
				obj.find('.insertComment').addClass('disabled');

			},
			complete: function() {
				obj.find('i').hide();
				setTimeout(function() {
					obj.find('.insertComment').removeClass('disabled');
				}, 2000);
			},
			success: function(json) {
				
				if( json['comment'] )
				{
					
					$('#commentList-' + question_id).find('ul.comment-list').append( json['comment'] ); 
					$('#insertComment-' + question_id).find('input[name="lastcomment"]').val( json['lastcomment'] ); 
					if( json['total_comment'] )
					{ 
						var getcomment = $('#getcomment-' + question_id ).text();
						getcomment = intval( getcomment ) + intval(json['total_comment']);
						$('#getcomment-' + question_id ).html( getcomment );
					}
					CKEDITOR.instances['comment-'+question_id+''].setData(''); 
					 
				}else if( json['update'] )
				{
					$('#group-' + json['comment_id'] ).find('.contentComment').html( json['update'] );
					$('#' + id + ' input[name="comment_id"]').val(0),
					CKEDITOR.instances['comment-'+question_id+''].setData(''); 
				}
				else if( json['error'] )
				{
					alert( json['error'] );
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				obj.find('.insertComment').removeClass('disabled');
			}
		});
		e.preventDefault() ;
		
		
		
	})

	$('body').on('click', 'a.loadMoreComment', function(e){
		var question_id = $(this).attr('data-question_id');
		var token = $(this).attr('data-token');
		var page = $(this).attr('data-page');
		var obj = $(this);
	 
		$.ajax({
			url: nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=comment&nocache=' + new Date().getTime(),
			type: 'post',
			dataType: 'json',
			data: { action : 'getOnlyComment', question_id : question_id, page : page, token : token } ,
			beforeSend: function() {
				obj.find('i').show();
				obj.prop('disabled', true).addClass('disabled');

			},
			complete: function() {
				obj.find('i').hide();
				setTimeout(function() {
					obj.prop('disabled', false).removeClass('disabled');
				}, 2000);
			},
			success: function(json) {
				if( json['comment'] )
				{
					$('#commentList-' + question_id ).find('.comment-list').append( json['comment'] );
					if( json['page'] )
					{
						obj.attr('data-page', json['page']);
					}
					if( json['loadMore'] == 0 )
					{
						$('#commentList-' + question_id).find('.loadmore').remove();
					}				
				}
				if( json['total_comment'] )
				{
					$('#getcomment-' + question_id ).html( json['total_comment'] );
				}
				if( json['error'] )
				{
					alert( json['error'] );
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				obj.prop('disabled', false);
			}
		});
		e.preventDefault() ;
		
		
		
	})

	$('body').on('click', 'a.canEdit', function(e){
		var question_id  = $(this).attr('data-question_id');
		var comment_id = $(this).attr('data-comment_id');
		var token = $(this).attr('data-token');
		var obj = $(this);
	 
		$.ajax({
			url: nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=comment&nocache=' + new Date().getTime(),
			type: 'post',
			dataType: 'json',
			data: { action : 'canEdit', comment_id : comment_id, question_id : question_id, token : token },
			beforeSend: function() {
				obj.find('i').replaceWith('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>');
				obj.prop('disabled', true).addClass('disabled');

			},
			complete: function() {
				obj.find('i').replaceWith('<i class="fa fa-edit fa-1x fa-fw"></i>');
				setTimeout(function() {
					obj.prop('disabled', false).removeClass('disabled');
				}, 2000);
			},
			success: function(json) {
				if( json['comment'] )
				{
					CKEDITOR.instances['comment-'+question_id+''].setData( json['comment'] );   
					$('#insertComment-'+question_id+'').find('input[name="comment_id"]').val( comment_id );
				}
				 
				if( json['error'] )
				{
					alert( json['error'] );
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				obj.find('i').replaceWith('<i class="fa fa-edit fa-1x fa-fw"></i>');
				obj.prop('disabled', false);
			}
		});
		e.preventDefault() ;
		
		
		
	})
	
	$('body').on('click', 'a.canDelete', function(e){
		var question_id  = $(this).attr('data-question_id');
		var comment_id = $(this).attr('data-comment_id');
		var token = $(this).attr('data-token');
		var obj = $(this);
		if( confirm('Bạn có chắc chắn xóa bình luận này không ?') )
		{
		
			$.ajax({
				url: nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=comment&nocache=' + new Date().getTime(),
				type: 'post',
				dataType: 'json',
				data: { action : 'canDelete', comment_id : comment_id, question_id : question_id, token : token },
				beforeSend: function() {
					obj.find('i').replaceWith('<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>');
					obj.prop('disabled', true).addClass('disabled');

				},
				complete: function() {
					obj.find('i').replaceWith('<i class="fa fa-trash fa-1x fa-fw"></i>');
					setTimeout(function() {
						obj.prop('disabled', false).removeClass('disabled');
					}, 2000);
				},
				success: function(json) {
					if( json['success'] )
					{			 
						$('#group-'+json['comment_id']+'').remove();
						$('#insertComment-' + question_id + ' input[name="comment_id"]').val(0);
					}		 
					else if( json['error'] )
					{
						alert( json['error'] );
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					obj.find('i').replaceWith('<i class="fa fa-trash fa-1x fa-fw"></i>');
					obj.prop('disabled', false);
				}
			});
		}
		e.preventDefault() ;
	 
	})

	$(document).on('click', '.insertComment', function(e){
		
		var obj = $(this).parent().parent();
		var id = obj.attr('id');
		var question_id = obj.attr('data-id');
		var comment =  CKEDITOR.instances['comment-'+question_id+''].getData();  
		if( strip_tags( comment, '<img>' ).length < 10 )
		{
			alert('Nội dung bình luận quá ngắn');
			return false;
		}
		$.ajax({
			url: nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=comment&nocache=' + new Date().getTime(),
			type: 'post',
			dataType: 'json',
			data :{
				action: 'insertComment',
				comment_id: $('#' + id + ' input[name="comment_id"]').val(),
				question_id: $('#' + id + ' input[name="question_id"]').val(),
				token: $('#' + id + ' input[name="token"]').val(),
				lastcomment: $('#' + id + ' input[name="lastcomment"]').val(),
				comment: comment 	
			},
			beforeSend: function() {
				obj.find('i').show();
				obj.find('.insertComment').addClass('disabled');

			},
			complete: function() {
				obj.find('i').hide();
				setTimeout(function() {
					obj.find('.insertComment').removeClass('disabled');
				}, 2000);
			},
			success: function(json) {
				
				if( json['comment'] )
				{
					
					$('#commentList-' + question_id).find('ul.comment-list').append( json['comment'] ); 
					$('#insertComment-' + question_id).find('input[name="lastcomment"]').val( json['lastcomment'] ); 
					if( json['total_comment'] )
					{ 
						var getcomment = $('#getcomment-' + question_id ).text();
						getcomment = intval( getcomment ) + intval(json['total_comment']);
						$('#getcomment-' + question_id ).html( getcomment );
					}
					CKEDITOR.instances['comment-'+question_id+''].setData(''); 
					 
				}else if( json['update'] )
				{
					$('#group-' + json['comment_id'] ).find('.contentComment').html( json['update'] );
					$('#' + id + ' input[name="comment_id"]').val(0),
					CKEDITOR.instances['comment-'+question_id+''].setData(''); 
				}
				else if( json['error'] )
				{
					alert( json['error'] );
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				obj.find('.insertComment').removeClass('disabled');
			}
		});
		e.preventDefault() ;
		
		
		
	})

	$('body').on('click', 'a.loadMoreComment', function(e){
		var question_id = $(this).attr('data-question_id');
		var token = $(this).attr('data-token');
		var page = $(this).attr('data-page');
		var obj = $(this);
	 
		$.ajax({
			url: nv_base_siteurl + 'index.php?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=comment&nocache=' + new Date().getTime(),
			type: 'post',
			dataType: 'json',
			data: { action : 'getOnlyComment', question_id : question_id, page : page, token : token } ,
			beforeSend: function() {
				obj.find('i').show();
				obj.prop('disabled', true).addClass('disabled');

			},
			complete: function() {
				obj.find('i').hide();
				setTimeout(function() {
					obj.prop('disabled', false).removeClass('disabled');
				}, 2000);
			},
			success: function(json) {
				if( json['comment'] )
				{
					$('#commentList-' + question_id ).find('.comment-list').append( json['comment'] );
					if( json['page'] )
					{
						obj.attr('data-page', json['page']);
					}
					if( json['loadMore'] == 0 )
					{
						$('#commentList-' + question_id).find('.loadmore').remove();
					}				
				}
				if( json['total_comment'] )
				{
					$('#getcomment-' + question_id ).html( json['total_comment'] );
				}
				if( json['error'] )
				{
					alert( json['error'] );
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				obj.prop('disabled', false);
			}
		});
		e.preventDefault() ;
		
		
		
	})

	
	$('[data-toggle=\'tooltip\']').tooltip({container: 'body', html: true});
 
	$('button[type=\'submit\']').on('click', function() {
		$("form[id*='form-']").submit();
	});
 
	$('.text-danger').each(function() {
		var element = $(this).parent().parent();
		
		if (element.hasClass('form-group')) {
			element.addClass('has-error');
		}
	});
	
	$('body').on('click', '.alert i.fa-times', function(){
		$(this).parent().slideUp( "slow", function() {
			$(this).remove();
		}); 
	})
});
 