(function($){
	var DOMUtilities;
	var galleryIndex = 0;
	
	addVID = {
		init: function(){
			if($('#vid').length){
				$('#vid').val(_lf_vid);
			}
		}
	}
	
	function modal(page, myEvent){
		switch(page){
			case 'contact':
				if ($('#modalBg').length > 0) {
					if ($('#modal').attr('class') == 'contact') {
						$('#modalBg').fadeIn('fast');
					}else{
						$('#modalBg').remove();
					}
				}
				if($('#modalBg').length == 0){
					$(document.body).append('<div id="modalBg"><div id="modalContainer"><div id="modal" class="contact">&nbsp;</div></div></div>');
					$.post(urlhome+"wp-admin/admin-ajax.php",{
						action:"contactEmail_action"
					}, function(data){
						$('#modalContainer').css('height', '490px');
						$('#modalContainer').css('top', '15%');
						$('#modal').html(data);
						$('#modalBg').fadeIn('fast');
						DOMUtilities.rollOvers();
					});
				}
				break;
				case 'contact-user':
				if ($('#modalBg').length > 0) {
					if ($('#modal').attr('class') == 'contact') {
						$('#modalBg').fadeIn('fast');
					}else{
						$('#modalBg').remove();
					}
				}
				if($('#modalBg').length == 0){
					$(document.body).append('<div id="modalBg"><div id="modalContainer"><div id="modal" class="contact">&nbsp;</div></div></div>');
					$.post(urlhome+"wp-admin/admin-ajax.php",{action:"contactuser_action"}, function(data){
						$('#modalContainer').css('height', '490px');
						$('#modalContainer').css('top', '15%');
						$('#modal').html(data);
						
						$('#modalBg').fadeIn('fast');
						DOMUtilities.rollOvers();
						
					});
				}
				break;
				case 'contact-student':
				if ($('#modalBg').length > 0) {
					if ($('#modal').attr('class') == 'contact') {
						$('#modalBg').fadeIn('fast');
					}else{
						$('#modalBg').remove();
					}
				}
				if($('#modalBg').length == 0){
					$(document.body).append('<div id="modalBg"><div id="modalContainer"><div id="modal" class="contact">&nbsp;</div></div></div>');
					$.post(urlhome+"wp-admin/admin-ajax.php",{action:"contactstudent_action"}, function(data){
						$('#modalContainer').css('height', '490px');
						$('#modalContainer').css('top', '15%');
						$('#modal').html(data);
						
						$('#modalBg').fadeIn('fast');
						DOMUtilities.rollOvers();
						
					});
				}
				break;
			
			/*Login Popup
				case 'login-pop':
				if ($('#modalBg').length > 0) {
					if ($('#modal').attr('class') == 'contact') {
						$('#modalBg').fadeIn('fast');
					}else{
						$('#modalBg').remove();
					}
				}
				if($('#modalBg').length == 0){
					$(document.body).append('<div id="modalBg"><div id="modalContainer"><div id="modal" class="contact">&nbsp;</div></div></div>');
					$.post(urlhome+"wp-admin/admin-ajax.php",{action:"loginpop_action"}, function(data){
						$('#modalContainer').css('height', '490px');
						$('#modalContainer').css('top', '15%');
						$('#modal').html(data);
						
						$('#modalBg').fadeIn('fast');
						DOMUtilities.rollOvers();
						
					});
				}
				break;
				
				*/
			default:
				return false;
		}
	}

    DOMUtilities = {
    	path: "",
    	images: [],
    	getPath: function(){
    		for(var i=0,path,scripts=$('head script'); i<scripts.length; i++){
    			var s = scripts[i];
    			if(s.src.match('application.js')){
    				path = s.src.replace("/javascripts/application.js","");
    			}
    		}
    		return path;
    	},
    	linkTargets: function(c){
    		this.setContext(c).context.find('a[rel*="external"]').attr('target','_blank');
    		return this;
    	},
    	rollOvers: function(c){
    		this.setContext(c).context.find('img.rollOver, input[type="image"].rollOver').hover(function(e){
    			if (!this.className.match(/active/)) {
    				this.src = this.src.replace("_i.", "_o.");
    			}
    		},function(e){
    			if (!this.className.match(/active/)) {
    				if (this.src.indexOf("_o.") != -1) {
    					this.src = this.src.replace("_o.", "_i.");
    				}
    				else 
    					if (this.src.indexOf("_a.") != -1) {
    						this.src = this.src.replace("_a.", "_i.");
    					}
    			}
    		}).filter('input[type="image"]').mousedown(function(e){
    			this.src = this.src.replace("_o.","_a.");
    		});
    		return this;
    	},
    	autoReplaceInputs: function(c){
    		this.setContext(c).context.find('input.autoReplace, textarea.autoReplace').addClass('empty').focus(function(e){
    			if(this.value == this.defaultValue){
    				this.value = "";
    			}
    			$(this).addClass('focus').removeClass('empty');
    		}).blur(function(e){
    			if(jQuery.trim(this.value) === ""){
    				this.value = this.defaultValue;
    				$(this).addClass('empty');
    			}
    			$(this).removeClass('focus');
    		});
    		return this;
    	},
    	setContext: function(c){
    		if(typeof(c) != "undefined"){
    			this.context = $(c);
    		}
    		return this;
    	},
    	imgPreload: function(){
    		var preloader=new Image();
    		for(var i=0; i<this.images.length; i++){
    			preloader.src = this.path + this.images[i];
    		}
    		return this;
    	},
    	initialize: function(c){
    		this.path = this.getPath();
    		if(typeof(c) == "undefined"){
    			c = $(document.body);
    		}
    		this.setContext(c).linkTargets().rollOvers().autoReplaceInputs();
    	}
    };
    
	
	


	
	
	formValidation = { 	
		init: function(){
			window.validateForms = [];
			if($('#contact_home').length){
				$('#contact_home div.hiddenFields').append('<input type="hidden" name="cerberus" value="1" id="cerberus" />');
				window.validateForms['contact_home'] = new FormValidator('contact_home',{name: 'Name Required', email: 'Email Address Required'});
				
				if ( $('#contact_home input:hidden[name="cerberus"]').val() == '1' ) {
					$('#contact_home').submit(function(s){
						s.preventDefault();
						return false;
					});
					$('#contact_home input[name="submit"]').click(function(e){
						if(window.validateForms['contact_home'].validate()){
							var submitButton = $(this);
							var loading = '<div id="processing_form"><img src="'+urltemp+'images/box/ajax-loader.gif" alt="Processing" /> Processing Submission...</div>'
							submitButton.hide();
							submitButton.parent().append(loading);							
							var success;
							e.preventDefault();
							var parameters = $('#contact_home').serialize();
							$.ajax({
								url: urlhome+"wp-admin/admin-ajax.php",
								data:'action=contact_pro&'+ parameters,
								dataType: 'json',
								type: 'POST',
								complete: function(response){
									var responseJSON = $.parseJSON(response.responseText);
									
									$('#homeThanks').delay(1000).fadeIn();
									if( responseJSON['contactf'] == 202 ) {
										$('#processing_form').remove();
										submitButton.show();
										//SUCCESS MESSAGE FADE IN
									} else { 
										$('#processing_form').remove();
										submitButton.show();
										//SUCCESS MESSAGE FADE IN
									}
																		
								}
							});		
							return false;
						}
					});
				}
			}
				
		}
	}

    $(document).ready(function(){

        DOMUtilities.initialize();

	
	
		formValidation.init();
		addVID.init();
						
		$('#contact,.contactform').click(function(e){
		
			modal('contact');
		});		
		$('#contact-user').click(function(e){
		
			modal('contact-user');
		});
		$('#contact-student').click(function(e){
		
			modal('contact-student');
		});
		/*
			Added .login-pop
			Added e.preventDefault(); to disallow returning an blank anchor link (#). It causes on some browsers to scroll right on top (notably older version if IE).
		
		$('.login-pop, #login-pop').click(function(e){
			e.preventDefault(); 
			modal('login-pop');
		});
		*/
		
		$('#modal .close').live('click', function(e){
			e.preventDefault();
			$('#modalBg').fadeOut('fast', function(){
				$('#modalBg').remove();
			});
		});
	
	
			
    });
	
})(jQuery);