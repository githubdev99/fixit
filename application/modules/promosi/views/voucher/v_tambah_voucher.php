<div class="row page-title">
    <div class="col-12">
        <h4 class="mb-1 mt-0">Buat Voucher</h4>
    </div>
</div>

<form action="<?= base_url() ?>promosi/voucher/tambah" method="post" enctype="multipart/form-data" name="voucher">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>Informasi Voucher</h4>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Kode Voucher <span class="text-danger">*</span></label>
                                <div class="col-lg-5">
                                    <input type="text" name="kode_promo" class="form-control" placeholder="Masukkan Nama Voucher" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Nama Voucher <span class="text-danger">*</span></label>
                                <div class="col-lg-5">
                                    <input type="text" name="judul_promo" class="form-control" placeholder="Masukkan Nama Voucher" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Target Voucher <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" id="no" name="tipe_promo" class="custom-control-input" value="no" onclick="show_target_voucher(this.value);" checked required>
                                        <label class="custom-control-label" for="no">Semua Produk</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="yes" name="tipe_promo" class="custom-control-input" value="yes" onclick="show_target_voucher(this.value);" required>
                                        <label class="custom-control-label" for="yes">Per-Kategori</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-border-dashed hide-element" id="tipe_promo_choice">
                                <div class="form-group row mt-1">
                                    <label class="col-lg-3 col-form-label">Kategori Produk <span class="text-danger">*</span></label>
                                    <div class="col-lg-6">
                                        <select class="form-control select2" name="id_kategori" id="kategori" data-placeholder="Pilih Kategori">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row hide-element" id="subKategori">
                                    <label class="col-lg-3 col-form-label">Sub Kategori Produk <span class="text-danger">*</span></label>
                                    <div class="col-lg-6">
                                        <select class="form-control select2" name="id_sub_kategori" id="sub_kategori" data-placeholder="Pilih Sub Kategori">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row mt-4">
                                <label class="col-lg-2 col-form-label">Jenis Potongan <span class="text-danger">*</span></label>
                                <div class="col-lg-5">
                                    <select class="form-control select2" name="tipe_diskon" onchange="show_tipe_diskon(this.value);" data-placeholder="Pilih Jenis Potongan" required>
                                        <option></option>
                                        <option value="nominal">Dalam Rupiah</option>
                                        <option value="presentase">Dalam Persentase</option>
                                    </select>

                                    <div id="dalam_rupiah" class="hide-element">
                                        <div class="input-group mt-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Rp.</div>
                                            </div>
                                            <input type="text" name="nominal_diskon" class="form-control" placeholder="Nominal Diskon" onkeypress="number_only(event)" onkeyup="running_rupiah('nominal_diskon', this.value)">
                                        </div>
                                        <span class="help-block">Hanya berisi angka (0-9)</span>
                                    </div>

                                    <div id="dalam_persentase" class="hide-element">
                                        <div class="input-group mt-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">%</div>
                                            </div>
                                            <input type="text" name="persentase_diskon" class="form-control" placeholder="Persentase Diskon" onkeypress="number_only(event)" onkeyup="running_rupiah('persentase_diskon', this.value)">
                                        </div>
                                        <span class="help-block">Hanya berisi angka (0-9)</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Kuota Voucher <span class="text-danger">*</span></label>
                                <div class="col-lg-5">
                                    <input type="text" name="kuota_voucher" class="form-control" placeholder="Masukkan Jumlah Voucher" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>Periode</h4>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <div class="col-lg-5">
                                    <input type="text" name="mulai" class="form-control datepicker" placeholder="Tanggal Mulai Voucher">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Tanggal Berakhir <span class="text-danger">*</span></label>
                                <div class="col-lg-5">
                                    <input type="text" name="berakhir" class="form-control datepicker" placeholder="Tanggal Berakhir Voucher">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>Media</h4>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Banner <span class="text-danger">*</span> &ensp;<i class="fas fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="Gunakan foto yang memiliki kecerahan cukup, disarankan untuk mengatur bagian foto yang ingin di crop"></i></label>
                                <div class="col-lg-5">
                                    <div class="Produk_JajaId">
                                        <div class="FotoProdukImg">
                                            <input type="file" class="form-control CustomBorderForm FileProduk NoBorder" accept=".png, .jpg, .jpeg" onchange="loadFile(event,1);" id="FileProduct_1" required>
                                            <input type="hidden" id="FileProductFix_1" name="banner_promo">
                                            <img id="output_1" class="OutputImg">
                                            <input type="hidden" id="CekFoto_1" value="T">
                                            <i class="fas fa-plus-circle Plus" id="plus_1"></i>
                                            <div class="BottomProduk" id="BottomProduk_1" data-type>
                                                <a href="javascript:" data-toggle="modal" data-target="#ModalCroping" onclick="ShowCroping(1); Croppie(1);" class="Croppie" id="ClickShowNow_1">
                                                    <i class="fas fa-crop Plus_Delete" id="CropingButton" data-toggle="tooltip" data-placement="bottom" title="Crop Foto"></i>
                                                </a>
                                                <a href="javascript:" onclick="RemovePhoto(1);">
                                                    <i class="far fa-trash-alt Plus_Delete text-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Foto"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="row">
                <div class="col text-right">
                    <a href="<?= base_url() ?>promosi/voucher" class="btn btn-soft-dark btn-lg mr-2">Batal</a>
                    <button type="submit" class="btn btn-info btn-lg" name="insert" value="insert">Buat Voucher</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal -->
<div id="ModalCroping" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 8888;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Crop Foto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="justify-content: center; display: flex;">
                <div id="LayoutCroppie" class="text-center" style="width: 650px; height: 350px;">
                    <input type="hidden" id="DataID">
                    <img id="outputModal" class="OutputImg hide-element">
                </div>
            </div>
            <hr>
            <div class="modal-footer">
                <button type="button" class="btn btn-soft-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-info crop_image">Simpan</button>
            </div>
        </div>
    </div>
</div>