const appUrl = $('meta[name="route"]').attr('content');
var edit = 0;

$(document).ready(function () {
    $('#header-title').text('Daftar Order Produk Masuk');
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
    $('#btn-edit-ov').click(function () {
        EditOrder(this);
    });
});