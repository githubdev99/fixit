<div class="row page-title">
    <div class="col-12">
        <div class="float-left">
            <h4 class="mb-1 mt-0">Tambah Data Kasir</h4>
        </div>
        <div class="float-right">
            <a href="<?= base_url() ?>admin/cashier" class="btn btn-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
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
                                <label class="col-lg-2 col-form-label">Username <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <input type="text" name="username" class="form-control add" placeholder="Masukkan username" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <div class="input-group mt-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                        </div>
                                        <input type="text" name="birth_date" class="form-control add datepicker" placeholder="Masukkan tanggal lahir" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <select class="form-control add select2" name="gender" data-placeholder="Pilih salah satu" required>
                                        <option value=""></option>
                                        <?php foreach ($this->core['enum']['gender'] as $key_gender) : $gender = ($key_gender == 'male') ? 'Laki-Laki' : 'Perempuan'; ?>
                                            <option value="<?= $key_gender ?>"><?= $gender ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">No. Telp <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <input type="text" name="phone_number" class="form-control add" placeholder="Masukkan no. telepon" required onkeypress="number_only(event)">
                                    <small class="help-block mt-1 ml-1">Hanya berisi angka (0-9)</small>
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
                    <a href="<?= base_url() ?>admin/cashier" class="btn btn-soft-dark btn-lg mr-2">Batal</a>
                    <button type="submit" class="btn btn-info btn-lg" name="add" value="add">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</form>