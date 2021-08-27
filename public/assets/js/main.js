const appUrl = $('meta[name="route"]').attr('content');
var notifAlign = "bottom";
(function($) {

	"use strict";

	var fullHeight = function() {

		$('.js-fullheight').css('height', $(window).height());
		$(window).resize(function(){
			$('.js-fullheight').css('height', $(window).height());
		});

	};
	fullHeight();

	var carousel = function() {
		$('.home-slider').owlCarousel({
	    loop:true,
	    autoplay: true,
	    margin:0,
	    animateOut: 'fadeOut',
	    animateIn: 'fadeIn',
	    nav:true,
	    dots: true,
	    autoplayHoverPause: false,
	    items: 1,
	    navText : ["<span class='ion-ios-arrow-back'></span>","<span class='ion-ios-arrow-forward'></span>"],
	    responsive:{
	      0:{
	        items:1
	      },
	      600:{
	        items:1
	      },
	      1000:{
	        items:1
	      }
	    }
		});

	};
	carousel();

	var venueList = function() {
		let venueList = $('#venue-list');
		$.ajax({
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			type: "GET",
			url: appUrl+'/VenueGetList',
			contentType: 'application/json',
			dataType: 'json',
			success: function (data) {
				try {
					if (data.code == null) {
						if (data.length > 0) {
							let rowHtml = '';
							for (let i = 0; i < data.length; i++) {
								rowHtml = 
								'<div class="pst-box-styl3 lst '+ ((i%2) !== 0 ? '' : 'rev') +'">' +
									'<div class="pst-thmb-styl3">' +
										'<a href="'+appUrl+'/venue/'+data[i].vnu_id+'" title="" itemprop="url"><img src="data:image/'+data[i].vp_filename.split('.').pop()+';base64,'+data[i].vp_photo+'" alt="'+data[i].vp_filename+'" itemprop="image">'+
										(data[i].vnu_status_tersedia == 1 ? '' : '<h3 class="position-absolute top-50 start-50 translate-middle text-thumb"><span>Coming Soon</span></h3>') +
										'</a>' +
									'</div>' +
									'<div class="pst-inf-styl3">' +
										'<span>'+ (data[i].vnu_tipe_waktu == 0 ? 'Gedung' : 'Ruangan') +'</span>' +
										'<h4 itemprop="headline"><a href="'+appUrl+'/venue/'+data[i].vnu_id+'" title="" itemprop="url">'+data[i].vnu_nama+'</a></h4>' +
										'<p itemprop="description">'+data[i].vnu_desc+'</p>' +
										'<a class="fa fa-arrow-right" href="'+appUrl+'/venue/'+data[i].vnu_id+'" title="" itemprop="url"></a>' +
									'</div>' +
								'</div>';
								venueList.append(rowHtml);
							}
						}
						else { throw new Error(); }
					} else { throw new Error(); }
				} catch (error) {
					$('#venue-section').hide();
				}
			},
			error: function () {
				notif({msg: '<b style="color: white;">Connection Error!</b>', type: "error", position: notifAlign});
				$('#venue-section').hide();
			}
		});
	};
	var productList = function () {
		let pdctList = $('#pdct-list');
		$.ajax({
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			type: "GET",
			url: appUrl+'/ProductGetList',
			contentType: 'application/json',
			dataType: 'json',
			success: function (data) {
				try {
					if (data.code == null) {
						if (data.length > 0) {
							$('#produk-all > i').text(data.length);
							$('#produk-songket > i').text(data.filter(d => d.pdct_kategori_id == 1).length);
							$('#produk-hiasan > i').text(data.filter(d => d.pdct_kategori_id == 2).length);
							$('#produk-batik > i').text(data.filter(d => d.pdct_kategori_id == 3).length);
							$('#produk-food > i').text(data.filter(d => d.pdct_kategori_id == 4).length);
							$('#produk-lainnya > i').text(data.filter(d => d.pdct_kategori_id == 5).length);
							let rowHtml = '';
							for (let i = 0; i < data.length; i++) {
								let imgSrc = 'data:image/'+data[i].pp_filename.split('.').pop()+';base64,'+data[i].pp_photo;
								rowHtml = 
								'<div class="col-md-3 col-sm-6 col-lg-3 fltr-itm fltr-itm'+data[i].pdct_kategori_id+'">' +
								'<div class="prtfl-box6">' +
									'<div class="prtfl-thmb6" style="height: 15rem;">' +
										'<img src="'+imgSrc+'" alt="'+data[i].pp_filename+'" itemprop="image">' +
										'<div class="prtfl-btns">' +
											'<a href="'+imgSrc+'" data-fancybox="gallery-produk" title="" itemprop="url"><i class="fa fa-search"></i></a>' +
											'<a href="'+appUrl+'/product/'+data[i].pdct_id+'" title="" itemprop="url"><i class="fa fa-link"></i></a>' +
										'</div>' +
									'</div>' +
									'<div class="prtfl-inf6 text-left">' +
										'<h4 itemprop="headline"><a href="'+appUrl+'/product/'+data[i].pdct_id+'" title="" itemprop="url">'+data[i].pdct_nama+'</a></h4>' +
											'<span>'+data[i].pdct_kategori_nama+'</span>' +
										'</div>' +
									'</div>' +
								'</div>';
								pdctList.append(rowHtml);
							}
						}
						else { throw new Error(); }
					} else { throw new Error(); }
				} catch (error) {
					$('#produk-section').hide();
				}
			},
			error: function () {
				notif({msg: '<b style="color: white;">Connection Error!</b>', type: "error", position: notifAlign});
				$('#produk-section').hide();
			}
		});
	};
	if (window.location.href == appUrl ||
			window.location.href === appUrl+'/' ||
			window.location.href === appUrl+'/#' ||
			window.location.href === appUrl+'/#about-section' ||
			window.location.href === appUrl+'/#promo-section' ||
			window.location.href === appUrl+'/#venue-section' ||
			window.location.href === appUrl+'/#produk-section' ||
			window.location.href === appUrl+'/#kontak-section') {
		venueList();
		productList();
	}

	$('a[href="#"]').attr('href', appUrl+'/#');
	$('a[href="#about-section"]').attr('href', appUrl+'/#about-section');
	$('a[href="#promo-section"]').attr('href', appUrl+'/#promo-section');
	$('a[href="#venue-section"]').attr('href', appUrl+'/#venue-section');
  $('a[href="#produk-section"]').attr('href', appUrl+'/#produk-section');
	$('a[href="#kontak-section"]').attr('href', appUrl+'/#kontak-section');
	
	if ($(window).width() > 500) {
		notifAlign = "center";
	}

})(jQuery);

