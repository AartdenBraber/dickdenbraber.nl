/*--------------------------------------------------------------
## General variables
--------------------------------------------------------------*/
var vh, vw, wpadminbarHeight = 0;
$(function() {
	vh = $(window).height();
	vw = window.innerWidth;
	var wpadminbar = document.getElementById('wpadminbar');
	if(wpadminbar)
		wpadminbarHeight = wpadminbar.offsetHeight || 0;
});
$(window).resize(function() {
	vh = $(window).height();
	vw = window.innerWidth;
	var wpadminbar = document.getElementById('wpadminbar');
	if(wpadminbar)
		wpadminbarHeight = wpadminbar.offsetHeight || 0;
});



/*--------------------------------------------------------------
## Add HTML5-validation and Captcha functionality to the form
--------------------------------------------------------------*/
var form = getElByClass('wpcf7-form');
$(document).ready(function() {
	var $form = $(form);

	if($form.length) {

		$form.removeAttr('novalidate');

		$form.find('[aria-required=true]').each(function() {
			$(this).attr('required',true)
		});


		// add a wrapper around dots to hide them when the screen size gets smaller
		var $formInside = $('.contactform-bottom fieldset');
		$formInside.html( $formInside.html().replace(/\. /g,'<span class="dot">. </span>') );


		/* add resizing functionality for select */
		// add HTML mask to fill that with the option-content whenever some option is selected
		$('.footer-contact-form').append('<select id="width_tmp_select" style="display: none;"><option id="width_tmp_option"></option></select>');

		// add functionality
		$('.footer-contact-form select').change(function(){
			$("#width_tmp_option").html($(this).find(':selected').text()); // set the option to contain the same text as the just-selected option
			$(this).width($("#width_tmp_select").css('width','').width()); // get the width from the mask and put it on the changed select
		}).change(); // and fire it once for every select


	}
});

if(form) {
	function submitMyForm () {
		form.submit();
	}

	$(function () {
		$(form).find("input, textarea").on( 'focus', function() {
		    $('.grecaptcha-badge').addClass( 'show' );
			console.log('focusses')
		  });
		$(form).on('submit', function (e) {
			e.preventDefault();
			grecaptcha.reset();
		    grecaptcha.execute();
		    return false;
		});
	});
}

/*--------------------------------------------------------------
## Very lightweight parallax scrolling
--------------------------------------------------------------*/
$(function() {
	if( !getSupportedTransform() )
		return false;

	var $parallax, defaultSpeed = .5;
	$(document).ready(function() {
		$parallax = $('.parallax');

	    // trigger reset
	    $(window).scroll(); 

	    // add parallax settings to elements using 
	    $parallax.parallaxInit();
	});

	$(window).resize(function() {

		$parallax.parallaxInit();
	});


	$.fn.parallaxInit = function() { 
		this.each(function() {
			var outerHeight = $(this).outerHeight(),
				height = outerHeight,
				extraHeight = parseFloat( $(this).data('speed') ) || defaultSpeed;


			/* if( extraHeight < 1 )
			extraHeight = 1 - extraHeight; */

			height += outerHeight * extraHeight;

			//if( $(this).outerHeight() > vh )
			height = vh + outerHeight * ( 1 - extraHeight);
			height *+ .1; //to account for possible lag - don't show the background

			$(this).find('.parallax-bg').css('height', height);
			
		});

	};



	//call function on scroll
	$(window).scroll(parallax);

	var parallaxElements = document.getElementsByClassName('parallax'),
	supportedTransform = getSupportedTransform();

	var el, scrollTop, elOffset;

	function parallax(){

		for( var i = 0, j = parallaxElements.length; i < j; i++ ) {

			el = parallaxElements[i];
			elOffset = el.getBoundingClientRect().top;

			// only change if the element is in viewport - save resources
			if( isInViewport(el) ) {
				var setSpeed = parseFloat(el.getAttribute('data-speed')) || defaultSpeed,
					speed = -( elOffset * setSpeed )

				el.getElementsByClassName('parallax-bg')[0].style[supportedTransform] = 'translate3d(0, ' + speed + 'px, 0)';

			}

		}

	}
});


/*-------------------------------------------------------------------
## Outline.js - remove outline on click and leave it for keyboard #
-------------------------------------------------------------------*/
// based on http://www.paciellogroup.com/blog/2012/04/how-to-remove-css-outlines-in-an-accessible-manner/
(function(d){var style_element=d.createElement('STYLE'),dom_events='addEventListener' in d,add_event_listener=function(type,callback){if(dom_events){d.addEventListener(type,callback)}else{d.attachEvent('on'+type,callback)}},set_css=function(css_text){!!style_element.styleSheet?style_element.styleSheet.cssText=css_text:style_element.innerHTML=css_text};d.getElementsByTagName('HEAD')[0].appendChild(style_element);add_event_listener('mousedown',function(){set_css(':focus{outline:0}::-moz-focus-inner{border:0;}')});add_event_listener('keydown',function(){set_css('')})})(document)





//add simple support for background images for LazySizes plugin
document.addEventListener('lazybeforeunveil', function(e){
	lazysizesAddBackgroundToElement(e.target);
});

$(document).ready(function() {
	setTimeout(function() {
		var lazysizesAlreadyLoaded = document.getElementsByClassName('lazyloaded'),
			lazysizesAlreadyLoadedLength = lazysizesAlreadyLoaded.length;
		for(var i = 0; i < lazysizesAlreadyLoadedLength; i++) {
			var element = lazysizesAlreadyLoaded[i];

			if(!element.src) {

				element.style.transition = 'none';
				element.style.opacity = 0;

				setTimeout(function(element) {
					lazysizesAddBackgroundToElement( element );
					element.style.transition = '';
					element.style.opacity = 1;
				}, 500, element);
			}
		}
	}, 500);
});

