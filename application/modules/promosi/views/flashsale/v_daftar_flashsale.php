<div class="row page-title">
    <div class="col-12">
        <div class="float-left">
            <h3 class="mb-1 mt-0">Flashsale Toko</h3>
        </div>
        <div class="float-right">
            <!-- <a href="<?= base_url() ?>promosi/flashsale/tambah" class="btn btn-success"><i class="fas fa-plus mr-2"></i>Buat Flashsale</a> -->
            <a href="javascript:;" class="btn btn-info" onclick="modal('tambah_flashsale');">Tambahkan Flashsale</a>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link active status_load" data-load="all" onclick="load_table({ status_produk: 'all' });">
                            Semua <span></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" data-toggle="tab" aria-expanded="true" class="nav-link status_load" data-load="hariini" onclick="load_table({ status_produk: 'hariini' });">
                            Sedang Berjalan <span></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link status_load" data-load="akandatang" onclick="load_table({ status_produk: 'akandatang' });">
                            Akan Datang <span></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link status_load" data-load="tidakaktif" onclick="load_table({ status_produk: 'tidakaktif' });">
                            Telah Berakhir <span></span>
                        </a>
                    </li> 
                </ul>

<!--                 
                                <label class="col-lg-2 col-form-label">Pilih Sesi <span class="text-danger">*</span></label>
                                <div class="col-lg-5">
                                    <select class="form-control select2" name="time_live" onchange="" data-placeholder="Pilih Sesi" required>
                                        <option></option>
                                        <option value="09:00">09:00</option>
                                        <option value="18:00">18:00</option>
                                    </select>
                                </div> -->
                      
             <div class="card-body">
                <table id="datatable" class="table table-bordered table-hover">
                    <thead class="table-info">
                        <!-- <tr>
                            <th>No.</th>
                            <th>Produk</th>
                            <th>Tanggal Live</th>
                            <th>Waktu Live</th>
                            <th>Jumlah Flashsale</th>
                            <th>Jumlah Terjual</th>
                            <th>Aksi</th>
                        </tr> -->

                        <tr>
                            <th>No.</th>
                            <th>Flashsale</th>
                            <th>Waktu Sesi</th>
                            <th>Jumlah Produk</th>
                            <!-- <th>Status</th> -->
                            <th>Aktifkan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="tambah_flashsale" tabindex="-1" role="dialog" aria-hidden="true" >
    <div class="modal-dialog modal-dialog-centered modal-xl" style="width:40%;">
        <div class="modal-content p-3" >
            <div class="modal-header">
                <h5 class="modal-title">Tambah Produk Flashsale</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body HeightModalCustom" >

                 <form action="<?= base_url() ?>promosi/flashsale/tambah" method="post" enctype="multipart/form-data" name="tambah_flashsale">


                 <div class="form-group ">
                    <label class="col-lg-2 col-form-label"> Pilih Sesi <span class="text-danger">*</span></label>
                    <div class="col-lg-10">
                        <select class="form-control select2" name="time_live" onchange="" data-placeholder="Pilih Sesi" required>
                            <option></option>
                            <option value="09:00">09:00 - 18:00 </option>
                            <option value="18:00">18:00 - 09:00 </option>
                         </select>
                    </div>
                </div>
                <div class="form-group ">
                    <label class="col-lg-2  col-form-label">Tanggal  <span class="text-danger">*</span></label>
                    <div class="col-lg-10">
                        <input type="text" name="date_live" class="form-control datepicker" placeholder="Pilih Tanggal  ">
                    </div>
                </div>

                <div class="form-group ">
                    <label class="col-lg-4  col-form-label">Judul Flashsale  <span class="text-danger">*</span></label>
                    <div class="col-lg-10">
                        <input type="text" name="judul_flashsale" class="form-control " placeholder=" Judul  ">
                    </div>
                </div>

              
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-soft-dark mr-2" data-dismiss="modal">Tutup</button>
                <!-- <button type="button" class="btn btn-info" ><i class="fas fa-plus mr-2"></i>Konfirmasi</button> -->
                <button type="submit" class="btn btn-info " name="insert" value="insert">Tambah</button>
            </div>

            </form>
        </div>
    </div>
</div>
