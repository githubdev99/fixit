<div class="row page-title">
    <div class="col-12">
        <h4 class="mb-1 mt-0"><i class="fas fa-store mr-2"></i> <?= $core['seller']->nama_toko ?></h4>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#informasi" data-toggle="tab" aria-expanded="false" class="nav-link active">
                        Informasi
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#banner" data-toggle="tab" aria-expanded="true" class="nav-link">
                        Banner Toko
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#lokasi" data-toggle="tab" aria-expanded="true" class="nav-link">
                        Lokasi
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#pengiriman" data-toggle="tab" aria-expanded="true" class="nav-link">
                        Pengiriman
                    </a>
                </li>


            </ul>
            <div class="card-body tab-content p-3">
                <div class="tab-pane show active" id="informasi">
                    <h5>Profil Toko</h5>
                    <hr>
                    <form action="<?= base_url() ?>pengaturan/toko" method="post" enctype="multipart/form-data" name="profil_toko">
                        <div class="row">
                            <div class="col">
                                <div class="form-group mt-3 mt-sm-0">
                                    <label>Nama Toko</label>
                                    <input type="text" name="nama_toko" class="form-control" value="<?= $core['seller']->nama_toko ?>" readonly>
                                    <span class="help-block">Nama toko tidak bisa diubah lagi</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group mt-3 mt-sm-0">
                                    <label>Greeting Message <span class="text-danger">*</span></label>
                                    <input type="text" name="greating_message" class="form-control" placeholder="Masukkan Greeting Message" required value="<?= $core['seller']->greating_message ?>">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div class="form-group mt-3 mt-sm-0">
                                    <label>Deskripsi Toko <span class="text-danger">*</span></label>
                                    <textarea name="deskripsi_toko" class="form-control" placeholder="Masukkan Deskripsi" required cols="20" rows="5"><?= $core['seller']->deskripsi_toko ?></textarea>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group mt-3 mt-sm-0">
                                    <label>Gambar Toko <span class="text-danger">*</span> &ensp;<i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="Gunakan foto yang memiliki kecerahan cukup, disarankan untuk mengatur bagian foto yang ingin di crop"></i></label>
                                    <br>
                                    <div class="Produk_JajaId">
                                        <div class="FotoProdukImg">
                                            <input type="file" class="form-control CustomBorderForm FileProduk NoBorder" accept=".png, .jpg, .jpeg" onchange="loadFile(event,1);" id="FileProduct_1">
                                            <input type="hidden" name="foto_old" value="<?= $core['seller']->foto ?>">
                                            <input type="hidden" id="FileProductFix_1" name="foto">
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
                        <div class="row">
                            <div class="col text-right">
                                <button type="submit" name="profil_toko" value="profil_toko" class="btn btn-info">Simpan</button>
                            </div>
                        </div>
                    </form>


                </div>

                <div class="tab-pane " id="banner">
                    <h5>Banner Toko</h5>
                    <hr>
                    <form action="<?= base_url() ?>pengaturan/toko" method="post" enctype="multipart/form-data" name="banner_toko">
                        <div class="row">
                            <div class="col">
                                <div class="form-group mt-3 mt-sm-0">
                                    <label>Banner Utama <span class="text-danger">*</span></label><br>
                                    <div class="Produk_JajaId">
                                        <div class="FotoProdukImg">
                                            <input type="file" class="form-control CustomBorderForm FileProduk NoBorder" accept=".png, .jpg, .jpeg" onchange="loadFile(event,2);" id="FileProduct_2">
                                            <input type="hidden" name="banner_old" value="<?= $core['seller']->banner ?>">
                                            <input type="hidden" id="FileProductFix_2" name="banner">
                                            <img id="output_2" class="OutputImg">
                                            <input type="hidden" id="CekFoto_2" value="T">
                                            <i class="fas fa-plus-circle Plus" id="plus_2"></i>
                                            <div class="BottomProduk" id="BottomProduk_2" data-type>
                                                <a href="javascript:" data-toggle="modal" data-target="#ModalCroping" onclick="ShowCroping(2); Croppie(2);" class="Croppie" id="ClickShowNow_2">
                                                    <i class="fas fa-crop Plus_Delete" id="CropingButton" data-toggle="tooltip" data-placement="bottom" title="Crop Foto"></i>
                                                </a>
                                                <a href="javascript:" onclick="RemovePhotoBanner(2, 'banner_old');">
                                                    <i class="far fa-trash-alt Plus_Delete text-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Foto"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <img src="<?= $core['folder_upload_other'] ?>file/<?= $core['seller']->foto ?>"> -->
                        <div class="row">
                            <div class="col">
                                <div class="form-group mt-3 mt-sm-0">
                                    <label>Banner Promo <span class="text-danger">*</span></label><br>


                                    <div class="Produk_JajaId">
                                        <div class="FotoProdukImg">
                                            <input type="file" class="form-control CustomBorderForm FileProduk NoBorder" accept=".png, .jpg, .jpeg" onchange="loadFile(event,3);" id="FileProduct_3">
                                            <input type="hidden" name="banner_old_1" value="<?= $core['seller']->banner_1 ?>">
                                            <input type="hidden" id="FileProductFix_3" name="banner_1">
                                            <img id="output_3" class="OutputImg">
                                            <input type="hidden" id="CekFoto_3" value="T">
                                            <i class="fas fa-plus-circle Plus" id="plus_3"></i>
                                            <div class="BottomProduk" id="BottomProduk_3" data-type>
                                                <a href="javascript:" data-toggle="modal" data-target="#ModalCroping" onclick="ShowCroping(3); Croppie(3);" class="Croppie" id="ClickShowNow_3">
                                                    <i class="fas fa-crop Plus_Delete" id="CropingButton" data-toggle="tooltip" data-placement="bottom" title="Crop Foto"></i>
                                                </a>
                                                <a href="javascript:" onclick="RemovePhotoBanner(3, 'banner_old_1');">
                                                    <i class="far fa-trash-alt Plus_Delete text-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Foto"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="Produk_JajaId">
                                        <div class="FotoProdukImg">
                                            <input type="file" class="form-control CustomBorderForm FileProduk NoBorder" accept=".png, .jpg, .jpeg" onchange="loadFile(event,4);" id="FileProduct_4">
                                            <input type="hidden" name="banner_old_2" value="<?= $core['seller']->banner_2 ?>">
                                            <input type="hidden" id="FileProductFix_4" name="banner_2">
                                            <img id="output_4" class="OutputImg">
                                            <input type="hidden" id="CekFoto_4" value="T">
                                            <i class="fas fa-plus-circle Plus" id="plus_4"></i>
                                            <div class="BottomProduk" id="BottomProduk_4" data-type>
                                                <a href="javascript:" data-toggle="modal" data-target="#ModalCroping" onclick="ShowCroping(4); Croppie(4);" class="Croppie" id="ClickShowNow_4">
                                                    <i class="fas fa-crop Plus_Delete" id="CropingButton" data-toggle="tooltip" data-placement="bottom" title="Crop Foto"></i>
                                                </a>
                                                <a href="javascript:" onclick="RemovePhotoBanner(4, 'banner_old_2');">
                                                    <i class="far fa-trash-alt Plus_Delete text-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Foto"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="Produk_JajaId">
                                        <div class="FotoProdukImg">
                                            <input type="file" class="form-control CustomBorderForm FileProduk NoBorder" accept=".png, .jpg, .jpeg" onchange="loadFile(event,5);" id="FileProduct_5">
                                            <input type="hidden" name="banner_old_3" value="<?= $core['seller']->banner_3 ?>">
                                            <input type="hidden" id="FileProductFix_5" name="banner_3">
                                            <img id="output_5" class="OutputImg">
                                            <input type="hidden" id="CekFoto_5" value="T">
                                            <i class="fas fa-plus-circle Plus" id="plus_5"></i>
                                            <div class="BottomProduk" id="BottomProduk_5" data-type>
                                                <a href="javascript:" data-toggle="modal" data-target="#ModalCroping" onclick="ShowCroping(5); Croppie(5);" class="Croppie" id="ClickShowNow_5">
                                                    <i class="fas fa-crop Plus_Delete" id="CropingButton" data-toggle="tooltip" data-placement="bottom" title="Crop Foto"></i>
                                                </a>
                                                <a href="javascript:" onclick="RemovePhotoBanner(5, 'banner_old_3');">
                                                    <i class="far fa-trash-alt Plus_Delete text-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Foto"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="Produk_JajaId">
                                        <div class="FotoProdukImg">
                                            <input type="file" class="form-control CustomBorderForm FileProduk NoBorder" accept=".png, .jpg, .jpeg" onchange="loadFile(event,6);" id="FileProduct_6">
                                            <input type="hidden" name="banner_old_4" value="<?= $core['seller']->banner_4 ?>">
                                            <input type="hidden" id="FileProductFix_6" name="banner_4">
                                            <img id="output_6" class="OutputImg">
                                            <input type="hidden" id="CekFoto_6" value="T">
                                            <i class="fas fa-plus-circle Plus" id="plus_6"></i>
                                            <div class="BottomProduk" id="BottomProduk_6" data-type>
                                                <a href="javascript:" data-toggle="modal" data-target="#ModalCroping" onclick="ShowCroping(6); Croppie(6);" class="Croppie" id="ClickShowNow_6">
                                                    <i class="fas fa-crop Plus_Delete" id="CropingButton" data-toggle="tooltip" data-placement="bottom" title="Crop Foto"></i>
                                                </a>
                                                <a href="javascript:" onclick="RemovePhotoBanner(6, 'banner_old_4');">
                                                    <i class="far fa-trash-alt Plus_Delete text-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Foto"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="Produk_JajaId">
                                        <div class="FotoProdukImg">
                                            <input type="file" class="form-control CustomBorderForm FileProduk NoBorder" accept=".png, .jpg, .jpeg" onchange="loadFile(event,7);" id="FileProduct_7">
                                            <input type="hidden" name="banner_old_5" value="<?= $core['seller']->banner_5 ?>">
                                            <input type="hidden" id="FileProductFix_7" name="banner_5">
                                            <img id="output_7" class="OutputImg">
                                            <input type="hidden" id="CekFoto_7" value="T">
                                            <i class="fas fa-plus-circle Plus" id="plus_7"></i>
                                            <div class="BottomProduk" id="BottomProduk_7" data-type>
                                                <a href="javascript:" data-toggle="modal" data-target="#ModalCroping" onclick="ShowCroping(7); Croppie(7);" class="Croppie" id="ClickShowNow_7">
                                                    <i class="fas fa-crop Plus_Delete" id="CropingButton" data-toggle="tooltip" data-placement="bottom" title="Crop Foto"></i>
                                                </a>
                                                <a href="javascript:" onclick="RemovePhotoBanner(7, 'banner_old_5');">
                                                    <i class="far fa-trash-alt Plus_Delete text-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Foto"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col text-right">
                                <button type="submit" name="banner_toko" value="banner_toko" class="btn btn-info">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="tab-pane" id="lokasi">
                    <h5>Lokasi Toko</h5>
                    <hr>
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="img-thumbnail img-responsive pl-3 pr-3">
                                <h5><?= $core['seller']->nama_toko ?></h5>
                                <label>Email : <br> <?= $core['customer']->email ?></label><br>
                                <label>Telepon : <br><?= (!empty($core['customer']->telepon)) ? $core['customer']->telepon : '-'; ?></label>
                            </div>
                        </div>
                        <div class="col-md-8 col-sm-12">
                            <div class="img-thumbnail img-responsive pl-3 pr-3">
                                <div class="row">
                                    <div class="col">
                                        <h6>Alamat</h6>
                                        <label><?= $core['seller']->alamat_toko ?></label>
                                    </div>
                                    <div class="col text-right">
                                        <button type="button" data-toggle="modal" data-target="#edit_alamat" class="btn btn-soft-dark btn-sm mt-1">
                                            <i class="fas fa-pen mr-2"></i>Edit
                                        </button>
                                    </div>
                                </div>
                                <br>
                                <label>Provinsi : <br><?= $core['seller']->nama_provinsi ?></label> &emsp;
                                <label>Kota/Kabupaten : <br><?= $core['seller']->nama_kota ?></label> &emsp;
                                <label>Kecamatan : <br><?= $core['seller']->nama_kecamatan ?></label> &emsp;
                                <label>Kelurahan : <br><?= $core['seller']->nama_kelurahan ?></label> &emsp;
                                <label>Kode Pos : <br><?= $core['seller']->kode_pos ?></label> &emsp;
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane" id="pengiriman">
                    <h5>Kurir Pengiriman</h5>
                    <input type="hidden" id="kurir">
                    <hr>
                    <div class="row" id="kurir_toko">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                <div id="LayoutCroppie" class="text-center" style="width: 512px; height: 512px;">
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

