$(document).ready(function () {
  getFacilities();
  getCsReview(parseInt($('#vnu_id').val()));
  $('.nav-item').removeClass('active');
  $('a[href="'+appUrl+'/#venue-section"]').parent().addClass('active');

  $('#gedung_aula').click(function (e) {
    e.preventDefault();
    $('#input-sewa-submit').css("background-color", "#cccccc");
    if (isNaN(parseInt($('#form-sewa input[id="cst_id"]').val()))) {
      notif({
        msg: '<b style="color: white;">Harap Login terlebih dahulu sebelum order!</b>',
        type: "warning",
        position: notifAlign
      });
      $('#login-modal').modal('show');
    }
    else {
      $('#sewa-modal').modal('show');
    }
  });
    
  $('#input-cek-submit').click(function (e) {
    e.preventDefault();
    checkVenue('#form-cek-gedung');
  });
  
  $('#input-sewa-tanggal').on('input', function() {
    $('#input-sewa-submit').css("background-color", "#FFD369");
  });
  
  $('#input-sewa-submit').click(function (e) {
    if( !$('#input-sewa-tanggal').val() ) {
        alert('isi tanggal terlebih dahulu');
    }else {
        e.preventDefault();
        createOrd('#form-sewa');
    }
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

  setTimeout(() => {
    if ($(window).width() < 500) { // For mobile
      renderMobileFacilities('#fcs_mobile_view','#input-sewa-morefacility', fcs.dataFacilities);
    }
    else {// For Desktop
      renderFacilities('#mfc_nama','#mfc_prices_pcs','#input-sewa-morefacility', fcs.dataFacilities);
    }
  }, 10000);

  $('#add-facility-btn').click(function (e) {
    addFacility(parseInt($('#input-sewa-jumlah').val()));
  });
  $('#hps-facility-btn').click(function (e) {
    deleteFacility(parseInt($('#input-sewa-jumlah').val()));
  });
});

//-- Adding Facilities --//
const fcs = { text: "", total: 0, dataFacilities: [] };

function renderFacilities(list1, list2, selectBox, data) {
  console.log("render", data);
  if (data.length > 0) {
    $(selectBox).empty();
    for (let i = 0; i < data.length; i++) {
      $(list1).append('<li>'+data[i].mfc_nama+'</li>');
      $(list2).append('<li>Rp '+data[i].mfc_harga+'/'+data[i].mfc_satuan+'</li>');
      $(selectBox).append(
        '<option value="'+data[i].mfc_harga+'">'+data[i].mfc_nama+' Rp '+data[i].mfc_harga+'/'+data[i].mfc_satuan+'</option>'
      );
    }
  }
}

function renderMobileFacilities(list, selectBox, data) {
  console.log("render", data);
  if (data.length > 0) {
    $(selectBox).empty();
    for (let i = 0; i < data.length; i++) {
      $(list).append('<li>'+data[i].mfc_nama+' (Rp '+data[i].mfc_harga+'/'+data[i].mfc_satuan+')</li>');
      $(selectBox).append(
        '<option value="'+data[i].mfc_harga+'">'+data[i].mfc_nama+' Rp '+data[i].mfc_harga+'/'+data[i].mfc_satuan+'</option>'
      );
    }
  }
}

//-- Get Facilities --//
function getFacilities() {
  $.ajax({
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		type: "GET",
		url: appUrl+'/morefacilities',
		data: null,
		dataType: 'json',
		contentType: 'application/json',
		success: function (data) {
			if (data) {
        fcs.dataFacilities = data;
      }
		},
		error: function () {
			notif({msg: '<b style="color: white;">Connection Error!</b>', type: "error", position: notifAlign});
		}
  });
}

function addFacility(jumlah) {
  if (isNaN(jumlah) || jumlah < 1) return false;
  let newText = $('#input-sewa-morefacility').find(':selected').text();
  if (fcs.text.includes(newText)) {
    alert("Fasilitas sudah dipilih");
    return false;
  }
  fcs.text += (jumlah + ' ' + newText.split('/').pop() + ' ' + newText + '\n');
  console.log(fcs.text);
  fcs.total += (parseInt($('#input-sewa-morefacility').val()) * jumlah);
  console.log(fcs.total);
  $('#facility-text').val(fcs.text);
}

function deleteFacility(jumlah) {
  if (isNaN(jumlah) || jumlah < 1) return false;
  let oldtext = $('#input-sewa-morefacility').find(':selected').text();
  if (!fcs.text.includes(jumlah + ' ' + oldtext.split('/').pop() + ' ' + oldtext)) {
    alert("Fasilitas belum dipilih atau jumlah tidak sesuai");
    return false;
  }
  fcs.text = fcs.text.replace(jumlah + ' ' + oldtext.split('/').pop() + ' ' + oldtext + '\n', '');
  console.log(fcs.text);
  fcs.total -= (parseInt($('#input-sewa-morefacility').val()) * jumlah);
  console.log(fcs.total);
  $('#facility-text').val(fcs.text);
}
//-----------------------//

//-- Check Availability API --//
function checkVenue(obj) {
  DisableBtn('#input-cek-submit');
  let tipe_waktu = parseInt($('#title-vnu-page').attr('data_time'));
  var formData = new FormData();
  formData.append("vnu_id",parseInt($(obj + ' input[id="cek-vnu_id"]').val()));
  formData.append("tanggal",$(obj + ' input[id="input-cek-tanggal"]').val());
  if (tipe_waktu == 1) {
    formData.append("waktu", $(obj + ' input[id="dari_jam"]').val() + " - " + $(obj + ' input[id="sampai_jam"]').val());
  } else {
    formData.append("waktu",$(obj + ' select[id="input-cek-waktu"]').val());
  }
  $.ajax({
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		type: $('#form-cek-gedung').attr('method'),
		url: appUrl+'/CheckVenue',
		data: formData,
		processData: false,
		contentType: false,
		success: function (data) {
      pesanAlert(data,notifAlign);
      $('#cek-modal').modal('hide');
		},
		error: function (data) {
			if (data.responseJSON.errors != null) {
        notif({msg: '<b style="color: white;">Data input tidak valid!</b>', type: "error", position: notifAlign});
      } else {
        notif({msg: '<b style="color: white;">Connection Error!</b>', type: "error", position: notifAlign});
      }
		},
		complete: function () {
			EnableBtn('#input-cek-submit','Check');
		}
	});
}


//-- Order API --//
function createOrd(obj) {
  let id = parseInt($('#cst_id').val());
  DisableBtn('#input-sewa-submit');
  if (!csValidation(id)) {
    notif({
      msg: '<b style="color: white;">Harap Login terlebih dahulu sebelum order!</b>',
      type: "warning",
      position: notifAlign
    });
    $('#sewa-modal').modal('hide');
		EnableBtn('#input-sewa-submit','Book Now');
    return false;
  }
  let no_telp = $(obj+' input[id="input-sewa-telepon"]').val();
  while (no_telp.includes('-')) {
    no_telp = no_telp.replace('-','');
  }
  let tipe_waktu = parseInt($('#title-vnu-page').attr('data_time'));
  let dari_jam = $(obj + ' input[id="dari_jam"]').val();
  let sampai_jam = $(obj + ' input[id="sampai_jam"]').val();
  console.log(dari_jam, sampai_jam);
  var formData = new FormData();
  formData.append("cst_id", id);
  formData.append("ov_vnu_id", parseInt($(obj+' input[id="vnu_id"]').val()));
  formData.append("ov_vnu_nama", $(obj+' input[id="input-sewa-namagedung"]').val());
  formData.append("ov_no_telp", "Khairul Hasan : 0812 8109 8822 & Nurul : 0895 6367 50473");
  formData.append("gst_nama", $(obj+' input[id="input-sewa-nama"]').val());
  formData.append("gst_alamat", $(obj+' input[id="input-sewa-alamat"]').val());
  formData.append("gst_no_telp", no_telp.charAt(0) === '0' ? no_telp : no_telp.charAt(0) === '+' ? no_telp : '+62 '+no_telp);
  formData.append("gst_rencana_pemakaian", $(obj+' input[id="input-sewa-tanggal"]').val());

  if (tipe_waktu == 1) {
    formData.append("waktu_sewa", parseInt($('#input-sewa-waktu').val()));
    formData.append("gst_waktu_pemakaian", "Jam: " + dari_jam + " - " + sampai_jam + " ("+$('#input-sewa-waktu').val()+" Jam)");
  } else {
    formData.append("gst_waktu_pemakaian", $('#input-sewa-waktu').val());
  }

  if ($(obj+' input[id="input-sewa-keperluan"]').val() != "")
    formData.append("gst_keperluan_pemakaian", $(obj+' input[id="input-sewa-keperluan"]').val());

  if (fcs.text !== "" && fcs.total !== 0) {
    formData.append("ov_more_facilities", fcs.text);
    formData.append("ov_lain_lain", fcs.total);
  }
  $.ajax({
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		type: $('#form-sewa').attr('method'),
		url: appUrl+'/VenueOrder',
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
				$('#sewa-modal').modal('hide');
				EnableBtn('#input-sewa-submit','Book Now');
		}
	});
}

//-- Review API --//
var fb_id = 0;

function getMyReview(email, vnu_id) {
  if (email !== undefined) {
    let request = {
      "column": "fb_vnu_id",
      "id": vnu_id,
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

function getCsReview(vnu_id) {
  let feedback = $('#venue-feedbacks');
  let request = {
    "column": "fb_vnu_id",
    "id": vnu_id
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
              rowHtml = '<div class="col-md-4 col-sm-12 col-xs-12 clearfix align-left mb-3">' +
                          '<div class="ce-feature-box-16 border margin-bottom">' +
                            '<div class="text-box text-center">' +
                              '<div class="imgbox-small round center overflow-hidden">' +
                                '<img src="'+appUrl+'/assets/img/profiles/DefaultPPimg.jpg" alt="profile" class="img-responsive"/>' +
                              '</div>' +
                              '<div class="text-box">' +
                                '<h6 class="title less-mar-1 mt-1">'+data[i].fb_cst_nama+'</h6>' +
                                '<p class="subtext">'+$('#title-vnu-page').text()+'</p>' +
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
  formData.append("fb_vnu_id", parseInt($(obj + ' input[id="fb_vnu_id"]').val()));
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