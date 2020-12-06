<style>
.progressbar {
  margin: 0;
  padding: 0;
  counter-reset: step;
}
.progressbar li {
  list-style-type: none;
  width: 25%;
  float: left;
  font-weight: 500;
  font-size: 12px;
  position: relative;
  text-align: center;

  color: #7d7d7d;
}
.progressbar li:before {
  width: 15px;
  height: 15px;
  content: '';
  line-height: 30px;
  border: 3px solid #7d7d7d;
  display: block;
  text-align: center;
  margin: 0 auto 10px auto;
  border-radius: 50%;
  background-color: white;
}
.progressbar li:after {
  width: 100%;
  height: 5px;
  content: '';
  position: absolute;
  background-color: #7d7d7d;
  top: 15px;
  left: -50%;
  z-index: -1;
}
.progressbar li:first-child:after {
  content: none;
}
.progressbar li.active {
  color: #dc3545;
}
.progressbar li.active:before {
  border-color: #dc3545;
}
.progressbar li.active + li:after {
  background-color: #dc3545;
}
</style>



<div class="row page-title">
    <div class="col-12">
        <h4 class="mb-1 mt-0">Daftar Pesanan</h4>
    </div>
</div>
                                                    
<div class="row">
    <div class="col-12">
        <div class="card">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link active status_load" data-load="all" onclick="load_table({ status_transaksi: 'all', tgl_transaksi: $('#filter_tgl_transaksi').val() });">
                        Semua
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link  status_load" data-load="pesanan-baru" onclick="load_table({ status_transaksi: 'pesanan-baru', tgl_transaksi: $('#filter_tgl_transaksi').val() });">
                        Pesanan Baru
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link status_load" data-load="belum-dibayar" onclick="load_table({ status_transaksi: 'belum-dibayar', tgl_transaksi: $('#filter_tgl_transaksi').val() });">    
                        Belum Dibayar
                    </a>
                </li>
                <li class="nav-item">
                  <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link status_load" data-load="perlu-dikirim" onclick="load_table({ status_transaksi: 'perlu-dikirim', tgl_transaksi: $('#filter_tgl_transaksi').val() });">    
                        Perlu Dikirim
                    </a>
                </li>
                <li class="nav-item">
                     <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link status_load" data-load="dikirimkan" onclick="load_table({ status_transaksi: 'dikirimkan', tgl_transaksi: $('#filter_tgl_transaksi').val() });">
                        Dikirimkan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link status_load" data-load="selesai" onclick="load_table({ status_transaksi: 'selesai', tgl_transaksi: $('#filter_tgl_transaksi').val() });">
                        Selesai
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link status_load" data-load="pembatalan" onclick="load_table({ status_transaksi: 'pembatalan', tgl_transaksi: $('#filter_tgl_transaksi').val() });">
                        Dibatalkan
                    </a>
                </li>
            </ul>

            <div class="card-body">
                <div class="form-row align-items-center mb-4">
                    <div class="col-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text"><i class="fas fa-calendar-day"></i></div>
                            </div>
                            <input type="text" class="form-control daterangepicker" id="filter_tgl_transaksi" placeholder="Tanggal Transaksi" onchange="show_button(this.value, '#filter_data');">
                            <button type="button" class="ml-3 btn btn-info" id="filter_data" disabled><i class="fas fa-filter mr-2"></i>Filter</button>
                        </div>
                    </div>
                    <div class="col text-right">
                        <div class="float-left">

                            <button type="button" id="clear_filter" class="btn btn-danger hide-element" style="display: none;"><i class="fas fa-sync-alt mr-2"></i>Clear</button>
                        </div>
                        <div class="float-right">
                            <div class="btn-group dropdown">
                                <button class="btn btn-soft-dark dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-download mr-2"></i>Unduh Laporan
                                </button>
                                <form class="dropdown-menu dropdown-lg p-3 dropdown-menu-right box-shadow" style="width: 500px; border: 1px solid #e6e6e6;">
                                    <div class="form-group">
                                        <label>Rentang Tanggal</label>
                                        <input type="text" class="form-control daterangepicker" placeholder="Tanggal Transaksi" onchange="show_button(this.value, '.download_data');">
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col">
                                            <button type="button" class="btn btn-danger btn-block download_data" disabled><i class="fas fa-file-pdf mr-2"></i>Format PDF</button>
                                        </div>
                                        <div class="col">
                                            <button type="button" class="btn btn-success btn-block download_data" disabled><i class="fas fa-file-excel mr-2"></i>Format Excel</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <br>

                <?php for ($i=0; $i < 3; $i++) { ?>
                    <div class="ph-item loading-pesanan">
                        <div class="ph-col-12">
                            <div class="ph-row">
                                <div class="ph-col-12 big"></div>
                            </div>
                        </div>
                        
                        <div class="ph-col-2">
                            <div class="ph-picture"></div>
                        </div>

                        <div class="ph-col-2">
                            <div class="ph-row">
                                <div class="ph-col-8"></div>
                                <div class="ph-col-12"></div>
                                <div class="ph-col-10"></div>
                                <div class="ph-col-12 empty"></div>
                                <div class="ph-col-12 empty"></div>
                                <div class="ph-col-12 empty"></div>
                                <div class="ph-col-12"></div>
                            </div>
                        </div>

                        <div class="ph-col-3">
                            <div class="ph-row">
                                <div class="ph-col-8"></div>
                                <div class="ph-col-12"></div>
                                <div class="ph-col-12"></div>
                                <div class="ph-col-10"></div>
                                <div class="ph-col-2 empty"></div>
                                <div class="ph-col-10"></div>
                            </div>
                        </div>

                        <div class="ph-col-2">
                            <div class="ph-row">
                                <div class="ph-col-10"></div>
                                <div class="ph-col-2 empty"></div>
                                <div class="ph-col-12"></div>
                                <div class="ph-col-8"></div>
                            </div>
                        </div>

                        <div class="ph-col-3">
                            <div class="ph-row">
                                <div class="ph-col-8"></div>
                                <div class="ph-col-4 empty"></div>
                                <div class="ph-col-12"></div>
                            </div>
                        </div>

                        <div class="ph-col-12">
                            <div class="ph-row">
                                <div class="ph-col-12 big"></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="table-responsive after-loading-pesanan" style="display: none;">
                    <table id="datatable1" class="table table-borderless" style="width: 100%;">
                        <thead>
                            <tr>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade"  id="ModalTerima" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index:8888;">
	<div class="modal-dialog" role="document" style="width:50%;">
	<form method="POST" action="?" enctype="multipart/form-data">

			<div class="modal-content">
			
				<div class="modal-header border-bottom">
					<h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-shield-alt" style="color:#444!important;"></i> Terima Pesanan &rsaquo; <span id="NamaProduk"></span></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>

				<div class="modal-body" style="padding:30px!important;">
					<p style="font-family:Open Sans;">Terima Pesanan Ini ? </p>
					<input type="hidden" name="id_data" class="form-control" required="" id="id_data">
					<input type="hidden" name="status_pembatalan" >
					<input type="hidden" name="terima_pesanan" value="terima">
					

					<input type="hidden" name="invoice" id="invoice">
				</div>
	
				<div class="modal-footer border-top">
					<button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
					<button type="submit" class="btn btn-info" style="margin-left:2%!important;" name="update_pesanan" value="update_pesanan">Konfirmasi</button>
				</div>

			</div>
		</form>
	</div>
</div>


<div class="modal fade"  id="ModalTolak" name="ModalTolak" tabindex="-1" role="dialog" aria-hidden="true">
	<div  role="document" class="modal-dialog modal-dialog-centered modal-xl" style="width:25%;">
	    <form method="POST" action="<?= base_url() ?>penjualan/pesanan" enctype="multipart/form-data" name="tolak_pesanan">
			<div class="modal-content">			
				<div class="modal-body" >
                <button type="button" class="swal2-close" data-dismiss="modal" aria-label="Close this dialog" style="display: flex;">×</button>
                    <span class="swal2-icon swal2-warning swal2-icon-show" style="display: flex;" >
                        <span class="swal2-icon-content"> ! </span>														
                    </span>
                    <span class="swal2-icon " style="display: flex; width:400px;" >
                        <h2>Tolak Pesanan Ini ?</h2>
                    </span>
                    <!-- <span> Alasan Ditolak : </span><br> -->
					<p style="font-family:Open Sans;">Kenapa Anda Menolak Pesanan Ini ? </p>

                    <div class="" style="display: flex; float:center;" > 
                    <input type="text" name="alasan_tolak"  id="alasan_tolak"  class="form-control" placeholder="Masukkan Alasan Pesanan Ditolak" required="">
                    <!-- <textarea  name="alasan_tolak" id="alasan_tolak"  class="form-control" ></textarea> -->
                    <input type="hidden" name="get_id_data_inv" class="form-control" required="" id="get_id_data_inv">
	   
                    </div>	
				</div>
	
				<div class="modal-footer border-top">
                     <button type="button" class=" swal2-confirm swal2-styled" style="display: inline-block; background-color: rgb(100, 176, 201);" data-dismiss="modal">Batal</button>
                    <button type="submit" name="tolak" value="tolak" class="swal2-cancel swal2-styled"  style="display: inline-block; background-color: rgb(221, 51, 51);">Tolak</button> 

				</div>

			</div>
		</form>
	</div>
</div>

<div class="modal fade"  id="IsiNomorResi"  name="IsiNomorResi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="z-index:8888;">
    <div class="modal-dialog" role="document" style="min-width:20%;">
        <form action="<?= base_url() ?>penjualan/pesanan" method="post" enctype="multipart/form-data" name="update_resi">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="exampleModalLabel"><i class="fas fa-shipping-fast" style="color:#708090!important;"></i> Masukkan Nomor Resi
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="padding:30px!important;">
                    <div class="form-group">
                        <!-- <input type="hidden" id="manual_booking" name="tipe_pengiriman" value="manual booking"> -->
                        <label>Nomor Resi</label>
                        <input type="text" name="get_nomor_resi" id="get_nomor_resi" class="form-control" required="" placeholder="Masukkan Nomor Resi dari Kurir/Expedisi">

                        <input type="hidden" name="get_id_data_order">
                        <input type="hidden" id="get_id_data" name="get_id_data">
                        <input type="hidden" id="invoice" name="invoice">

                    </div>
                    <div class="row">
                        <div style="margin:10px!important;padding:20px!important;border-radius: 4px;background: #f0f8ff;text-align:right!important;">
                            <h5 style="text-align:right;color:#555!important;">
                                <span>Invoice <br>                                                                                    
                                    <span id="get_invoice" style="color:#64B0C9;font-size:12pt!important;font-weight:bold;"></span>
                                </span>
                            </h5>
                            <h5 style="text-align:right;text-transform: Capitalize;color:#555!important;">Pengiriman Kurir:<br>
                                <span id="get_pengiriman" style="font-weight:bold;color:#64B0C9;font-size:13pt!important;"><br></span></h5>
                            <h5 style="text-align:right;text-transform: Capitalize;color:#555!important;">Biaya Ongkir Estimasi:<br> 
                                <span id="get_biaya_ongkir" style="font-weight:bold;color:#64B0C9;font-size:13pt!important;"><br></span></h5>
                            <small style="text-align:left!important;">	
                                <b>Catatan:</b><br>
                                Harga Ongkos Kirim diatas adalah harga yang dipilih oleh pembeli melalui estimasi biaya pengiriman dari harga kurir sesuai jarak dari lokasi toko ke lokasi pengiriman pembeli.
                            </small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-soft-secondary mr-2" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info" name="insert_resi" value="insert_resi">Update</button> 
                </div>
            </div>
        </form>
    </div>
</div>

<!-- <div class="modal" id="modalTrack" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
											<div class="modal-dialog" role="document" style="min-width: 30%;">
												<div class="modal-content">
												<div class="modal-header">
													<center>
														<h5 class="modal-title" id="exampleModalLongTitle">Track</h5>
													</center>
													<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
													</button>
												</div>
												<div id="list-track" class="mt-2 mb-2">

												</div>
												</div>
												</div>
											</div> -->