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
                                    <small class="help-block mt-1 ml-1">Hanya berisi angka (0-9)</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Stok <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <input type="text" name="stock" class="form-control add" placeholder="Masukkan stok" required onkeypress="number_only(event)">
                                    <small class="help-block mt-1 ml-1">Hanya berisi angka (0-9)</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Jenis <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <div class="custom-control custom-radio mb-2">
                                        <input type="radio" id="no" name="jenis" class="custom-control-input" value="no" onclick="show_jenis(this.value);" checked required>
                                        <label class="custom-control-label" for="no">Umum</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" id="yes" name="jenis" class="custom-control-input" value="yes" onclick="show_jenis(this.value);" required>
                                        <label class="custom-control-label" for="yes">Per-Jenis</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-border-dashed" id="jenis_choice" style="display: none;">
                                <div class="form-group row mt-1">
                                    <label class="col-lg-3 col-form-label">Kendaraan</label>
                                    <div class="col-lg-6">
                                        <select class="form-control select2" name="vehicle_id" data-placeholder="Pilih salah satu">
                                        </select>
                                        <small class="help-block mt-1 ml-1">Kosongkan bila tidak ada</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-3 col-form-label">Detail Kendaraan</label>
                                    <div class="col-lg-6">
                                        <select class="form-control select2" name="vehicle_children_id" data-placeholder="Pilih salah satu">
                                        </select>
                                        <small class="help-block mt-1 ml-1">Kosongkan bila tidak ada</small>
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
                    <a href="<?= base_url() ?>admin/item" class="btn btn-soft-dark btn-lg mr-2">Batal</a>
                    <button type="submit" class="btn btn-info btn-lg" name="add" value="add">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>