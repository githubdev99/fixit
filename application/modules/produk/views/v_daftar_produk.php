<div class="row page-title">
    <div class="col-12">
        <div class="float-left">
            <h4 class="mb-1 mt-0">Daftar Produk</h4>
        </div>
        <div class="float-right">
            <a href="<?= base_url() ?>produk/tambah" class="btn btn-success"><i class="fas fa-plus mr-2"></i>Tambah Produk</a>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link active status_load" data-load="all" onclick="load_table({ status_produk: 'all' });">
                        Semua <span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" data-toggle="tab" aria-expanded="true" class="nav-link status_load" data-load="menunggu konfirmasi" onclick="load_table({ status_produk: 'menunggu konfirmasi' });">
                        Konfirmasi <span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link status_load" data-load="live" onclick="load_table({ status_produk: 'live' });">
                        Live <span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link status_load" data-load="habis" onclick="load_table({ status_produk: 'habis' });">
                        Habis <span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link status_load" data-load="arsipkan" onclick="load_table({ status_produk: 'arsipkan' });">
                        Diarsipkan <span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link status_load" data-load="ditolak" onclick="load_table({ status_produk: 'ditolak' });">
                        Ditolak <span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link status_load" data-load="blokir" onclick="load_table({ status_produk: 'blokir' });">
                        Diblokir <span></span>
                    </a>
                </li>
            </ul>
            <div class="card-body">
                <?php for ($i=0; $i < 3; $i++) { ?>
                    <div class="ph-item loading-produk">
                        <div class="ph-col-12">
                            <div class="ph-row">
                                <div class="ph-col-12 big"></div>
                            </div>
                        </div>

                        <div class="ph-col-1">
                            <div class="ph-row">
                                <div class="ph-col-12"></div>
                            </div>
                        </div>
                        
                        <div class="ph-col-1">
                            <div class="ph-picture" style="height: 100px !important;"></div>
                        </div>

                        <div class="ph-col-4">
                            <div class="ph-row">
                                <div class="ph-col-4"></div>
                                <div class="ph-col-8 empty"></div>
                                <div class="ph-col-6"></div>
                                <div class="ph-col-6 empty"></div>
                                <div class="ph-col-8"></div>
                            </div>
                        </div>

                        <div class="ph-col-2">
                            <div class="ph-row">
                                <div class="ph-col-12"></div>
                            </div>
                        </div>

                        <div class="ph-col-2">
                            <div class="ph-row">
                                <div class="ph-col-12"></div>
                            </div>
                        </div>

                        <div class="ph-col-2">
                            <div class="ph-row">
                                <div class="ph-col-12"></div>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="table-responsive after-loading-produk" style="display: none;">
                    <table id="datatable" class="table table-bordered table-hover" style="width: 100%;">
                        <thead class="table-info">
                            <tr>
                                <th>No.</th>
                                <th>Nama Produk</th>
                                <th>Harga &ensp;<i class="fas fa-question-circle" data-toggle="tooltip" data-placement="right" title="Harga yang ditampilkan hanya harga terendah - tertinggi"></i></th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
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