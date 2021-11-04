const appUrl = $('meta[name="route"]').attr('content');
var ctx_col = "";
var ctx_id = 0;

$(document).ready(function () {
    $('#header-title').text('Daftar Promo');
    $('#nama-alrt').hide();
    $('#harga-alrt').hide();
    $('#diskon-alrt').hide();
    $("#AddEditModal").on('hide.bs.modal', function (e) {
        $('#nama').css('border-color', 'lightgray');
        $('#nama-alrt').hide();
        $('#harga').css('border-color', 'lightgray');
        $('#harga-alrt').hide();
        $('#diskon').css('border-color', 'lightgray');
        $('#diskon-alrt').hide();
        ClearInput();
    });
    $("#Add-btn").click(function() {
        ShowAddModals(this);
    });
    $("#btn-add-prm").click(function() {
        AddPromo(this);
    });
    $('#btn-edit-prm').click(function () {
        EditPromo(this);
    });
});

// Modals for Show Details
function ShowDetails(obj) {
    let actionTitle = 'Informasi Promo';
    $('#form').attr('method', "");
    $('#form').attr('action', "");
    $("#AddEditModal").on('show.bs.modal', function (e) {
        var modal = $(this);
        modal.find('.modal-title').text(actionTitle);
        $('#select-services').prop('disabled', 'disabled');
        $('#services').prop('disabled', 'disabled');
        $('#harga-promo').show();
        $("#btn-edit-prm").hide();
        $("#btn-add-prm").hide();
    });
    GetPromo(parseInt(obj.attributes.data_id.value));
    setTimeout(function () { $("#AddEditModal").modal('show'); }, 2000);
}

// Modals for Add Data
function ShowAddModals() {
    let actionTitle = 'Tambah Layanan Promo';
    $('#form').attr('method', "POST");
    $('#form').attr('action', appUrl + "/Promo");
    ClearInput();
    $("#AddEditModal").on('show.bs.modal', function (e) {
        var modal = $(this);
        modal.find('.modal-title').text(actionTitle);
        $('#select-services').prop('disabled', false);
        $('#services').prop('disabled', false);
        $('#harga-promo').hide();
        $("#btn-add-prm").show();
        $("#btn-edit-prm").hide();
    });
    GetServicesList($('#select-services').val());
    setTimeout(function () {
        ctx_col = $('#services').find(':selected').attr('data_col');
        ctx_id = $('#services').val();
    }, 1500);
}

// Modals for Edit Data
function ShowEditModals(obj) {
    let actionTitle = 'Ubah Layanan Promo';
    let edit = parseInt(obj.attributes.data_id.value);
    $('#form').attr('method', "PUT");
    $('#form').attr('action', appUrl + "/Promo/" + edit);
    $("#AddEditModal").on('show.bs.modal', function (e) {
        var modal = $(this);
        modal.find('.modal-title').text(actionTitle);
        $('#select-services').prop('disabled', 'disabled');
        $('#services').prop('disabled', 'disabled');
        $('#harga-promo').show();
        $("#btn-edit-prm").show();
        $("#btn-add-prm").hide();
    });
    GetPromo(edit);
    setTimeout(function () { $("#AddEditModal").modal('show'); }, 2000);
}

function ClearInput() {
    $('#nama').val('');
    $('#diskon').val('');
    $('#harga').val('');
}

//JQuery Events
$('#select-services').on('change', function () {
    $('#label-service').text("Pilih " + $(this).find(':selected').text());
    GetServicesList($(this).val());
});

$('#services').on('change', function () {
    ctx_col = $(this).find(':selected').attr('data_col');
    ctx_id = $(this).val();
    $('#harga').val($(this).find(':selected').attr('data_harga'));
});

function DisableBtn(selector) {
    $(selector).prop('disabled', true);
    $(selector).text('Tunggu ...');
}

// JQuery Validate
function validasi() {
    var isValid = true;
    if($('#nama').val().trim() == "") {
        $('#nama').css('border-color', 'Red');
        $('#nama-alrt').show();
        isValid = false;
    } else {
        $('#nama').css('border-color', 'lightgray');
        $('#nama-alrt').hide();
    }
    if ($('#harga').val().trim() == "" || $('#harga').val().trim() == 0) {
        $('#harga').css('border-color', 'Red');
        $('#harga-alrt').show();
        isValid = false;
    } else {
        $('#harga').css('border-color', 'lightgray');
        $('#harga-alrt').hide();
    }
    if ($('#diskon').val().trim() == "" || $('#diskon').val().trim() == 0) {
        $('#diskon').css('border-color', 'Red');
        $('#diskon-alrt').show();
        isValid = false;
    } else {
        $('#diskon').css('border-color', 'lightgray');
        $('#diskon-alrt').hide();
    }
    return isValid;
}

