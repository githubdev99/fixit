<div class="row page-title">
    <div class="col-12">
        <div class="float-left">
            <h4 class="mb-1 mt-0">Edit Data Mekanik</h4>
        </div>
        <div class="float-right">
            <a href="<?= base_url() ?>admin/mechanic" class="btn btn-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<form action="<?= site_url(uri_string()) ?>" method="post" enctype="multipart/form-data" name="edit">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Nama <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <input type="text" name="name" class="form-control edit" placeholder="Masukkan nama" required value="<?= $get_data['name'] ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <div class="input-group mt-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-calendar-alt"></i></div>
                                        </div>
                                        <input type="text" name="birth_date" class="form-control edit datepicker" placeholder="Masukkan tanggal lahir" required value="<?= date('d M Y', strtotime($get_data['birth_date'])) ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <select class="form-control edit select2" name="gender" data-placeholder="Pilih salah satu" required>
                                        <option value=""></option>
                                        <?php foreach ($this->core['enum']['gender'] as $key_gender) : $gender = ($key_gender == 'male') ? 'Laki-Laki' : 'Perempuan';
                                            $selected = ($key_gender == $get_data['gender']) ? 'selected' : ''; ?>
                                            <option value="<?= $key_gender ?>" <?= $selected ?>><?= $gender ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">No. Telp <span class="text-danger">*</span></label>
                                <div class="col-lg-10">
                                    <input type="text" name="phone_number" class="form-control edit" placeholder="Masukkan no. telepon" required onkeypress="number_only(event)" value="<?= $get_data['phone_number'] ?>">
                                    <small class="help-block mt-1 ml-1">Hanya berisi angka (0-9)</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">Alamat</label>
                                <div class="col-lg-10">
                                    <textarea name="address" class="form-control edit" placeholder="Masukkan alamat" cols="20" rows="5"><?= $get_data['address'] ?></textarea>
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
                    <a href="<?= base_url() ?>admin/mechanic" class="btn btn-soft-dark btn-lg mr-2">Batal</a>
                    <button type="submit" class="btn btn-info btn-lg" name="edit" value="edit">Edit</button>
                </div>
            </div>
        </div>
    </div>
</form>