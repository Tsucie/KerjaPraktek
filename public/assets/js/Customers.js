const appUrl = $('meta[name="route"]').attr('content');

$(document).ready(function () {
    $('#header-title').text('Daftar User Customer');
});

// Modals for Show Details
function ShowDetails(obj) {
    GetUser(parseInt(obj.attributes.data_id.value));
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