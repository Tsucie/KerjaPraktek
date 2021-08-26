$(document).ready(function () {
  getCsReview(parseInt($('#pdct_id').val()));
  $('.nav-item').removeClass('active');
  $('a[href="'+appUrl+'/#produk-section"]').parent().addClass('active');

  $('#order-btn').click(function (e) {
    e.preventDefault();
    if (isNaN(parseInt($('#form-beli input[id="cst_id"]').val()))) {
      notif({
        msg: '<b style="color: white;">Harap Login terlebih dahulu sebelum order!</b>',
        type: "warning",
        position: notifAlign
      });
      $('#login-modal').modal('show');
    }
    else {
      $('#beli-modal').modal('show');
    }
  });

  $('#input-beli-submit').click(function (e) {
    e.preventDefault();
    createOrd('#form-beli');
  });

  $('#rvw-btn').click(function (e) {
    e.preventDefault();
    getMyReview($('#rvw-btn').attr('data-email'),parseInt($('#rvw-btn').attr('data-id')));
  });

  $('#form-feedback').submit(function (e) {
    e.preventDefault();
    addReview('#form-feedback');
  });

  $('#input-feedback-update').click(function (e) {
    e.preventDefault();
    updateReview('#form-feedback');
  });
});

//-- Order API --//
function createOrd(obj) {
  let id = parseInt($('#cst_id').val());
  DisableBtn('#input-beli-submit');
  if (!csValidation(id)) {
    notif({ msg: '<b style="color: white;">Harap Login terlebih dahulu sebelum order!</b>',type: "warning",position: notifAlign});
    $('#beli-modal').modal('hide');
		EnableBtn('#input-beli-submit','Pesan');
    return false;
  }
  let qty = parseInt($(obj+' input[id="input-beli-jumlah"]').val());
  if (isNaN(qty) || qty < 1) {
    notif({msg: '<b style="color: white;">Jumlah produk tidak benar!</b>',type: "error",position: notifAlign});
    EnableBtn('#input-beli-submit','Pesan');
    return false;
  }
  let no_telp = $(obj+' input[id="input-beli-telepon"]').val();
  if (no_telp.match('/[A-Za-z]/')) {
    notif({msg: '<b style="color: white;">Nomor telpon tidak benar!</b>',type: "error",position: notifAlign});
    EnableBtn('#input-beli-submit','Pesan');
    return false;
  }
  while (no_telp.includes('-')) {
    no_telp = no_telp.replace('-','');
  }
  var formData = new FormData();
  formData.append("cst_id", id);
  formData.append("no_telp", no_telp.charAt(0) === '0' ? no_telp : no_telp.charAt(0) === '+' ? no_telp : '+62'+no_telp);
  formData.append("pdct_id", parseInt($(obj+' input[id="pdct_id"]').val()));
  formData.append("pdct_qty", qty);
  formData.append("alamat_pemesanan", $(obj+' input[id="input-beli-alamat"]').val());
  formData.append("note", $('#input-beli-catatan').val());

  $.ajax({
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		type: $('#form-beli').attr('method'),
		url: appUrl+'/ProductOrder',
		data: formData,
		processData: false,
		contentType: false,
		success: function (data) {
			pesanAlert(data,notifAlign);
		},
		error: function () {
				notif({msg: '<b style="color: white;">Connection Error!</b>', type: "error", position: notifAlign});
		},
		complete: function () {
				$('#beli-modal').modal('hide');
				EnableBtn('#input-beli-submit','Pesan');
		}
	});
}

//-- Review API --//
var fb_id = 0;

function getMyReview(email, pdct_id) {
  if (email !== undefined) {
    let request = {
      "column": "fb_pdct_id",
      "id": pdct_id,
      "email": email
    };
    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      type: "GET",
      url: appUrl+'/CustomerReview',
      data: request,
      contentType: 'application/json',
      dataType: 'json',
      success: function (data) {
        if (data.code == null) {
          fb_id = data[0].fb_id;
          $('#input-feedback-ulas').val(data[0].fb_text);
          $('#form-feedback input[id="star-'+data[0].fb_rating+'"]').prop('checked', true);
          $('#input-feedback-submit').hide();
          $('#input-feedback-update').show();
        }
        else {
          $('#input-feedback-update').hide();
          $('#input-feedback-submit').show();
        }
      },
      error: function () {
        notif({msg: '<b style="color: white;">Connection Error!</b>', type: "error", position: notifAlign});
      },
      complete: function () {
        $('#feedback-modal').modal('show');
      }
    });
  }
}

