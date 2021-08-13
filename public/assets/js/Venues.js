const appUrl = $('meta[name="route"]').attr('content');
var edit = 0;

$(document).ready(function () {
    $('#header-title').text('Daftar Gedung');
    $('#nama-alrt').hide();
    $('#harga-alrt').hide();
    $("#AddEditModal").on('hide.bs.modal', function (e) {
        $('#nama').css('border-color', 'lightgray');
        $('#nama-alrt').hide();
        $('#harga').css('border-color', 'lightgray');
        $('#harga-alrt').hide();
        ClearInput();
    });
    $('#tipe_waktu').on('change', function (e) {
        toggleHourInputs(this);
    });
    $("#Add-btn").click(function() {
        ShowAddModals(this);
    });
    $("#btn-add-vnu").click(function() {
        AddVenue(this);
    });
    $('#btn-edit-vnu').click(function () {
        EditVenue(this);
    });
});

// Modals for Show Details
function ShowDetails(obj) {
    let actionTitle = 'Informasi Gedung';
    $('#form').attr('method', "");
    $('#form').attr('action', "");
    $("#AddEditModal").on('show.bs.modal', function (e) {
        var modal = $(this);
        modal.find('.modal-title').text(actionTitle);
        $('#gallery-photo-add').hide();
        $("#btn-edit-vnu").hide();
        $("#btn-add-vnu").hide();
    });
    GetVenue(parseInt(obj.attributes.data_id.value));
}

// Modals for Add Data
function ShowAddModals() {
    let actionTitle = 'Tambah Layanan Gedung';
    $('#form').attr('method', "POST");
    $('#form').attr('action', appUrl + "/Venue");
    ClearInput();
    $("#AddEditModal").on('show.bs.modal', function (e) {
        var modal = $(this);
        modal.find('.modal-title').text(actionTitle);
        $('#gallery-photo-add').show();
        $("#btn-add-vnu").show();
        $("#btn-edit-vnu").hide();
    });
}

// Modals for Edit Data
function ShowEditModals(obj) {
    let actionTitle = 'Ubah Layanan Gedung';
    edit = parseInt(obj.attributes.data_id.value);
    $('#form').attr('method', "PUT");
    $('#form').attr('action', appUrl + "/Venue");
    $("#AddEditModal").on('show.bs.modal', function (e) {
        var modal = $(this);
        modal.find('.modal-title').text(actionTitle);
        $('#gallery-photo-add').show();
        $("#btn-edit-vnu").show();
        $("#btn-add-vnu").hide();
    });
    GetVenue(edit);
}

function ClearInput() {
    $('div.gallery').html("");
    $('#gallery-photo-add').val(null);
    $('#nama').val('');
    $('#desc').val('');
    $('#fasilitas').val('');
    $('#harga').val('');
    $('#jam_siang').val('');
    $('#jam_malam').val('');
}

function toggleHourInputs(selector) {
    switch ($(selector).val()) {
        case "0":
            $('#siang').show();
            $('#malam').show();
            break;
        case "1":
            $('#siang').hide();
            $('#malam').hide();
            break;
        default:
            $('#siang').show();
            $('#malam').show();
            break;
    }
}

// JQuery Image Preview
$(function() {
    // Multiple images preview in browser
    var imagesPreview = function(input, placeToInsertImagePreview) {
        $('div.gallery').html("");
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

    $('#gallery-photo-add').on('change', function() {
        imagesPreview(this, 'div.gallery');
    });
});

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
    return isValid;
}

function DisableBtn(selector) {
    $(selector).prop('disabled', true);
    $(selector).text('Tunggu ...');
}

