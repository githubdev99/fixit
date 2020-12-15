<div class="row page-title">
    <div class="col-12">
        <div class="float-left">
            <h4 class="mb-1 mt-0">Data Barang</h4>
        </div>
        <div class="float-right">
            <a href="<?= base_url() ?>admin/item/form" class="btn btn-info"><i class="fas fa-plus mr-2"></i>Tambah Data</a>
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
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Harga</th>
                                <th>Stok</th>
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