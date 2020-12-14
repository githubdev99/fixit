<div class="row page-title">
    <div class="col-12">
        <div class="float-left">
            <h4 class="mb-1 mt-0">Tambah Data Barang</h4>
        </div>
        <div class="float-right">
            <a href="<?= base_url() ?>admin/item" class="btn btn-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<form action="<?= site_url(uri_string()) ?>" method="post" enctype="multipart/form-data" name="add">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Nama <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <input type="text" name="name" class="form-control add" placeholder="Masukkan nama" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Harga <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Rp.</div>
                                        </div>
                                        <input type="text" name="price" class="form-control add" placeholder="Masukkan harga" required onkeypress="number_only(event)" onkeyup="running_rupiah('price', this.value)">
                                    </div>
                                    <small class="help-block float-left mt-1 ml-1">Hanya berisi angka (0-9)</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Stok <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <input type="text" name="stock" class="form-control add" placeholder="Masukkan stok" required onkeypress="number_only(event)">
                                    <small class="help-block float-left mt-1 ml-1">Hanya berisi angka (0-9)</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <select class="form-control add select2" name="gender" data-placeholder="Pilih salah satu" required>
                                        <option value=""></option>
                                        <?php foreach ($this->core['enum']['gender'] as $key_gender) : ?>
                                            <option value="<?= $key_gender ?>"><?= $gender ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Alamat</label>
                                <div class="col-lg-10">
                                    <textarea name="address" class="form-control add" placeholder="Masukkan alamat" cols="20" rows="5"></textarea>
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
                    <a href="<?= base_url() ?>admin/item" class="btn btn-soft-dark btn-lg mr-2">Batal</a>
                    <button type="submit" class="btn btn-info btn-lg" name="add" value="add">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>