// CRUD
function GetServicesList(context) {
    let comboBox = $('#services');
    $.ajax({
        type: "GET",
        url: appUrl + "/" + context + "SelectList",
        contentType: "application/json",
        dataType: "json",
        data: null,
        success: function (data) {
            comboBox.empty();
            if (data.length > 0) {
                let opsi = '';
                for (let i = 0; i < data.length; i++) {
                    opsi = context == "Venue" ?
                        '<option value="'+data[i].vnu_id+
                            '" title="'+data[i].vnu_desc+
                            '" data_harga="'+data[i].vnu_harga+
                            '" data_col="vnu_id">'+
                            data[i].vnu_nama
                        +'</option>' :
                        '<option value="'+data[i].pdct_id+
                            '" title="'+data[i].pdct_desc+
                            '" data_harga="'+data[i].pdct_harga+
                            '" data_col="pdct_id">'+
                            data[i].pdct_nama
                        +'</option>';
                    comboBox.append(opsi);
                }
                $('#harga').val(data[0].vnu_harga ?? data[0].pdct_harga);
            }
            else {
                comboBox.append('<option selected value="null">Tidak Ada Layanan</option>');
            }
        },
        error: function () {
            notif({msg: "<b>Connection Error!</b>", type: "error", position: "center"});
        }
    });
}

function AddPromo() {
    if (!validasi()) return false;
    DisableBtn('#btn-add-prm');
    var formData = new FormData();
    ctx_col = $('#services').find(':selected').attr('data_col');
    ctx_id = $('#services').val();
    console.log(ctx_col + ': ' + ctx_id);

    formData.append(ctx_col, ctx_id);
    formData.append("harga", $('#harga').val());
    formData.append("nama", $('#nama').val());
    formData.append("desc", $('#desc').val());
    formData.append("diskon", $('#diskon').val());

    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: $('#form').attr('method'),
        url: $('#form').attr('action'),
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
            $('#btn-add-prm').prop('disabled', false);
            $('#btn-add-prm').text('Tambah');
            ClearInput();
        }
    });
}

function GetPromo(id) {
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: "GET",
        url: appUrl + "/Promo/" + id,
        contentType: "application/json",
        success: function (data) {
            if (data.code != null) {
                pesanAlert(data);
            }
            else {
                let context = data[0].prm_pdct_id == null ? "Venue" : "Product";
                GetServicesList(context);
                let value = data[0].prm_pdct_id == null ? data[0].prm_vnu_id : data[0].prm_pdct_id;
                $('#select-services').val(context);
                $('#nama').val(data[0].prm_nama);
                $('#desc').val(data[0].prm_desc);
                $('#diskon').val(data[0].prm_disc_percent);
                $('#prm_harga').val(data[0].prm_harga_promo);
                setTimeout(function () {
                    $('#services').val(value);
                    $('#harga').val($('#services').find(':selected').attr('data_harga'));
                }, 1500);
            }
        },
        error: function () {
            notif({msg: "<b>Connection Error!</b>", type: "error", position: "center"});
        }
    });
}

function EditPromo() {
    if (!validasi()) return false;
    DisableBtn('#btn-edit-prm');
    var formData = new FormData();
    formData.append("harga", $('#harga').val());
    formData.append("nama", $('#nama').val());
    formData.append("desc", $('#desc').val());
    formData.append("diskon", $('#diskon').val());

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
            ClearInput();
        }
    });
}

function DeletePromo(obj) {
    let obj_id = parseInt(obj.attributes.data_id.value);
    let obj_name = obj.attributes.data_name.value;
    $("#DeleteModal").on('show.bs.modal', function (e) {
        var modal = $(this);
        modal.find('.modal-body').text("Yakin ingin hapus data Promo " + obj_name + "?");
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
        url: appUrl + "/Promo/" + id,
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
            notif({msg: "<b>Connection Error!</b>", type: "error", position: "center"});
        },
        complete: function() {
            $("#DeleteModal").modal('hide');
            $('#btn-hps').prop('disabled', false);
            $('#btn-hps').text('Hapus');
        }
    });
}