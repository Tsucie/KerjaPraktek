const appUrl = $('meta[name="route"]').attr('content');

$(document).ready(function () {
    $('#header-title').text('Daftar User Customer');
});

// Modals for Show Details
function ShowDetails(obj) {
    let vnu_ord = parseInt(obj.attributes.data_vnu_ord.value);
    let pdct_ord = parseInt(obj.attributes.data_pdct_ord.value);
    $('#total-ord').val('Total Order: '+(vnu_ord+pdct_ord));
    $('#vnu-ord').val('Order Venue: '+vnu_ord);
    $('#pdct-ord').val('Order Produk: '+pdct_ord);
    GetUser(parseInt(obj.attributes.data_id.value));
}

function DisableBtn(selector) {
    $(selector).prop('disabled', true);
    $(selector).text('Tunggu ...');
}

function GetUser(id) {
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: "GET",
        url: appUrl + "/Customer/" + id,
        contentType: "application/json",
        success: function (data) {
            if (data != null) {
                $('#nama').val(data[0].cst_name);
                $('#email').val(data[0].cst_email);
                $('#no_telp').val(data[0].cst_no_telp);
                $('#alamat').val(data[0].cst_alamat);
                $("#DetailModal").modal('show');
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

function DeleteUser(obj) {
    let obj_id = parseInt(obj.attributes.data_id.value);
    let obj_name = obj.attributes.data_name.value;
    $("#DeleteModal").on('show.bs.modal', function (e) {
        var modal = $(this);
        modal.find('.modal-body').text("Yakin ingin hapus data Customer " + obj_name + "?");
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
        url: appUrl + "/Customer/" + id,
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