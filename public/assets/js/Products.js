const appUrl = $('meta[name="route"]').attr('content');
var edit = 0;

$(document).ready(function () {
    $('#header-title').text('Daftar Produk');
    $('#nama-alrt').hide();
    $('#harga-alrt').hide();
    $('#stock-alrt').hide();
    $("#AddEditModal").on('hide.bs.modal', function (e) {
        $('#nama').css('border-color', 'lightgray');
        $('#nama-alrt').hide();
        $('#harga').css('border-color', 'lightgray');
        $('#harga-alrt').hide();
        $('#stock').css('border-color', 'lightgray');
        $('#stock-alrt').hide();
        ClearInput();
    });
    $("#Add-btn").click(function() {
        ShowAddModals(this);
    });
    $("#btn-add-pdct").click(function() {
        AddProduct(this);
    });
    $('#btn-edit-pdct').click(function () {
        EditProduct(this);
    });
});

// Modals for Show Details
function ShowDetails(obj) {
    let actionTitle = 'Informasi Produk';
    $('#form').attr('method', "");
    $('#form').attr('action', "");
    $("#AddEditModal").on('show.bs.modal', function (e) {
        var modal = $(this);
        modal.find('.modal-title').text(actionTitle);
        $('#gallery-photo-add').hide();
        $("#btn-edit-pdct").hide();
        $("#btn-add-pdct").hide();
    });
    GetProduct(parseInt(obj.attributes.data_id.value));
}

// Modals for Add Data
function ShowAddModals() {
    let actionTitle = 'Tambah Layanan Produk';
    $('#form').attr('method', "POST");
    $('#form').attr('action', appUrl + "/Product");
    ClearInput();
    $("#AddEditModal").on('show.bs.modal', function (e) {
        var modal = $(this);
        modal.find('.modal-title').text(actionTitle);
        $('#gallery-photo-add').show();
        $("#btn-add-pdct").show();
        $("#btn-edit-pdct").hide();
    });
}

// Modals for Edit Data
function ShowEditModals(obj) {
    let actionTitle = 'Ubah Layanan Produk';
    edit = parseInt(obj.attributes.data_id.value);
    $('#form').attr('method', "PUT");
    $('#form').attr('action', appUrl + "/Product");
    $("#AddEditModal").on('show.bs.modal', function (e) {
        var modal = $(this);
        modal.find('.modal-title').text(actionTitle);
        $('#gallery-photo-add').hide();
        $("#btn-edit-pdct").show();
        $("#btn-add-pdct").hide();
    });
    GetProduct(edit);
}

function ClearInput() {
    $('div.gallery').html("");
    $('#images').val(null);
    $('#nama').val('');
    $('#desc').val('');
    $('#stock').val('');
    $('#harga').val('');
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
    if ($('#stock').val().trim() == "" || $('#stock').val().trim() == 0) {
        $('#stock').css('border-color', 'Red');
        $('#stock-alrt').show();
        isValid = false;
    } else {
        $('#stock').css('border-color', 'lightgray');
        $('#stock-alrt').hide();
    }
    return isValid;
}

function DisableBtn(selector) {
    $(selector).prop('disabled', true);
    $(selector).text('Tunggu ...');
}

function AddProduct() {
    if (!validasi()) return false;
    DisableBtn('#btn-add-pdct');
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
    formData.append("stock", $('#stock').val());
    formData.append("harga", $('#harga').val());

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
            $('#btn-add-pdct').prop('disabled', false);
            $('#btn-add-pdct').text('Tambah');
            ClearInput();
        }
    });
}

function GetProduct(id) {
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: "GET",
        url: appUrl + "/Product/" + id,
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
                        let imgExtension = images[i].pp_filename.split('.').pop();
                        $($.parseHTML('<img width="100" height="auto" id="service-img">'))
                            .attr('src', 'data:image/'+imgExtension+';base64,'+images[i].pp_photo)
                            .appendTo(gallery);
                    }
                }
                $('#nama').val(data.product[0].pdct_nama);
                $('#desc').val(data.product[0].pdct_desc);
                $('#stock').val(data.product[0].pdct_stock);
                $('#harga').val(data.product[0].pdct_harga);
                $("#AddEditModal").modal('show');
            }
        },
        error: function () {
            notif({msg: "<b>Connection Error!</b>", type: "error", position: "center"});
        }
    });
}

function EditProduct() {
    if (!validasi()) return false;
    DisableBtn('#btn-edit-vnu');

    var formData = new FormData();
    formData.append("id", edit);
    formData.append("nama", $('#nama').val());
    formData.append("desc", $('#desc').val());
    formData.append("stock", $('#stock').val());
    formData.append("harga", $('#harga').val());

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

function DeleteProduct(obj) {
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
        url: appUrl + "/Product",
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