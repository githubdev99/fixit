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

<form action="<?= base_url() ?>promosi/flashsale/edit/<?= $get_data->id_data ?>" method="post" enctype="multipart/form-data" name="flashsale">

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
                                <label class="col-lg-4  col-form-label"><b><?= $get_data->judul_flashsale ?></b></label>
                        
                                    <!-- <input type="text" name="judul_flashsale" value="<?= $get_data->judul_flashsale ?> " class="form-control " placeholder=" Judul  "> -->
                               
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label"> Sesi   </label>
                                <div class="col-lg-5">
                                    <label  class="col-lg-2 col-form-label"><?= $get_data->time_live ?> </label>
                                    <!-- <select class="form-control select2" name="time_live" onchange="" data-placeholder="Pilih Sesi" required>
                                        <option value="<?= $get_data->time_live ?>"><?= $get_data->time_live ?></option>
                                        <option value="09:00">09:00</option>
                                        <option value="18:00">18:00</option>
                                    </select> -->
                                </div>
                            </div>
    
                        

                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Tanggal  </label>
                                <div class="col-lg-5">
                                    <label  class="col-lg-5 col-form-label"><?= date('d M Y',strtotime($get_data->date_live)) ?> </label>
                                    <!-- <input type="text" name="date_live" class="form-control datepicker" placeholder="Pilih Tanggal" value="<?= date('d M Y',strtotime($get_data->date_live)) ?>"> -->
                                </div>
                            </div>

                         
                
                        </div>
                    </div>
                </div>
            </div>
        </div>


      
    </div>
</form>

<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                <table id="datatable1" class="table table-bordered table-hover">
                    <thead class="">
                        <tr >
                            <th >No.</th>
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

</div></div></div></div>



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
                    <a href="<?= base_url() ?>promosi/flashsale/edit/<?= encrypt_text($get_data->id_data) ?> " class="btn btn-info"><i class="fas fa-plus mr-2"></i>Konfirmasi</a>

                </div>          
        </div>
    </div>
</div>



<div class="modal fade" id="edit_produk" name="edit_produk" tabindex="-1" role="dialog" aria-hidden="true" >
    <div class="modal-dialog modal-dialog-centered modal-xl" style="width:40%;">
        <div class="modal-content " >
            <div class="modal-header">
                <h5 class="modal-title">Edit Produk Flashsale</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body HeightModalCustom" >
            <form action="<?= base_url() ?>promosi/flashsale/produk_flashsale" method="post" enctype="multipart/form-data" name="edit_produk_flash">

                <!-- <input type="text" name="id_flashsale" class="form-control" " >
                <input type="text" name="id_data" class="form-control" " > -->


                <div class="row" style="margin-bottom: 50px; align:center;">
                    <div class="container">
                        <div class="col-md-12">
                        <div class="form-group row" style="align:center;">
                                <label class="col-lg-1 col-form-label" > </label>
                                <label class="col-form-label" >Diskon  <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                            <input type="text" name="diskon" class="form-control" maxlength="3" onkeypress="number_only(event)" onkeyup="running_rupiah('diskon', this.value)">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">%</div>
                                            </div>
                                        </div>
                                </div>
                                <label class=" col-form-label">Stok Flashsale  <span class="text-danger">*</span></label>
                                <div class="col-lg-3">
                                    <input type="text" name="jumlah_flashsale" class="form-control" onkeypress="number_only(event)" >
                                </div>
                        </div>  
                 

                        </div>
                    </div>
                </div>
            </div>
                <div class="modal-footer">
                     <div id="jumlahp"></div>
                    <button type="button" class="btn btn-soft-dark mr-2 btn-sm" data-dismiss="modal">Tutup</button>                       
                    <button type="submit" class="btn btn-info " name="edit_produk" value="edit_produk">Tambah</button>
                </div> 
            </form>         
        </div>
    </div>
</div>