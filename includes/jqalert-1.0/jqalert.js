(function( $ ){

	var methods = {
		init : function( options ) {

			var settings = {
				'message'      : 'no message',
				'icon'         : '',
				'title'        : '',
				'warning'      : false,
				'error'        : false,
				'info'         : false,
				'help'         : false,
				'width'        : 0,
				'success'      : null,
				'failure'      : null
			};

					
			// if user clicked on button, the overlay layer or the dialogbox, close the dialog  
			$('a.btn-ok, #dialog-overlay, #dialog-box').click(function () {     
				$('#dialog-overlay, #dialog-box').hide();       
				return false;
			});

		
			// if user resize the window, call the same function again
			// to make sure the overlay fills the screen and dialogbox aligned to center    
			$(window).resize(function () {
				//only do it if the dialog box is not hidden
				if( ! $('#dialog-box').is(':hidden') ) {
					$(this).jqalert("resize");
				}
			}); 			

			return this.each(function() {        
				// If options exist, lets merge them
				// with our default settings
				if( options ) { 
					$.extend( settings, options );
				}		

				if( $("#jqalert-dialog-overlay").length == 0 ) {
					$("body").append( $("<div></div>").attr("id","jqalert-dialog-overlay").css("display","none") );
				}
				if( $("#jqalert-dialog-box").length == 0 ) {
					$("body").append(
							$("<div></div>").attr("id","jqalert-dialog-box").css("display","none").append(
								$("<div></div>").attr("id","jqalert-dialog-box-content").append(
									$("<div></div>").attr("id","jqalert-dialog-box-message")
								)
							)
						);
				}
				
				$(this).click( function() {

					// display the message
					var xhtml = '';
					
					var buttonClose = null;
					var buttonYes = null;
					var buttonNo = null;

					if( settings.title != null && settings.title != "" ) {
						xhtml += "<h2>"+settings.title+"</h2>";
					}
					xhtml += settings.message;
					
					if( settings.success != null || settings.failure != null ) {
						buttonYes = $("<a/>").attr("href","javascript:;")
									.removeClass()
									.addClass("button")
									.addClass("yes")
									.html("Yes")
									.css("float","left")
									.click( settings.success != null ? function() { settings.success(); $(this).jqalert("close"); } : function() { $(this).jqalert("close"); } );
						buttonNo = $("<a/>").attr("href","javascript:;")
									.removeClass()
									.addClass("button")
									.addClass("no")
									.css("float","left")
									.html("No")
									.click( settings.failure != null ? function() { settings.failure(); $(this).jqalert("close"); } : function() { $(this).jqalert("close"); } );
					}
					else {
						buttonClose = $("<a/>").attr("href","javascript:;")
									.removeClass()
									.addClass("button")
									.addClass("close")
									.html("Close")
									.click( function() { $(this).jqalert("close"); } );
					}
					
					if( settings.width > 0 ) {
						$('#jqalert-dialog-box').width( settings.width );
					}
					
					$('#jqalert-dialog-box-message').removeClass().html(xhtml).append($("<div/>").css("clear","both")).append( buttonClose ).append( buttonYes ).append( buttonNo ).append($("<div/>").css("clear","both"));

					if( settings.icon != null && settings.icon != "") {
						$('#jqalert-dialog-box-message').prepend( $("<img/>").attr("src",settings.icon).css("float","left") );
					}
					else if( settings.error ) {
						$('#jqalert-dialog-box-message').addClass("error");
					}
					else if( settings.warning ) {
						$('#jqalert-dialog-box-message').addClass("warning");
					}
					else if( settings.info ) {
						$('#jqalert-dialog-box-message').addClass("info");
					}
					else if( settings.help ) {
						$('#jqalert-dialog-box-message').addClass("help");
					}
					
					$(this).jqalert("resize");
	
					$('#jqalert-dialog-overlay').show();
					$('#jqalert-dialog-box').show();

				} );
				
			} );
		},
		close: function( options ) {
			$('#jqalert-dialog-overlay, #jqalert-dialog-box').hide(); 
		},
		resize: function( options ) {
			var settings = {};
			
			return this.each(function() {        
				// If options exist, lets merge them
				// with our default settings
				if( options ) { 
					$.extend( settings, options );
				}

				// get the screen height and width  
				var maskHeight = $(document).height();  
				var maskWidth = $(window).width();

				// calculate the values for center alignment
				var dialogTop =  (maskHeight/2) - ($('#jqalert-dialog-box').height());  
				var dialogLeft = (maskWidth/2) - ($('#jqalert-dialog-box').width()/2); 

				// assign values to the overlay and dialog box
				$('#jqalert-dialog-overlay').css({height:maskHeight, width:maskWidth});
				$('#jqalert-dialog-box').css("top", ( $(window).height() - $('#jqalert-dialog-box').height() ) / 2+$(window).scrollTop() + "px");
				$('#jqalert-dialog-box').css("left", ( $(window).width() - $('#jqalert-dialog-box').width() ) / 2+$(window).scrollLeft() + "px");
				
			} );
		}
	};
	
	$.fn.jqalert = function( method ) {
		if( methods[method] ) {
			return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
		}
		else if( typeof method === 'object' || ! method ) {
			return methods.init.apply( this, arguments );
		}
		else {
			$.error( 'Method ' +  method + ' does not exist on jQuery.jqalert' );
		}
	};
})( jQuery );