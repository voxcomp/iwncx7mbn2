
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';

(function($) {
	window.uniqueUsername = function(username,el) {
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});
        $.ajax({
            url: "/ajax/username/"+username,
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                if(data=='false') {
	                $("#"+el).parent().find(".alert").remove();
	                $("#"+el).parent().addClass("has-error").append('<div class="alert-danger alert">The username has already been used.</div>');
                } else {
	                $("#"+el).parent().removeClass("has-error").addClass("has-success").find(".alert").remove();
                }
            }
        });
	}
	window.uniqueTeam = function(event,teamname,el) {
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});
        $.ajax({
            url: "/ajax/team/"+event+"/"+teamname.replace(" ","~"),
            type: 'POST',
            dataType: 'html',
            success: function(data) {
                if(data=='false') {
	                $("#"+el).parent().find(".alert").remove();
	                $("#"+el).parent().addClass("has-error").append('<div class="alert-danger alert">The team name has already been used.</div>');
                } else {
	                $("#"+el).parent().removeClass("has-error").addClass("has-success").find(".alert").remove();
                }
            }
        });
	}
	window.checkCoupon = function(coupon,el,payamount,donateamount) {
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});
        $.ajax({
            url: "/ajax/coupon/"+coupon+"/"+payamount,
            type: 'POST',
            dataType: 'JSON',
            success: function(data) {
                if(!data.status) {
	                $("#"+el).parent().find(".alert").remove();
	                $("#"+el).parent().addClass("has-error").append('<div class="alert-danger alert">The discount code entered is not valid.</div>');
	                var total = payamount+donateamount;
	                $("h3.amount-confirm").text("A payment for $"+total.toFixed(2)+" will be submitted.");
                } else {
	                $("#"+el).parent().removeClass("has-error").addClass("has-success").find(".alert").remove();
	                var total = data.value+donateamount;
	                $("h3.amount-confirm").text("A payment for $"+total.toFixed(2)+" will be submitted.");
	                window.payamount = total;
                }
            }
        });
	}
	window.AjaxSubmission = function(url, redirect, data='', reload=true, el=null, showstatus=false, statusel=null) {
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});
        if(data!='') {
            $.ajax({
                url: url,
                data: {data : data},
                type: 'POST',
                dataType: 'html',
                success: function(data) {
	                if(reload) {
		                window.location = redirect;
		            } else {
			            if(el!==null) {
				            el.fadeOut(300,function() { el.remove(); });
				        } else if(showstatus) {
					        statusel.removeClass('hide').text(data).slideDown(300,function() {
						        statusel.fadeTo(2000, 500).slideUp(500, function(){
								    statusel.slideUp(500);
								});
					        });
				        }
		            }
                }
            });
        } else {
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'html',
                success: function(data) {
	                if(reload) {
		                window.location = redirect;
		            } else {
			            if(el!==null) {
				            el.fadeOut(300,function() { el.remove(); });
				        } else if(showstatus) {
					        statusel.removeClass('hide').text(data).slideDown(300,function() {
						        statusel.fadeTo(2000, 500).slideUp(500, function(){
								    statusel.slideUp(500);
								});
					        });
				        }
		            }
                }
            });
        }
	}
	window.AjaxConfirmDialog = function(msg, title, url, redirect, data='', reload=true, el=null, showstatus=false, statusel=null) {
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});

	    $("#dialog-confirm").html(msg);
	
	    // Define the Dialog and its properties.
	    $("#dialog-confirm").dialog({
	        resizable: false,
	        show: {
		        effect: "fade",
		        duration: 300
	        },
	        hide: {
		        effect: "fade",
		        duration: 300
	        },
	        open: function(event,ui) {
		        $(".ui-widget-overlay").addClass('custom-overlay');
		        $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
	        },
	        close: function(event,ui) {
		        $(".ui-widget-overlay").removeClass('custom-overlay');
	        },
	        modal: true,
	        title: title,
	        height: 250,
	        width: 400,
	        buttons: {
	            "Yes": function () {
	                $(this).dialog('close');
	                if(data!='') {
		                $.ajax({
			                url: url,
			                data: {data : data},
			                type: 'POST',
			                dataType: 'html',
			                success: function(data) {
				                if(reload) {
					                window.location = redirect;
					            } else {
						            if(el!==null) {
							            el.fadeOut(300,function() { el.remove(); });
							        } else if(showstatus) {
								        statusel.removeClass('hide').text(data).slideDown(300,function() {
									        statusel.fadeTo(2000, 500).slideUp(500, function(){
											    statusel.slideUp(500);
											});
								        });
							        }
					            }
			                }
		                });
	                } else {
		                $.ajax({
			                url: url,
			                type: 'POST',
			                dataType: 'html',
			                success: function(data) {
				                if(reload) {
					                window.location = redirect;
					            } else {
						            if(el!==null) {
							            el.fadeOut(300,function() { el.remove(); });
							        } else if(showstatus) {
								        statusel.removeClass('hide').text(data).slideDown(300,function() {
									        statusel.fadeTo(2000, 500).slideUp(500, function(){
											    statusel.slideUp(500);
											});
								        });
							        }
					            }
			                }
		                });
	                }
	            },
	            "No": function () {
	                $(this).dialog('close');
	            }
	        }
	    });
	}
	window.SubmitConfirmDialog = function(msg, title, formEl) {
	    $("#dialog-confirm").html(msg);
	
	    // Define the Dialog and its properties.
	    $("#dialog-confirm").dialog({
	        resizable: false,
	        show: {
		        effect: "fade",
		        duration: 300
	        },
	        hide: {
		        effect: "fade",
		        duration: 300
	        },
	        open: function(event,ui) {
		        $(".ui-widget-overlay").addClass('custom-overlay');
		        $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
	        },
	        close: function(event,ui) {
		        $(".ui-widget-overlay").removeClass('custom-overlay');
	        },
	        modal: true,
	        title: title,
	        height: 250,
	        width: 400,
	        buttons: {
	            "Yes": function () {
	                $(this).dialog('close');
	            },
	            "No": function () {
	                $(this).dialog('close');
	                formEl.submit();
	            }
	        }
	    });
	}
	window.AlertDialog = function(msg, title, url="", height=250, width=400) {
		if(url=="") {
		    $("#dialog-confirm").html(msg);
		} else {
			$.get(url,function(data) {
				$("#dialog-confirm").html(data);
			});
		}
	
		if(width>($(window).outerWidth()*0.80)) {
			width = ($(window).outerWidth()*0.80);
		}
		if(height>($(window).outerHeight()*0.80)) {
			height = ($(window).outerHeight()*0.80);
		}
	
	    // Define the Dialog and its properties.
	    $("#dialog-confirm").dialog({
	        resizable: false,
	        show: {
		        effect: "fade",
		        duration: 300
	        },
	        hide: {
		        effect: "fade",
		        duration: 300
	        },
	        open: function(event,ui) {
		        $(".ui-widget-overlay").addClass('custom-overlay');
		        $(".ui-dialog-titlebar-close", ui.dialog | ui).hide();
	        },
	        close: function(event,ui) {
		        $(".ui-widget-overlay").removeClass('custom-overlay');
	        },
	        modal: true,
	        title: title,
	        height: height,
	        width: width,
	        buttons: {
	            "OK": function () {
	                $(this).dialog('close');
	            }
	        }
	    });
	}
	window.SubmitCroppedPhoto = function(path = "profile") {
		$.ajaxSetup({
		    headers: {
		        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		    }
		});
		//            data: {photoData:$('#profile_photo_form input[name=photoData]').val(), original:$('#profile_photo_form input[name=original]').val(), path:path},
        $.ajax({
            url: '/ajax/profile/cropped',
            type: 'POST',
            data: {photoData:$('#profile_photo_form input[name=photoData]').val(), path:path},
            dataType: 'json',
            beforeSend: function(jqXHR, settings) {
	            $(".photo-loading").show();
	            $(".photo-save-btn").hide();
	            $("#profile-photo").cropper('disable');
            },
            success: function(data) {
				$('#profile_photo_form input[name=photoData]').val('');
				$('#profile_photo_form input[name=original]').val('');
				$('.photo-directions').slideUp(300);
				$('#photo').val(data.file);
				$('#profile-photo').cropper('destroy').slideUp(300,function() {
					$('#profile-photo').attr("src",data.image).delay(1000).slideDown(300);
				});
				$('#profile-photo-save').fadeOut(300,function(){
					$('#profile-buttons').fadeIn(300);
				});
            },
			error: function(responseText, statusText, xhr, $form) {
				console.log(responseText);
				console.log(statusText);
				$('#profile-photo-field').parent().addClass("has-error");
				$('.photo.help-block').text('There was an error uploading the file.  Please rename the photo or try again with a different photo.');
				$('#profile_photo_form input[name=photoData]').val('');
				$('#profile_photo_form input[name=original]').val('');
				$('#profile-photo').cropper('destroy').slideUp(300,function() {
					$('#profile-photo').attr("src",'');
				});
				$('#photo').val('');
				$('.photo-directions').slideUp(300);
			},
        });
	}
	function showUserSearchResults(responseText, statusText, xhr, $form) {
	    $(".search-results").html(responseText);
		$(".form-column").removeClass('col-sm-offset-3');
		$(".data-column").fadeIn(300);
	}

	$(document).ready(function() {
		$("input[type=text], input[type=email]").focus(function() {
			$(this).select();
		});
		
        $(".drop-link").click(function() { $(this).toggleClass('active').nextAll(".drop-area").eq(0).slideToggle(400); });
		
		$(".recurring").click(function(e) {
			e.preventDefault();
			el = $(this);
			$('.recurring').removeClass("active");
			el.addClass("active");
			$("#recurring").val(el.data('recurring'));
		});
		$(".donate-amount").click(function(e) {
			e.preventDefault();
			$("#donatesubmitmsg").hide();
			$("#donatesubmit").show();
			$(".donate-amount").removeClass("active");
			$(this).addClass("active");
			if($(this).data('amount')!='other') {
				$("input[name=amount]").val($(this).data('amount'));
				$("#other-amount").slideUp(400);
				$(".amount-confirm .final-amount").text('$'+$(this).data('amount')).parent().slideDown(200);
			} else {
				$("input[name=amount]").val('0');
				$("#other-amount").slideDown(400);
				$("#otheramount").val('0');
				$(".amount-confirm .final-amount").text('$'+$("#otheramount").val()).parent().slideDown(200);
			}
		});
		$("#otheramount").keyup(function() {
			$(".amount-confirm .final-amount").text('$'+$("#otheramount").val()).parent().slideDown(200);
		});
	    if($('input[name=phone], input[name=twilio_number]').length) {
			$("input[name=phone]").mask("999-999-9999");
			$("input[name=twilio_number]").mask("999-999-9999", {optional:true});
	    }
	    $( "input.datepicker" ).datepicker({
			changeMonth: true,
			changeYear: true,
			minDate:'-1y',
			yearRange:"-1:+20",
			altFormat:"mm-dd-YYYY"
	    });
	    if($('input.datepicker').length) {
			$("input.datepicker").mask("99/99/9999");
	    }
	    $('.btn-option').click(function() {
		    $(this).toggleClass('active');
		    $(this).siblings('.btn-option').removeClass('active');
		    $(this).parent().siblings().children('.btn-option').removeClass('active');
	    });
		if($('#search_form').length) {
			$('#search_form').submit(function() {
				$(this).ajaxSubmit({
					target: '.search-results',
					dataType: 'html',
					type: 'POST',
					success: showUserSearchResults,
				});
				return false;
			});
		}
		if($('#profile_photo_form').length) {
			$('#profile_photo_form #profile-photo-field').on("change",function() {
				//$('#profile_photo_form').submit();
				var bar = $('.bar');
				var percent = $('.percent');
				var status = $('#status');
				$('#profile_photo_form').ajaxSubmit({
					dataType: 'json',
					success: function(responseText, statusText, xhr, $form) {
						$('#profile-buttons').fadeOut(300,function(){
							$('#profile-photo-save').fadeIn(300);
						});
						$('#photoStatus').slideUp(300);
						$('#profile-photo-field').parent().removeClass("has-error");
						$('.photo.help-block').text('');
						$('#profile-photo').attr("src",responseText.image).delay(500).slideDown(300,function() {
							if($("input[name='path'").length) {
								$('#profile-photo').cropper({
									cropend: function(data) {
										originalData = $('#profile-photo').cropper("getCroppedCanvas");
										$('input[name=photoData]').val(originalData.toDataURL());
									},
									ready: function(data) {
										originalData = $('#profile-photo').cropper("getCroppedCanvas");
										$('input[name=photoData]').val(originalData.toDataURL());
									},
									zoomable:false,
									rotatable:true,
									movable:false
								});
							} else {
								$('#profile-photo').cropper({
									aspectRatio:1/1,
									cropend: function(data) {
										originalData = $('#profile-photo').cropper("getCroppedCanvas");
										$('input[name=photoData]').val(originalData.toDataURL());
									},
									ready: function(data) {
										originalData = $('#profile-photo').cropper("getCroppedCanvas");
										$('input[name=photoData]').val(originalData.toDataURL());
									},
									zoomable:false,
									rotatable:true,
									movable:false
								});
							}
							$('.btn-rotate').on("click",function() {
								var datamethod;
								var dataoption;
								datamethod=$(this).attr("data-method");
								dataoption=$(this).attr("data-option");
								$('#profile-photo').cropper(datamethod,dataoption);
							});
				            $(".photo-loading").hide();
				            $(".photo-save-btn").show();
							$('.photo-directions').slideDown(300);
						});
						$('#photo').val(responseText.file);
						$('#profile_photo_form input[name=original]').val(responseText.file);
						$('#profile-photo-field').val('');
					},
					error: function(responseText, statusText, xhr, $form) {
						console.log(responseText);
						console.log(statusText);
						$('#profile-photo-field').parent().addClass("has-error");
						$('.photo.help-block').text('There was an error uploading the file.  Please rename the photo or try again with a different photo.');
					},
					beforeSend: function() {
						$("#photoStatus").progressbar({
							value:false,
						}).slideDown(300);
					},
					uploadProgress: function(event, pos, total, percentComplete) {
						$("#photoStatus").progressbar("value",percentComplete);
					}
				});
			});
		}
        $(window).on("scroll",function() {
            wtop=$(window).scrollTop();
            if(wtop>165) {
                    if(!$(".header").hasClass("sticky")) {
                            $(".header").addClass("sticky");
                        }
            } else if(wtop<165) {
                    if($(".header").hasClass("sticky")) {
                            $(".header").removeClass("sticky");
                        }
            }
        });
	    if($(".alert").length) {
			$(".alert").not(".nohide").fadeTo(2000, 500).slideUp(500, function(){
			    $(".alert").slideUp(500);
			});
	    }
	});
})(jQuery);