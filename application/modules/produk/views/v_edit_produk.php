<div class="row page-title">
    <div class="col-12">
        <h4 class="mb-1 mt-0">Tambah Produk</h4>
    </div>
</div>

<form action="<?= base_url() ?>produk/edit/<?= encrypt_text($get_data['master']->id_produk) ?>" method="post" enctype="multipart/form-data" name="produk">
    <input type="hidden" name="produk_variasi_harga" value="<?= $get_data['master']->variasi_harga ?>">
    <input type="hidden" name="id_variasi" value="<?= (strtolower($get_data['master']->variasi_harga) == 't') ? encrypt_text($get_data['variasi'][0]->id_data) : ''; ?>">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>Pilih Kategori</h4>
                    <hr>
                    <div class="row">
                        <div class="col-xl-4 col-sm-6">
                            <div class="form-group mt-3 mt-sm-0">
                                <label>Kategori <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="id_kategori" id="kategori" data-placeholder="Pilih Kategori" required>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-4 col-sm-6 hide-element" id="subKategori">
                            <div class="form-group mt-3 mt-sm-0">
                                <label>Sub Kategori <span class="text-danger">*</span></label>
                                <select class="form-control select2" name="id_sub_kategori" id="sub_kategori" data-placeholder="Pilih Sub Kategori">
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>Informasi Produk</h4>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Nama Produk <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <input type="text" name="nama_produk" class="form-control" placeholder="Sepatu Sneakers Pria Tokostore Kanvas Hitam Seri C28B" maxlength="100" onkeyup="show_maxlength('nama_produk');" required value="<?= $get_data['master']->nama_produk ?>">
                                    <span class="help-block float-right" id="maxlength_nama_produk">0/100</span>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Deskripsi Produk <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <textarea class="form-control" name="deskripsi" rows="15" maxlength="5000" onkeyup="show_maxlength('deskripsi');" placeholder="Sepatu Sneakers Pria Tokostore Kanvas Hitam Seri C28B

- Model simple
- Nyaman Digunakan
- Tersedia warna hitam
- Sole PVC (injection shoes) yang nyaman dan awet untuk digunakan sehari - hari

Bahan:
Upper: Semi Leather (kulit tidak pecah-pecah)
Sole: Premium Rubber Sole

Ukuran
39 : 25,5 cm
40 : 26 cm
41 : 26.5 cm
42 : 27 cm
43 : 27.5 - 28 cm

