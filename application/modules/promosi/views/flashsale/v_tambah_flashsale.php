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
        <h4 class="mb-1 mt-0">Buat Flashsale Toko</h4>
    </div>
</div>

<form action="<?= base_url() ?>promosi/flashsale/tambah" method="post" enctype="multipart/form-data" name="flashsale">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5>Tambahkan Produk Flashsale</h5>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-lg-1 col-form-label"> Sesi : </label>
                                <div class="col-lg-5">
                                   
                                </div>
                                <label class=" col-form-label">Tanggal  : </label>
                                <div class="col-lg-1 col-lg-5">

                                </div>
                            </div>   <br>                     

                            <div class="form-group row" align="center">
                            <label class="col-lg-1 col-form-label"></label>
                             <p   class="form-control"  style="align:center;resize:none;width:900px;height:200px;"><br><br><br>
                                <a href="javascript:;" class="btn btn-info" onclick="modal('tambah');">Tambahkan Produk</a>
                                 <br><br><span style="color:#888;">Silahkan Tambahkan Produk untuk Flashsale di Toko mu.</span>
                             </p>
                            </div>                
                        </div>                 
            
                    </div>
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
                 <!-- <form id="frm-example" action="" method="POST"> -->

                 <form action="<?= base_url() ?>promosi/flashsale/tambah_produk" method="post" enctype="multipart/form-data" name="tambah_produk">


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
                    <!-- <p><button>Submit</button></p>
                    <p><b>Selected rows data:</b></p>
                    <pre id="example-console-rows"></pre>

                    <p><b>Form data as submitted to the server:</b></p>
                    <pre id="example-console-form"></pre> -->

              
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-soft-dark mr-2" data-dismiss="modal">Tutup</button>
                <!-- <button type="button" class="btn btn-info" ><i class="fas fa-plus mr-2"></i>Konfirmasi</button> -->
                <button type="submit" class="btn btn-info " name="tambah_produk" value="tambah_produk">Tambah</button>

                <!-- <a href="<?= base_url() ?>promosi/flashsale/produk_ditambahkan" class="btn btn-info"><i class="fas fa-plus mr-2"></i>Konfirmasi</a> -->

            </div>

            </form>
        </div>
    </div>
</div>

