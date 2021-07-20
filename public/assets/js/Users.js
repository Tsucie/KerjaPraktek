const appUrl = $('meta[name="route"]').attr('content');

$(document).ready(function () {
    $('#header-title').text('Daftar User Admin');
    $('#email-alrt').hide();
    $('#password-alrt').hide();
    $("#AddEditModal").on('hide.bs.modal', function (e) {
        $('#email').css('border-color', 'lightgray');
        $('#email-alrt').hide();
        $('#password').css('border-color', 'lightgray');
        $('#password-alrt').hide();
    });
    $("#Add-btn").click(function() {
        ShowAddModals(this);
    });
    $("#btn-add-user").click(function() {
        AddUser(this);
    });
    $('#btn-edit-user').click(function () {
        EditUser(this);
    });
});

// Modals for Show Details
function ShowDetails(obj) {
    let actionTitle = 'Informasi Admin';
    $('#form').attr('method', "");
    $('#form').attr('action', "");
    $("#AddEditModal").on('show.bs.modal', function (e) {
        var modal = $(this);
        modal.find('.modal-title').text(actionTitle);
        $('#pass-txtbox').hide();
        $("#btn-edit-user").hide();
        $("#btn-add-user").hide();
    });
    GetUser(parseInt(obj.attributes.data_id.value));
}

// Modals for Add Data
function ShowAddModals() {
    let actionTitle = 'Tambah User Admin';
    $('#form').attr('method', "POST");
    $('#form').attr('action', appUrl + "/User");
    ClearInput();
    $("#AddEditModal").on('show.bs.modal', function (e) {
        var modal = $(this);
        modal.find('.modal-title').text(actionTitle);
        $('#pass-txtbox').show();
        $("#btn-add-user").show();
        $("#btn-edit-user").hide();
    });
}

// Modals for Edit Data
function ShowEditModals(obj) {
    let actionTitle = 'Ubah User Admin';
    let obj_id = parseInt(obj.attributes.data_id.value);
    $('#form').attr('method', "PUT");
    $('#form').attr('action', appUrl + "/User/" + obj_id);
    $("#AddEditModal").on('show.bs.modal', function (e) {
        var modal = $(this);
        modal.find('.modal-title').text(actionTitle);
        $('#pass-txtbox').hide();
        $("#btn-edit-user").show();
        $("#btn-add-user").hide();
    });
    GetUser(obj_id);
}

function ClearInput() {
    $('#email').val('');
    $('#password').val('');
    $('#nama').val('');
}

function DisableBtn(selector) {
    $(selector).prop('disabled', true);
    $(selector).text('Tunggu ...');
}

// JQuery Validate
function validasi() {
    var isValid = true;
    if($('#email').val().trim() == "") {
        $('#email').css('border-color', 'Red');
        $('#email-alrt').show();
        isValid = false;
    } else {
        $('#email').css('border-color', 'lightgray');
        $('#email-alrt').hide();
    }
    return isValid;
}

function AddUser() {
    var res = validasi();
    if($('#password').val().trim() == "") {
        $('#password').css('border-color', 'Red');
        $('#password-alrt').show();
        res = false;
    } else {
        $('#password').css('border-color', 'lightgray');
        $('#password-alrt').hide();
    }
    if(res == false) return false;
    DisableBtn('#btn-add-user');
    var formData = new FormData();
    
    formData.append("email", $('#email').val());
    formData.append("password", $('#password').val());
    formData.append("nama", $('#nama').val());

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
            ClearInput();
        }
    });
}

function GetUser(id) {
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: "GET",
        url: appUrl + "/User/" + id,
        contentType: "application/json",
        success: function (data) {
            if (data != null) {
                $('#email').val(data[0].email);
                $('#nama').val(data[0].name);
                $("#AddEditModal").modal('show');
            }
            else {
                pesanAlert(data);
            }
        },
        error: function () {
            notif({msg: "<b>Connection Error!</b>", type: "error", position: "center"});
        }
    });
}

function EditUser() {
    var res = validasi();
    if(res == false) return false;
    DisableBtn('#btn-edit-user');
    var formData = new FormData();
    
    formData.append("email", $('#email').val());
    formData.append("nama", $('#nama').val());

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

function DeleteUser(obj) {
    let obj_id = parseInt(obj.attributes.data_id.value);
    let obj_name = obj.attributes.data_name.value;
    $("#DeleteModal").on('show.bs.modal', function (e) {
        var modal = $(this);
        modal.find('.modal-body').text("Yakin ingin hapus data User " + obj_name + "?");
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
        url: appUrl + "/User/" + id,
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
        }
    });
}