$('#tetap').hide();

$(window).scroll(function() {
	var scrollTop = $(window).scrollTop();
	if ( scrollTop > 900 && $(window).width() > 992) { 
		$('#tetap').show();
		$('.ftco-section').hide();
	}else if (scrollTop < 900 && $(window).width() > 992) {
		$('#tetap').hide();
		$('.ftco-section').show();
	}
});

$('.modal').on('shown.bs.modal', function (e) {
	if ($(window).width() < 450) { 
		$('#respon-header').hide();
	}
});

//modal on close
$(".modal").on("hidden.bs.modal", function () {
	if ($(window).width() < 450) { 
		$('#respon-header').show();
	}

	var x = document.getElementById("input-login-password");
	var y = document.getElementById("input-regis-password");
	if (x.type === "text"||y.type === "text") {
		x.type = "password";
		$('input + label + a > .fa').addClass("fa-eye-slash");
		$('input + label + a > .fa').removeClass("fa-eye");
	}

	$(this).find('form').trigger('reset');
});

//Registration
$('#regis-form').submit(function (e) {
	e.preventDefault();
	DisableBtn('#input-regis-submit');
	if (!validatePass($('#input-regis-password').val(), $('#input-regis-confirm-password').val())) {
		notif({msg: '<b style="color: white;">Password dan Confirm Password tidak sama!</b>', type: "error", position: notifAlign});
		EnableBtn('#input-regis-submit','Sign Up');
		return false;
	}
	let no_telp = $('#input-regis-telepon').val();
	if (no_telp.match('/[A-Za-z]/')) {
    notif({
      msg: '<b style="color: white;">Nomor telpon tidak benar!</b>',
      type: "error",
      position: notifAlign
    });
    EnableBtn('#input-regis-submit','Sign Up');
    return false;
  }
	var formData = new FormData();
	formData.append("nama", $('#input-regis-nama').val());
	formData.append("email", $('#input-regis-email').val());
	formData.append("password", $('#input-regis-password').val());
	formData.append("alamat", $('#input-regis-alamat').val());
	formData.append("no_telp", no_telp.charAt(0) === '0' ? no_telp : '+62'+no_telp);

	$.ajax({
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		type: $('#regis-form').attr('method'),
		url: appUrl+'/Customer/signup',
		data: formData,
		processData: false,
		contentType: false,
		success: function (data) {
			pesanAlert(data, notifAlign);
		},
		error: function () {
				notif({msg: '<b style="color: white;">Connection Error!</b>', type: "error", position: notifAlign});
		},
		complete: function () {
				$('#regis-modal').modal('hide');
				EnableBtn('#input-regis-submit','Sign Up');
		}
	});
});