Edisi terbatas dari Tokostore dengan model baru dan trendy untukmu. Didesain untuk bisa dipakai dalam berbagai acara. Sangat nyaman saat dipakai sehingga dapat menunjang penampilan dan kepercayaan dirimu. Beli sekarang sebelum kehabisan!" required><?= $get_data['master']->deskripsi ?></textarea>
                                    <span class="help-block float-right" id="maxlength_deskripsi">0/5000</span>
                                    <div class="clearfix"></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Brand <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <select class="form-control select2" name="merek" required>
                                        <option value="">Pilih Brand</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Asal Produk <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <select class="form-control select2" name="asal_produk" required>
                                        <option value="">Pilih Asal Produk</option>
                                    </select>
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
                    <h4>Informasi Penjualan</h4>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Pre-Order <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <select class="form-control select2" name="pre_order" data-placeholder="Pilih Salah Satu" onchange="show_pre_order_choice(this.value)" required>
                                        <option></option>
                                        <option value="T" <?= (strtolower($get_data['master']->pre_order) == 't') ? 'selected' : ''; ?>>Tidak</option>
                                        <option value="Y" <?= (strtolower($get_data['master']->pre_order) == 'y') ? 'selected' : ''; ?>>Ya</option>
                                    </select>
                                    <span class="help-block">Kirimkan produk dalam 2 hari (tidak termasuk hari Sabtu, Minggu, libur nasional dan non-operasional jasa kirim).</span>
                                </div>
                            </div>
                            <div class="form-group row form-border-dashed hide-element" id="pre_order_choice">
                                <label class="col-lg-2 col-form-label">Masa Pengemasan <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <select class="form-control select2" name="masa_pengemasan" data-placeholder="Pilih Salah Satu">
                                        <option></option>
                                        <?php for ($i = 7; $i <= 15; $i++) { ?>
                                            <option value="<?= $i ?>" <?= (strtolower($get_data['master']->pre_order) == 'y' && $get_data['master']->masa_pengemasan == $i) ? 'selected' : ''; ?>><?= $i ?> (Hari)</option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Kondisi Produk <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" id="baru" name="kondisi" class="custom-control-input" value="baru" required <?= (strtolower($get_data['master']->kondisi) == 'baru') ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="baru">Baru</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="pernah_dipakai" name="kondisi" class="custom-control-input" value="pernah dipakai" required <?= (strtolower($get_data['master']->kondisi) == 'pernah dipakai') ? 'checked' : ''; ?>>
                                        <label class="custom-control-label" for="pernah_dipakai">Pernah Dipakai</label>
                                    </div>
                                </div>
                            </div>
                            <div id="harga_normal">
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Kode SKU <span class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <input type="text" name="kode_sku_single" class="form-control" placeholder="Kode SKU untuk produk" required value="<?= (strtolower($get_data['master']->variasi_harga) == 't') ? $get_data['variasi'][0]->kode_sku : ''; ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Harga Satuan <span class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">Rp.</div>
                                            </div>
                                            <input type="text" name="harga_single" class="form-control" placeholder="Harga Satuan" required onkeypress="number_only(event)" onkeyup="running_rupiah('harga_single', this.value)" value="<?= (strtolower($get_data['master']->variasi_harga) == 't') ? number_format($get_data['variasi'][0]->harga_normal, 0, '', '.') : ''; ?>">
                                        </div>
                                        <span class="help-block">Hanya berisi angka (0-9)</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Stok Produk <span class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <input type="text" name="stok_single" class="form-control" placeholder="Jumlah Stok" required onkeypress="number_only(event)" value="<?= (strtolower($get_data['master']->variasi_harga) == 't') ? $get_data['variasi'][0]->stok : ''; ?>">
                                        <span class="help-block">Hanya berisi angka (0-9)</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Variasi Produk</label>
                                    <div class="col-lg-10">
                                        <button type="button" class="btn btn-outline-info btn-block" onclick="variasi_config(true);">Aktifkan Variasi Harga</button>
                                    </div>
                                </div>
                            </div>

                            <div class="form-border-dashed hide-element" id="variasi">
                                <div class="form-group row">
                                    <label class="col-lg-2 col-form-label">Variasi Produk <span class="text-danger">*</span></label>
                                    <div class="col-lg-10">
                                        <button type="button" class="btn btn-outline-danger btn-block" onclick="variasi_config(false);">Batalkan Variasi Harga</button>
                                    </div>
                                </div>
                                <div class="row">
                                    <input type="hidden" id="counter_variasi" value="<?= (strtolower($get_data['master']->variasi_harga) == 'y') ? $get_data['variasi_total'] : 1; ?>">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row" id="col_variasi">
                                                    <?php $i_variasi = 1;
                                                    foreach ($get_data['variasi'] as $key_variasi) : ?>
                                                        <div class="col-12 mt-4" id="form_variasi_<?= $i_variasi ?>">
                                                            <div class="form-group row">
                                                                <label class="col-lg-2 col-form-label">Tipe Variasi</label>
                                                                <div class="col-lg-10">
                                                                    <select class="form-control select2" name="pilihan[]" data-placeholder="Pilih Salah Satu" onchange="show_model(<?= $i_variasi ?>, this.value);">
                                                                        <option></option>
                                                                        <option value="model" <?= (!empty($key_variasi->model)) ? 'selected' : ''; ?>>Model</option>
                                                                        <option value="warna" <?= (!empty($key_variasi->warna)) ? 'selected' : ''; ?>>Warna</option>
                                                                        <option value="ukuran" <?= (!empty($key_variasi->ukuran)) ? 'selected' : ''; ?>>Ukuran</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="form-group row">
                                                                <label class="col-lg-2 col-form-label">Nama Variasi</label>
                                                                <div class="col-lg-10">
                                                                    <div id="daftar_model_<?= $i_variasi ?>" style="display: none;">
                                                                        <select class="form-control select2" data-placeholder="Pilih Salah Satu" onchange="copying_variasi(<?= $i_variasi ?>, this.value);">
                                                                        </select>
                                                                    </div>
                                                                    <div id="not_daftar_model_<?= $i_variasi ?>">
                                                                        <input type="text" name="nama[]" id="text_nama_<?= $i_variasi ?>" class="form-control not_daftar_model" placeholder="Masukkan nama variasi sesuai tipe, Contoh: Biru" onkeyup="copying_variasi(<?= $i_variasi ?>, this.value);">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php $i_variasi++;
                                                    endforeach ?>
                                                </div>
                                            </div>
                                        </div>
                                        <p><a href="javascript:;" class="text-info ml-1" onclick="add_variasi();"><i class="fas fa-plus mr-2"></i>Tambah Variasi</a></p>
                                        <?php if ($get_data['variasi_total'] > 1) : ?>
                                            <p id="remove_button"><a href="javascript:;" class="text-danger ml-1" onclick="remove_variasi();"><i class="fas fa-trash mr-2"></i>Hapus</a></p>
                                        <?php else : ?>
                                            <p class="hide-element" id="remove_button"><a href="javascript:;" class="text-danger ml-1" onclick="remove_variasi();"><i class="fas fa-trash mr-2"></i>Hapus</a></p>
                                        <?php endif ?>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <h4 class="header-title mb-4">Daftar Variasi</h4>

                                                <div class="table-responsive">
                                                    <table class="table table-bordered mb-0">
                                                        <thead>
                                                            <tr>
                                                                <th style="border: 1px solid;">Tipe</th>
                                                                <th style="border: 1px solid;">Harga</th>
                                                                <th style="border: 1px solid;">Stok</th>
                                                                <th style="border: 1px solid;">Kode SKU</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="table_variasi">
                                                            <?php $i_variasi2 = 1;
                                                            foreach ($get_data['variasi'] as $key_variasi2) : ?>
                                                                <tr id="tr_variasi_<?= $i_variasi2 ?>">
                                                                    <?php if (!empty($key_variasi2->model)) : ?>
                                                                        <td style="border: 1px solid;" id="read_nama_<?= $i_variasi2 ?>"><?= $key_variasi2->model ?></td>
                                                                    <?php elseif (!empty($key_variasi2->warna)) : ?>
                                                                        <td style="border: 1px solid;" id="read_nama_<?= $i_variasi2 ?>"><?= $key_variasi2->warna ?></td>
                                                                    <?php elseif (!empty($key_variasi2->ukuran)) : ?>
                                                                        <td style="border: 1px solid;" id="read_nama_<?= $i_variasi2 ?>"><?= $key_variasi2->ukuran ?></td>
                                                                    <?php endif ?>
                                                                    <td style="border: 1px solid;">
                                                                        <div class="input-group">
                                                                            <div class="input-group-prepend">
                                                                                <div class="input-group-text">Rp.</div>
                                                                            </div>
                                                                            <input type="text" name="harga[]" class="form-control" placeholder="0" onkeypress="number_only(event)" onkeyup="running_rupiah_array('#tr_variasi_<?= $i_variasi2 ?>', 'harga[]', this.value)" value="<?= $key_variasi2->harga_normal ?>">
                                                                        </div>
                                                                    </td>
                                                                    <td style="border: 1px solid;">
                                                                        <input type="text" name="stok[]" class="form-control" placeholder="0" onkeypress="number_only(event)" value="<?= $key_variasi2->stok ?>">
                                                                    </td>
                                                                    <td style="border: 1px solid;">
                                                                        <input type="text" name="sku[]" class="form-control" placeholder="SKU" value="<?= $key_variasi2->kode_sku ?>">
                                                                    </td>
                                                                </tr>
                                                            <?php $i_variasi2++;
                                                            endforeach ?>
                                                        </tbody>
                                                    </table>
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
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>Foto Produk</h4>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-warning">
                                <b>Info:</b> Gunakan foto yang memiliki kecerahan cukup, disarankan untuk mengatur bagian foto yang ingin di <i>crop</i>.
                            </div>

                            <div class="text-center">
                                <?php for ($i = 1; $i <= 5; $i++) { ?>
                                    <input type="hidden" name="id_foto_<?= $i ?>">
                                    <div class="Produk_JajaId">
                                        <div class="FotoProdukImg">
                                            <input type="file" class="form-control CustomBorderForm FileProduk NoBorder" accept=".png, .jpg, .jpeg" onchange="loadFile(event,<?= $i; ?>);" id="FileProduct_<?= $i; ?>" <?= ($i == 1) ? 'required' : ''; ?>>
                                            <input type="hidden" id="FileProductFix_<?= $i; ?>" name="file_foto_<?= $i; ?>">
                                            <img id="output_<?= $i; ?>" class="OutputImg">
                                            <input type="hidden" id="CekFoto_<?= $i; ?>" value="T">
                                            <i class="fas fa-plus-circle Plus" id="plus_<?= $i ?>"></i>
                                            <div class="BottomProduk" id="BottomProduk_<?= $i; ?>" data-type>
                                                <a href="javascript:" data-toggle="modal" data-target="#ModalCroping" onclick="ShowCroping(<?= $i; ?>); Croppie(<?= $i; ?>);" class="Croppie" id="ClickShowNow_<?= $i; ?>">
                                                    <i class="fas fa-crop Plus_Delete" id="CropingButton" data-toggle="tooltip" data-placement="bottom" title="Crop Foto"></i>
                                                </a>
                                                <a href="javascript:" onclick="RemovePhoto(<?= $i; ?>);">
                                                    <i class="far fa-trash-alt Plus_Delete text-danger" data-toggle="tooltip" data-placement="bottom" title="Hapus Foto"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4>Pengiriman</h4>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Berat <span class="text-danger">*</span></label>
                                <div class="col-lg-4">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <select class="form-control" name="tipe_berat" style="background-color: #F6F6F7;">
                                                <option value="gram">Gram (g)</option>
                                                <option value="kilogram">Kilogram (kg)</option>
                                            </select>
                                        </div>
                                        <input type="text" name="berat" class="form-control" placeholder="Berat" onkeypress="number_only(event)" required value="<?= $get_data['master']->berat ?>">
                                    </div>
                                    <span class="help-block">Hanya berisi angka (0-9)</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Ukuran <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <div class="row">
                                        <div class="col">
                                            <div class="input-group">
                                                <input type="text" name="ukuran_paket_panjang" class="form-control" placeholder="Panjang" onkeypress="number_only(event)" required value="<?= $get_data['master']->ukuran_paket_panjang ?>">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">cm</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="input-group">
                                                <input type="text" name="ukuran_paket_lebar" class="form-control" placeholder="Lebar" onkeypress="number_only(event)" required value="<?= $get_data['master']->ukuran_paket_lebar ?>">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">cm</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="input-group">
                                                <input type="text" name="ukuran_paket_tinggi" class="form-control" placeholder="Tinggi" onkeypress="number_only(event)" required value="<?= $get_data['master']->ukuran_paket_tinggi ?>">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">cm</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="help-block">Hanya berisi angka (0-9)</span>
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
                    <button type="submit" class="btn btn-soft-dark btn-lg mr-2" name="edit-arsipkan" value="edit-arsipkan">Edit sebagai draft</button>
                    <button type="submit" class="btn btn-info btn-lg" name="edit-live" value="edit-live">Edit dan tampilkan</button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Modal -->
<div id="ModalCroping" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 8888;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Crop Foto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="justify-content: center; display: flex;">
                <div id="LayoutCroppie" style="width: 450px; height: 450px;">
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