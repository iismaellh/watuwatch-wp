/* global */
(function( $ ) {
	var isMobile = '';
	if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) && 'ontouchstart' in document.documentElement)
	{
		isMobile = true;
	}
	else
	{
		isMobile = false;
	}

	function watuDetectIE() {
		var ua = window.navigator.userAgent;

    var msie = ua.indexOf('MSIE ');
    if (msie > 0) {
        // IE 10 or older => return version number
        return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
    }

    var trident = ua.indexOf('Trident/');
    if (trident > 0) {
        // IE 11 => return version number
        var rv = ua.indexOf('rv:');
        return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
    }

    var edge = ua.indexOf('Edge/');
    if (edge > 0) {
       // Edge (IE 12+) => return version number
       return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10);
    }

    // other browser
    return false;
	}

	function watuLightboxInit() {
		[].forEach.call($('a[data-show-id]'), function(elem) {
			elem.onclick = basicLightbox.create('<div class="movie-trailer"><a>Close Trailer</a><iframe src="https://www.youtube.com/embed/' + $(elem).data('trailer') + '" width="720" height="444" frameborder="0"></iframe></div>',
				{
		    beforeShow: function(elem) {
		        elem.element().querySelector('a').onclick = elem.close
		    }
			}).show
		})
	}

	function watuLazyLoad() {
		$('img[data-src], .real').each(function() {
			var img = $(this);
			var src = img.attr('data-src');
			
			img.attr('src', src);
			img.on('load', function() {
				img.removeAttr('data-src');
				img.prev('.placeholder').css('opacity', 0);
			});
		});
	}

	function watuLimitText(selector, limit) {
		$(selector).text(function(index, currentText) {
	  		return currentText.split(' ', limit).join(' ') + '..';
		});
	}

	function watuCalculateRatio(selector) {
		$(selector).each(function() {
			var width = $(this).innerWidth();
			$(this).css('height', width * 9 / 16);
		})
	}

	var swipercredits = new Swiper(".swiper-year-selector", {
			freeMode: true,
			grabCursor: true,
			scrollbarHide: false,
			scrollbarDraggable: true,
			spaceBetween: 2,
			slidesPerView: 12,
			effect: 'slide' // 'slide' or 'fade' or 'cube' or 'coverflow' or 'flip'
	});

	var swipetitle = [];

	$('.swiper-container-discover .swiper-slide').each(function() {
			var title = $(this).attr('title');
			swipetitle.push(title);
	})

	var swiperdiscover = new Swiper('.swiper-container-discover', {
				effect: 'fade',
				pagination: {
					el: '.swiper-pagination',
					clickable: true,
				},
				autoplay: {
	        delay: 7000,
	        disableOnInteraction: false,
	    	},
	});

	// get query parameters
	$(window).on('resize', function() {
		watuCalculateRatio('.landscape');
	});

	$(window).trigger('resize');

	$('.swiper-container-discover .swiper-slide').on('click', function() {
		link = $(this).find('.swiper-link').attr('href');
		window.location.href = link;
	});

	// get query parameters
	function watuGetQueryParams(qs) {
    qs = qs.split("+").join(" ");
    var params = {},
        tokens,
        re = /[?&]?([^=]+)=([^&]*)/g;

    while (tokens = re.exec(qs)) {
        params[decodeURIComponent(tokens[1])]
            = decodeURIComponent(tokens[2]);
    }
    return params;
	}

	// set the poster size
	function watuPosterSize() {
		var query   = watuGetQueryParams(window.location.search);
		var wrapper = $('.movie-container-wrapper');

		if( query['size'] == 'small' ) {
			return false;
		}

		if( query['size'] == 'medium' ) {
			wrapper.removeClass('display-small');
			wrapper.addClass('display-medium');
			wrapper.find('.movie-item:nth-child(6n-5)').addClass('first');
			wrapper.find('.movie-item:nth-child(6n)').addClass('last');
		}

		if( query['size'] == 'large' ) {
			wrapper.removeClass('display-small');
			wrapper.addClass('display-large');
			wrapper.find('.movie-item:nth-child(3n-2)').addClass('first');
			wrapper.find('.movie-item:nth-child(3n)').addClass('last');
		}

		return false;
	}

	watuPosterSize();

	// detect if the browser support flex or grid
	function watuDetectGridSupport() {
		var wrapper = $('.movie-container-wrapper');

		if( wrapper.css('display') == 'block' ) {
			wrapper.addClass('flex');
			if( wrapper.hasClass('display-small' ) ) {
				wrapper.find('.movie-item:nth-child(10n-9)').addClass('first');
				wrapper.find('.movie-item:nth-child(10n)').addClass('last');
			}

			return true;
		}
	}

	watuDetectGridSupport();

	// remove empty key from object
	function watuCleanObj(obj) {
	  var propNames = Object.getOwnPropertyNames(obj);
	  for (var i = 0; i < propNames.length; i++) {
	    var propName = propNames[i];
	    if (obj[propName] === null || obj[propName] === undefined || obj[propName] === '') {
	      delete obj[propName];
	    }
	  }
	}

	// query films
	$('.dropdown-menu li a, .swiper-year-selector a, .filter-page').on('click', function(e) {
	  e.preventDefault();
		var genre, sort, size, year, paged, params, query, parameters, redirect;
    genre   = $(this).data('genre');
		sort    = $(this).data('sort');
		size    = $(this).data('size');
		release = $(this).data('release');
		paged   = $(this).data('paged');
		query   = location.search.substring(1);

		if ( query == '' ) {
			params = {'genre' : null, 'sort_by' : null, 'size' : null, 'release' : null, 'paged' : null};
		} else {
			params = JSON.parse('{"' + decodeURI(query).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}')
		}

		if( $(this).hasClass('genre') && params['genre'] != genre ) {
			params['genre'] = genre;
		}

		if( $(this).hasClass('sortby') && params['sort_by'] != sort ) {
			params['sort_by'] = sort;
		}

		if( $(this).hasClass('size') && params['size'] != size ) {
			params['size'] = size;
		}

		if( $(this).hasClass('release') && params['release'] != release ) {
			params['release'] = release;
		}

		if( $(this).hasClass('next') && params['paged'] != paged ) {
			params['paged'] = paged + 1;
		}

		if( $(this).hasClass('prev') && params['paged'] != paged ) {
			params['paged'] = paged - 1;
		}

		watuCleanObj(params);

		parameters = $.param(params);

		redirect = window.location.pathname.replace(/\/+(page)+\/+([\d]|\d\d)+\//g, "") + '?' + parameters;

		if (e.target.className === 'size') {
			redirect = window.location.pathname + '?' + parameters;
		}

		window.location.href = redirect;
	});

	function watuCapitalize(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
	}

	function watuRenderFilmsQuery() {
		var query  = location.search.substring(1);

		if ( query == '' || query == undefined ) return;

		$('.movie-query .current').css('display', 'inline');
		var	params = JSON.parse('{"' + decodeURI(query).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');

		for(var key in params) {
			var paramStr = key;
			var keyStr   = params[key];
			var dataStr  = '';
			var value = params[key];

			switch (key) {
				case ('size'):
					dataStr = 'data-size';
					paramStr = 'display';
					break;
				case ('release'):
					dataStr = 'data-release';
					paramStr = 'released';
					break;
				case ('genre'):
					dataStr = 'data-genre';
					break;
				case ('sort'):
					dataStr = 'data-sort';
					break;
				case ('paged'):
					dataStr = 'data-paged';
					paramStr = 'Page';
					break;
				default:
			}

			switch (keyStr) {
				case ('28'):
					value = 'Action';
					break;
				case ('12'):
					value = 'Adventure';
					break;
				case ('16'):
					value = 'Animation';
					break;
				case ('35'):
					value = 'Comedy';
					break;
				case ('80'):
					value = 'Crime';
					break;
				case ('99'):
					value = 'Documentary';
					break;
				case ('18'):
					value = 'Drama';
					break;
				case ('10751'):
					value = 'Family';
					break;
				case ('14'):
					value = 'Fantasy';
					break;
				case ('36'):
					value = 'History';
					break;
				case ('27'):
					value = 'Horror';
					break;
				case ('10402'):
					value = 'Music';
					break;
				case ('9648'):
					value = 'Mystery';
					break;
				case ('10749'):
					value = 'Romance';
					break;
				case ('878'):
					value = 'Science Fiction';
					break;
				case ('10770'):
					value = 'TV Movie';
				case ('37'):
					value = 'Western';
					break;
				case ('10752'):
					value = 'War';
					break;
				case ('53'):
					value = 'Thriller';
					break;
				case ('popularity.desc'):
					value = 'Most Popular';
					break;
				case ('popularity.asc'):
					value = 'Least Popular';
					break;
				case ('release_date.desc'):
					value = 'Latest';
					break;
				case ('release_date.asc'):
					value = 'Oldest';
					break;
				case ('revenue.asc'):
					value = 'Highest Grossing';
					break;
				case ('vote_average.desc'):
					value = 'Highest Vote Avg';
					break;
				case ('vote_average.asc'):
					value = 'Lowest Vote Avg';
					break;
				case ('vote_count.desc'):
					value = 'Highest Vote Count';
					break;
				case ('vote_count.asc'):
					value = 'Lowest Vote Count';
					break;
				default:
			}

			$('.movie-param-current[data-type='+ key +']').html(watuCapitalize(value));

			$('<a title="Click to remove" href="#" class="movie-param" data-type="' + key + '">' + watuCapitalize(paramStr.replace('_', ' ')) + ': ' + watuCapitalize(value) + '</a>').appendTo('.movie-query');
		}
	}

	$('.remove-filters').on('click', function() {
		window.location.href = $(this).attr('href');
	});

	function watuRemoveParam(obj, ObjKey) {
		for(var key in obj) {
			if (key === ObjKey) {
				delete obj[key];
			}
		}
	}

	$('.movie-query').on('click', '.movie-param', function(e) {
		e.preventDefault();
		var query  = location.search.substring(1);
		console.log(query);
		if ( query == '' || query == undefined ) return;
		var	params = JSON.parse('{"' + decodeURI(query).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');

		watuRemoveParam(params, $(this).data('type'));
		parameters = $.param(params);

		window.location.href = window.location.pathname + '?' + parameters;
	});

	$('.back').on('click', function() {
		console.log(window.location.pathname.indexOf('/page/'));
		var query  = location.search.substring(1);

		if ( ( query == '' || query == undefined ) && window.location.pathname.indexOf('/page/') == 0) return;

		if ( query !== '' ) {
			var	params = JSON.parse('{"' + decodeURI(query).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');
			watuRemoveParam(params, 'sort_by');
			parameters = '?' + $.param(params);
		} else {
			parameters = '';
		}

		window.location.href = window.location.pathname.replace(/\/+(page)+\/+([\d]|\d\d)+\//g, "") + parameters;
	});

	function watuNoMovie() {
		var query  = location.search.substring(1);
		if ( query == '' || query == undefined ) return;
		var	params = JSON.parse('{"' + decodeURI(query).replace(/"/g, '\\"').replace(/&/g, '","').replace(/=/g,'":"') + '"}');

		watuRemoveParam(params, 'sort_by');
		parameters = $.param(params);

		if( !$.trim( $('.movie-container-wrapper').html() ).length ) {
			var message  = '<h3>I\'m sorry! Movies not found...</h3>';
					message += '<p>Please refine the filter or wait for 7 seconds then we\'ll redirect you automatically.</p>';

			$('.movie-pagination').css('display', 'none');
			$('.movie-container-wrapper').addClass('nomovie');
			$(message).appendTo('.movie-container-wrapper');

			setTimeout(function() {
				window.location.href = window.location.pathname.replace(/\/+(page)+\/+([\d]|\d\d)+\//g, "") + '?' + parameters;
			}, 7000);
		}
	}

	function watuMoreContent() {
		var showChar, ellipsestext, moretext, lesstext;

    showChar = 250;
    ellipsestext = "...";
    moretext = "more";
    lesstext = "less";

    $('.more').each(function() {
      var content = $(this).html();

      var c = content.substr(0, showChar);

      var html = c + '<span class="moreellipses">' + ellipsestext+ '&nbsp;</span><a href="" class="morelink">' + moretext + '</a></span>';
			var modal = content + '<a href="" class="closelink">close</a></span>';

      $(this).html(html);
			$(this).append('<div class="moremodal"></div>');
			$("body").prepend('<div class="modalbg"></div>');
			$('.moremodal').html(modal);
			$('.moremodal').append('<span class="moremodalclose">x</span>');
    });

    $(".morelink").click(function() {
			$('.modalbg').toggle();
      $('.moremodal').addClass('slidemodal');
      return false;
    });

		$(".closelink, .moremodalclose, .modalbg").click(function() {
			$('.modalbg').toggle();
      $('.moremodal').removeClass('slidemodal');
      return false;
    });
	}

	function watuMoreTooltip() {
		var ie, count, moretext, lesstext;

		ie = watuDetectIE();

		if(ie) return;

		count = 20;
		moretext = "display all casts...";
		lesstext = "less casts...";

		$('#casts').each(function() {
				var content, show = [], hide = [], c, h;
				content = $(this).find('.tooltip-toggle');

				if(content.length > count) {
					c = content.slice(0, count);
					h = content.slice(count, content.length);

					c.map(function(index, value) {
						show.push(value);
					});

					h.map(function(index, value) {
						hide.push(value);
					});

					var html = '<span class="moreellipses"></span><span class="morecontent"><span></span>&nbsp;&nbsp;<a href="" class="morecast">' + moretext + '</a></span>';

					$(this).html(html);

					$(show).insertBefore('.moreellipses');
					$(hide).appendTo('.morecontent span');
				}
		});

		$(".morecast").click(function(){
				if($(this).hasClass("less")) {
						$(this).removeClass("less");
						$(this).html(moretext);
				} else {
						$(this).addClass("less");
						$(this).html(lesstext);
				}
				$(this).parent().prev().toggle();
				$(this).prev().toggle();
				return false;
		});
	}

	// fire on document ready.
	$( document ).ready( function() {
		watuMoreTooltip();

		watuMoreContent();

		watuNoMovie();

		watuRenderFilmsQuery();

		$('.dropdown-toggle').dropdown();

		$('.tooltip-toggle').tooltip({
			trigger: 'hover'
		});

		watuLazyLoad();

		watuLightboxInit();
		
		//creates ajax search
        new $.AviaAjaxSearch({scope:'#masthead'});
	});

	$.AviaAjaxSearch  =  function(options)
	{
	   var defaults = {
            delay: 300,                //delay in ms until the user stops typing.
            minChars: 3,               //dont start searching before we got at least that much characters
            scope: 'body'

        };

        this.options = $.extend({}, defaults, options);
        this.scope   = $(this.options.scope);
        this.timer   = false;
        this.lastVal = "";
		
        this.bind_events();
	};


	$.AviaAjaxSearch.prototype =
    {
        bind_events: function()
        {
            this.scope.on('keyup', '#s:not(".av_disable_ajax_search #s")' , $.proxy( this.try_search, this));
        },

        try_search: function(e)
        {
            clearTimeout(this.timer);

            //only execute search if chars are at least "minChars" and search differs from last one
            if(e.currentTarget.value.length >= this.options.minChars && this.lastVal != $.trim(e.currentTarget.value))
            {
                //wait at least "delay" miliseconds to execute ajax. if user types again during that time dont execute
                this.timer = setTimeout($.proxy( this.do_search, this, e), this.options.delay);
            }
        },

        do_search: function(e)
        {
            var obj          = this,
                currentField = $(e.currentTarget).attr( "autocomplete", "off" ),
                form         = currentField.parents('form:eq(0)'),
                results      = form.find('.ajax_search_response'),
                loading      = $('<div class="ajax_load"><span class="ajax_load_inner"></span></div>'),
                action 		 = form.attr('action'),
                values       = form.serialize();
                values      += '&action=avia_ajax_search';

           	//check if the form got get parameters applied and also apply them
           	if(action.indexOf('?') != -1)
           	{
           		action  = action.split('?');
           		values += "&" + action[1];
           	}

            if(!results.length) results = $('<div class="ajax_search_response"></div>').appendTo(form);

            //return if we already hit a no result and user is still typing
            if(results.find('.ajax_not_found').length && e.currentTarget.value.indexOf(this.lastVal) != -1) return;

            this.lastVal = e.currentTarget.value;

            $.ajax({
				url: avia_framework_globals.ajaxurl,
				type: "POST",
				data:values,
				beforeSend: function()
				{
					loading.insertAfter(currentField);
				},
				success: function(response)
				{
				    if(response == 0) response = "";
                    results.html(response);
				},
				complete: function()
				{
				    loading.remove();
				}
			});
        }
    };
})( jQuery );
