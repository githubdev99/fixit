<?php
$created_at = explode(' ', $get_data['created_at']);
$updated_at = (!empty($get_data['updated_at'])) ? explode(' ', $get_data['updated_at']) : null;
?>

<div class="row page-title">
    <div class="col-12">
        <div class="float-left">
            <h4 class="mb-1 mt-0">Detail Data Kendaraan</h4>
        </div>
        <div class="float-right">
            <a href="<?= base_url() ?>admin/vehicle" class="btn btn-primary"><i class="fas fa-arrow-left mr-2"></i>Kembali</a>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<div class="row col-center">
    <div class="col-8">
        <div class="card">
            <div class="card-body">
                <table border="0" cellpadding="5" style="width: 100%;">
                    <tr>
                        <td class="text-right">
                            <strong>Nama</strong>
                        </td>
                        <td>&ensp;</td>
                        <td><?= $get_data['name'] ?></td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Tanggal Input</strong>
                        </td>
                        <td>&ensp;</td>
                        <td><?= date_indo(date('d-m-Y', strtotime($created_at[0]))) ?> <?= $created_at[1] ?></td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Tanggal Update</strong>
                        </td>
                        <td>&ensp;</td>
                        <td><?= (!empty($updated_at)) ? date_indo(date('d-m-Y', strtotime($created_at[0]))) . ' ' . $updated_at[1] : 'Belum Update'; ?></td>
                    </tr>
                    <tr>
                        <td class="text-right">
                            <strong>Status</strong>
                        </td>
                        <td>&ensp;</td>
                        <td>
                            <?php if ($get_data['in_active'] != 0) : ?>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="<?= $get_data['id'] ?>" onclick="show_modal({ modal: 'not_active', id: '<?= $get_data['id'] ?>' })" checked>
                                    <label class="custom-control-label" for="<?= $get_data['id'] ?>"></label>
                                </div>
                            <?php else : ?>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input" id="<?= $get_data['id'] ?>" onclick="show_modal({ modal: 'active', id: '<?= $get_data['id'] ?>' })">
                                    <label class="custom-control-label" for="<?= $get_data['id'] ?>"></label>
                                </div>
                            <?php endif ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="float-left">
            <button type="button" onclick="show_modal({ modal: 'delete', id: '<?= $get_data['id'] ?>' })" class="btn btn-danger btn-lg">Hapus</button>
        </div>
        <div class="float-right">
            <button type="button" class="btn btn-info btn-lg" onclick="show_modal({ modal: 'edit', id: '<?= $get_data['id'] ?>' })">Edit</button>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<div class="card mt-5">
    <div class="row page-title ml-2 mr-2">
        <div class="col-12">
            <div class="float-left">
                <h4 class="mb-1 mt-0">Detail Kendaraan</h4>
            </div>
            <div class="float-right">
                <button type="button" class="btn btn-info" onclick="show_modal({ modal: 'add_child' })"><i class="fas fa-plus mr-2"></i>Tambah Data</button>
            </div>
            <div class="clearfix"></div>
        </div>
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
                                <th>Nama</th>
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