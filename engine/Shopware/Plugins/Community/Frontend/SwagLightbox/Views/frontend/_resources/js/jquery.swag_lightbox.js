/**
 * swagLightbox
 *
 * Yet Another Lightbox with Thumbnail
 *
 * @author: 	stp/shopware AG <stp@shopware.de>
 * @version:	v1
 * @date:		2011-01-23
 * @lastchange:	2011-02-16
 * @package:	SwagLightbox
 * @subpackage:	frontend/_resources/js/jquery.swag_lightbox.js
 */
(function($) {
	//Changing article informations on variants
	$(document).ready(function() {
		var j = 0;
		$('#sAdd.variant option').each(function(i, el) {
			if($(el).val().length) {
				$(el).data('index', j);
				j++;
			}
		});
		if(typeof(isVariant) != 'undefined' && isVariant === true) {
			$.changeDetails(0);
		}	
		$('#sAdd.variant').change(function() {
			$.changeDetails($(this).val());
		});
	});
		
	 $.changeDetails = function (ordernumber) {
		 if(ordernumber) {
			ordernumber = ordernumber.replace(/\./g, '\\.');
		}
    	if (typeof($.checkNotification) == 'function') {
        	if (!ordernumber){
            	$.checkNotification($.ordernumber);
        	}else {
        		$.checkNotification(ordernumber);
        	}
        }
    	try { 
			if(!ordernumber || $('#instock_'+ordernumber).val() > 0) {
				$('#article_notification').hide();
				$('#detailCartButton').show();
				$('#detailBuyInfoNotAvailable').hide();
			} else {
				$('#article_notification').show();
				if($('#detailBuyInfoNotAvailable').length) {
					$('#detailCartButton').hide();
					$('#detailBuyInfoNotAvailable').show();
				}
			}
        } catch(e) {}
        
        if (!ordernumber) {
        	// Hide Pseudoprice
        	$('.PseudoPrice').hide();
        
        	// Hide all other thumbnails
            if (isVariant) {
                var thumbs = $('.thumb_box').children('a:[id]');
                thumbs.each(function (i, el) {
                    if ($(el).attr('id') != 'thumb' + $.ordernumber) { $(el).hide(); }
                });
            }
          	
            // Hide basket
            $('#basketButton').css('opacity', '0.4');
        } else {
        
        	// Show Pseudo price
        	$('#'+ordernumber).find('.PseudoPrice').show();
        	
        	// Change informations
            $('#article_details').html($('#' + ordernumber).html());
            
            //Set basket button to active
            $('#basketButton').css('opacity', '1.0');
            
            // Change main image
            $('a#zoom1 img').attr('src', $('#img' + ordernumber).find('img').attr('src'));
            
            $('.thumbnails .thumbs .' + $.ordernumber +', .thumbnails .images .' + $.ordernumber).addClass('displaynone');
            $('.thumbnails .thumbs .' + ordernumber +', .thumbnails .images .' + ordernumber).removeClass('displaynone');
            
            $.swagLightbox.getImages('big');
            $.swagLightbox.getImages('small');
            
            //Wenn eine andere Variante ausgewaehlt ist
            if(ordernumber != $.ordernumber) {
            	
            	try {
            		var idx = $('#sAdd.variant option:selected').data('index');
            		$('a#zoom1 img').data('index', idx);
            	} catch(err) {}
            	
            	//Wenn ZoomViewer ist aktiv
            	useZoom = parseInt(useZoom);
            	$('a#zoom1').attr('href', $('#img' + ordernumber).children('a').attr('href'));
            	if(useZoom) {
            		try {
            			$('a#zoom1 img').attr('src', $('#img' + ordernumber).find('img').attr('src')).attr('title', $('#img' + ordernumber).children('a').attr('title'));
            		} catch(err) {}
            	} else {
            		 //Lightboxlink wechseln
                	$('a#zoom1').attr('href', $('#img' + ordernumber).children('a').attr('href'));
            	}
            	
            	//Thumbnails wechseln
            	$('#thumb' + $.ordernumber).hide();
                $('#thumb' + ordernumber).show();
                
                //neue Ordernumber in die globale Variable schreiben
                $.ordernumber = ordernumber;
            	try {
            		$('#variantOrdernumber').val(ordernumber);
            	}catch (err){
            		
            	}
            	// try to active liveshopping
            	try {
            		$('#article_details').liveshopping();
            	} catch(err) {}
            }
        }
    };
})(jQuery);