function AddVenue() {
    if (!validasi()) return false;
    DisableBtn('#btn-add-vnu');
    var formData = new FormData();
    let input = $('#gallery-photo-add')[0];
    if (input.files) {
        let length = input.files.length;
        for (let i = 0; i < length; i++) {
            formData.append("images"+i, input.files[i]);
        }
        formData.append("imgLength", input.files.length);
    }
    formData.append("nama", $('#nama').val());
    formData.append("desc", $('#desc').val());
    formData.append("fasilitas", $('#fasilitas').val());
    formData.append("harga", $('#harga').val());
    formData.append("tipe_waktu", $('#tipe_waktu').val());
    formData.append("jam_siang", $('#jam_siang').val());
    formData.append("jam_malam", $('#jam_malam').val());
    formData.append("status_tersedia", $('#status_tersedia').val());

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
            $('#btn-add-vnu').prop('disabled', false);
            $('#btn-add-vnu').text('Tambah');
            ClearInput();
        }
    });
}

function GetVenue(id) {
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: "GET",
        url: appUrl + "/Venue/" + id,
        contentType: "application/json",
        success: function (data) {
            if (data.code != null) {
                pesanAlert(data);
            }
            else {
                if (data.photos.length > 0) {
                    let images = data.photos;
                    var gallery = 'div.gallery';
                    $(gallery).html("");
                    for (let i = 0; i < images.length; i++) {
                        let imgExtension = images[i].vp_filename.split('.').pop();
                        $($.parseHTML('<img width="100" height="auto" id="service-img">'))
                            .attr('src', 'data:image/'+imgExtension+';base64,'+images[i].vp_photo)
                            .appendTo(gallery);
                    }
                }
                $('#nama').val(data.venue[0].vnu_nama);
                $('#desc').val(data.venue[0].vnu_desc);
                $('#fasilitas').val(data.venue[0].vnu_fasilitas);
                $('#harga').val(data.venue[0].vnu_harga);
                $('#tipe_waktu').val(data.venue[0].vnu_tipe_waktu);
                if (data.venue[0].vnu_tipe_waktu == 1) {
                    $('#siang').hide();
                    $('#malam').hide();
                } else {
                    $('#siang').show();
                    $('#malam').show();
                    $('#jam_siang').val(data.venue[0].vnu_jam_pemakaian_siang);
                    $('#jam_malam').val(data.venue[0].vnu_jam_pemakaian_malam);
                }
                $('#status_tersedia').val(data.venue[0].vnu_status_tersedia);
                setTimeout(function () { $("#AddEditModal").modal('show'); }, 1000);
            }
        },
        error: function () {
            notif({msg: "<b>Connection Error!</b>", type: "error", position: "center"});
        }
    });
}

function EditVenue() {
    if (!validasi()) return false;
    DisableBtn('#btn-edit-vnu');
    var formData = new FormData();
    let input = $('#gallery-photo-add')[0];
    if (input.files) {
        let length = input.files.length;
        for (let i = 0; i < length; i++) {
            formData.append("images"+i, input.files[i]);
        }
        formData.append("imgLength", input.files.length);
    }
    formData.append("id", edit);
    formData.append("nama", $('#nama').val());
    formData.append("desc", $('#desc').val());
    formData.append("fasilitas", $('#fasilitas').val());
    formData.append("harga", $('#harga').val());
    formData.append("tipe_waktu", $('#tipe_waktu').val());
    formData.append("jam_siang", $('#jam_siang').val());
    formData.append("jam_malam", $('#jam_malam').val());
    formData.append("status_tersedia", $('#status_tersedia').val());

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

function DeleteVenue(obj) {
    let obj_id = parseInt(obj.attributes.data_id.value);
    let obj_name = obj.attributes.data_name.value;
    $("#DeleteModal").on('show.bs.modal', function (e) {
        var modal = $(this);
        modal.find('.modal-body').text("Yakin ingin hapus data Layanan " + obj_name + "?");
        $('#btn-hps').attr('data_id', obj_id);
    });
    $("#DeleteModal").modal('show');
}

function Delete(obj) {
    DisableBtn('#btn-hps');
    let obj_id = { "id": parseInt(obj.attributes.data_id.value) };
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: "DELETE",
        url: appUrl + "/Venue",
        dataType: "json",
        contentType: "application/json",
        data: JSON.stringify(obj_id),
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