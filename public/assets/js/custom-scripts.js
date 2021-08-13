$(document).ready(function(){
  'use strict';

  if ($(window).width() >= 500) {
    $('.delet').show();
    $('#promo-tag').hide();
    $('#promo-price').hide();
  }

  // Start Fungsi Set Harga Sewa Mobile Phone //
  if ($(window).width() < 500) { 
    $('.alert-carousel').hide();
    $('.top-spac70 .container').css("padding","0 25px");
    $('.harga > span').css("color","#FF4848");
    $('.delet').hide();
    $('#promo-tag').show();
    $('#promo-price').show();
    $('.harga').append("<span style='font-weight: lighter; font-size: 16px;'>Deskripsi &nbsp; <i class='fa fa-chevron-down' style='color: #2A93F6;' aria-hidden='true'></i></span>");
    $('.srv-dtl-inr .col-md-5 p').css("text-align", "justify");
  }
  if ($(window).width() <= 425) {
    $('#promo-tag').css("margin-left","50px");
  }
  if ($(window).width() <= 407)  {
    $('#promo-tag').css("margin-left","25px");
  }
  if ($(window).width() <= 383)  {
    $('#promo-tag').css("margin-left","15px");
  }
  if ($(window).width() <= 373)  {
    $('#promo-tag').css("margin-left","0");
  }
  $( window ).resize(function() {
    if ($(window).width() > 500) {
      location.reload();
    }
    if ($(window).width() <= 425) {
      $('#promo-tag').css("margin-left","50px");
    }
    if ($(window).width() <= 407)  {
      $('#promo-tag').css("margin-left","25px");
    }
    if ($(window).width() <= 383)  {
      $('#promo-tag').css("margin-left","15px");
    }
    if ($(window).width() <= 373)  {
      $('#promo-tag').css("margin-left","0");
    }
  });
  // End Fungsi Set Harga Sewa Mobile Phone //

  //===== Delay Anmiation =====// 
  var drop = $('.prtfl-inf > *, .prtfl-inf2 > *, .prtfl-inf4 > *, .prtfl-inf5 > *, .tem-inf4 > *');
  $('.prtfl-inf, .prtfl-inf2, .prtfl-inf4, .prtfl-inf5, .tem-inf4').each(function () {
    var delay = 0;
    $(this).find(drop).each(function () {
      $(this).css({ transitionDelay: delay + 'ms' });
      delay += 150;
    });
  });
  var drop2 = $('.mnu-cntr > ul > li')
  $('.mnu-cntr > ul').each(function () {
    var delay2 = 0;
    $(this).find(drop2).each(function () {
      $(this).css({ transitionDelay: delay2 + 'ms' });
      delay2 += 50;
    });
  });

  //===== Wow Animation Setting =====//
  var wow = new WOW({
    boxClass: 'wow',      // default
    animateClass: 'animated', // default
    offset: 0,          // default
    mobile: true,       // default
    live: true        // default
  });

  wow.init();

  //===== TypeIt =====//
  if ($('.explr-inf > h4 span').length) {
    new TypeIt('.explr-inf > h4 span', {
      strings: ['Silungkang Venue'],
      speed: 200,
      breakLines: false,
      loop: true,
      waitUntilVisible: true
    }).go();
  }

  //===== Circliful =====//
  if ($.isFunction($.fn.circliful)) {
    $("#fnct-prg1").circliful({
      animation: 1,
      animationStep: 5,
      foregroundBorderWidth: 8,
      backgroundBorderWidth: 8,
      percent: 65,
      textSize: 27,
      textStyle: 'font-size: 12px;',
      textColor: '#fff',
      backgroundColor: '#fff',
      foregroundColor: '#833dcc'
    });

    $("#fnct-prg2").circliful({
      animation: 1,
      animationStep: 5,
      foregroundBorderWidth: 8,
      backgroundBorderWidth: 8,
      percent: 75,
      textSize: 27,
      textStyle: 'font-size: 12px;',
      textColor: '#fff',
      backgroundColor: '#fff',
      foregroundColor: '#833dcc'
    });

    $("#dsgn-dvpmnt-prg1").circliful({
      animation: 1,
      animationStep: 5,
      foregroundBorderWidth: 3,
      backgroundBorderWidth: 3,
      percent: 50,
      textSize: 22,
      textStyle: 'font-size: 12px;',
      textColor: '#fff',
      backgroundColor: '#fff',
      foregroundColor: '#833dcc'
    });

    $("#dsgn-dvpmnt-prg2").circliful({
      animation: 1,
      animationStep: 5,
      foregroundBorderWidth: 3,
      backgroundBorderWidth: 3,
      percent: 65,
      textSize: 22,
      textStyle: 'font-size: 12px;',
      textColor: '#fff',
      backgroundColor: '#fff',
      foregroundColor: '#833dcc'
    });

    $("#dsgn-dvpmnt-prg3").circliful({
      animation: 1,
      animationStep: 5,
      foregroundBorderWidth: 3,
      backgroundBorderWidth: 3,
      percent: 80,
      textSize: 22,
      textStyle: 'font-size: 12px;',
      textColor: '#fff',
      backgroundColor: '#fff',
      foregroundColor: '#833dcc'
    });
  }

  //===== Search Script =====//
  $('.srch-btn').on('click',function(){
    $('.hdr-srch-bx').addClass('active');
    return false;
  });

  $('.srch-cls-btn').on('click',function(){
    $('.hdr-srch-bx').removeClass('active');
    return false;
  });

  //===== Menu Script =====//
  $('.mnu-btn').on('click', function () {
    $(this).toggleClass('active');
    $('.mnu-wrp').toggleClass('fadin');
    $('html').toggleClass('mnu-actv');
    return false;
  });

  //===== Responsive Header =====//
  $('.rspn-mnu-btn').on('click', function () {
    $('.rsnp-mnu').addClass('slidein');
    return false;
  });
  $('.rspn-mnu-cls').on('click', function () {
    $('.rsnp-mnu').removeClass('slidein');
    return false;
  });
  $('.rsnp-mnu li.menu-item-has-children > a').on('click', function () {
    $(this).parent().siblings().children('ul').slideUp();
    $(this).parent().siblings().removeClass('active');
    $(this).parent().children('ul').slideToggle();
    $(this).parent().toggleClass('active');
    return false;
  });

  //===== Counter Up =====//
  if ($.isFunction($.fn.counterUp)) {
    $('.counter').counterUp({
      delay: 10,
      time: 2000
    });
  }

  //===== Scrollbar =====//
  if ($('.res-menu, .mnu-wrp, .mnu-cntr > ul ul.children').length > 0) {
    var ps = new PerfectScrollbar('.res-menu, .mnu-wrp, .mnu-cntr > ul ul.children');
  }
  if ($('.mnu-wrp').length > 0) {
    var ps = new PerfectScrollbar('.mnu-wrp');
  }
  if ($('.mnu-cntr > ul ul.children').length > 0) {
    var ps = new PerfectScrollbar('.mnu-cntr > ul ul.children');
  }

  //===== LightBox =====//
  if ($.isFunction($.fn.fancybox)) {
    $('[data-fancybox],[data-fancybox="gallery"],[data-fancybox="gallery2"]').fancybox({});
  }

  // //===== Select =====//
  // if ($('select').length > 0) {
  //   $('select').selectpicker();
  // }

  //===== TouchSpin =====//
  if ($.isFunction($.fn.TouchSpin)) {
    $('.quantity > input').TouchSpin();
  }

  //===== Count Down =====//
  if ($.isFunction($.fn.downCount)) {
    $('.countdown').downCount({
      date: '12/12/2019 12:00:00',
      offset: +5
    });
  }

  //===== Accordion =====//
  $('#tgl1 .tgl-cnt').hide();
  $('#tgl1 h4:first').addClass('active').next().slideDown(500).parent().addClass('activate');
  $('#tgl1 h4').on('click', function() {
    if ($(this).next().is(':hidden')) {
      $('#tgl1 h4').removeClass('active').next().slideUp(500).parent().removeClass('activate');
      $(this).toggleClass('active').next().slideDown(500).parent().toggleClass('activate');
    }
  });

  //===== Owl Carousel =====//
  if ($.isFunction($.fn.owlCarousel)) {

    //=== Testimonials Carousel ===//
    $('.testi-car').owlCarousel({
      autoplay: true,
      smartSpeed: 1500,
      loop: true,
      items: 1,
      dots: true,
      slideSpeed: 15000,
      autoplayHoverPause: true,
      nav: false,
      margin: 30,
      animateIn: 'fadeIn',
      animateOut: 'fadeOut',
      navText: [
      "<i class='fa fa-angle-up'></i>",
      "<i class='fa fa-angle-down'></i>"
      ]
    });

    //=== Team Carousel ===//
    $('.tem-car').owlCarousel({
      autoplay: true,
      smartSpeed: 1500,
      loop: true,
      items: 1,
      dots: true,
      slideSpeed: 15000,
      autoplayHoverPause: true,
      nav: false,
      margin: 10,
      navText: [
      "<i class='fa fa-arrow-left'></i>",
      "<i class='fa fa-arrow-right'></i>"
      ],
      responsive:{
        0:{items: 1,},
        481:{items: 2},
        770:{items: 2},
        981:{items: 2},
        1025:{items: 2},
        1200:{items: 2}
      }
    });

    //=== Widget Gallery Carousel ===//
    $('.wdgt-gal-car').owlCarousel({
      autoplay: true,
      smartSpeed: 1500,
      loop: true,
      items: 1,
      dots: true,
      slideSpeed: 5000,
      autoplayHoverPause: true,
      nav: false,
      margin: 0,
      animateIn: 'fadeIn',
      animateOut: 'fadeOut',
      navText: [
      "<i class='fa fa-angle-left'></i>",
      "<i class='fa fa-angle-right'></i>"
      ]
    });

    //=== Team Carousel 2 ===//
    $('.tem-car2').owlCarousel({
      autoplay: true,
      smartSpeed: 1500,
      loop: true,
      items: 1,
      dots: false,
      slideSpeed: 5000,
      autoplayHoverPause: true,
      nav: true,
      margin: 30,
      animateIn: 'fadeIn',
      animateOut: 'fadeOut',
      navText: [
      "<span><i class='fa fa-long-arrow-left'></i>Previous</span>",
      "<span>Next<i class='fa fa-long-arrow-right'></i></span>"
      ]
    });

    //=== Team Carousel 3 ===//
    $('.tem-car3').owlCarousel({
      autoplay: true,
      smartSpeed: 1500,
      loop: true,
      items: 3,
      dots: true,
      slideSpeed: 5000,
      autoplayHoverPause: true,
      nav: false,
      margin: 2,
      navText: [
      "<i class='fa fa-angle-left'></i>",
      "<i class='fa fa-angle-right'></i>"
      ],
      responsive:{
        0:{items: 1},
        481:{items: 2},
        770:{items: 3},
        981:{items: 3},
        1025:{items: 3},
        1200:{items: 3}
      }
    });

    //=== Team Carousel 4 ===//
    $('.tem-car4').owlCarousel({
      autoplay: true,
      smartSpeed: 1500,
      loop: true,
      items: 4,
      dots: false,
      slideSpeed: 5000,
      autoplayHoverPause: true,
      nav: false,
      margin: 0,
      navText: [
      "<i class='fa fa-angle-left'></i>",
      "<i class='fa fa-angle-right'></i>"
      ],
      responsive:{
        0:{items: 1},
        481:{items: 2},
        770:{items: 3},
        981:{items: 3},
        1025:{items: 4},
        1200:{items: 4}
      }
    });

    //=== Sponsors Carousel ===//
    $('.spnsr-car').owlCarousel({
      autoplay: true,
      smartSpeed: 1500,
      loop: true,
      items: 5,
      dots: false,
      slideSpeed: 2000,
      autoplayHoverPause: true,
      nav: false,
      margin: 0,
      animateIn: 'fadeIn',
      animateOut: 'fadeOut',
      navText: [
      "<i class='fa fa-arrow-left'></i>",
      "<i class='fa fa-arrow-right'></i>"
      ],
      responsive:{
        0:{items: 2},
        481:{items: 3},
        770:{items: 3},
        981:{items: 4},
        1025:{items: 5},
        1200:{items: 5}
      }
    });

    //=== Company Features Carousel ===//
    $('.cmp-feat-car').owlCarousel({
      autoplay: true,
      smartSpeed: 1500,
      loop: true,
      items: 1,
      dots: true,
      slideSpeed: 5000,
      autoplayHoverPause: true,
      nav: false,
      margin: 30,
      animateIn: 'fadeIn',
      animateOut: 'fadeOut',
      navText: [
      "<span><i class='fa fa-long-arrow-left'></i>Previous</span>",
      "<span>Next<i class='fa fa-long-arrow-right'></i></span>"
      ]
    });

    //=== Team Carousel 5 ===//
    $('.tem-car5').owlCarousel({
      autoplay: true,
      smartSpeed: 1500,
      loop: true,
      items: 4,
      dots: true,
      slideSpeed: 5000,
      autoplayHoverPause: true,
      nav: false,
      margin: 5,
      navText: [
      "<i class='fa fa-angle-left'></i>",
      "<i class='fa fa-angle-right'></i>"
      ],
      responsive:{
        0:{items: 1},
        481:{items: 2},
        770:{items: 3},
        981:{items: 3},
        1025:{items: 4},
        1200:{items: 4}
      }
    });

    //=== Service Images Carousel ===//
    $('.srv-dtl-img-car').owlCarousel({
      autoplay: true,
      smartSpeed: 1500,
      loop: true,
      items: 1,
      dots: false,
      slideSpeed: 5000,
      autoplayHoverPause: true,
      nav: true,
      margin: 0,
      animateIn: 'fadeIn',
      animateOut: 'fadeOut',
      navText: [
      "<i class='fa fa-angle-left'></i>",
      "<i class='fa fa-angle-right'></i>"
      ]
    });

    //=== Portfolio Carousel ===//
    $('.prtfl-car').owlCarousel({
      autoplay: true,
      smartSpeed: 1500,
      loop: true,
      items: 3,
      dots: false,
      slideSpeed: 5000,
      autoplayHoverPause: true,
      nav: true,
      margin: 30,
      navText: [
      "<i class='fa fa-angle-left'></i>",
      "<i class='fa fa-angle-right'></i>"
      ],
      responsive:{
        0:{items: 1},
        481:{items: 2},
        770:{items: 2},
        981:{items: 3},
        1025:{items: 3},
        1200:{items: 3}
      }
    });
    

  }

  //===== Slick Carousel =====//
  if ($.isFunction($.fn.slick)) {        
    //=== Portfolio Detail Image Carousel ===//
    $('.prtfl-dtl-car').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      dots: false,
      arrows: true,
      centerMode: true,
      centerPadding: '400px',
      focusOnSelect: true,
      infinite: true,
      prevArrow: '<div class="slick-prev slick-arrows"><i class="fa fa-chevron-left"></i></div>',
      nextArrow: '<div class="slick-next slick-arrows"><i class="fa fa-chevron-right"></i></div>',
      responsive: [
      {
        breakpoint: 1370,
        settings: {
          centerPadding: '250px',
        }
      },
      {
        breakpoint: 990,
        settings: {
          centerPadding: '200px',
        }
      },
      {
        breakpoint: 770,
        settings: {
          centerPadding: '100px',
        }
      },
      {
        breakpoint: 490,
        settings: {
          centerPadding: '50px',
        }
      }
      ]
    });
  }

});//===== Document Ready Ends =====//


