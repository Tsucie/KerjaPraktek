const appUrl = $('meta[name="route"]').attr('content');
var edit = 0;

$(document).ready(function () {
    $('#header-title').text('Daftar Feedback');
});

// Modals for Show Details
function ShowDetails(obj) {
    let actionTitle = 'Informasi Pemesanan';
    $("#AddEditModal").on('show.bs.modal', function (e) {
        var modal = $(this);
        modal.find('.modal-title').text(actionTitle);
    });
    GetFeedback(parseInt(obj.attributes.data_id.value), obj.attributes.data_nama.value);
}

function GetFeedback(id, name) {
    $.ajax({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        type: "GET",
        url: appUrl + "/Feedback/" + id,
        contentType: "application/json",
        success: function (data) {
            if (data.code != null) {
                pesanAlert(data);
            }
            else {
                $('#cst_name').val(data[0].fb_cst_nama);
                $('#cst_email').val(data[0].fb_cst_email);
                $('#review').val(name);
                let rating = data[0].fb_rating;
                let ratingHtml = '<h5>'+rating+'  ';
                for (let i = 0; i < 5; i++) {
                    let star = '<span class="fa fa-star'+(rating <= i ? '' : ' checked') +'"></span>';
                    ratingHtml += star;
                }
                ratingHtml += '</h5>';
                $('#fb_rating').html(ratingHtml);
                $('#fb_text').val(data[0].fb_text);
                $("#AddEditModal").modal('show');
            }
        },
        error: function () {
            notif({msg: "<b>Connection Error!</b>", type: "error", position: "center"});
        }
    });
}