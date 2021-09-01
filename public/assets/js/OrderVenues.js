const appUrl = $('meta[name="route"]').attr('content');
var edit = 0;

$(document).ready(function () {
    $('#header-title').text('Daftar Order Gedung/Ruangan Masuk');
    $('#btn-edit-ov').click(function () {
        EditOrder(this);
    });
});

$('#ov_status_order').on('change', function (e) {
    if ($(this).val() == 2 || $(this).val() == 3) {
        $('#div-input-bukti').removeClass('d-none');
    }
    else {
        $('#div-input-bukti').addClass('d-none');
    }
});

// JQuery Image Preview
$(function() {
    var imagesPreview = function(input, placeToInsertImagePreview) {
        $(placeToInsertImagePreview).html("");
        if (input.files) {
            var filesAmount = input.files.length;
            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();
                reader.onload = function(event) {
                    $($.parseHTML('<img width="100" height="auto" id="service-img">'))
                        .attr('src', event.target.result)
                        .appendTo(placeToInsertImagePreview);
                }
                reader.readAsDataURL(input.files[i]);
            }
        }
    };
    $('#ov_bukti_transfer_file').on('change', function() {
        imagesPreview(this, 'div.gallery');
    });
});

function clearImg() {
    $('#ov_bukti_transfer_file').val(null);
    $('div.gallery').html('');
    $('#div-input-bukti').addClass('d-none');
}

// Modals for Show Details
function ShowDetails(obj) {
    let actionTitle = 'Informasi Pemesanan';
    $('#ov_bukti_transfer_file').hide();
    $('#form').attr('method', "");
    $('#form').attr('action', "");
    $("#AddEditModal").on('show.bs.modal', function (e) {
        var modal = $(this);
        modal.find('.modal-title').text(actionTitle);
        $("#btn-edit-ov").hide();
    });
    GetOrder(parseInt(obj.attributes.data_id.value));
}

// Modals for Edit Data
function ShowEditModals(obj) {
    let actionTitle = obj.attributes.data_text.value;
    edit = parseInt(obj.attributes.data_id.value);
    $('#ov_bukti_transfer_file').show();
    $('#form').attr('method', "PUT");
    $('#form').attr('action', appUrl + "/OrderVenue/" + edit);
    $("#AddEditModal").on('show.bs.modal', function (e) {
        var modal = $(this);
        modal.find('.modal-title').text(actionTitle);
        $("#btn-edit-ov").show();
    });
    GetOrder(edit);
}

function DisableBtn(selector) {
    $(selector).prop('disabled', true);
    $(selector).text('Tunggu ...');
}

function GetOrder(id) {
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: "GET",
        url: appUrl + "/OrderVenue/" + id,
        contentType: "application/json",
        success: function (data) {
            if (data.code != null) {
                pesanAlert(data);
            }
            else {
                $('#cst_name').val(data[0].customer.cst_name);
                $('#gst_nama').val(data[0].guest.gst_nama);
                $('#gst_alamat').val(data[0].guest.gst_alamat);
                $('#gst_no_telp').val(data[0].guest.gst_no_telp);
                $('#gst_rencana_pemakaian').val(data[0].guest.gst_rencana_pemakaian);
                $('#gst_keperluan_pemakaian').val(data[0].guest.gst_keperluan_pemakaian);
                $('#gst_waktu_pemakaian').val(data[0].guest.gst_waktu_pemakaian);
                if (data[0].venue.vnu_tipe_waktu == 1) {
                    $('div#pl_fee').hide();
                    $('#vnu_jam_pemakaian_siang').val('Dari jam   : ' + data[0].venue.vnu_jam_pemakaian_siang);
                    $('#vnu_jam_pemakaian_malam').val('Sampai jam : ' + data[0].venue.vnu_jam_pemakaian_malam);
                }
                else {
                    $('div#pl_fee').show();
                    $('#vnu_jam_pemakaian_siang').val('Siang : ' +  data[0].venue.vnu_jam_pemakaian_siang);
                    $('#vnu_jam_pemakaian_malam').val('Malam : ' + data[0].venue.vnu_jam_pemakaian_malam);
                }
                $('#vnu_nama').val(data[0].venue.vnu_nama);
                $('#ov_no_telp').val(data[0].ov_no_telp);
                $('#vnu_harga').val(data[0].ov_harga_sewa);
                $('#ov_biaya_lain').val(data[0].ov_biaya_lain);
                $('#ov_nama_catering').val(data[0].ov_nama_catering);
                $('#ov_fee_catering').val(data[0].ov_fee_catering);
                $('#ov_fee_pelaminan').val(data[0].ov_fee_pelaminan);
                $('#ov_more_facilities').val(data[0].ov_more_facilities);
                $('#ov_lain_lain').val(data[0].ov_lain_lain);
                $('#ov_sum_lain_lain').val(data[0].ov_sum_lain_lain);
                $('#ov_sum_biaya').val(data[0].ov_sum_biaya);
                $('#ov_down_payment').val(data[0].ov_down_payment);
                $('#ov_remaining_payment').val(data[0].ov_remaining_payment);
                $('#ov_status_order').val(data[0].ov_status_order);
                $('#ov_contact_customer').val(data[0].ov_contact_customer);
                $('#ov_note_to_customer').val(data[0].ov_note_to_customer);
                if (data[0].ov_bukti_transfer_file != null) {
                    $('#div-input-bukti').removeClass('d-none');
                    var gallery = 'div.gallery';
                    $(gallery).html("");
                    let fileExt = data[0].ov_bukti_transfer_filename.split('.').pop();
                    $($.parseHTML('<img width="100" height="auto" id="service-img">'))
                        .attr('src', 'data:image/'+fileExt+';base64,'+data[0].ov_bukti_transfer_file)
                        .appendTo(gallery);
                }
                else { clearImg(); }
                $("#AddEditModal").modal('show');
            }
        },
        error: function () {
            notif({msg: "<b>Connection Error!</b>", type: "error", position: "center"});
        }
    });
}

