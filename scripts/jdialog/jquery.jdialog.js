/* Copyright (c) 2008 Kean Loong Tan http://www.gimiti.com/kltan
 * Licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * Version: 1.1 (March 26, 2008)
 * Requires: jQuery 1.2+
 */
 
(function($) {

	var dialogDisplayed = false;
	var currentPos = true;

	$.fn.createDialog = function(options) {

		// Extend our default options with those provided.
		var opts = $.extend({}, $.fn.createDialog.defaults, options);
		$(this).click(function(){ 
			currentPos = opts.center;
			if (!dialogDisplayed) { //display dialog if none is there
				$("body").prepend('<div id="jDialogProgressBar"><img src="/pegasus/scripts/jdialog/ajax-loader.gif" /></div><div id="jDialogOverlay"></div><div id="jDialogContainer"></div>');
				overlayPos(1);
				dialogDisplayed=true;
			}
			
			if(opts.progress)
				$("#jDialogProgressBar").show();
				
			$.ajax({
				type: opts.method,
				data: opts.data,
				url: opts.addr,
				success: function(msg){
					$("#jDialogContainer").html(msg);
					if (currentPos)
						reposition();
					$("#jDialogProgressBar").fadeOut(900);
				}
			});
			//only IE6 needs this function
			if($.browser.msie && parseInt($.browser.version) < 7) {
				$(window).scroll(function(){
					if(dialogDisplayed==1) {
						overlayPos();
						if (currentPos)
							reposition();
					}
				});
			}
			$(window).resize(function(){
				if (dialogDisplayed==1) {
					overlayPos();
					if (currentPos)
						reposition();
				}
			});
			
			$(window).unload( function () {
				if (dialogDisplayed==1)
					$.closeDialog();
			});
			
			$(window).keydown(function(event){
				if (event.keyCode == 27) 
					$.closeDialog();
			});

		});
		
		//private function
		function overlayPos(init){
			var left = 0;
			var top = 0;
			var overlayWidth = $(window).width();
			var overlayHeight = $(document).height();
			var winHeight =  $(window).height();
		
			if ($.browser.msie && parseInt($.browser.version) < 7) { //if IE6
				$("#jDialogOverlay").css({
									  top: 0, 
									  left: 0, 
									  width: overlayWidth, 
									  height: overlayHeight, 
									  position: "absolute",
									  display: "block",
									  color: opts.bg,
									  zIndex: opts.index
								  });
			}
			else { //other browsers
				$("#jDialogOverlay").css({
									  top: 0, 
									  left: 0, 
									  width: overlayWidth, 
									  height: winHeight, 
									  position: "fixed",
									  display: "block",
									  background: opts.bg,
									  zIndex: opts.index
								  }).show();
			}
			
			if (init==1) {
				$("#jDialogOverlay").css("opacity", 0);
				$("#jDialogOverlay").fadeTo(200, opts.opacity);
			}
		}
		
		//private function
		function reposition(){ //calculate the position
			var left = 0;
			var top = 0;
			var winWidth = $(window).width();
			var winHeight =  $(window).height();
			var dialogHeight = $("#jDialogContainer").children().height();
			var dialogWidth = $("#jDialogContainer").children().width();
		
			if ($.browser.msie) {
				left = document.body.scrollLeft || document.documentElement.scrollLeft;
				top = document.body.scrollTop || document.documentElement.scrollTop;
			}
			else {
				left = window.pageXOffset;
				top = window.pageYOffset;
			}
		
			var topOff = top + winHeight/2 - dialogHeight/2; //offset for IE6
			var	leftOff = left + winWidth/2 - dialogWidth/2; //offset for IE6
			var topFixed = topOff - top;
			var	leftFixed = leftOff - left;
			
			if ($.browser.msie && parseInt($.browser.version) < 7) { // IE6
				//IE 6 fix
				$("select").hide();
				//IE 6 fix
				$("#jDialogContainer select").show(); 
				//IE6 doesn't support fixed position
				$("#jDialogContainer").children().css({
														  top: topOff,
														  left: leftOff,
														  position: "absolute",
														  zIndex: (opts.index+1)
													  }).show(); 
			}
			else{	// firefox and IE7
				$("#jDialogContainer").children().css({
														  top: topFixed, 
														  left: leftFixed, 
														  position: "fixed", 
														  zIndex: (opts.index+1)
													  }).show();
			}
		}
	};
	
	$.fn.createDialog.defaults = {
		progress: true,
		center: true,
		method: 'GET',
		data: '',
		opacity: 0.85,
		bg: '#FFFFFF',
		index: 2000
	};
	
	$.closeDialog = function(){
		dialogDisplayed=false;
		if($.browser.msie && parseInt($.browser.version) < 7) //IE6 bug
			$("select").show();
		//fade out and remove DOM nodes
		$("#jDialogOverlay").fadeTo(200, 0, function(){
			$("#jDialogContainer, #jDialogOverlay, #jDialogProgressBar").remove();
		});
		
	};

})(jQuery);