<div class="row page-title">
    <div class="col-12">
        <div class="float-left">
            <h4 class="mb-1 mt-0">Data Mekanik</h4>
        </div>
        <div class="float-right">
            <button type="button" class="btn btn-info" onclick="show_modal({ modal: 'add' });"><i class="fas fa-plus mr-2"></i>Tambah Data</button>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered table-hover" style="width: 100%;">
                        <thead class="table-info">
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Telepon</th>
                                <th>Jenis Kelamin</th>
                                <th>Tanggal Input</th>
                                <th>Tanggal Update</th>
                                <th>Opsi</th>
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

<div class="modal fade" id="add" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-3">
            <form action="<?= base_url() ?>admin/mechanic" method="post" enctype="multipart/form-data" name="add">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Mekanik</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Nama <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Username <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Password <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="password" name="password" class="form-control" placeholder="Masukkan nama" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Bank <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <select class="form-control select2" name="bank" data-placeholder="Pilih Bank" required>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Nomor Rekening <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" name="account" class="form-control" placeholder="Masukkan No. Rekening" onkeypress="number_only(event)" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger mr-2" data-dismiss="modal">Batal</button>
                    <button type="submit" name="add" value="add" class="btn btn-info">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>