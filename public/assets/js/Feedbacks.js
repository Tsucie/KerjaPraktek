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
    GetFeedback(parseInt(obj.attributes.data_id.value));
}