function lazysizesAddBackgroundToElement(el) {
    var bg = el.getAttribute('data-bg'),
    	isResponsive = el.classList.contains('responsive-bg');

    if(isResponsive) {
    	var backgrounds = bg.split(','),
    		len = backgrounds.length,
    		background,
    		responsiveBackgrounds;

    	for(var i = 0; i < len; i++) {
    		background = backgrounds[i];
    		responsiveBackgrounds = background.split(','),
    		responsiveBackgroundsLength = responsiveBackgrounds.length;

    		for(var j = 0; j < responsiveBackgroundsLength; j++) {
    			var responsiveBg = responsiveBackgrounds[j].split(') ');

    			// grabs the biggest version
    			if(responsiveBg[1] < vw)
    				bg = responsiveBg[0] + ')';
    		}
    	}
    } 

    if(bg){
        el.style.backgroundImage = bg;
    }
}



/*---------------------------------------------------------------------
## Add a tooltip to each abbr or .tooltip-element, based on the title
---------------------------------------------------------------------*/
//call this function on ready
$(document).ready(addAbbreviationTooltip_func);

function addAbbreviationTooltip_func() {

	$('abbr, .tooltip').each(function() {
		var tooltipText = $(this).attr('title'),
			abbrText = $(this).html();

		$(this).text('').attr('title','').addClass('tooltip tooltip-effect-2').append('<span class="tooltip-item">' + abbrText + '</span><span class="tooltip-content"><span class="tooltip-text">' + tooltipText + '</span></span>')
	});
}


/*--------------------------------------------------------------
## Header
--------------------------------------------------------------*/

//call this function on ready
$(document).ready(changeHeader_func);

var changeHeaderTimeout, 
	changeHeaderTimeout2,
clearHeaderTimeouts = function() {
	clearTimeout(changeHeaderTimeout);
    clearTimeout(changeHeaderTimeout2);
};
function changeHeader_func() {

	var lastScrollTop = 0, 
		delta = 250,
		didScroll,
		$header = $('.site-header'),
		$description = $('.site-description'),
		navbarHeight = offset = $header.outerHeight() + wpadminbarHeight;

    //call on initiation first thing to reset the header if needed (for example if the page is scrolled halfway)
    changeHeader();

    //call this function on scroll
	$(window).scroll(changeHeader);


	var navbarHeight = $header.outerHeight(),
		header = $header[0],
	    treshold = 0,
	    lastScroll = 0;
	function changeHeader() {

		var top = $(window).scrollTop(),
			offset = 50,
			isSticky = $header.hasClass('stick-to-top');

        // Just add the class if it's got enough distance from the top and if the class is not already added
        if( top > 10) {
        	$header.addClass('stick-to-top');

        	if(!isSticky) {
	        	clearHeaderTimeouts();
	        	changeHeaderTimeout = setTimeout(function() {
	        		$description.addClass('animated');

	        		clearHeaderTimeouts();
	        		changeHeaderTimeout2 = setTimeout(function() {
	        			clearHeaderTimeouts();
	        			$description.addClass('animationDone');
	        		}, 700)
	        	}, 1000);
	        }
        } else if ( top < offset && isSticky ) {
        	clearHeaderTimeouts();
        	$header.removeClass('stick-to-top');
        	$description.removeClass('animated animationDone');
        }

        if(top > offset * 10) {
	    	var newScroll = top,
		        diff = newScroll-lastScroll;

		    // normalize treshold range
		    treshold = (treshold+diff>navbarHeight) ? navbarHeight : treshold+diff;
		    treshold = (treshold < 0) ? 0 : treshold;

		    $header.css('top', (-treshold) + wpadminbarHeight +'px');

		    lastScroll = newScroll;
		} else {
			$header.css('top', wpadminbarHeight);
		}

	    lastScrollTop = top;

	}

}

/*--------------------------------------------------------------
## Add menu functionality. The rest is CSS
--------------------------------------------------------------*/
/*function disableScrolling(){
    var x=window.scrollX;
    var y=window.scrollY;
    window.onscroll=function(){
    	$('html, body').stop().animate({
		   scrollTop: y
		}, 1000);
    };
}*/

function enableScrolling(){
    window.onscroll=function(){};
}

var showingMenuBodyClass = 'show-menu';
$('.menu-toggle').click(function() {
	if( $('body').hasClass(showingMenuBodyClass) ) {
		$('body').removeClass(showingMenuBodyClass);
		//enableScrolling();
	} else {
		$('body').addClass(showingMenuBodyClass);
		//disableScrolling();
	}
});
/*
$('.home #site-navigation .smooth-scroll').click(function(e) {
	e.preventDefault();

	$('body').removeClass(showingMenuBodyClass);
	enableScrolling();
});
*/

/*--------------------------------------------------------------
## Add hover functionality for posts
--------------------------------------------------------------*/
function toggleHover(el, add) {
  $(el).closest('.post').find('.post__image__inside')
    .toggleClass('hovered', add);
}
$('#main').on({
	mouseenter: function () {
	  toggleHover(this, true);
	}, mouseleave: function () {
	  toggleHover(this, false);
	},
	touchstart: function () {
	  toggleHover(this, true);
	}, touchend: function () {
	  toggleHover(this, false);
	},
}, '.post .ajax-popup-link');


