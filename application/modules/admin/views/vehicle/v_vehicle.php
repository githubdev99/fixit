<div class="row page-title">
    <div class="col-12">
        <div class="float-left">
            <h4 class="mb-1 mt-0">Data Kendaraan</h4>
        </div>
        <div class="float-right">
            <button type="button" class="btn btn-info" onclick="show_modal({ modal: 'add' })"><i class="fas fa-plus mr-2"></i>Tambah Data</button>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link active status_load" data-load="all" onclick="load_table({ in_active: 'all' });">
                        Semua
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" data-toggle="tab" aria-expanded="true" class="nav-link status_load" data-load="1" onclick="load_table({ in_active: 1 });">
                        Aktif
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link status_load" data-load="0" onclick="load_table({ in_active: 0 });">
                        Tidak Aktif
                    </a>
                </li>
            </ul>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered table-hover" style="width: 100%;">
                        <thead class="table-info">
                            <tr>
                                <th>No.</th>
                                <th>Jenis</th>
                                <th>Tanggal Input</th>
                                <th>Tanggal Update</th>
                                <th>Status</th>
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
            <form action="<?= base_url() ?>admin/vehicle" method="post" enctype="multipart/form-data" name="add">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Kendaraan</h5>
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
                                    <input type="text" name="name" class="form-control add" placeholder="Masukkan nama" required>
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

<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-3">
            <form action="<?= base_url() ?>admin/vehicle" method="post" enctype="multipart/form-data" name="edit">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Kendaraan</h5>
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
                                    <input type="text" name="name" class="form-control edit" placeholder="Masukkan nama" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id">
                    <input type="hidden" name="in_active">
                    <button type="button" class="btn btn-danger mr-2" data-dismiss="modal">Batal</button>
                    <button type="submit" name="edit" value="edit" class="btn btn-info">Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>