<div id="ModalCroping1" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 8888;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Crop Foto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="justify-content: center; display: flex;">
                <div id="LayoutCroppie1" class="text-center" style="width: 1110x; height: 260;">
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

<div class="modal fade" id="edit_alamat" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-3">
            <form action="<?= base_url() ?>pengaturan/toko" method="post" enctype="multipart/form-data" name="ubah_alamat">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Alamat Toko : <?= $core['seller']->nama_toko ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="modal_rekening('daftar');">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Provinsi <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select class="form-control select2" name="provinsi" required>
                                        <option value="">Pilih Provinsi</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Kota/Kabupaten <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select class="form-control select2" name="kota_kabupaten" required>
                                        <option value="">Pilih Kota/Kabupaten</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Kecamatan <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select class="form-control select2" name="kecamatan" required>
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Kelurahan <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select class="form-control select2" name="kelurahan" required>
                                        <option value="">Pilih Kelurahan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Kode Pos <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" name="kode_pos" class="form-control" placeholder="Masukkan Kode Pos" value="<?= $core['seller']->kode_pos ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Alamat Toko <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <textarea name="alamat_toko" class="form-control" placeholder="Masukkan Alamat Toko" required cols="20" rows="5"><?= $core['seller']->alamat_toko ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger mr-2" data-dismiss="modal" onclick="modal_rekening('daftar');">Batal</button>
                    <button type="submit" class="btn btn-info" name="ubah_alamat" value="ubah_alamat">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>