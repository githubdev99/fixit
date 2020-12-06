<style>
.HeightModalCustom {
			max-height: 450px;
			width: 100%;
			overflow-y: auto;
			display: inline-block;
		}
</style>
<div class="row page-title">
    <div class="col-12">
        <h3 class="mb-1 mt-0"> Flashsale Toko</h3>
    </div>
</div>



    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">      
                <h5> Tambahkan Produk Flashsale </h5>   
                <span style="color:#888;">Atur Diskon Produk Flashsale Toko mu.</span>      
                <p align="right">
                    <a href="javascript:;" class="btn btn-info btn-sm" onclick="modal('tambah');">Tambahkan Produk</a>
                </p>
   
                    <hr>

                    <div class="row">
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-lg-1 col-form-label"> Sesi :   <?php echo $get_data->time_live ?></label>
                                <br>
                                <label class=" col-form-label">Tanggal  :  <?=  date('d M Y', strtotime($get_data->date_live))?></label>
                                <div class="col-lg-1 col-lg-5">

                                </div>
                            </div>   <br>                     

                                      
                        </div>                 
            
                    </div>

                    
                    <form action="<?= base_url() ?>promosi/flashsale/produk_ditambahkan" method="post" enctype="multipart/form-data" name="flashsale">

                    <table id="datatable" class="table table-bordered table-hover">
                    <thead class="">
                        <tr>
                            <th>No.</th>
                            <th>Produk</th>
                            <th>Harga Awal</th>
                            <th width="100px">Diskon</th>
                            <th  width="100px">Stok Flashsale</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                    </table>
                    <!-- <div class="table-responsive">
                                                    <table class="table table-bordered mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th >Produk</th>
                                                                <th >Harga Awal</th>
                                                                <th >Diskon</th>
                                                                <th >Stok Saat Ini</th>
                                                                <th >Stok Flashsale</th>
                                                                <th >Aksi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="table1" >
                                                        
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td >
                                                                    <div class="input-group">                                                                  
                                                                        <input type="text" name="stok[]" class="form-control" placeholder="0" onkeypress="number_only(event)">
                                                                    </div>
                                                                </td><td>                                                   
                                                                </td>
                                                                <td >
                                                                  
                                                                    <input type="text" name="stok[]" class="form-control" placeholder="0" onkeypress="number_only(event)">
                                                                </td>
                                                                <td >
                                                                    <p  id="remove_button"><a href="javascript:;" class="text-danger ml-1" onclick="remove_variasi();"><i class="fas fa-trash mr-2"></i>Hapus</a></p>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div> -->




                <!-- <p><br><b>Selected rows data:</b></p>
                    <pre id="example-console-rows"></pre> -->


              
                </div>
            </div>
        </div>

  


        <div class="col-12">
            <div class="row">
                <div class="col text-right">
                    <a href="<?= base_url() ?>promosi/flashsale" class="btn btn-soft-dark  mr-2">Batal</a>
                    <button type="submit" class="btn btn-info " name="insert" value="insert">Buat flashsale</button>
                </div>
            </div>
        </div>
    </div>
</form>




    

<div class="modal fade" id="tambah_produk" tabindex="-1" role="dialog" aria-hidden="true" >
    <div class="modal-dialog modal-dialog-centered modal-xl" style="width:60%;">
        <div class="modal-content p-3" >
            <div class="modal-header">
                <h5 class="modal-title">Tambah Produk Flashsale</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body HeightModalCustom" >
                 <!-- <select class="form-control select2 " name="id_produk" id="produk" data-placeholder="Pilih Produk" required style="width:500px;"></select><br><br> -->
                 
                 <!-- <form id="frm-example" action="" method="POST">
                <table id="example" class="table table-bordered table-hover" style="align:center;width:100%;">
                    <thead class="table-info">
                        <tr>
                            <th></th>
                            <th>Nama Produk</th>
                            <th>Harga </th>
                            <th>Stok</th>
                      
                    </thead>
                    <tbody>
                    </tbody>
                    </table> 
              
                </form> -->

<div class="row" style="margin-bottom: 70px;">
	<div class="container">
		<div class="col-md-12">
				<div style="margin-top: 3%;">
	
				</div>
				<div class="clearfix"></div>

				<div class="Layout BlurOriginal Buled" style="margin-top:2%;">
					<div class="LayoutContent">
						<div class="table-responsive">
							<table class="table tabel-striped Table_JajaID">
								<thead class="table-info">
									<tr class="text-center">
										<td ></td>
										<td >Produk</td>
										<td >Harga Satuan</td>
										<td>Kuantitas</td>
									</tr>
								</thead>

								<tbody class="">
								</tbody>

							</table>
						</div>
					</div>
				</div>

				<div id="getFlashsale"> </div>

              
				

		</div>
	</div>
</div>





            </div>
                <div class="modal-footer">
                     <div id="jumlahp"></div>
                    <button type="button" class="btn btn-soft-dark mr-2" data-dismiss="modal">Tutup</button>          
                    <!-- <button  type="button" class="btn btn-info" data-dismiss="modal">Konfirmasi</button> -->
                    <!-- <button type="submit" class="btn btn-info" name="flashsale_button" id="flashsale_button" value="flashsale_button">Konfirmasi</button> -->
                    
                    <a href="<?= base_url() ?>promosi/flashsale/produk_ditambahkan" class="btn btn-info"><i class="fas fa-plus mr-2"></i>Konfirmasi</a>
                <!-- <div id="konfirmasi"></div> -->
                    <!-- <button class="btn btn-info" >Konfirmasi</button> -->
                </div>

          
        </div>
    </div>
</div>

