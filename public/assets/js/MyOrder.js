$(document).ready(function () {
  $('.nav-item').removeClass('active');
  getVenueOrders(parseInt($('#title-mo-page').attr('data_id')));
  // getProductOrders(parseInt($('#title-mo-page').attr('data_id')));
  if ($(window).width() < 500) {
    $('#myorder-title').attr('style', 'text-align: center;');
  }
});

function getVenueOrders(id) {
  let vnu_list = $('#vnu_orders');
  let request = { "cst_id": id };
  $.ajax({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    type: "GET",
    url: appUrl+'/VenueOrderGetListById',
    data: request,
    contentType: 'application/json',
    dataType: 'json',
    success: function (data) {
      try {
        let col = '';
        if ($(window).width() > 500) col = 'col-5 ';
        if (data.code == null) {
          let rowHtml = '';
          let status = { bg: '', text: '' };
          for (let i = 0; i < data.data.length; i++) {
            decideStatus(data.data[i].ov_status_order, status);
            if ((i%2) === 0) rowHtml = '';
            rowHtml += ((i%2) === 0 ? '<div class="row">' : '');
            rowHtml += (
            '<div class="'+col+'card rounded shadow m-2">' +
              '<a role="button" onclick="getVenueOrderDetail('+data.data[i].ov_id+')">' +
              '<div class="p-4">' +
                '<h5 style="text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">'+data.data[i].ov_vnu_nama+'</h5>' +
                '<ul style="list-style: none; padding-left: 1rem;">' +
                  '<li><b>Atas Nama         : </b>'+data.data[i].gst_nama+'</li>' +
                  '<li><b>Rencana Pemakaian : </b>'+data.data[i].gst_rencana_pemakaian+' - '+data.data[i].gst_waktu_pemakaian+'</li>' +
                  '<li><b>Biaya             : </b>Rp '+data.data[i].ov_sum_biaya+'</li>' +
                  '<li><b>Status Order      : </b>' +
                    '<button class="btn btn-sm '+status.bg+' text-white rounded shadow" role="button">' + status.text + '</button>' +
                  '</li>' +
                '</ul>' +
              '</div></a>' +
            '</div>'
            );
            rowHtml += ((i%2) !== 0 ? '</div>' : '');
            if ((i%2) !== 0 || (i+1) === data.data.length) vnu_list.append(rowHtml);
          }
        } else { throw new Error(); }
      } catch (error) {
        console.log(error);
        vnu_list.append('<p>Belum ada Pesanan nih ...</p>');
      }
    },
    error: function () {
      notif({msg: '<b style="color: white;">Connection Error!</b>', type: "error", position: notifAlign});
      vnu_list.append('<p>Belum ada Pesanan nih ...</p>');
    },
    complete: function () {}
  });
}

