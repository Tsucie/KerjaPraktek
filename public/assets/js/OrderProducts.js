const appUrl = $('meta[name="route"]').attr('content');
var edit = 0;

$(document).ready(function () {
    $('#header-title').text('Daftar Order Produk Masuk');
    $('#btn-edit-op').click(function () {
        EditOrder(this);
    });
});

$('#op_status_order').on('change', function (e) {
    if ($(this).val() == 2 || $(this).val() == 3) {
        $('#input-bukti').removeClass('d-none');
        $('#input-resi').removeClass('d-none');
    }
    else {
        $('#input-bukti').addClass('d-none');
        $('#input-resi').addClass('d-none');
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
    $('#op_bukti_transfer_file').on('change', function() {
        imagesPreview(this, '#bukti-gallery');
    });
    $('#op_resi_file').on('change', function() {
        imagesPreview(this, '#resi-gallery');
    });
});

$('#op_persen_pajak').on('change', function (e) {
    $('#op_nominal_pajak').val(
        parseInt($('#op_sum_harga_produk').val()) * parseInt($(this).val()) / 100
    );
});

$('#op_sum_biaya').on('focus', function (e) {
    $(this).val(
        parseInt($('#op_sum_harga_produk').val()) + parseInt($('#op_harga_ongkir').val()) + parseInt($('#op_nominal_pajak').val())
    );
});

function clearImg(divSelector) {
    $(divSelector+' > input').val(null);
    $(divSelector+' > div.gallery').html('');
    $(divSelector).addClass('d-none');
}

// Modals for Show Details
function ShowDetails(obj) {
    let actionTitle = 'Informasi Pemesanan';
    $('#form').attr('method', "");
    $('#form').attr('action', "");
    $("#AddEditModal").on('show.bs.modal', function (e) {
        var modal = $(this);
        modal.find('.modal-title').text(actionTitle);
        $('#op_bukti_transfer_file').hide();
        $('#op_resi_file').hide();
        $("#btn-edit-op").hide();
    });
    GetOrder(parseInt(obj.attributes.data_id.value));
}

// Modals for Edit Data
function ShowEditModals(obj) {
    let actionTitle = obj.attributes.data_text.value;
    edit = parseInt(obj.attributes.data_id.value);
    $('#form').attr('method', "PUT");
    $('#form').attr('action', appUrl + "/OrderProduct/" + edit);
    $("#AddEditModal").on('show.bs.modal', function (e) {
        var modal = $(this);
        modal.find('.modal-title').text(actionTitle);
        $("#btn-edit-op").show();
        $('#input-bukti').removeClass('d-none');
        $('#input-resi').removeClass('d-none');
        $('#op_bukti_transfer_file').show();
        $('#op_resi_file').show();
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
        url: appUrl + "/OrderProduct/" + id,
        contentType: "application/json",
        success: function (data) {
            if (data.code != null) {
                pesanAlert(data);
            }
            else {
                $('#cst_name').val(data[0].customer.cst_name);
                $('#cst_alamat').val(data[0].customer.cst_alamat);
                $('#cst_no_telp').val(data[0].customer.cst_no_telp);
                $('#cst_email').val(data[0].customer.cst_email);
                $('#op_tanggal_order').val(data[0].op_tanggal_order);
                $('#pdct_nama').val(data[0].product.pdct_nama);
                $('#pdct_kode').val(data[0].product.pdct_kode);
                $('#pdct_harga').val(data[0].product.pdct_harga);
                $('#odp_pdct_qty').val(data[0].detail.odp_pdct_qty);
                $('#op_note_to_admin').val(data[0].op_note_to_admin);
                $('#op_lokasi_pengiriman').val(data[0].op_lokasi_pengiriman);
                $('#op_sum_harga_produk').val(data[0].op_sum_harga_produk);
                $('#op_harga_ongkir').val(data[0].op_harga_ongkir);
                $('#op_persen_pajak').val(data[0].op_persen_pajak);
                $('#op_nominal_pajak').val(data[0].op_nominal_pajak);
                $('#op_alamat_pengiriman').val(data[0].op_alamat_pengiriman);
                $('#op_alamat_pemesanan').val(data[0].op_alamat_pemesanan);
                $('#op_status_order').val(data[0].op_status_order);
                $('#op_contact_customer').val(data[0].op_contact_customer);
                $('#op_sum_biaya').val(
                    parseInt($('#op_sum_harga_produk').val()) +
                    parseInt($('#op_harga_ongkir').val()) +
                    parseInt($('#op_nominal_pajak').val())
                );
                if (data[0].op_bukti_transfer_file != null) {
                    var gallery = 'div.gallery';
                    $('#input-bukti > '+gallery).html("");
                    let fileExt = data[0].op_bukti_transfer_filename.split('.').pop();
                    $($.parseHTML('<img width="100" height="auto" id="service-img">'))
                        .attr('src', 'data:image/'+fileExt+';base64,'+data[0].op_bukti_transfer_file)
                        .appendTo('#input-bukti > '+gallery);
                }
                else { clearImg('#input-bukti'); }
                if (data[0].op_resi_file != null) {
                    var gallery = 'div.gallery';
                    $('#input-resi > '+gallery).html("");
                    let fileExt = data[0].op_resi_filename.split('.').pop();
                    $($.parseHTML('<img width="100" height="auto" id="service-img">'))
                        .attr('src', 'data:image/'+fileExt+';base64,'+data[0].op_resi_file)
                        .appendTo('#input-resi > '+gallery);
                }
                else { clearImg('#input-resi'); }
                $('#op_note_to_customer').val(data[0].op_note_to_customer);
                $("#AddEditModal").modal('show');
            }
        },
        error: function () {
            notif({msg: "<b>Connection Error!</b>", type: "error", position: "center"});
        }
    });
}

function EditOrder() {
    DisableBtn('#btn-edit-op');
    var formData = new FormData();
    formData.append("alamat_pemesanan", $('#op_alamat_pemesanan').val());
    formData.append("op_lokasi_pengiriman", $('#op_lokasi_pengiriman').val());
    if ($('#op_harga_ongkir').val() != "") formData.append("op_harga_ongkir", parseInt($('#op_harga_ongkir').val()));
    if ($('#op_persen_pajak').val() != "") formData.append("op_persen_pajak", parseInt($('#op_persen_pajak').val()));
    if ($('#op_nominal_pajak').val() != "") formData.append("op_nominal_pajak", parseInt($('#op_nominal_pajak').val()));
    if ($('#op_alamat_pengiriman').val() != "") formData.append("op_alamat_pengiriman", $('#op_alamat_pengiriman').val());
    if ($('#op_bukti_transfer_file')[0].files[0]) formData.append("op_bukti_transfer_file", $('#op_bukti_transfer_file')[0].files[0]);
    if ($('#op_resi_file')[0].files[0]) formData.append("op_resi_file", $('#op_resi_file')[0].files[0]);
    formData.append("op_status_order", parseInt($('#op_status_order').val()));
    formData.append("op_contact_customer", parseInt($('#op_contact_customer').val()));
    formData.append("op_note_to_customer", $('#op_note_to_customer').val());

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
            $('#btn-edit-op').prop('disabled', false);
            $('#btn-edit-op').text('Simpan');
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
        url: appUrl + "/OrderProduct/" + id,
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