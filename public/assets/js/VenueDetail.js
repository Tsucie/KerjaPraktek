$(document).ready(function () {
  getFacilities();
  getMyReview($('#rvw-btn').attr('data-email'));
  $('.nav-item').removeClass('active');
  $('a[href="#venue-section"]').parent().addClass('active');

  $('#input-cek-submit').click(function (e) {
    e.preventDefault();
    checkVenue('#form-cek-gedung');
  });

  $('#input-sewa-submit').click(function (e) {
    e.preventDefault();
    createOrd('#form-sewa');
  });

  $('#form-feedback').submit(function (e) {
    e.preventDefault();
    addReview('#form-feedback');
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
  // facility();
});

//-- Adding Facilities --//
const fcs = { text: "", total: 0, dataFacilities: [] };

function renderFacilities(list1, list2, selectBox, data) {
  // let data = fcs.dataFacilities;
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
  // let data = fcs.dataFacilities;
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
  // var dataArr;
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
			notif({msg: '<b style="color: white;">Connection Error!</b>', type: "error", position: "center"});
		}
  });
  // return Promise.all(dataArr);
}

function addFacility(jumlah) {
  if (isNaN(jumlah) || jumlah < 1) return false;
  let newText = $('#input-sewa-morefacility').find(':selected').text();
  if (fcs.text.includes(newText)) {
    alert("Fasilitas sudah dipilih");
    return false;
  }
  fcs.text += (jumlah + ' ' + newText + '\n');
  console.log(fcs.text);
  fcs.total += (parseInt($('#input-sewa-morefacility').val()) * jumlah);
  console.log(fcs.total);
  $('#facility-text').val(fcs.text);
}

function deleteFacility(jumlah) {
  if (isNaN(jumlah) || jumlah < 1) return false;
  let oldtext = $('#input-sewa-morefacility').find(':selected').text();
  if (!fcs.text.includes(jumlah + ' ' + oldtext)) {
    alert("Fasilitas belum dipilih atau jumlah tidak sesuai");
    return false;
  }
  fcs.text = fcs.text.replace(jumlah + ' ' + oldtext + '\n', '');
  console.log(fcs.text);
  fcs.total -= (parseInt($('#input-sewa-morefacility').val()) * jumlah);
  console.log(fcs.total);
  $('#facility-text').val(fcs.text);
}
//-----------------------//

//-- Check Availability API --//
function checkVenue(obj) {
  DisableBtn('#input-cek-submit');
  var formData = new FormData();
  formData.append("vnu_id",parseInt($(obj + ' input[id="cek-vnu_id"]').val()));
  formData.append("tanggal",$(obj + ' input[id="input-cek-tanggal"]').val());
  formData.append("waktu",$(obj + ' select[id="input-cek-waktu"]').val());
  $.ajax({
		headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
		type: $('#form-cek-gedung').attr('method'),
		url: appUrl+'/CheckVenue',
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
				$('#cek-modal').modal('hide');
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
      type: "error",
      position: "center"
    });
    $('#sewa-modal').modal('hide');
		EnableBtn('#input-sewa-submit','Book Now');
    return false;
  }
  let no_telp = $(obj+' input[id="input-sewa-telepon"]').val();
  var formData = new FormData();
  formData.append("cst_id", id);
  formData.append("ov_vnu_id", parseInt($(obj+' input[id="vnu_id"]').val()));
  formData.append("ov_vnu_nama", $(obj+' input[id="input-sewa-namagedung"]').val());
  formData.append("ov_no_telp", "Khairul Hasan : 0812 8109 8822 & Nurul : 0895 6367 50473");
  formData.append("gst_nama", $(obj+' input[id="input-sewa-nama"]').val());
  formData.append("gst_alamat", $(obj+' input[id="input-sewa-alamat"]').val());
  formData.append("gst_no_telp", no_telp.charAt(0) === '0' ? no_telp : '+62'+no_telp);
  formData.append("gst_rencana_pemakaian", $(obj+' input[id="input-sewa-tanggal"]').val());
  formData.append("gst_waktu_pemakaian", $(obj+' select[id="input-sewa-waktu"]').val());
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
			pesanAlert(data);
		},
		error: function () {
				notif({msg: '<b style="color: white;">Connection Error!</b>', type: "error", position: "center"});
		},
		complete: function () {
				$('#sewa-modal').modal('hide');
				EnableBtn('#input-sewa-submit','Book Now');
		}
	});
}

//-- Review API --//
function getMyReview(email) {
  if (email !== undefined) {
    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      type: "GET",
      url: appUrl+'/Review/' + email,
      data: null,
      processData: false,
      contentType: false,
      success: function (data) {
        if (data.code == null) {
          $('#input-feedback-ulas').val(data[0].fb_text);
          $('#form-feedback input[id="star-'+data[0].fb_rating+'"]').prop('checked', true);
        }
      },
      error: function () {
        notif({msg: '<b style="color: white;">Connection Error!</b>', type: "error", position: "center"});
      },
      complete: function () {
        $('#feedback-modal').modal('hide');
        EnableBtn('#input-feedback-submit','Kirim');
      }
    });
  }
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
      pesanAlert(data);
      setTimeout(function () { window.location.reload() }, 2000);
		},
		error: function () {
			notif({msg: '<b style="color: white;">Connection Error!</b>', type: "error", position: "center"});
		},
		complete: function () {
			$('#feedback-modal').modal('hide');
			EnableBtn('#input-feedback-submit','Kirim');
		}
	});
}

// Update Review ?

// Delete Review ?

// Validation
var csValidation = (id) => !isNaN(id);