function EditOrder() {
    DisableBtn('#btn-edit-ov');
    var formData = new FormData();
    formData.append("ov_no_telp", $('#ov_no_telp').val());
    if ($('#ov_nama_catering').val() != "") formData.append("ov_nama_catering", $('#ov_nama_catering').val());
    if ($('#ov_more_facilities').val() != "") formData.append("ov_more_facilities", $('#ov_more_facilities').val());
    if ($('#ov_biaya_lain').val() != "") formData.append("ov_biaya_lain", parseInt($('#ov_biaya_lain').val()));
    if ($('#ov_fee_catering').val() != "") formData.append("ov_fee_catering", parseInt($('#ov_fee_catering').val()));
    if ($('#ov_fee_pelaminan').val() != "") formData.append("ov_fee_pelaminan", parseInt($('#ov_fee_pelaminan').val()));
    if ($('#ov_lain_lain').val() != "") formData.append("ov_lain_lain", parseInt($('#ov_lain_lain').val()));
    if ($('#ov_sum_lain_lain').val() != "") formData.append("ov_sum_lain_lain", parseInt($('#ov_sum_lain_lain').val()));
    if ($('#ov_sum_biaya').val() != "") formData.append("ov_sum_biaya", parseInt($('#ov_sum_biaya').val()));
    if ($('#ov_down_payment').val() != "") formData.append("ov_down_payment", parseInt($('#ov_down_payment').val()));
    if ($('#ov_remaining_payment').val() != "") formData.append("ov_remaining_payment", parseInt($('#ov_remaining_payment').val()));
    if ($('#ov_bukti_transfer_file')[0].files[0]) formData.append("ov_bukti_transfer_file", $('#ov_bukti_transfer_file')[0].files[0]);
    formData.append("ov_status_order", parseInt($('#ov_status_order').val()));
    formData.append("ov_contact_customer", parseInt($('#ov_contact_customer').val()));
    formData.append("ov_note_to_customer", $('#ov_note_to_customer').val());
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: "POST",
        url: $('#form').attr('action') + "?_method=PUT",
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
            notif({msg: "<b>Connection Error!</b>", type: "error", position: "center"});
        },
        complete: function () {
            $('#AddEditModal').modal('hide');
            $('#btn-edit-ov').prop('disabled', false);
            $('#btn-edit-ov').text('Simpan')
        }
    });
}

function DeleteOrder(obj) {
    let obj_id = parseInt(obj.attributes.data_id.value);
    let obj_name = obj.attributes.data_name.value;
    $("#DeleteModal").on('show.bs.modal', function (e) {
        var modal = $(this);
        modal.find('.modal-body').text("Yakin ingin hapus data Pemesanan atas nama " + obj_name + "?");
        $('#btn-hps').attr('data_id', obj_id);
    });
    $("#DeleteModal").modal('show');
}

function Delete(obj) {
    DisableBtn('#btn-hps');
    let id = parseInt(obj.attributes.data_id.value);
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: "DELETE",
        url: appUrl + "/OrderVenue/" + id,
        dataType: "json",
        contentType: "application/json",
        data: null,
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
            notif({msg: "<b>Connection Error!</b>", type: "error", position: "center"});
        },
        complete: function() {
            $("#DeleteModal").modal('hide');
            $('#btn-hps').prop('disabled', false);
            $('#btn-hps').text('Hapus');
        }
    });
}