//login Function
$("#login-form").submit(function(e) {
		e.preventDefault();
		DisableBtn('#input-login-submit');
		var formData = new FormData();
		formData.append("email",$('#input-login-email').val());
		formData.append("password",$('#input-login-password').val());

		$.ajax({
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			type: $('#regis-form').attr('method'),
			url: appUrl+'/Customer/signin',
			data: formData,
			processData: false,
			contentType: false,
			success: function (data) {
				if (data.code == 1) {
					$('#login-modal').modal('hide');
					pesanAlert(data, notifAlign);
					setTimeout(function () { window.location.reload() }, 1500);
				}
				else {
					pesanAlert(data);
					EnableBtn('#input-login-submit','Login');
				}
			},
			error: function () {
					notif({msg: '<b style="color: white;">Connection Error!</b>', type: "error", position: notifAlign});
			}
		});
});

//logout Function
$('#logout-btn').click(function (e) {
	e.preventDefault();
	logout();
});
$('#logout-btn-mobile').click(function (e) {
	e.preventDefault();
	logout();
});

function logout() {
	let konfirmasi = confirm("Yakin ingin logout?");
	if (konfirmasi) {
		$.ajax({
			headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
			type: 'POST',
			url: appUrl+'/Customer/signout',
			data: null,
			processData: false,
			contentType: false,
			success: function (data) {
				if (data.code == 1) {
					pesanAlert(data, notifAlign);
					$('#respon-menu').removeClass('slidein');
					setTimeout(function () { window.location.href = '/' }, 1500);
				}
				else {
					pesanAlert(data);
				}
			},
			error: function () {
				notif({msg: '<b style="color: white;">Connection Error!</b>', type: "error", position: notifAlign});
			}
		});
	}
}

function show_pass_login() {
	var x = document.getElementById("input-login-password");
	if (x.type === "password") {
		x.type = "text";
		$('input + label + a > .fa').removeClass("fa-eye-slash");
		$('input + label + a > .fa').addClass("fa-eye");
	}else if (x.type === "text") {
		x.type = "password";
		$('input + label + a > .fa').addClass("fa-eye-slash");
		$('input + label + a > .fa').removeClass("fa-eye");
	}
};

function show_pass_regis(idselector) {
	var x = document.getElementById(idselector);
	if (x.type === "password") {
		x.type = "text";
		$('#'+idselector+' + label + a > .fa').removeClass("fa-eye-slash");
		$('#'+idselector+' + label + a > .fa').addClass("fa-eye");
	}else if (x.type === "text") {
		x.type = "password";
		$('#'+idselector+' + label + a > .fa').addClass("fa-eye-slash");
		$('#'+idselector+' + label + a > .fa').removeClass("fa-eye");
	}
};

$('.nav-link').click(function () {
	let parent = $(this).parent();
	$('.nav-item').removeClass('active');
	parent.addClass('active');
});

function DisableBtn(selector) {
	$(selector).prop('disabled', true);
	$(selector).text('Tunggu ...');
}

function EnableBtn(selector, btnName = '') {
	$(selector).prop('disabled', false);
	$(selector).text(btnName);
}

var validatePass = (pass, cpass) => (pass === cpass);