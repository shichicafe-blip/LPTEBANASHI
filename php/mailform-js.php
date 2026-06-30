
/*--------------------------------------------------------------------------
	
	Script Name    : Responsive Mailform
	Author         : FIRSTSTEP - Motohiro Tani
	Author URL     : https://www.1-firststep.com
	Create Date    : 2014/03/25
	Version        : 9.0
	Last Update    : 2025/11/14
	
--------------------------------------------------------------------------*/


(function( $ ) {
	
	// global variable init
	var mailform_dt    = $( 'form#mail_form dl dt' );
	var confirm_window = 0;
	var rm_token       = '';
	var scroll_amount  = 70;
	var required_text  = '必須';
	var optional_text  = '任意';
	
	
	
	
	// function resize
	function resize() {
		
		$( '.loading-layer' ).css({
			'width': $( window ).width() + 'px',
			'height': window.innerHeight + 'px'
		});
		
	}
	
	
	
	
	// function slice_method
	function slice_method( el ) {
		
		var dt      = el.parents( 'dd' ).prev( 'dt' );
		var dt_name = dt.html().replace( /<i.*<\/i>/gi, '' );
		dt_name     = dt_name.replace( /<span.*<\/span>/gi, '' );
		dt_name     = dt_name.replace( /<br>|<br \/>/gi, '' );
		return dt_name;
		
	}
	
	
	
	
	// function error_span
	function error_span( e, dt, comment, bool ) {
		
		if ( bool === true ) {
			var m = e.parents( 'dd' ).find( 'span.error_blank' ).text( dt + 'が' + comment + 'されていません' );
		} else {
			var m = e.parents( 'dd' ).find( 'span.error_blank' ).text( '' );
		}
		
	}
	
	
	
	
	// function compare_method
	function compare_method( s, e ) {
		
		if ( s > e ) {
			return e;
		} else {
			return s;
		}
		
	}
	
	
	
	
	// function hidden_append
	function hidden_append( name, value ) {
		
		$( '<input />' )
			.attr({
				type: 'hidden',
				id: name,
				name: name,
				value: value
			})
			.appendTo( $( 'p#form_submit' ) );
		
	}
	
	
	
	
	// function token_get
	function token_get() {
		
		var form = $( 'form#mail_form' );
		
		
		if ( form.length > 0 ) {
			$.ajax({
				type: form.attr( 'method' ),
				url: form.attr( 'action' ),
				cache: false,
				dataType: 'text',
				data: 'token_get=true&javascript_action=true',
				
				success: function( res ) {
					var response = res.split( ',' );
					if ( response[0] === 'token_success' ) {
						rm_token = response[1];
						setTimeout(function() {
							token_get();
						}, 900000 );
					} else {
						window.alert( 'トークンの取得に失敗しました。' );
					}
				},
				
				error: function( res ) {
					window.alert( 'Ajax通信が失敗しました。\nページの再読み込みをしてからもう一度お試しください。' );
				}
			});
		}
		
	}
	
	
	
	
	// function required_check
	function required_check() {
		
		var error        = 0;
		var scroll_point = $( 'body' ).height();
		
		
		for ( var i = 0; i < mailform_dt.length; i++ ) {
			if ( mailform_dt.eq(i).next( 'dd' ).find( 'input' ).length && mailform_dt.eq(i).next( 'dd' ).hasClass( 'required' ) ) {
				
				var elements = mailform_dt.eq(i).next( 'dd' ).find( 'input' );
				var dt_name  = slice_method( elements.eq(0) );
				
				if ( elements.eq(0).attr( 'type' ) === 'radio' || elements.eq(0).attr( 'type' ) === 'checkbox' ) {
					
					var list_error = 0;
					for ( var j = 0; j < elements.length; j++ ) {
						if ( elements.eq(j).prop( 'checked' ) === false ) {
							list_error++;
						}
					}
					
					if ( list_error === elements.length ) {
						error_span( elements.eq(0), dt_name, '選択', true );
						error++;
						scroll_point = compare_method( scroll_point, elements.eq(0).offset().top );
					} else {
						error_span( elements.eq(0), dt_name, '', false );
					}
					
				} else {
					
					var list_error = 0;
					for ( var j = 0; j < elements.length; j++ ) {
						if ( elements.eq(j).val() === '' ) {
							list_error++;
						}
					}
					
					if ( list_error !== 0 ) {
						error_span( elements.eq(0), dt_name, '入力', true );
						error++;
						scroll_point = compare_method( scroll_point, elements.eq(0).offset().top );
					} else {
						error_span( elements.eq(0), dt_name, '', false );
					}
					
				}
			}
			
			
			if ( mailform_dt.eq(i).next( 'dd' ).find( 'select' ).length && mailform_dt.eq(i).next( 'dd' ).hasClass( 'required' ) ) {
				var elements = mailform_dt.eq(i).next( 'dd' ).find( 'select' );
				var dt_name  = slice_method( elements.eq(0) );
				
				var list_error = 0;
				for ( var j = 0; j < elements.length; j++ ) {
					if ( elements.eq(j).val() === '' ) {
						list_error++;
					}
				}
				
				if ( list_error !== 0 ) {
					error_span( elements.eq(0), dt_name, '選択', true );
					error++;
					scroll_point = compare_method( scroll_point, elements.eq(0).offset().top );
				} else {
					error_span( elements.eq(0), dt_name, '', false );
				}
			}
			
			
			if ( mailform_dt.eq(i).next( 'dd' ).find( 'textarea' ).length && mailform_dt.eq(i).next( 'dd' ).hasClass( 'required' ) ) {
				var elements = mailform_dt.eq(i).next( 'dd' ).find( 'textarea' );
				var dt_name  = slice_method( elements.eq(0) );
				if ( elements.eq(0).val() === '' ) {
					error_span( elements.eq(0), dt_name, '入力', true );
					error++;
					scroll_point = compare_method( scroll_point, elements.eq(0).offset().top );
				} else {
					error_span( elements.eq(0), dt_name, '', false );
				}
			}
			
			
			if ( mailform_dt.eq(i).next( 'dd' ).find( 'input' ).length && mailform_dt.eq(i).next( 'dd' ).find( 'input' ).eq(0).attr( 'type' ) === 'email' ) {
				var elements = mailform_dt.eq(i).next( 'dd' ).find( 'input' );
				var dt_name  = slice_method( elements.eq(0) );
				if ( elements.eq(0).val() !== '' && ! ( elements.eq(0).val().match(/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/) ) ) {
					elements.eq(0).parents( 'dd' ).find( 'span.error_format' ).text( '正しいメールアドレスの書式ではありません。' );
					error++;
					scroll_point = compare_method( scroll_point, elements.eq(0).offset().top );
				} else {
					elements.eq(0).parents( 'dd' ).find( 'span.error_format' ).text( '' );
				}
			}
		}
		
		
		if ( $( 'input[name="mail_address_confirm"]' ).length && $( 'input[name="mail_address"]' ).length ) {
			var element   = $( 'input[name="mail_address_confirm"]' );
			var element_2 = $( 'input[name="mail_address"]' );
			var dt_name   = slice_method( element );
			
			if ( element.val() !== '' && element.val() !== element_2.val() ) {
				element.parents( 'dd' ).find( 'span.error_match' ).text( 'メールアドレスが一致しません。' );
				error++;
				scroll_point = compare_method( scroll_point, element.offset().top );
			} else {
				element.parents( 'dd' ).find( 'span.error_match' ).text( '' );
			}
		}
		
		
		
		
		if ( error === 0 ) {
			
			// ポップアップを出さずにそのまま送信する
			send_setup();
			order_set();
			send_method();
			
		} else {
			
			var default_behavior = $( 'html' ).css( 'scroll-behavior' );
			
			$( 'html' ).css( 'scroll-behavior', 'auto' );
			
			$( 'html, body' ).animate({
				scrollTop: scroll_point - scroll_amount
			}, 500, function() {
				$( 'html' ).css( 'scroll-behavior', default_behavior );
			});
			
		}
		
	}
	
	
	
	
	// function send_setup
	function send_setup() {
		
		hidden_append( 'javascript_action', true );
		hidden_append( 'token', rm_token );
		
		var now_url = encodeURI( document.URL );
		hidden_append( 'now_url', now_url );
		
		var before_url = encodeURI( document.referrer );
		hidden_append( 'before_url', before_url );
		
	}
	
	
	
	
	// function order_set
	function order_set() {
		
		var order_number = 0;
		for ( var i = 0; i < mailform_dt.length; i++ ) {
			
			if ( mailform_dt.eq(i).next( 'dd' ).find( 'input' ).length ) {
				var elements = mailform_dt.eq(i).next( 'dd' ).find( 'input' );
				var dt_name  = slice_method( elements.eq(0) );
				
				if ( elements.eq(0).attr( 'type' ) === 'radio' || elements.eq(0).attr( 'type' ) === 'checkbox' ) {
					
					var attr_name = elements.eq(0).attr( 'name' ).replace( /\[|\]/g, '' );
					var attr_type = elements.eq(0).attr( 'type' );
					order_number++;
					hidden_append( 'order_' + order_number, attr_type + ',' + attr_name + ',false,' + dt_name );
					
				} else {
					
					for ( var j = 0; j < elements.length; j++ ) {
						var attr_name = elements.eq(j).attr( 'name' );
						var attr_type = elements.eq(j).attr( 'type' );
						if ( j === 0 ) {
							var connect = 'false';
						} else {
							var connect = 'true';
						}
						order_number++;
						hidden_append( 'order_' + order_number, attr_type + ',' + attr_name + ',' + connect + ',' + dt_name );
					}
					
				}
			}
			
			
			if ( mailform_dt.eq(i).next( 'dd' ).find( 'select' ).length ) {
				var elements = mailform_dt.eq(i).next( 'dd' ).find( 'select' );
				var dt_name  = slice_method( elements.eq(0) );
				
				for ( var j = 0; j < elements.length; j++ ) {
					var attr_name = elements.eq(j).attr( 'name' );
					var attr_type = 'select';
					if ( j === 0 ) {
						var connect = 'false';
					} else {
						var connect = 'true';
					}
					order_number++;
					hidden_append( 'order_' + order_number, attr_type + ',' + attr_name + ',' + connect + ',' + dt_name );
				}
			}
			
			
			if ( mailform_dt.eq(i).next( 'dd' ).find( 'textarea' ).length ) {
				var elements = mailform_dt.eq(i).next( 'dd' ).find( 'textarea' );
				var dt_name  = slice_method( elements.eq(0) );
				var attr_name = elements.eq(0).attr( 'name' );
				var attr_type = 'textarea';
				order_number++;
				hidden_append( 'order_' + order_number, attr_type + ',' + attr_name + ',false,' + dt_name );
			}
			
		}
		
		
		hidden_append( 'order_count', order_number );
		
	}
	
	
	
	
	// function send_method
	function send_method() {
		
		$( '<div>' )
			.addClass( 'loading-layer' )
			.appendTo( 'body' )
			.append( '<span class="loading"></span>' );
		
		setTimeout(function() {
			
			var form_data = new FormData( $( 'form#mail_form' ).get(0) );
			
			$.ajax({
				type: $( 'form#mail_form' ).attr( 'method' ),
				url: $( 'form#mail_form' ).attr( 'action' ),
				cache: false,
				dataType: 'html',
				data: form_data,
				contentType: false,
				processData: false,
				
				success: function( res ) {
					$( 'div.loading-layer, span.loading' ).remove();
					var response = res.split( ',' );
					if ( response[0] === 'send_success' ) {
						window.location.href = response[1];
					} else {
						$( 'input#form_submit_button' ).nextAll( 'input' ).remove();
						response[1] = response[1].replace( /<br>|<br \/>/gi, "\n" );
						window.alert( response[1] );
						ios_bugfix();
					}
				},
				
				error: function( res ) {
					$( 'div.loading-layer, span.loading' ).remove();
					$( 'input#form_submit_button' ).nextAll( 'input' ).remove();
					window.alert( '通信に失敗しました。\nページの再読み込みをしてからもう一度お試しください。' );
				}
			});
			
		}, 1000 );
		
	}
	
	
	
	
	// function ios_bugfix
	function ios_bugfix() {
		
	}
	
	
	
	
	// function action_add
	function action_add() {
		
		if ( $( '#mailform-js' ).length ) {
			var href = $( '#mailform-js' ).attr( 'src' ).replace( /js\/mailform-js\.php/gi, 'php/mailform.php' );
			$( 'form#mail_form' ).attr( 'action', href );
		}
		
	}
	
	
	
	
	// function brackets_add
	function brackets_add() {
		
		for ( var i = 0; i < mailform_dt.length; i++ ) {
			
			if ( mailform_dt.eq(i).next( 'dd' ).find( 'input' ).length ) {
				var elements = mailform_dt.eq(i).next( 'dd' ).find( 'input' );
				
				if ( elements.eq(0).attr( 'type' ) === 'checkbox' ) {
					
					for ( var j = 0; j < elements.length; j++ ) {
						if ( elements.eq(j).attr( 'name' ).indexOf( '[]' ) === -1 ) {
							var attr_name = elements.eq(j).attr( 'name' );
							elements.eq(j).attr( 'name', attr_name + '[]' );
						}
					}
					
				}
			}
			
		}
		
	}
	
	
	
	
	// page setting
	for ( var i = 0; i < mailform_dt.length; i++ ) {
		if ( mailform_dt.eq(i).find( 'i' ).length ) {
			if ( mailform_dt.eq(i).next( 'dd' ).hasClass( 'required' ) ) {
				$( '<span/>' )
					.text( required_text )
					.addClass( 'required' )
					.prependTo( mailform_dt.eq(i).find( 'i' ) );
			} else {
				$( '<span/>' )
					.text( optional_text )
					.addClass( 'optional' )
					.prependTo( mailform_dt.eq(i).find( 'i' ) );
			}
		}
		
		$( '<span/>' )
			.addClass( 'error_blank' )
			.appendTo( mailform_dt.eq(i).next( 'dd' ) );
		
		if ( mailform_dt.eq(i).next( 'dd' ).find( 'input' ).length && mailform_dt.eq(i).next( 'dd' ).find( 'input' ).eq(0).attr( 'type' ) === 'email' ) {
			$( '<span/>' )
				.addClass( 'error_format' )
				.appendTo( mailform_dt.eq(i).next( 'dd' ) );
		}
	}
	
	
	if ( $( 'input[name="mail_address_confirm"]' ).length ) {
		$( '<span/>' )
			.addClass( 'error_match' )
			.appendTo( $( 'input[name="mail_address_confirm"]' ).parents( 'dd' ) );
	}
	
	
	$( 'form#mail_form input' ).on( 'keydown', function( e ) {
		if ( ( e.which && e.which === 13 ) || ( e.keyCode && e.keyCode === 13 ) ) {
			return false;
		} else {
			return true;
		}
	});
	
	
	$( window ).on( 'resize', resize );
	
	action_add();
	
	brackets_add();
	
	
	// token_get(); // 公開（静的配信）コピーでは無効化: バックエンド未配置のため、読み込み時の通信失敗アラートを防止


	$( 'input#form_submit_button' ).on( 'click', required_check );
	
})( jQuery );