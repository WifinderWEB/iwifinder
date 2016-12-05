$(function () {
    $('header nav.main .submenu > ul > li > a').hover(function () {
        $('header nav.main .submenu li').removeClass('active');
        if ($(this).next().hasClass('submenu2')) {
            $(this).parent().addClass('active');
        }
    });
    $('body').on('click', '.readmore', function () {
        if ($(this).parent().find('.moreinfo').length) $(this).parent().find('.moreinfo').toggle(200);
        else $(this).parent().parent().find('.moreinfo').toggle(200);
        $(this).find('span').toggle(200);
    });
    $('section.catalog article .panel .view span').click(function () {
        $(this).addClass('active').siblings().removeClass('active');
        $('#productlist').removeClass().addClass($(this).attr('rel'));
    });
    if ($('section.catalog article .panel .sort select, select.css, section.register select').length) {
        $('section.catalog article .panel .sort select, select.css, section.register select').each(function () {
            var div = '<div class="input select">';
            div += '<span>' + $(this).find('option[value="' + $(this).val() + '"]').html() + '</span><ul>';
            $(this).find('option').each(function () {
                div += '<li data-value="' + $(this).val() + '" ' + ($(this).prop('selected') ? 'class="selected"' : '') + '>' + $(this).html() + '</li>';
            });
            div += '</ul></div>';
            $(this).hide().after(div);
        });

        $('div.input.select > span').click(function () {
            $(this).parent().find('ul').toggle(200);
        });


        $('div.input.select li').click(function () {
            $(this).addClass('selected').siblings().removeClass('selected');
            $(this).parents('div.select').prev().val($(this).data('value')).trigger('change');
            $(this).parents('div.input.select').find('span').html($(this).html());
            $(this).parent().toggle(200);
        });
    }
    //Диапазон в фильтре
    $('section.catalog aside .line_range').each(function () {
        var slider = this;
        var aside = $(slider).closest('div.filter');

        noUiSlider.create(slider, {
            start: [$(slider).data('current-min'), $(slider).data('current-max')],
            step: $(slider).data('step') || 1,
            connect: true,
            range: {
                'min': $(slider).data('min'),
                'max': $(slider).data('max')
            }
        });

        if ($(slider).data('current-min') == $(slider).data('current-max')) {
            $(slider).attr('disabled', ' disabled');
        }

        slider.noUiSlider.on('change', function (values, handle) {
            if (handle) {
                $('input.range.max', aside).trigger('change');
            } else {
                $('input.range.min', aside).trigger('change');
            }
        })

        slider.noUiSlider.on('update', function (values, handle) {
            if (handle) {
                $('input.range.max', aside).val(isNaN(parseFloat(values[handle])) ? '' : parseFloat(values[handle]));
            } else {
                $('input.range.min', aside).val(isNaN(parseFloat(values[handle])) ? '' : parseFloat(values[handle]));
            }
        });

        $('input.range', aside).change(function () {
            slider.noUiSlider.set([$(this).parent().find('input.min').val(), $(this).parent().find('input.max').val()]);
        });
    })

    //Разукрашиваем chechbox И radio
    if ($('input[type="checkbox"], input[type="radio"]').length) {
        $('input[type="checkbox"], input[type="radio"]').not('.hidden').each(function () {
            $(this).hide().after('<span class="input checkbox ' + ($(this).prop('checked') ? 'checked' : '') + '" rel="' + $(this).attr('type') + $(this).attr('name') + '"></span>');
        });
    }

    $('body').on('change', 'input[type="checkbox"], input[type="radio"]', function () {
        if ($(this).attr('type') == 'radio') {
            $(this).parents('form').find('span.input.checkbox[rel="' + $(this).attr('type') + $(this).attr('name') + '"]').removeClass('checked');
        }

        if ($(this).is(":checked")) $(this).next().addClass('checked');
        else $(this).next().removeClass('checked');
    });

    //активируем клики
    $('body').on('click', 'span.input.checkbox', function (e) {
        if (!$(this).parents('label').length) $(this).prev().trigger('click');//trigger change not work with radio
    });

    //раскрытие фильтра в каталоге
    $('section.catalog aside .filter .name').not('[data-one="1"]').click(function () {
        $(this).parents('.filter').find('.block').toggle(200);
        $(this).parents('.filter').toggleClass('active');
    });

    var sleepsearch;
    $("header .dop-menu input[name='q']").on("input", function (e) {
        if (!sleepsearch) {
            sleepsearch = 1;
            setTimeout(function () {
                var input = $("header .dop-menu input[name='q']");
                var q = input.val();
                if (q.length > 2) {
                    $("#search_results").show();
                    input.parents('form').addClass('active');
                    $.ajax({
                        url: "/app_dev.php/api/frontend/search/",
                        global: false,
                        data: {query: q},
                        success: function (data) {
                            console.log(data);
                            $('#search_results .wrap').html('');
                            if (data.count == 0) {
                                $('#search_results .wrap').html('<p>Сожалеем, но ничего не найдено.</p>');
                            } else {
                                var count = 5;
                                if (data.content.length < 5)
                                    count = data.content.length;
                                for (var i = 0; i <= count - 1; i++) {
                                    var li = '<li>' +
                                        '<a target="_blank" href="/catalog/' + data.content[i].id + '/' + data.content[i].alias + '">' +
                                        '<span class="img">' +
                                        '<img src="//' + data.content[i].image_path + '" alt="">' +
                                        '</span>' +
                                        data.content[i].title +
                                        '</a>' +
                                        '</li>';

                                    $('#search_results .wrap').append(li);
                                }

                                $('#search_results .wrap').append('<li class="view_all"><a href="/products/search?query=' + data.query + '">Остальные результаты</a></li>');
                            }
                        }
                    });
                } else {
                    $("#search_results").hide();
                    input.parents('form').removeClass('active');
                }
                sleepsearch = 0;
            }, 1000);
        }
    });

    $("#search_results .close").on('click', function () {
        $('#search_results').hide();
        $(this).parents('form').removeClass('active');
    });


    if ($.isFunction($.fancybox)) $('.fancybox').fancybox();

    $('body').on('click', '#product_delivery .delivery li .title .map, .pvz_block .map', function () {


        $('.deliveryMaps table td.' + $(this).attr('rel')).trigger('click');

        //popup_window('<div id="pvzmap"></div>',$(this).data('address').split('||').join('. '));//замена || на .
        //pvz_map('pvzmap', $(this).data('address').split('||'));

    });

    $('body').on('click', '.pvz_block .map', function () {
        popup_window('<div id="pvzmap"></div>', $(this).data('address').split('||').join('. '));//замена || на .
        pvz_map('pvzmap', $(this).data('address').split('||'));
    });

    //Пользовательская панель в футере
    $(window).scroll(function () {
        if ($('body').hasClass('mobile')) return;

        $('section.user-panel,section.scroll-top')['fade' + ($(this).scrollTop() > 400 ? 'In' : 'Out')](500);
        if ($('.main-slider').length) $('a.sale_bnr')['fade' + ($(this).scrollTop() > 800 ? 'In' : 'Out')](500);
        else if (!$('.sale_banner').length) $('a.sale_bnr')['fade' + ($(this).scrollTop() > 400 ? 'In' : 'Out')](500);
        if ($('#product_menu').length) $('#product_menu')[($(this).scrollTop() >= (!$('#product_menu').hasClass('fixed') ? $('#product_menu').offset().top : $('#product_menu + section').offset().top) ? 'add' : 'remove') + 'Class']('fixed');
    });

    //back to top
    $('section.scroll-top ul li .scrolltotop').click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 800);
        return false;
    });


    $('.help-info').click(function () {
        $(this).toggleClass('active');
    });

    //popup окно с видео из ютуба

    if ($('.popup_youtube').length) {

        $('body').on('click', '.popup_youtube', function () {
            $(this).attr('href', $(this).attr('rel'));
        });

        $('.popup_youtube')
            .fancybox({
                'width': '75%',
                'height': '75%',
                'autoScale': false,
                'transitionIn': 'none',
                'transitionOut': 'none',
                'type': 'iframe'
            });
    }

    if ($("img.lazy").length) $("img.lazy").lazyload();
    $('header nav.main > ul > li').hover(
        function () {
            $(this).find("img.lazy").each(function () {
                $(this).attr("src", $(this).data("original"));
            });
        }
    );

    $('section.product-list ul').each(function () {
        if (!$(this).parents('.wrap-mobile-slider, .slick-slider').length) $(this).wrap('<div class="wrap-mobile-slider">');
    });

    if ($('section.product-list .wrap-mobile-slider ul').length) $('section.product-list .wrap-mobile-slider ul').addClass('mobile-slick').slick({
        infinite: true,
        speed: 700,
        slidesToShow: 2,
        variableWidth: true
    });


    if ($.isFunction($().slick)) {

        $(".slider ul.s.auto").slick({
            lazyLoad: 'ondemand',
            dots: true,
            speed: 700,
            autoplay: true,
            autoplaySpeed: 5000,
            pauseOnHover: false
        });

        $(".slider ul.s").slick({
            lazyLoad: 'ondemand',
            dots: true,
            speed: 700,
        });

        $(".slider ul.multi").slick({
            lazyLoad: 'ondemand',
            infinite: true,
            speed: 700,
            slidesToShow: 8,
            variableWidth: true
        });

        $(".slider ul.multi4").slick({
            lazyLoad: 'ondemand',
            infinite: true,
            speed: 700,
            slidesToShow: 4,
            variableWidth: true
        });

        //ptcab, sonar
        if ($('section.bnrs + section.products-hit, section.slider + section.novelty').length) {
            $('section.bnrs + section.products-hit ul, section.slider + section.novelty ul').addClass('mobile-slick').slick({
                infinite: true,
                speed: 700,
                slidesToShow: 2,
                variableWidth: true
            });
        }
    }
    if ($('.quicksand-contents li').length) {
        $('.quicksand-contents li').each(function (i) {
            $(this).attr('data-id', 'tab' + i);
        });
        $('.quicksand-tabs li:first-child').addClass('active');
        $('.quicksand-contents .block.show').each(function (i) {
            $(this).clone().removeClass('show').insertAfter($(this));

        });
        $('.quicksand-tabs li').on('click', function (e) {
            if ($(this).hasClass('active')) return false;

            var tab = $(this);

            tab.addClass('active').siblings().removeClass('active');

            tab.parents('.quicksand-wrapper').find('.quicksand-contents .block.show ul').quicksand(
                tab.parents('.quicksand-wrapper').find('.quicksand-contents li.tag-' + tab.data('tag')),
                {
                    duration: 1000,
                    useScaling: true,
                    adjustHeight: false
                }
            );

            e.preventDefault();
        });
    }

    $('input[name="accept_rules"]').click(function () {
        if ($('input[name="accept_rules"]').is(":checked")) {
            if (!$('input[name="register_submit_button"]').is(":visible")) {
                $('.confirm').removeClass('error');
                $('input[name="register_submit_button"]').show()
            }
        } else {
            if ($('input[name="register_submit_button"]').is(":visible")) {
                $('input[name="register_submit_button"]').hide()
            }
        }
    });
    $("#fos_user_registration_form_phone").mask("+7 (999) 999-9999");
    $('form[name="fos_user_registration_form"]').submit(function(){
        if (!$('input[name="accept_rules"]').is(":checked")) {
            $('.confirm').addClass('error');
            return false;
        }
    });
});