function getCsReview(pdct_id) {
  let feedback = $('#product-feedbacks');
  let request = {
    "column": "fb_pdct_id",
    "id": pdct_id
  };
  $.ajax({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    type: "GET",
    url: appUrl+'/Reviews',
    data: request,
    contentType: 'application/json',
    dataType: 'json',
    success: function (data) {
      try {
        if (data.code == null) {
          if (data.length > 0) {
            let rowHtml = '';
            for (let i = 0; i < data.length; i++) {
              rowHtml = 
              '<div class="col-md-4 col-sm-12 col-xs-12 clearfix align-left mb-3">' +
                '<div class="ce-feature-box-16 border margin-bottom">' +
                  '<div class="text-box text-center">' +
                    '<div class="imgbox-small round center overflow-hidden">' +
                      '<img src="'+appUrl+'/assets/img/profiles/DefaultPPimg.jpg" alt="profile" class="img-responsive"/>' +
                    '</div>' +
                    '<div class="text-box">' +
                      '<h6 class="title less-mar-1 mt-1">'+data[i].fb_cst_nama+'</h6>' +
                      '<p class="subtext">'+$('#title-pdct-page').text()+'</p>' +
                    '</div>' +
                    '<br>' +
                    '<p class="content">'+data[i].fb_text+'</p>' +
                  '</div>' +
                '</div>' +
              '</div>';
              feedback.append(rowHtml);
            }
          }
          else { throw new Error(); }
        } else { throw new Error(); }
      } catch (error) {
        $('#sec-ulasan').hide();
      }
    },
    error: function () {
      notif({msg: '<b style="color: white;">Connection Error!</b>', type: "error", position: notifAlign});
      $('#sec-ulasan').hide();
    },
    complete: function () {}
  });
}

function addReview(obj) {
  DisableBtn('#input-feedback-submit');
  var formData = new FormData();
  formData.append("fb_pdct_id", parseInt($(obj + ' input[id="fb_pdct_id"]').val()));
  formData.append("fb_cst_nama", $(obj + ' input[id="input-feedback-nama"]').val());
  formData.append("fb_cst_email", $(obj + ' input[id="input-feedback-email"]').val());
  formData.append("fb_text", $(obj + ' textarea[id="input-feedback-ulas"]').val());
  formData.append("fb_rating", parseInt($(obj + ' input[name="star"]:checked').val()));
  $.ajax({
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		type: $('#form-feedback').attr('method'),
		url: appUrl+'/Review',
		data: formData,
		processData: false,
		contentType: false,
		success: function (data) {
      pesanAlert(data,notifAlign);
      if (data.code == 1) {
        setTimeout(function () { window.location.reload() }, 2000);
      }
		},
		error: function () {
			notif({msg: '<b style="color: white;">Connection Error!</b>', type: "error", position: notifAlign});
		},
		complete: function () {
			$('#feedback-modal').modal('hide');
			EnableBtn('#input-feedback-submit','Kirim');
		}
	});
}

function updateReview(obj) {
  DisableBtn('#input-feedback-update');
  var data = {
    "fb_cst_nama": $(obj + ' input[id="input-feedback-nama"]').val(),
    "fb_cst_email": $(obj + ' input[id="input-feedback-email"]').val(),
    "fb_text": $(obj + ' textarea[id="input-feedback-ulas"]').val(),
    "fb_rating": parseInt($(obj + ' input[name="star"]:checked').val())
  }
  $.ajax({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    type: 'PUT',
    url: appUrl+'/Review/' + fb_id,
    data: JSON.stringify(data),
    contentType: "application/json",
    success: function (data) {
      pesanAlert(data,notifAlign);
      if (data.code == 1) {
        setTimeout(function () { window.location.reload() }, 2000);
      }
    },
    error: function () {
        notif({msg: "<b>Connection Error!</b>", type: "error", position: notifAlign});
    },
    complete: function () {
      $('#feedback-modal').modal('hide');
			EnableBtn('#input-feedback-update','Simpan');
    }
  });
}

// Validation
var csValidation = (id) => !isNaN(id);