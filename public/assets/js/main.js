const appUrl = $('meta[name="route"]').attr('content');

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

	$('a[href="#"]').attr('href', appUrl+'/#');
	$('a[href="#about-section"]').attr('href', appUrl+'/#about-section');
  $('a[href="#promo-section"]').attr('href', appUrl+'/#promo-section');
  $('a[href="#produk-section"]').attr('href', appUrl+'/#produk-section');
  $('a[href="#kontak-section"]').attr('href', appUrl+'/#kontak-section');

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
})

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
	let no_telp = $('#input-regis-telepon').val();
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
			pesanAlert(data);
		},
		error: function () {
				notif({msg: '<b style="color: white;">Connection Error!</b>', type: "error", position: "center"});
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
					pesanAlert(data);
					setTimeout(function () { window.location.reload() }, 2000);
				}
				else {
					pesanAlert(data);
				}
			},
			error: function () {
					notif({msg: '<b style="color: white;">Connection Error!</b>', type: "error", position: "center"});
			},
			complete: function () {
					$('#login-modal').modal('hide');
					EnableBtn('#input-login-submit','Login');
			}
		});
});

//logout Function
$('#logout-btn').click(function (e) {
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
					pesanAlert(data);
					setTimeout(function () { window.location.reload() }, 2000);
				}
				else {
					pesanAlert(data);
				}
			},
			error: function () {
				notif({msg: '<b style="color: white;">Connection Error!</b>', type: "error", position: "center"});
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

function show_pass_regis() {
	var x = document.getElementById("input-regis-password");
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