$(document).ready(function () {
  $('.nav-item').removeClass('active');
  getVenueOrders(parseInt($('#title-mo-page').attr('data_id')));
  getProductOrders(parseInt($('#title-mo-page').attr('data_id')));
  if ($(window).width() < 500) {
    $('#myorder-title').attr('style', 'text-align: center;');
  }
});

var modal = $('#order-detail-modal');

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
        if (data.data != null && data.data.length > 0) {
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
        // console.log(error);
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
                '<li><b>Fasilitas Tambahan : </b><br><textarea style="background-color: #fff;width: 100%;height: 4rem;" disabled>'+data[0].ov_more_facilities+'</textarea></li><hr class="dotted-hr">' +
                '<li><b>Fee Fasilitas Tambahan : </b>Rp '+data[0].ov_lain_lain+'</li><hr class="dotted-hr">'
              ) +
              (data[0].ov_sum_lain_lain == null ? '' : '<li><b>Total Biaya Tambahan : </b>Rp '+data[0].ov_sum_lain_lain+'</li><hr class="dotted-hr">') +
              '<li><b>Total Biaya       : Rp '+data[0].ov_sum_biaya+'</b></li><hr class="dotted-hr">' +
              (data[0].ov_down_payment == null ? '' : '<li><b>DP : Rp '+data[0].ov_down_payment+'</b></li><hr class="dotted-hr">') +
              (data[0].ov_remaining_payment == null ? '' : '<li><b>Pembayaran yang harus di lunaskan : Rp '+data[0].ov_remaining_payment+'</b></li><hr class="dotted-hr">') +
              '<li><b>Status Order      : </b>' +
                '<button class="btn btn-sm '+status.bg+' text-white rounded" role="button">' + status.text + '</button>' +
              '</li><hr class="dotted-hr">' +
              (data[0].ov_note_to_customer == null ? '' : '<li><b>Note : </b><br><textarea style="background-color: #fff;width: 100%;height: 4rem;" disabled>'+data[0].ov_note_to_customer+'</textarea></li><hr class="dotted-hr">') +
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
  let pdct_list = $('#pdct_orders');
  let request = { "cst_id": id };
  $.ajax({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    type: "GET",
    url: appUrl+'/ProductOrderGetListById',
    data: request,
    contentType: 'application/json',
    dataType: 'json',
    success: function (data) {
      try {
        let col = '';
        if ($(window).width() > 500) col = 'col-5 ';
        if (data.data != null && data.data.length > 0) {
          let rowHtml = '';
          let status = { bg: '', text: '' };
          for (let i = 0; i < data.data.length; i++) {
            decideStatus(data.data[i].op_status_order, status);
            if (data.data[i].op_status_order == 2) status.text = 'Sudah Bayar';
            else if (data.data[i].op_status_order == 3) status.text = 'Sudah Dikirim';
            if ((i%2) === 0) rowHtml = '';
            rowHtml += ((i%2) === 0 ? '<div class="row">' : '');
            rowHtml += (
            '<div class="'+col+'card rounded shadow m-2">' +
              '<a role="button" onclick="getProductOrderDetail('+data.data[i].op_id+')">' +
              '<div class="p-4">' +
                '<h5 style="text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">'+data.data[i].pdct_nama+'</h5>' +
                '<ul style="list-style: none; padding-left: 1rem;">' +
                  '<li><b>Atas Nama         : </b>'+data.data[i].cst_name+'</li>' +
                  '<li><b>Tanggal Order     : </b>'+data.data[i].op_tanggal_order+'</li>' +
                  '<li><b>Biaya             : </b>Rp '+(data.data[i].op_sum_biaya == null ? data.data[i].op_sum_harga_produk : data.data[i].op_sum_biaya)+'</li>' +
                  '<li><b>Status Order      : </b>' +
                    '<button class="btn btn-sm '+status.bg+' text-white rounded shadow" role="button">' + status.text + '</button>' +
                  '</li>' +
                '</ul>' +
              '</div></a>' +
            '</div>'
            );
            rowHtml += ((i%2) !== 0 ? '</div>' : '');
            if ((i%2) !== 0 || (i+1) === data.data.length) pdct_list.append(rowHtml);
          }
        } else { throw new Error(); }
      } catch (error) {
        // console.log(error);
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

function getProductOrderDetail(op_id) {
  $.ajax({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
    type: "GET",
    url: appUrl+'/ProductOrder/'+parseInt(op_id),
    contentType: 'application/json',
    dataType: 'json',
    success: function (data) {
      try {
        if (data.code == null) {
          modal.find('.modal-title').text('Detail Pemesanan Gedung/Ruangan');
          let status = { bg: '', text: '' };
          decideStatus(data[0].op_status_order, status);
          modal.find('.modal-body').html(
            '<button type="button" class="btn-close position-absolute top-0 start-100 translate-middle" data-bs-dismiss="modal" aria-label="Close">&times;</button>' +
            '<h5 style="text-shadow: 1px 1px 2px rgba(0,0,0,0.2);">'+data[0].product.pdct_nama+'</h5>' +
            '<ul style="list-style: none; padding-left: 1rem;">' +
              '<hr><li><h6>Informasi Pemesan</h6></li><hr>'+
              '<li><b>Atas Nama : </b>'+data[0].customer.cst_name+'</li><hr class="dotted-hr">' +
              '<li><b>Alamat    : </b><br><textarea style="background-color: #fff;width: 100%;height: 4rem;" disabled>'+data[0].op_alamat_pemesanan+'</textarea></li><hr class="dotted-hr">' +
              '<li><b>Tanggal Pesan : </b>'+data[0].op_tanggal_order+'</li><hr class="dotted-hr"><br>' +
              '<hr><li><h6>Detail Pemesanan Produk</h6></li><hr>' +
              '<li><b>Lokasi pengiriman produk dari: </b><br><textarea style="background-color: #fff;width: 100%;height: 4rem;" disabled>'+data[0].op_lokasi_pengiriman+'</textarea></li><hr class="dotted-hr">' +
              '<li><b>Produk dikirim ke: </b><br><textarea style="background-color: #fff;width: 100%;height: 4rem;" disabled>'+data[0].op_alamat_pemesanan+'</textarea></li><hr class="dotted-hr">' +
              '<li><b>Kode Produk : </b>'+data[0].detail.odp_pdct_kode+'</li><hr class="dotted-hr">' +
              '<li><b>Harga Produk : </b>Rp '+data[0].detail.odp_pdct_harga+'</li><hr class="dotted-hr">' +
              '<li><b>Jumlah : </b>'+data[0].detail.odp_pdct_qty+'</li><hr class="dotted-hr">' +
              '<li><b>Total Harga Produk : Rp '+data[0].op_sum_harga_produk+'</b></li><hr class="dotted-hr">' +
              ((data[0].op_harga_ongkir != null) ?
                '<li><b>Ongkos Kirim : </b>Rp '+data[0].op_harga_ongkir+'</li><hr class="dotted-hr">' : ''
              ) +
              ((data[0].op_persen_pajak != null && data[0].op_nominal_pajak != null) ?
                '<li><b>Pajak : </b>'+data[0].op_persen_pajak+'%</li><hr class="dotted-hr">' +
                '<li><b>Nominal : </b>Rp '+data[0].op_nominal_pajak+'</li><hr class="dotted-hr">' : ''
              ) +
              ((data[0].op_sum_biaya != null) ?
                '<li><b>Total Biaya : Rp '+data[0].op_sum_biaya+'</b></li><hr class="dotted-hr">' : ''
              ) +
              (data[0].op_resi_file != null ?
                '<li><b>Resi Pembayaran : </b><br><img src="data:image/'+data[0].op_resi_filename.split('.').pop()+';base64,'+data[0].op_resi_file+'" width="200" height="auto" style="padding: 4px; margin: 8px;border-radius: 4px; box-shadow: 1px 1px 4px rgba(0, 0, 0, 0.2);"/></li><hr class="dotted-hr">' : ''
              ) +
              '<li><b>Status Order : </b>' +
                '<button class="btn btn-sm '+status.bg+' text-white rounded" role="button">' + status.text + '</button>' +
              '</li><hr class="dotted-hr">' +
              (data[0].op_note_to_customer == null ? '' : '<li><b>Note : </b><br><textarea style="background-color: #fff;width: 100%;height: 4rem;" disabled>'+data[0].op_note_to_customer+'</textarea></li><hr class="dotted-hr">') +
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