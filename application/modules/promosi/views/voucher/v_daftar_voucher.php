<div class="row page-title">
    <div class="col-12">
        <div class="float-left">
            <h4 class="mb-1 mt-0">Voucher</h4>
        </div>
        <div class="float-right">
            <a href="<?= base_url() ?>promosi/voucher/tambah" class="btn btn-success"><i class="fas fa-plus mr-2"></i>Buat Voucher</a>
        </div>
        <div class="clearfix"></div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link active status_load" data-load="all" onclick="load_table({ status_aktif: 'all', jenis_potongan: $('#jenis_potongan').val() });">
                        Semua <span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" data-toggle="tab" aria-expanded="true" class="nav-link status_load" data-load="Aktif" onclick="load_table({ status_aktif: 'Aktif', jenis_potongan: $('#jenis_potongan').val() });">
                        Aktif <span></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link status_load" data-load="Tidak Aktif" onclick="load_table({ status_aktif: 'Tidak Aktif', jenis_potongan: $('#jenis_potongan').val() });">
                        Tidak Aktif <span></span>
                    </a>
                </li>
            </ul>
            <div class="card-body">
                <div class="form-row align-items-center mb-4">
                    <div class="col-6">
                        <select class="form-control select2" id="jenis_potongan" data-placeholder="Jenis Potongan" onchange="show_button(this.value, '#filter_data');" required>
                            <option></option>
                            <option value="diskon">Diskon</option>
                            <option value="nominal">Nominal</option>
                        </select>
                    </div>
                    <div class="col">
                        <button type="button" class="ml-3 mr-3 btn btn-info" id="filter_data" disabled><i class="fas fa-filter mr-2"></i>Filter</button>
                        <button type="button" id="clear_filter" class="btn btn-danger hide-element" style="display: none;"><i class="fas fa-sync-alt mr-2"></i>Clear</button>
                    </div>
                </div>

                <?php for ($i=0; $i < 3; $i++) { ?>
                    <div class="ph-item loading-voucher">
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
                        
                        <div class="ph-col-2">
                            <div class="ph-picture" style="height: 100px !important;"></div>
                        </div>

                        <div class="ph-col-3">
                            <div class="ph-row">
                                <div class="ph-col-10"></div>
                                <div class="ph-col-2 empty"></div>
                                <div class="ph-col-12"></div>
                            </div>
                        </div>

                        <div class="ph-col-1">
                            <div class="ph-row">
                                <div class="ph-col-12"></div>
                            </div>
                        </div>

                        <div class="ph-col-1">
                            <div class="ph-row">
                                <div class="ph-col-12"></div>
                            </div>
                        </div>

                        <div class="ph-col-2">
                            <div class="ph-row">
                                <div class="ph-col-10"></div>
                                <div class="ph-col-2 empty"></div>
                                <div class="ph-col-12"></div>
                                <div class="ph-col-10"></div>
                                <div class="ph-col-2 empty"></div>
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

                <div class="table-responsive after-loading-voucher" style="display: none;">
                    <table id="datatable" class="table table-bordered table-hover" style="width: 100%;">
                        <thead class="table-info">
                            <tr>
                                <th>No.</th>
                                <th>Voucher</th>
                                <th>Target</th>
                                <th>Kuota</th>
                                <th>Potongan</th>
                                <th>Periode</th>
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