(function($) {

	$.fn.CloudZoom = function() {
		// .. empty function
	}
	
	$.fn.slimbox = function() {
		// .. empty function
	};
	$.slimbox = function() {
		// .. empty function
	};
	
	// default config
	var config = {
		imgInfo: '#detail .thumbnails',
		fadeTo: 0.8,
		fadeSpeed: 350,
		resizeSpeed: 600,
		_onclose: false,
		_container: '#zoom1',
		_single: true,
		_active: 0,
		_images: [],
		_thumbs: [],
		_arrowLeft: [],
		_arrowRight: [],
		_close: null,
		_overlay: null,
		_lightbox: null,
		_thumbCon: null,
		_infoCon: null,
		_activeThumb: 0,
		_scrollCon: null,
		_cssAnimations: false
	};
	
	$.fn.swagLightbox = function(settings) {
		if(settings) { $.extend(config, settings); }
		
		$(config._container).data('index', 0);

		// thumbnail support
		$('#detail .thumb_box a').each(function(i, el) {
			$(el).data('index', i).unbind('click').bind('click', function(event) {
                event.preventDefault();

                if($(config._container).is('.loader')) {
                    return false;
                }
                var thumb = $(el);

                $(config._container).addClass('loader').attr('href', thumb.attr('href')).data('index', thumb.data('index'));

                $('.thumb_box a').removeClass('active');
                thumb.addClass('active');

                var tmpImg = $(config._container).find('img');
                tmpImg.fadeOut(config.fadeSpeed, function() {
					$.swagLightbox.thumbnailSupport(this, thumb);
				});
			});
		});
		
		// create overlay
		config._overlay = $('<div id="swag_overlay"></div>').hide().appendTo(document.body);
		
		// create lightbox
		config._lightbox = $('<div id="swag_lightbox"></div>').hide().appendTo(document.body);
		
		// create close button
		config._close = $('<div id="swag_close"></div>').hide().appendTo(document.body).bind('click', function() {
			$.swagLightbox.close();
		});
		
		// get images
		$.swagLightbox.getImages('big');
		$.swagLightbox.getImages('small');
		
		if(config._images.length > 1) {
			config._single = false;
		}
		
		// overlay close handler
		config._overlay.bind('click', function() {
			$.swagLightbox.close();
		});
		
		this.each(function() {
			var $this = $(this);
			
			// add event handler
			$this.unbind('click').bind('click', function(event) {
				event.preventDefault();

				config._activeThumb = $this.data('index');
				
				// fade in overlay
				if(config._cssAnimations) {
					config._overlay.show().animateWithCss({
						'opacity': config.fadeTo
					}, config.fadeSpeed, 'linear');
				} else {
					config._overlay.fadeTo(config.fadeSpeed, config.fadeTo);
				}
				
				// animate lightbox
				$.swagLightbox.animateLightbox(this);
			});
		});
	}
	
	$.swagLightbox = {

		// get all images in an array
		getImages: function(size) {

			var ret = [];
			var sel = null;
			
			if(size == 'big') { sel = '.images span:not(".displaynone")'; } else { sel = '.thumbs span:not(".displaynone")'; }
			var imgs = $(config.imgInfo).find(sel);
				
			$.each(imgs, function(i, el) { ret[i] = $(el).html(); });
			
			if(size == 'big') {
				config._images = ret;
			} else {
				config._thumbs = ret;
			}
			
			if(config._images.length > 1) {
				config._single = false;
			}
		},
		
		changeImage: function(idx) {

			config._activeThumb = parseInt(idx);
			activeImg = config._images[idx];
			
			// Arrow support
			if(!config._single) {
				if(config._activeThumb <= 0) {
					config._arrowLeft.hide();
				} else {
					config._arrowLeft.show();
				}
				if(config._activeThumb >= config._images.length - 1) {
					config._arrowRight.hide();
				} else {
					config._arrowRight.show();
				}
			}
			
			var img = config._lightbox.find('img');
			var newImg = $('<img>', { 'src': activeImg }).css('opacity', 0);
			
			img.fadeOut(config.fadeSpeed);
			
			// preload image
			$(newImg).one('load', function() {

				// remove old image from DOM
				img.remove();
				
				window.setTimeout(function() {
					config._lightbox.addClass('active');
				}, config.fadeSpeed / 2);
				
				// set active thumbnail
				config._thumbCon.find('.active').removeClass('active');
				config._thumbCon.find('a[index^='+ idx +']').addClass('active');
				newImg.prependTo(config._lightbox);

				config._close.animate({
					'marginLeft': ($(this).width() / 2) - 10
				}, config.resizeSpeed);
				config._thumbCon.animate({
					'width': $(this).width()
				}, config.resizeSpeed);
				config._lightbox.animate({
					'width': $(this).width(),
					'height': $(this).height(),
					'marginLeft': -$(this).width() / 2
				}, config.resizeSpeed, function() {
					$(newImg).fadeTo(config.fadeSpeed, 1);
				});	
			}).each(function() {
				if(this.complete) $(this).trigger('load');
			});
		},
				
		close: function() {

			config._onclose = true;
			config._close.hide();

			// fadeout overlay and lightbox
			if(config._cssAnimations) {
				config._overlay.animateWithCss({
					'opacity': 0
				}, config.fadeSpeed, 'linear', function() {
					config._overlay.hide();
				});
				config._lightbox.animateWithCss({
					'opacity': 0
				}, config.fadeSpeed, 'linear', function() {
					config._lightbox.hide().removeClass('active').empty();
				});
			} else {
				config._overlay.fadeOut(config.fadeSpeed, function() {
					config._overlay.hide();
				});
				config._lightbox.fadeOut(config.fadeSpeed, function() {
					config._lightbox.hide().removeClass('active').empty();
				});
			}
		},
		
		animateLightbox: function(imgSrc) {

			config._onclose = false;

			config._lightbox.fadeIn(config.fadeSpeed, function() {
				$.swagLightbox.arrowSupport();
			});

			var img = $('<img>', {
				'src': $(imgSrc).attr('href'),
				'css': { opacity: 0 }
			});

			// preload image
			$(img).one('load', function() {
				img.appendTo(config._lightbox);
				
				// add thumbnails
				if(!config._single) {
					$.swagLightbox.showThumbnails();

					config._thumbCon.css({
						'opacity': 0,
						'width': $(this).width()
					});
				}
				config._close.hide().css(
					'marginLeft', ($(this).width() / 2) - 10
				);
				
				config._lightbox.css({
					'top':   $(window).scrollTop() + 20,
					'marginLeft': -($(this).width() / 2),
					'height': $(this).height(),
					'width': $(this).width()
				});

				config._lightbox.animate({
					'opacity': 1
				}, config.fadeSpeed, function() {
					if(config._onclose == false) {
						config._close.show();
						config._lightbox.addClass('active');
						img.animate({
							'opacity': 1
						}, config.fadeSpeed, function() {
							$.swagLightbox.showCloseButton();
						});
					}
				});
			}).each(function() {
				if(this.complete) $(this).trigger('load');
			});

		},
		
		showCloseButton: function() {
						
			config._close.show(config.fadeSpeed);

			// bind close evebt
			config._close.bind('click', function() {
				$.swagLightbox.close();
			});
		},
		
		showThumbnails: function() {
			config._thumbCon = $('<div id="swag_thumbs"></div>');

			$.each(config._thumbs, function(i, el) {
				var thumb = $('<a>', {
					'href': config._images[i],
					'index': i,
					'css': {
						'backgroundImage': 'url(' + config._thumbs[i] + ')'
					}
				}).appendTo(config._thumbCon).bind('click', function(event) {
					event.preventDefault();
					$.swagLightbox.changeImage($(this).attr('index'));
				});
			});
			
			config._thumbCon.find('a[index^=' + config._activeThumb + ']').addClass('active');
			
			config._thumbCon.appendTo(config._lightbox);
			
			
			if(config._cssAnimations) {
				config._thumbCon.animateWithCss({
					'opacity': 1
				}, config.resizeSpeed, 'linear');
			} else {
				config._thumbCon.animate({
					'opacity': 1
				}, config.resizeSpeed);
			}
			
		},
		
		// preload image and fades it in
		thumbnailSupport: function(img, thumb) {
			var newImg = $('<img>', {
				'src': thumb.attr('rev'),
				'alt': thumb.attr('title')
			}).css('opacity', 0.0001);
			
			// preload image
			$(newImg).load(function() {

				// remove old image from DOM
				$(img).remove();
				
				$(newImg).appendTo($(config._container));
				
				window.setTimeout(function() {
					$(config._container).removeClass('loader');
				}, config.fadeSpeed / 2);
				
				// fade in new image
				if(config._cssAnimations) {
					$(newImg).animateWithCss({
						'opacity': 1	
					}, config.fadeSpeed, 'linear');
				} else {
					$(newImg).fadeTo(config.fadeSpeed, 1);
				}
			});
		},
		
		arrowSupport: function() {
			// Arrow support			
			if(!config._single) {
				
				config._arrowLeft = $('<div id="swag_leftArrow"></div>').appendTo(config._lightbox);
				config._arrowRight = $('<div id="swag_rightArrow"></div>').appendTo(config._lightbox);
				
				config._arrowLeft.bind('click', function() {
					$.swagLightbox.changeImage(config._activeThumb - 1);
				});
				config._arrowRight.bind('click', function() {
					$.swagLightbox.changeImage(config._activeThumb + 1);
				});
				
				if(config._activeThumb <= 0) {
					config._arrowLeft.hide();
				} else {
					config._arrowLeft.show();
				}
				if(config._activeThumb >= config._images.length) {
					config._arrowRight.hide();
				} else {
					config._arrowRight.show();
				}
			}
		}
	}
})(jQuery);