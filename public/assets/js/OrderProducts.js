const appUrl = $('meta[name="route"]').attr('content');
var edit = 0;

$(document).ready(function () {
    $('#header-title').text('Daftar Order Produk Masuk');
    $('#btn-edit-op').click(function () {
        EditOrder(this);
    });
});

// Modals for Show Details
function ShowDetails(obj) {
    let actionTitle = 'Informasi Pemesanan';
    $('#form').attr('method', "");
    $('#form').attr('action', "");
    $("#AddEditModal").on('show.bs.modal', function (e) {
        var modal = $(this);
        modal.find('.modal-title').text(actionTitle);
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
    formData.append("op_status_order", parseInt($('#op_status_order').val()));
    formData.append("op_contact_customer", parseInt($('#op_contact_customer').val()));

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