;( function( $, window, document, undefined )
{
    'use strict';
 
    var $list       = $( '.posts-container' ),
        $items      = $list.find( 'li' ),
        setHeights  = function()
        {
            $items.css( 'height', 'auto' );
 
            var perRow = Math.floor( $list.width() / $items.width() );
            if( perRow == null || perRow < 2 ) return true;
 
            for( var i = 0, j = $items.length; i < j; i += perRow )
            {
                var maxHeight   = 0,
                    $row        = $items.slice( i, i + perRow );
 
                $row.each( function()
                {
                    var itemHeight = parseInt( $( this ).outerHeight() );
                    if ( itemHeight > maxHeight ) maxHeight = itemHeight;
                });
                $row.css( 'height', maxHeight );
            }
        };
 
    setHeights();
    $( window ).on( 'resize', setHeights );
 
})( jQuery, window, document );

/*--------------------------------------------------------------
## Archive list show
--------------------------------------------------------------*/
$('.show-archive-button').click(function() {
	$('.archive-list').slideToggle();
	$(this).toggleClass('activated');
});

/*--------------------------------------------------------------
## Smooth scroll to link
--------------------------------------------------------------*/
$('body.home:not(.show-menu) .smooth-scroll').click(function(e) {
	e.preventDefault();

	var target = $(this).attr('href');
	if( $(this).find('a').length )
		target = $(this).find('a').attr('href');

	target = target.replace('/','');

    $('html, body').animate({
        scrollTop: $(target).offset().top
    }, 500);
});


/*--------------------------------------------------------------
## Go to previous page from single page
--------------------------------------------------------------*/
$('.mfp-close').click(function(e) {
	if( ~document.referrer.indexOf('dickdenbraber') // if user comes from the same domain
		&& !/[0-9]{4}/.test(document.referrer) // has no date in it: is not an other post
	) { 
		e.preventDefault();
		history.go(-1); 
	}
});


/*--------------------------------------------------------------
## Typer plugin
--------------------------------------------------------------*/
//create the typing-effect for that element. Add the class 'type-me' to the element and add a new attribute 'data-text="Text here [verwijder]delete this[verwijder]and type this after that"'


$(document).ready(function() {
		$('.type-me').each(function() {
			$(this).type();
		});
});