// /*!
//  * Start Bootstrap - Agnecy Bootstrap Theme (http://startbootstrap.com)
//  * Code licensed under the Apache License v2.0.
//  * For details, see http://www.apache.org/licenses/LICENSE-2.0.
//  */
//
// // jQuery for page scrolling feature - requires jQuery Easing plugin
// $(function() {
//     $('a.page-scroll').bind('click', function(event) {
//         var $anchor = $(this);
//         $('html, body').stop().animate({
//             scrollTop: $($anchor.attr('href')).offset().top - 70
//         }, 1000, 'easeInOutExpo');
//         event.preventDefault();
//     });
// });
//
// // Highlight the top nav as scrolling occurs
// $('body').scrollspy({
//     target: '.navbar',
//     offset: 75
// })
//
// // Closes the Responsive Menu on Menu Item Click
// $('.navbar-collapse ul li a').click(function() {
//     $('.navbar-toggle:visible').click();
// });
//
// $('.dropdown').click(function (e) {
// 	if (!$('#cartInfo .dropdown-menu').is(e.target) && $('#cartInfo .dropdown-menu').has(e.target).length != 0 && $('.open').has(e.target).length != 0) {
// 		return false;
// 	}
// })
//
// /* Header Toggle Animation
//  * Partially inspired by codrops
//  * http://www.codrops.com
//  */
// var cbpAnimatedHeader = (function() {
// 	var docElem = document.documentElement,
// 		header = $('.navbar-default'),
// 		didScroll = false,
// 		changeHeaderOn = 100;
//
// 	function init() {
// 		window.addEventListener('scroll', function( event ) {
// 			if(!didScroll) {
// 				didScroll = true;
// 				setTimeout( scrollPage, 150 );
// 			}
// 		}, false);
// 	}
//
// 	function scrollPage() {
// 		var sy = scrollY();
// 		if (sy >= changeHeaderOn) {
// 			header.addClass('navbar-shrink');
//
// 			setTimeout(function() {
// 				header.addClass('navbar-shrink-scroll');
// 			},300)
// 		}
// 		else {
// 			header.removeClass('navbar-shrink');
// 			header.removeClass('navbar-shrink-scroll');
// 			setTimeout(function() { // redundancy check
// 				header.removeClass('navbar-shrink-scroll');
// 			},300)
// 		}
// 		didScroll = false;
// 	}
//
// 	function scrollY() {
// 		return window.pageYOffset || docElem.scrollTop;
// 	}
//
// 	init();
//
// })();