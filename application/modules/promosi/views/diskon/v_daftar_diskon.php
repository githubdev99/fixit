<div class="row page-title">
    <div class="col-12">
        <h4 class="mb-1 mt-0">Pengaturan Diskon</h4>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link active status_load" data-load="all" onclick="load_table({ status_diskon: 'all' });">
                        Semua <span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link status_load" data-load="aktif" onclick="load_table({ status_diskon: 'aktif' });">
                        Aktif <span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link status_load" data-load="tidak aktif" onclick="load_table({ status_diskon: 'tidak aktif' });">
                        Tidak Aktif <span></span>
                    </a>
                </li>
            </ul>
            <div class="card-body">
                <?php for ($i=0; $i < 3; $i++) { ?>
                    <div class="ph-item loading-produk">
                        <div class="ph-col-12">
                            <div class="ph-row">
                                <div class="ph-col-12 big"></div>
                            </div>
                        </div>

                        <div class="ph-col-1">
                            <div class="ph-row">
                                <div class="ph-col-12"></div>
                            </div>
                        </div>
                        
                        <div class="ph-col-1">
                            <div class="ph-picture" style="height: 100px !important;"></div>
                        </div>

                        <div class="ph-col-4">
                            <div class="ph-row">
                                <div class="ph-col-4"></div>
                                <div class="ph-col-8 empty"></div>
                                <div class="ph-col-6"></div>
                                <div class="ph-col-6 empty"></div>
                                <div class="ph-col-8"></div>
                            </div>
                        </div>

                        <div class="ph-col-2">
                            <div class="ph-row">
                                <div class="ph-col-12"></div>
                            </div>
                        </div>

                        <div class="ph-col-2">
                            <div class="ph-row">
                                <div class="ph-col-12"></div>
                            </div>
                        </div>

                        <div class="ph-col-2">
                            <div class="ph-row">
                                <div class="ph-col-12"></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="table-responsive after-loading-produk" style="display: none;">
                    <table id="datatable" class="table table-bordered table-hover" style="width: 100%;">
                        <thead class="table-info">
                            <tr>
                                <th>No.</th>
                                <th>Nama Produk</th>
                                <th>Harga</th>
                                <th>Periode</th>
                                <th>Status</th>
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
</div>

<!-- Modal -->
<div class="modal fade" id="atur_diskon" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content p-3">
            <form action="<?= base_url() ?>promosi/diskon" method="post" enctype="multipart/form-data" name="diskon">
                <div class="modal-header">
                    <h5 class="modal-title">Atur Diskon</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive bg-shadow">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="vertical-align: top;">Produk</th>
                                    <th style="vertical-align: top;">Harga Normal</th>
                                    <th style="vertical-align: top;">Harga Diskon</th>
                                    <th width="180px" style="vertical-align: top;">Diskon (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-3">
                                                <img src="" width="50" class="img-responsive" id="foto">
                                            </div>
                                            <div class="col">
                                                <span id="nama_produk"></span>
                                                <br>
                                                <span id="kode_sku"></span>
                                                <br>
                                                <span id="kategori_produk"></span>
                                                <br>
                                                <span id="stok"></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span id="harga_normal"></span></td>
                                    <td><span id="harga_variasi">Belum ada diskon</span></td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" name="presentase_diskon" class="form-control" maxlength="3" onkeypress="number_only(event)" required>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">%</div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h5 class="modal-title mt-5 mb-3">Periode Diskon</h5>
                    <div class="bg-shadow">
                        <div class="row p-3">
                            <div class="col-xl-4 col-sm-6">
                                <div class="form-group mt-3 mt-sm-0">
                                    <label>Mulai <span class="text-danger">*</span></label>
                                    <input type="text" name="tgl_mulai_diskon" class="form-control datepicker periode_diskon" placeholder="Tanggal Mulai Diskon" required>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6">
                                <div class="form-group mt-3 mt-sm-0">
                                    <label>Berakhir <span class="text-danger">*</span></label>
                                    <input type="text" name="tgl_berakhir_diskon" class="form-control datepicker periode_diskon" placeholder="Tanggal Berakhir Diskon" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_data">
                    <input type="hidden" name="harga_normal">
                    <input type="hidden" name="harga_variasi">
                    <input type="hidden" name="nominal_diskon">

                    <button type="button" class="btn btn-soft-dark mr-2" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-info" name="atur_diskon" value="atur_diskon" disabled>Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="ubah_diskon" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content p-3">
            <form action="<?= base_url() ?>promosi/diskon" method="post" enctype="multipart/form-data" name="diskon">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Diskon</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive bg-shadow">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="vertical-align: top;">Produk</th>
                                    <th style="vertical-align: top;">Harga Normal</th>
                                    <th style="vertical-align: top;">Harga Diskon</th>
                                    <th width="180px" style="vertical-align: top;">Diskon (%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-3">
                                                <img src="" width="50" class="img-responsive" id="foto">
                                            </div>
                                            <div class="col">
                                                <span id="nama_produk"></span>
                                                <br>
                                                <span id="kode_sku"></span>
                                                <br>
                                                <span id="kategori_produk"></span>
                                                <br>
                                                <span id="stok"></span>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span id="harga_normal"></span></td>
                                    <td><span id="harga_variasi">Belum ada diskon</span></td>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" name="presentase_diskon" class="form-control" maxlength="3" onkeypress="number_only(event)" required>
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">%</div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <h5 class="modal-title mt-5 mb-3">Periode Diskon</h5>
                    <div class="bg-shadow">
                        <div class="row p-3">
                            <div class="col-xl-4 col-sm-6">
                                <div class="form-group mt-3 mt-sm-0">
                                    <label>Mulai <span class="text-danger">*</span></label>
                                    <input type="text" name="tgl_mulai_diskon" class="form-control datepicker" placeholder="Tanggal Mulai Diskon" required>
                                </div>
                            </div>
                            <div class="col-xl-4 col-sm-6">
                                <div class="form-group mt-3 mt-sm-0">
                                    <label>Berakhir <span class="text-danger">*</span></label>
                                    <input type="text" name="tgl_berakhir_diskon" class="form-control datepicker" placeholder="Tanggal Berakhir Diskon" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer row">
                    <input type="hidden" name="id_data">
                    <input type="hidden" name="harga_normal">
                    <input type="hidden" name="harga_variasi">
                    <input type="hidden" name="nominal_diskon">

                    <div class="col">
                        <button type="button" class="btn btn-soft-danger" onclick="hapus_diskon();"><i class="fas fa-times mr-2"></i>Hapus Diskon</button>
                    </div>
                    <div class="col text-right">
                        <button type="button" class="btn btn-soft-dark mr-2" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-info" name="ubah_diskon" value="ubah_diskon">Ubah</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>