$(window).on('load',function(){
  'use strict';

  var menu_height = $('header').innerHeight();
  if ($('header').hasClass('stick')) {
    $('main').css({'padding-top': menu_height});
  }

  if ($('header').hasClass('stick style3')) {
    $('main').css({'padding-top': 40});
  }

  var menu_height2 = $('.menu-wrp3').innerHeight();
  if ($('header').hasClass('stick style3')) {
    $('.mnu-hgt-pad').css({'padding-top': menu_height2});
  }

  if ($('header').hasClass('stick style4')) {
    $('main').css({'padding-top': 0});
  }

  if ($('header').hasClass('stick style5')) {
    $('main').css({'padding-top': 0});
  }

  if ($('header').hasClass('stick style7')) {
    $('main').css({'padding-top': 0});
  }

  if ($('header').hasClass('stick style8')) {
    $('main').css({'padding-top': 0});
  }

  if ($('header').hasClass('stick style9')) {
    $('main').css({'padding-top': 0});
  }

  if ($('header').hasClass('stick style10')) {
    $('main').css({'padding-top': 0});
  }

  if ($('div.paddlrt60 header').hasClass('stick style11')) {
    $('main').css({'padding-top': 0});
  }

  if ($('header').hasClass('stick style11')) {
    $('div.paddlrt60:first-child').css({'padding-top': menu_height + 60});
  }

  //===== Isotope =====//
  if (jQuery('.fltr-itm').length > 0) {
    if (jQuery().isotope) {
      var jQuerycontainer = jQuery('.masonry, .msonry'); // cache container
      var jQuerycontainer2 = jQuery('.msonry2'); // cache container
      jQuerycontainer.isotope({
        itemSelector: '.fltr-itm',
        columnWidth: 1,
        layoutMode: 'fitRows',
      });
      jQuerycontainer2.isotope({
        itemSelector: '.fltr-itm',
        columnWidth: 1
      });
      jQuery('.fltr-btns a, .fltr-lnks a').click(function () {
        var selector = jQuery(this).attr('data-filter');
        jQuery('.fltr-btns li, .fltr-lnks li').removeClass('active');
        jQuery(this).parent().addClass('active');
        jQuerycontainer.isotope({ filter: selector });
        jQuerycontainer2.isotope({ filter: selector });
        return false;
      });
      jQuerycontainer.isotope('layout'); // layout/layout
      jQuerycontainer2.isotope('layout'); // layout/layout
    }

    jQuery(window).resize(function () {
      if (jQuery().isotope) {
        jQuery('.masonry, .msonry, .msonry2').isotope('layout'); // layout/relayout on window resize
      }
    });
  }
});//===== Window onLoad Ends =====//