function getVenueOrderDetail(ov_id) {
  let modal = $('#order-detail-modal');
  $.ajax({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    type: "GET",
    url: appUrl+'/VenueOrder/'+parseInt(ov_id),
    contentType: 'application/json',
    dataType: 'json',
    success: function (data) {
      try {
        if (data.code == null) {
          modal.find('.modal-title').text('Detail Pemesanan Gedung/Ruangan');
          let status = { bg: '', text: '' };
          decideStatus(data[0].ov_status_order, status);
          modal.find('.modal-body').html(
            '<button type="button" class="btn-close position-absolute top-0 start-100 translate-middle" data-bs-dismiss="modal" aria-label="Close">&times;</button>' +
            '<h5 style="text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">'+data[0].ov_vnu_nama+'</h5>' +
            '<ul style="list-style: none; padding-left: 1rem;">' +
              '<hr><li><h6>Informasi Penyewa</h6></li><hr>'+
              '<li><b>Atas Nama         : </b>'+data[0].guest.gst_nama+'</li><hr class="dotted-hr">' +
              '<li><b>Rencana Pemakaian : </b>'+data[0].guest.gst_rencana_pemakaian+' - '+(
                data[0].venue.vnu_tipe_waktu == 1 ? data[0].guest.gst_waktu_pemakaian + ' jam' : data[0].guest.gst_waktu_pemakaian
              )+'</li><hr class="dotted-hr">' +
              '<li><b>Keperluan Pemakaian : </b>'+data[0].guest.gst_keperluan_pemakaian+'</li><hr class="dotted-hr"><br>' +
              '<hr><li><h6>Ketentuan Pemakaian</h6></li><hr>' +
              (data[0].venue.vnu_tipe_waktu == 0 ?
                '<li><b>Jam Pemakaian   : </b><br>Siang : '+data[0].venue.vnu_jam_pemakaian_siang+
                                           '<br>Malam : '+data[0].venue.vnu_jam_pemakaian_malam+'</li><hr class="dotted-hr">' :
                '<li><b>Jam Pemakaian   : </b><br>Dari Jam : '+data[0].venue.vnu_jam_pemakaian_siang+
                                           '<br>Sampai Jam : '+data[0].venue.vnu_jam_pemakaian_malam+'</li><hr class="dotted-hr">'
              ) +
              '<li><b>No. Telp/HP Admin : </b>'+data[0].ov_no_telp+'</li><hr class="dotted-hr">' +
              '<li><b>Harga Sewa        : </b>Rp '+data[0].ov_harga_sewa+'</li><hr class="dotted-hr">' +
              (data[0].ov_nama_catering == null || data[0].ov_fee_catering == null ?
                '' :
                '<li><b>Nama Catering   : </b>'+data[0].ov_nama_catering+'</li><hr class="dotted-hr">' +
                '<li><b>Fee Catering    : </b>Rp '+data[0].ov_fee_catering+'</li><hr class="dotted-hr">'
              ) +
              (data[0].ov_fee_pelaminan == null ? '' : '<li><b>Fee Pelaminan : </b>Rp '+data[0].ov_fee_pelaminan+'</li><hr class="dotted-hr">') +
              (data[0].ov_biaya_lain == null ? '' : '<li><b>Biaya lainnya : </b>Rp '+data[0].ov_biaya_lain+'</li><hr class="dotted-hr">') +
              (data[0].ov_more_facilities == null || data[0].ov_lain_lain == null ?
                '' :
                '<li><b>Fasilitas Tambahan : </b><br><textarea style="background-color: #fff;width: 100%;height: 8rem;" disabled>'+data[0].ov_more_facilities+'</textarea></li><hr class="dotted-hr">' +
                '<li><b>Fee Fasilitas Tambahan : </b>Rp '+data[0].ov_lain_lain+'</li><hr class="dotted-hr">'
              ) +
              (data[0].ov_sum_lain_lain == null ? '' : '<li><b>Total Biaya Tambahan : </b>Rp '+data[0].ov_sum_lain_lain+'</li><hr class="dotted-hr">') +
              '<li><b>Total Biaya       : Rp '+data[0].ov_sum_biaya+'</b></li><hr class="dotted-hr">' +
              (data[0].ov_down_payment == null ? '' : '<li><b>DP : Rp '+data[0].ov_down_payment+'</b></li><hr class="dotted-hr">') +
              (data[0].ov_remaining_payment == null ? '' : '<li><b>Pembayaran yang harus di lunaskan : Rp '+data[0].ov_remaining_payment+'</b></li><hr class="dotted-hr">') +
              '<li><b>Status Order      : </b>' +
                '<button class="btn btn-sm '+status.bg+' text-white rounded" role="button">' + status.text + '</button>' +
              '</li><hr class="dotted-hr">' +
              (data[0].ov_note_to_customer == null ? '' : '<li><b>Note : </b><br><textarea style="background-color: #fff;width: 100%;height: 8rem;" disabled>'+data[0].ov_note_to_customer+'</textarea></li><hr class="dotted-hr">') +
            '</ul><br>'
          );
          modal.modal('show');
        }
        else { throw new Error(); }
      } catch (error) {
        console.log(error);
        pesanAlert(data, notifAlign);
      }
    },
    error: function () {
      notif({msg: '<b style="color: white;">Connection Error!</b>', type: "error", position: notifAlign});
    },
    complete: function () {}
  });
}

function getProductOrders(id) {
  
}

function getProductOrderDetail(modal, op_id) {

}

function decideStatus(stat, status) {
  switch (stat) {
    case 0:
      status.bg = 'bg-warning';
      status.text = 'Sedang Di Proses';
      break;
    case 1:
      status.bg = 'bg-success';
      status.text = 'Terverifikasi';
      break;
    case 2:
      status.bg = 'bg-primary';
      status.text = 'Sudah Bayar DP';
      break;
    case 3:
      status.bg = 'bg-success';
      status.text = 'Sudah Lunas';
      break;
    case 4:
      status.bg = 'bg-danger';
      status.text = 'Order Ditolak';
      break;
    default:
      status.bg = 'bg-default';
      status.text = 'Tidak Diketahui';
      break;
  }
  return status;
}