$.fn.type = function() {
	var $typer = this,
		$typerSpan = this.find('.typed-text'),
		str = $typerSpan.html(),
	    str = ( typeof Hyphenator != 'undefined') ? Hyphenator.hyphenate( str, 'nl') : str,
	    typeSpeed = $typer.attr('data-type-speed') || 1,
	    hasDeletePart = str.match(/\[del\]/);

	$typerSpan.html('').css('display','inline');

	var strParts = hasDeletePart ? str.split(/\[del\]/) : [str, "", ""],
	    isTag,
	    text;

	var stringParts = str.split(/\[del\]/); // split the parts to be deleted at the beginning
	var stringObjects = [];

	stringParts.forEach(function(part) {
		if(part.indexOf("[/del]") >= 0) {
			var parts = part.split(/\[\/del\]/); // split the parts to be deleted in [part to be deleted] and [part to write] (after [/del])
			var deleteThisPart = parts[0];
			stringObjects.push({"type": "write", "part": deleteThisPart}); // first write
			stringObjects.push({"type": "delete", "part": deleteThisPart}); // then delete

			var writeThisPart = parts[1];
			stringObjects.push({"type": "write", "part": writeThisPart});
		} else {
			stringObjects.push({"type": "write", "part": part});
		}
	});

	var addSpeed = 120 * typeSpeed, // speed to add
	    deleteSpeed = 80 * typeSpeed,
	    totalWritingTime = 2000; 

	$(window).scroll(typeTrigger);

	function typeTrigger() {
		if( isInViewport($typer) ){
			$typer.addClass('currentlyTyping');
			type();
			$(window).off("scroll", typeTrigger);
		}
	}
	typeTrigger();

	function type() {
		var counter = 0;
		var textToType = "";
		stringObjects.forEach(function(strObj) {
			if(strObj.type === "write") {
				strObj.part.split('').forEach(function(character, i) {
					speed = addSpeed;
					// Simulate a thinking-process if it's not the first letter of the entire text, but it _is_ the first letter of a part
					if(i === 0 && counter !== 0) {
						speed += 800;
					}

				    // if it's a punctuation character
				    if(character.match(/[.,#!$%\^&\*;:=\-_`~]/)) {
				    	speed += 1000;
				    	character = '<span class="punctuationChar">' + character + '</span>';
				    }

					writeCharacter(character, speed);
				});
			}
			else if(strObj.type === "delete") {
				strObj.part.split('').forEach(function(character, i) {
					speed = deleteSpeed;
					// Simulate a thinking-process if it's about to commence its destructive work.
					if(i === 0) {
						speed += 800;
					}
					deleteCharacter(character, speed);
				});
			} 
			counter += 1;
		});

		function writeCharacter(character, speed) {
			// get random number between the set speed and half of that to create a more natural way of typing
			speed = ~~(Math.random() * speed) + (speed - (speed / 2));
			totalWritingTime += speed;

			// don't type if it's an HTML-tag
		    if( character === '<' ) isTag = true;
		    if( character === '>' ) isTag = false;

			textToType += character;

		    if (!isTag) {
				setTimeout(function(textToType) {
			    	updateText(textToType);
			    }, totalWritingTime, textToType);
			}
		}

		function deleteCharacter(character, speed) {
			textToType = textToType.slice(0, -1);
			// get random number between the set speed and half of that to create a more natural way of typing
			speed = ~~(Math.random() * speed) + (speed - (speed / 2));
			totalWritingTime += speed;

			setTimeout(function(textToType) {
		    	updateText(textToType);
		    }, totalWritingTime, textToType);
		}

		function updateText(writeThis) {
			$typerSpan.html(writeThis);
		}
	}

  // make this a fully functional jQuery function by returning the elementobject
	return this; 
};










/*--------------------------------------------------------------
## Homepage-specific functions
--------------------------------------------------------------*/
//call this function on ready
$(document).ready(addHomepageFunctionality);

function addHomepageFunctionality() {
/*
	//add the published-by carousel to homepage
	(function() {

		$('.published-by__carousel').owlCarousel({
			items: 4,
			loop: true,
			nav: true,
			dotsContainer: false,
			dots: false,
			autoplay: true,
			autoplayTimeout: 3000,
			autoplayHoverPause: true,		
			paginationSpeed:1000,
			pagination: false,
		    smartSpeed: 1000,
	   		navText: ['&#12296;','&#12297;']

		});

	})();
*/

	//add the published-by carousel to homepage
	(function() {
		var $owls = $('.category-display-carousel');
		if( !$owls.length ) 
			return false;

		$owls.each(function() {
			var $owl = $(this);

			$owl.owlCarousel({
				items: 1,
				singleItem: true,
				loop: false,
				nav: false,
				dots: true,
				autoplay: false,
				paginationSpeed: 1000,
				pagination: false,
				autoHeight: true,
				touchDrag: false,
	     		mouseDrag: false
			});

			var $picker = $('.category-picker');
			$picker.click(function() {
				$owl.find('.owl-dot:first-child').trigger('click');

				$picker.removeClass('active');
				$(this).addClass('active');

				var dotToClick = 'last';
				if( $(this).is(':first-child') )
					dotToClick = 'first';
				$owl.find('.owl-dot:' + dotToClick + '-child').trigger('click');
				
				//scroll to top if the top of the element is not in view
				if( $owl[0].getBoundingClientRect().top < 0 ) {

					$('body, html').animate({
						scrollTop: $('.set-into-top-hero').offset().top + $('.set-into-top-hero').outerHeight() - 150
					})
				}
			});
		});



		/*--------------------------------------------------------------
		## Stick to top
		--------------------------------------------------------------*/

		var category_picker_container = document.getElementById('category-picker-container'),
			category_picker_container__width = category_picker_container.offsetWidth,
			$category_picker_container = $(category_picker_container),
			$category_picker = $category_picker_container.find('.category-picker'),

			homepage_main_content = getElByClass('homepage-main-content'),
			homepage_main_content__height = homepage_main_content.offsetHeight,
			sticky_class = 'stick-to-top',
			header = getElByClass('site-header');



		$(window).scroll(changePickerPos);

		function changePickerPos(reset) {
			if( !isInViewport(homepage_main_content) ) {
				category_picker_container.style.display = 'none';
				return false;
			}
			else
				category_picker_container.style.display = '';


			var offset = header.getBoundingClientRect().bottom + 20 + wpadminbarHeight,
				offset = (offset < 20 ? 20 : offset),
				offsetTop = homepage_main_content.getBoundingClientRect().top,
				homepage_main_content__height = homepage_main_content.offsetHeight;

			// if it is at the top and/or needs to be reset
			if( offsetTop > offset || typeof reset != 'undefined' && reset == true ) {
				if( category_picker_container.classList.contains(sticky_class) ){
					category_picker_container.classList.remove(sticky_class, 'hidden-bottom');
					category_picker_container.setAttribute('style','');
					homepage_main_content.style.paddingTop = '0';
					category_picker_container.style.top = '';
				}
			} 
			// if it's at the bottom and needs to be removed
			else if (offsetTop + homepage_main_content__height <= 700) {
				// if it's in the viewport
				if( offsetTop + homepage_main_content__height > 0 ){
					category_picker_container.classList.add(sticky_class)
					category_picker_container.style.top = offset + 'px';
					
					var marginDistance = ( (offsetTop + homepage_main_content__height) ) * ((category_picker_container__width / 2) / 700);
					marginDistance = ((category_picker_container__width / 2) - marginDistance) * 2;

					// remove the element and conserve energy when it's out of the viewport-width
					if( getElByClass('category-picker right').getBoundingClientRect().left > window.outerWidth ) { 
						$category_picker.css('display','none');
						return false;
					} else {
						$category_picker.css('display','');
					}

					$category_picker.css('opacity', ((offsetTop + homepage_main_content__height) / 700));
					$category_picker.first().css('transform', 'translateX(' + -marginDistance + 'px)');
					$category_picker.last().css('transform', 'translateX(' + marginDistance + 'px)');

					category_picker_container.classList.add('hidden-bottom');

				}
			}
			// if it's between the top and bottom and needs to have position: fixed;
			else {
				category_picker_container.classList.add(sticky_class);
				category_picker_container.style.top = offset + 'px';
				if( category_picker_container.classList.contains('hidden-bottom') ){
					$category_picker.css({
						transform: 'none',
						opacity: 1
					});
					category_picker_container.classList.remove('hidden-bottom');
				}
				homepage_main_content.style.paddingTop = category_picker_container.offsetHeight + 20 + 'px'
			}
		}

		/*function changePickerPos(reset) {
			if( !isInViewport(homepage_main_content) )
				category_picker_container.style.display = 'none';
			else
				category_picker_container.style.display = '';

			var offsetTop = homepage_main_content.getBoundingClientRect().top,
				homepage_main_content__height = homepage_main_content.offsetHeight;

			// if it is at the top and/or needs to be reset
			if( offsetTop > offset || typeof reset != 'undefined' && reset == true ) {
				if( category_picker_container.classList.contains(sticky_class) ){
					category_picker_container.classList.remove(sticky_class, 'hidden-bottom');
					category_picker_container.setAttribute('style','');
					homepage_main_content.style.paddingTop = '0';
				}
			} 
			// if it's at the bottom and needs to be removed
			else if (offsetTop + homepage_main_content__height <= 700) {
				// if it's in the viewport
				if( offsetTop + homepage_main_content__height > 0 ){
					category_picker_container.classList.add(sticky_class)
					
					var marginDistance = ( (offsetTop + homepage_main_content__height) ) * ((category_picker_container__width / 2) / 700);
					marginDistance = ((category_picker_container__width / 2) - marginDistance) * 2;

					// remove the element and conserve energy when it's out of the viewport-width
					if( marginDistance > window.outerWidth / 4 ) { // divided by two: two elements. Again for the half of those elements
						$category_picker.css('display','none');
						return false;
					} else {
						$category_picker.css('display','');
					}

					$category_picker.css('opacity', ((offsetTop + homepage_main_content__height) / 700));
					$category_picker.first().css('transform', 'translateX(' + -marginDistance + 'px)');
					$category_picker.last().css('transform', 'translateX(' + marginDistance + 'px)');

					category_picker_container.classList.add('hidden-bottom');

				}
			}
			// if it's between the top and bottom and needs to have position: fixed;
			else {
				category_picker_container.classList.add(sticky_class);
				if( category_picker_container.classList.contains('hidden-bottom') ){
					$category_picker.css({
						transform: 'none',
						opacity: 1
					});
					category_picker_container.classList.remove('hidden-bottom');
				}
				homepage_main_content.style.paddingTop = category_picker_container.offsetHeight + 'px'
			}
		}*/

	})();



	//add the testimonials carousel to homepage
	(function() {
		var $owl = $('.testimonials-carousel');
		$owl.owlCarousel({
			items: 1,
			singleItem: true,
			loop: true,
			nav: true,
			dots: false,
			autoplayTimeout: 15000,
			autoplayHoverPause: true,		
			paginationSpeed:1000,
			pagination: false,
		    smartSpeed: 1000,
	   		navText: ['&#12296;','&#12297;'],
	   		onRefreshed: function() {
	   			$owl.closest('.parallax').parallaxInit();
	   		},
	   		responsive: {
	   			0: {
	   				touchDrag: false,
					autoplay: false,
	   			},
	   			700: {
	   				touchDrag: true,
					autoplay: true
	   			}
	   		}

		});

	})();



}



/*$(function loadMagnificPopup() {

	if(typeof $.magnificPopup == "undefined") {
		return setTimeout(loadMagnificPopup, 100);
	}

	// Add it after jquery.magnific-popup.js and before first initialization code
	$.extend(true, $.magnificPopup.defaults, {
	  tClose: 'Sluiten (Esc)', // Alt text on close button
	  tLoading: 'Wordt geladen...<br>Klik om te annuleren', // Text that is displayed during loading. Can contain %curr% and %total% keys
	  gallery: {
	    tPrev: 'Vorige (Pijltje naar links)', // Alt text on left arrow
	    tNext: 'Volgende (Pijltje naar links)', // Alt text on right arrow
	    tCounter: '%curr% van %total%' // Markup for "1 of 7" counter
	  },
	  image: {
	    tError: '<a href="%url%">De afbeelding</a> kon niet geladen worden.' // Error message when image could not be loaded
	  },
	  ajax: {
	    tError: '<a href="%url%">De inhoud</a> kon niet geladen worden.' // Error message when ajax request failed
	  }
	});


	var originalUrl = window.location.href,
		magnificPopup = null,
		startWindowScroll = 0;
	$('body').magnificPopup({
		delegate: '.ajax-popup-link',
		type: 'ajax',
		fixedBgPos: true,
		fixedContentPos: true,
		zoom: {
	    enabled: true, // By default it's false, so don't forget to enable it

	    duration: 300, // duration of the effect, in milliseconds
	    easing: 'ease-in-out', // CSS transition easing function
	},
	closeBtnInside:true,
	ajax: {
		settings: {
			type: 'POST',
			data: { 
				ajaxRequest: true
			}
		}
	},
	callbacks: {
		open: function() {

			$('.mfp-bg').animate({opacity:1},500);

			History.Adapter.bind(window, 'statechange', closePopup);
			History.pushState({ url: document.location.href }, document.title, this.st.el.attr('href'));
			$(window).on('unload beforeunload', closePopup);

			magnificPopup = this;

		},
		updateStatus: function(data) {

			if(data.status === 'ready') {
				startWindowScroll = $(window).scrollTop();
				$('html').addClass('mfp-helper');

				var $html = $( this.contentContainer );
				$html.find( '.hide' ).css('visibility','hidden');
				$html.find( '.type-me' ).type();
				Hyphenator.hyphenate($html.addClass('hyphenate')[0], 'nl');
			}

		},
		close: cleanupPopup
	}
	});

	function closePopup () {
	    if (magnificPopup != null)
	        magnificPopup.close();
	}
	function cleanupPopup() {
	  	$(window).unbind('statechange', closePopup)
                    .off('unload beforeunload', closePopup);
        var State = History.getState();
        History.replaceState(null, document.title, State.data["url"]);
        magnificPopup = null;

        $('html').removeClass('mfp-helper');
        setTimeout(function(){
          $('body').animate({ scrollTop: startWindowScroll }, 0);
      	}, 0);

		$('.mfp-bg').animate({opacity:.8},500);
	}


});*/



/*--------------------------------------------------------------
## FUNCTIONS
--------------------------------------------------------------*/

//shorthand to get the element by classname
function getElByClass( className ) {

	return document.getElementsByClassName( className )[0]

}

//check if it's a mobile device that's visiting
function isMobile() {
    return $('.is-mobile').css('opacity') == "1"; //is set in CSS Media Queries
}

//see if the element is in the viewport, use either jQuery object or not
function isInViewport( el ) {
	if(el instanceof jQuery)
		el = el[0]

	var elOffset = el.getBoundingClientRect().top;

	return elOffset < vh && elOffset + el.offsetHeight > 0;

}





/**
 * Provides requestAnimationFrame in a cross browser way.
 * @author paulirish / http://paulirish.com/
 */

if ( !window.requestAnimationFrame ) {

	window.requestAnimationFrame = ( function() {

		return window.webkitRequestAnimationFrame ||
		window.mozRequestAnimationFrame ||
		window.oRequestAnimationFrame ||
		window.msRequestAnimationFrame ||
		function( /* function FrameRequestCallback */ callback, /* DOMElement Element */ element ) {

			window.setTimeout( callback, 1000 / 60 );

		};

	} )();

}


function getSupportedTransform() {
    var prefixes = 'transform WebkitTransform MozTransform OTransform msTransform'.split(' ');
    var div = document.createElement('div');
    for(var i = 0; i < prefixes.length; i++) {
        if(div && div.style[prefixes[i]] !== undefined) {
            return prefixes[i];
        }
    }
    return false;
}


/*! @source http://purl.eligrey.com/github/classList.js/blob/master/classList.js*/
;if("document" in self&&!("classList" in document.createElement("_"))){(function(j){"use strict";if(!("Element" in j)){return}var a="classList",f="prototype",m=j.Element[f],b=Object,k=String[f].trim||function(){return this.replace(/^\s+|\s+$/g,"")},c=Array[f].indexOf||function(q){var p=0,o=this.length;for(;p<o;p++){if(p in this&&this[p]===q){return p}}return -1},n=function(o,p){this.name=o;this.code=DOMException[o];this.message=p},g=function(p,o){if(o===""){throw new n("SYNTAX_ERR","An invalid or illegal string was specified")}if(/\s/.test(o)){throw new n("INVALID_CHARACTER_ERR","String contains an invalid character")}return c.call(p,o)},d=function(s){var r=k.call(s.getAttribute("class")||""),q=r?r.split(/\s+/):[],p=0,o=q.length;for(;p<o;p++){this.push(q[p])}this._updateClassName=function(){s.setAttribute("class",this.toString())}},e=d[f]=[],i=function(){return new d(this)};n[f]=Error[f];e.item=function(o){return this[o]||null};e.contains=function(o){o+="";return g(this,o)!==-1};e.add=function(){var s=arguments,r=0,p=s.length,q,o=false;do{q=s[r]+"";if(g(this,q)===-1){this.push(q);o=true}}while(++r<p);if(o){this._updateClassName()}};e.remove=function(){var t=arguments,s=0,p=t.length,r,o=false;do{r=t[s]+"";var q=g(this,r);if(q!==-1){this.splice(q,1);o=true}}while(++s<p);if(o){this._updateClassName()}};e.toggle=function(p,q){p+="";var o=this.contains(p),r=o?q!==true&&"remove":q!==false&&"add";if(r){this[r](p)}return !o};e.toString=function(){return this.join(" ")};if(b.defineProperty){var l={get:i,enumerable:true,configurable:true};try{b.defineProperty(m,a,l)}catch(h){if(h.number===-2146823252){l.enumerable=false;b.defineProperty(m,a,l)}}}else{if(b[f].__defineGetter__){m.__defineGetter__(a,i)}}}(self))};



/*$(window).load(function() {
	$(this).impulse();
})*/

/*
 * LucidScroll
 * 
 * Created by Shikkediel (c) 2013-2015 http://ataredo.com
 * 
 * Version: 2.5.4
 *
 */
//!function(e){e.fn.impulse=function(t){function n(){g=p;var t=Date.now(),i=Math.min(t-O,w),u=i/w,o=e.easing[H[M]](u,i,0,1,w);d.each(function(t){if(!S[t]){var n=B[t]+o*v;e(this).scrollTop(n),a(e(this),t,u)}}),1>u&&!m?I=requestAnimationFrame(n):f=!1}function i(){g=p,d.each(function(t){S[t]||e(this).stop().animate({scrollTop:q[t]},{duration:w,easing:H[M],progress:function(n,i){a(e(this),t,i)},complete:function(){f=!1}})})}function a(e,t,n){100*n>=b.reset&&(E=0),u(e,t)&&(S[t]=!0,C||e.stop(!0,!0),o()&&(m=!0,E=0))}function u(e,t){var n=e.scrollTop(),i=0==n&&q[t]<0,a=n==D[t]&&q[t]>D[t];return i||a}function o(){return S.every(function(e){return e})}function r(){var t,n=e();P.length||(c=!0,P=x),P.each(function(){s(this)?t||(n=C?n.add(Q):n.add(h()),t=!0):n=n.add(e(this))}),b.target=P=n,P.each(function(e){s(this)?T[e]="hub":T[e]="sub"}),c&&x.length!=P.length&&(x=P)}function s(e){var t=[window,document,"HTML","BODY"];return t.indexOf(e)>-1||t.indexOf(e.tagName)>-1}function h(){var t=Q.scrollTop();if(Q.scrollTop(1),e("html").scrollTop())var n=e("html");else n=e(e("body").scrollTop()?"body":"html, body");return t?Q.scrollTop(t):Q.scrollTop(0),n}function l(){P.each(function(t){"hub"==T[t]?j[t]=e(document).hei ë     ë                     ¬Ë             õ×    Žë            Àë    0      Àë                d(!0,{target:e(),delay:!1},e.fn.impulse["default"],t),x=this,P=b.target,T={},j=[],D=[],B=[],S=[],E=0,H=[],q=[];if(window.requestAnimationFrame)var C=!0;return r(),l(),x.each(function(t){e(this).mousewheel(function(a,u){return f?!1:(1==b.delay&&(f=!0),M=t,c&&x.length>1?(d=e(this),D[0]=j[M]):(d=P,D=j),d.each(function(t){var n=B[t]=e(this).scrollTop();1==u&&0==n||-1==u&&n==D0t_    0t_                    ð4`            @·`    ˜t_            Pt_    N      Pt_            ==E?H[M]="curp0À    p0À                    ÀÝ¾             ®~    Ø0À            0À    Ù      0À             À      3´            ),C?(I&&cancelAnimationFrame(I),O=Date.now(),I=requestAnimationFrame(n)):i(),!1))})}),Q.resize(function(){clearTimeout(y),y=setTimeout(l,100)}),this},e.fn.impulse["default"]={range:135,leap:1.35,tempo:500,sloth:1.1,constrain:5,reset:85,effect:"easeOutSine",fluid:!1,propagate:!0}}(jQuery),!function(e){"function"==typeof define&&define.amd?define(["jquery"],e):"object"==typeof exports?module.exports=e:e(jQuery)}(function(e){function t(t){var o=t||window.event,r=s.call(arguments,1),h=0,c=0,f=0,d=0,g=0,m=0;if(t=e.event.fix(o),t.type="mousewheel","detail"in o&&(f=-1*o.detail),"wheelDelta"in o&&(f=o.wheelDelta),"wheelDeltaY"in o&&(f=o.wheelDeltaY),"wheelDeltaX"in o&&(c=-1*o.wheelDeltaX),"axis"in o&&o.axis===o.HORIZONTAL_AXIS&&(c=-1*f,f=0),h=0===f?c:f,"deltaY"in o&&(f=-1*o.deltaY,h=f),"deltaX"in o&&(c=o.deltaX,0===f&&(h=-1*c)),0!==f||0!==c){if(1===o.deltaMode){var p=e.data(this,"mousewheel-line-height");h*=p,f*=p,c*=p}else if(2===o.deltaMode){var M=e.data(this,"mousewheel-page-height");h*=M,f*=M,c*=M}if(d=Math.max(Math.abs(f),Math.abs(c)),(!u||u>d)&&(u=d,i(o,d)&&(u/=40)),i(o,d)&&(h/=40,c/=40,f/=40),h=Math[h>=1?"floor":"ceil"](h/u),c=Math[c>=1?"floor":"ceil"](c/u),f=Math[f>=1?"floor":"ceil"](f/u),l.settings.normalizeOffset&&this.getBoundingClientRect){var v=this.getBoundingClientRect();g=t.clientX-v.left,m=t.clientY-v.top}return t.deltaX=c,t.deltaY=f,t.deltaFactor=u,t.offsetX=g,t.offsetY=m,t.deltaMode=0,r.unshift(t,h,c,f),a&&clearTimeout(a),a=setTimeout(n,200),(e.event.dispatch||e.event.handle).apply(this,r)}}function n(){u=null}function i(e,t){return l.settings.adjustOldDeltas&&"mousewheel"===e.type&&t%120===0}var a,u,o=["wheel","mousewheel","DOMMouseScroll","MozMousePixelScroll"],r="onwheel"in document||document.documentMode>=9?["wheel"]:["mousewheel","DomMouseScroll","MozMousePixelScroll"],s=Array.prototype.slice;if(e.event.fixHooks)for(var h=o.length;h;)e.event.fixHooks[o[--h]]=e.event.mouseHooks;var l=e.event.special.mousewheel={version:"3.1.12",setup:function(){if(this.addEventListener)for(var n=r.length;n;)this.addEventListener(r[--n],t,!1);else this.onmousewheel=t;e.data(this,"mousewheel-line-height",l.getLineHeight(this)),e.data(this,"mousewheel-page-height",l.getPageHeight(this))},teardown:function(){if(this.removeEventListener)for(var n=r.length;n;)this.removeEventListener(r[--n],t,!1);else this.onmousewheel=null;e.removeData(this,"mousewheel-line-height"),e.removeData(this,"mousewheel-page-height")},getLineHeight:function(t){var n=e(t),i=n["offsetParent"in e.fn?"offsetParent":"parent"]();return i.length||(i=e("body")),parseInt(i.css("fontSize"),10)||parseInt(n.css("fontSize"),10)||16},getPageHeight:function(t){return e(t).height()},settings:{adjustOldDeltas:!0,normalizeOffset:!0}};e.fn.extend({mousewheel:function(e){return e?this.bind("mousewheel",e):this.trigger("mousewheel")},unmousewheel:function(e){return this.unbind("mousewheel",e)}})}),jQuery.easing.curve=jQuery.easing.swing,jQuery.extend(jQuery.easing,{"default":"easeOutQuad",swing:function(e,t,n,i,a){return jQuery.easing[jQuery.easing["default"]](e,t,n,i,a)},easeInQuad:function(e,t,n,i,a){return i*(t/=a)*t+n},easeOutQuad:function(e,t,n,i,a){return-i*(t/=a)*(t-2)+n},easeInOutQuad:function(e,t,n,i,a){return(t/=a/2)<1?i/2*t*t+n:-i/2*(--t*(t-2)-1)+n},easeInCubic:function(e,t,n,i,a){return i*(t/=a)*t*t+n},easeOutCubic:function(e,t,n,i,a){return i*((t=t/a-1)*t*t+1)+n},easeInOutCubic:function(e,t,n,i,a){return(t/=a/2)<1?i/2*t*t*t+n:i/2*((t-=2)*t*t+2)+n},easeInQuart:function(e,t,n,i,a){return i*(t/=a)*t*t*t+n},easeOutQuart:function(e,t,n,i,a){return-i*((t=t/a-1)*t*t*t-1)+n},easeInOutQuart:function(e,t,n,i,a){return(t/=a/2)<1?i/2*t*t*t*t+n:-i/2*((t-=2)*t*t*t-2)+n},easeInQuint:function(e,t,n,i,a){return i*(t/=a)*t*t*t*t+n},easeOutQuint:function(e,t,n,i,a){return i*((t=t/a-1)*t*t*t*t+1)+n},easeInOutQuint:function(e,t,n,i,a){return(t/=a/2)<1?i/2*t*t*t*t*t+n:i/2*((t-=2)*t*t*t*t+2)+n},easeInSine:function(e,t,n,i,a){return-i*Math.cos(t/a*(Math.PI/2))+i+n},easeOutSine:function(e,t,n,i,a){return i*Math.sin(t/a*(Math.PI/2))+n},easeInOutSine:function(e,t,n,i,a){return-i/2*(Math.cos(Math.PI*t/a)-1)+n},easeInExpo:function(e,t,n,i,a){return 0==t?n:i*Math.pow(2,10*(t/a-1))+n},easeOutExpo:function(e,t,n,i,a){return t==a?n+i:i*(-Math.pow(2,-10*t/a)+1)+n},easeInOutExpo:function(e,t,n,i,a){return 0==t?n:t==a?n+i:(t/=a/2)<1?i/2*Math.pow(2,10*(t-1))+n:i/2*(-Math.pow(2,-10*--t)+2)+n},easeInCirc:function(e,t,n,i,a){return-i*(Math.sqrt(1-(t/=a)*t)-1)+n},easeOutCirc:function(e,t,n,i,a){return i*Math.sqrt(1-(t=t/a-1)*t)+n},easeInOutCirc:function(e,t,n,i,a){return(t/=a/2)<1?-i/2*(Math.sqrt(1-t*t)-1)+n:i/2*(Math.sqrt(1-(t-=2)*t)+1)+n},easeInElastic:function(e,t,n,i,a){var u=1.70158,o=0,r=i;if(0==t)return n;if(1==(t/=a))return n+i;if(o||(o=.3*a),r<Math.abs(i)){r=i;var u=o/4}else var u=o/(2*Math.PI)*Math.asin(i/r);return-(r*Math.pow(2,10*(t-=1))*Math.sin(2*(t*a-u)*Math.PI/o))+n},easeOutElastic:function(e,t,n,i,a){var u=1.70158,o=0,r=i;if(0==t)return n;if(1==(t/=a))return n+i;if(o||(o=.3*a),r<Math.abs(i)){r=i;var u=o/4}else var u=o/(2*Math.PI)*Math.asin(i/r);return r*Math.pow(2,-10*t)*Math.sin(2*(t*a-u)*Math.PI/o)+i+n},easeInOutElastic:function(e,t,n,i,a){var u=1.70158,o=0,r=i;if(0==t)return n;if(2==(t/=a/2))return n+i;if(o||(o=.3*a*1.5),r<Math.abs(i)){r=i;var u=o/4}else var u=o/(2*Math.PI)*Math.asin(i/r);return 1>t?-.5*r*Math.pow(2,10*(t-=1))*Math.sin(2*(t*a-u)*Math.PI/o)+n:r*Math.pow(2,-10*(t-=1))*Math.sin(2*(t*a-u)*Math.PI/o)*.5+i+n},easeInBack:function(e,t,n,i,a,u){return void 0==u&&(u=1.70158),i*(t/=a)*t*((u+1)*t-u)+n},easeOutBack:function(e,t,n,i,a,u){return void 0==u&&(u=1.70158),i*((t=t/a-1)*t*((u+1)*t+u)+1)+n},easeInOutBack:function(e,t,n,i,a,u){return void 0==u&&(u=1.70158),(t/=a/2)<1?i/2*t*t*(((u*=1.525)+1)*t-u)+n:i/2*((t-=2)*t*(((u*=1.525)+1)*t+u)+2)+n},easeInBounce:function(e,t,n,i,a){return i-jQuery.easing.easeOutBounce(e,a-t,0,i,a)+n},easeOutBounce:function(e,t,n,i,a){return(t/=a)<1/2.75?7.5625*i*t*t+n:2/2.75>t?i*(7.5625*(t-=1.5/2.75)*t+.75)+n:2.5/2.75>t?i*(7.5625*(t-=2.25/2.75)*t+.9375)+n:i*(7.5625*(t-=2.625/2.75)*t+.984375)+n},easeInOutBounce:function(e,t,n,i,a){return a/2>t?.5*jQuery.easing.easeInBounce(e,2*t,0,i,a)+n:.5*jQuery.easing.easeOutBounce(e,2*t-a,0,i,a)+.5*i+n}});