//===== Sticky Header =====//
$(window).on('scroll',function () {
  'use strict';

  var menu_height3 = $('.sticky-header').innerHeight();
  var scroll = $(window).scrollTop();
  if (scroll >= menu_height3) {
    $('body').addClass('sticky-active');
  } else {
    $('body').removeClass('sticky-active');
  }
});//===== Window onScroll Ends =====//

$('.rspn-login-btn').click(function() {
  $('#regis-modal').modal('hide');
  $('#cek-modal').modal('hide');
  $('#sewa-modal').modal('hide');
  $('.rsnp-mnu').removeClass('slidein');
  return false;
});

$('.rspn-regis-btn').click(function() {
  $('#login-modal').modal('hide');
  $('#cek-modal').modal('hide');
  $('#sewa-modal').modal('hide');
  $('#beli-modal').modal('hide');
  $('.rsnp-mnu').removeClass('slidein');
  return false;
});

$('.login-btn').click(function() {
  $('#regis-modal').modal('hide');
  $('#cek-modal').modal('hide');
  $('#sewa-modal').modal('hide');
  $('#beli-modal').modal('hide');
});

$('.regis-btn').click(function() {
  $('#login-modal').modal('hide');
  $('#cek-modal').modal('hide');
  $('#sewa-modal').modal('hide');
  $('#beli-modal').modal('hide');
});

window.onresize = function(event){
  if($(window).width() <= 992){
    $('.ftco-section').hide();
    $('#tetap').hide();
  }else if($(window).width() > 992){
    $('.ftco-section').show();
  }

  var scrollTop = $(window).scrollTop();
  if ( scrollTop > 900 && $(window).width() > 992) { 
		$('#tetap').show();
		$('.ftco-section').hide();
		// $('.fly-high').css('margin-top', '0');
		console.log($('.ftco-section').offset().top);
		console.log(scrollTop);
	}else if (scrollTop < 900 && $(window).width() > 992) {
		$('#tetap').hide();
		$('.ftco-section').show();
		// $('.fly-high').css('margin-top', '50');
	}
};