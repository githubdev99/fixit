<div class="row page-title">
    <div class="col-12">
        <h4 class="mb-1 mt-0">Informasi Saldo</h4>
    </div>
</div>

<div class="row">
    <div class="col-xl-5">
        <div class="card bg-shadow">
            <div class="card-body p-0">
                <div class="media p-3">
                    <div class="media-body">
                        <div class="row" style="border-bottom: 1px solid #e6e6e6;">
                            <div class="col-2">
                                <span class="badge badge-soft-success font-size-24"><i class="fas fa-money-bill-wave"></i></span>
                            </div>
                            <div class="col">
                                <span class="text-muted text-uppercase font-size-14 font-weight-bold">Saldo Aktif</span>
                                <h5>Rp.<?php $total = $saldo_aktif - $saldo_akanlepas - $saldo_terlepas;
                                        echo number_format($total, 0); ?></h5>
                            </div>
                            <div class="col text-right mb-2">
                                <!-- <button type="button" class="btn btn-info btn-block mb-2">Tarik Saldo</button> -->
                                <a href="javascript:;" class="btn btn-info btn-block mb-2" onclick="modal_rekening('pin');">Tarik Saldo</a>
                                <a href="javascript:;" class="text-info" onclick="modal_rekening('daftar');"><i class="far fa-credit-card mr-2"></i>Daftar Rekening</a>
                            </div>
                        </div>

                        <div class="row mt-3" style="border-bottom: 1px solid #e6e6e6;">
                            <div class="col">
                                <span class="text-muted text-uppercase font-size-14 font-weight-bold">Penghasilan &ensp;<i class="fas fa-exclamation-circle" data-toggle="tooltip" data-placement="bottom" title="Berisi dana yang telah didapat dari hasil penjualan."></i></span>
                            </div>
                            <div class="col text-right mb-3">
                                <span class="text-muted text-uppercase font-size-14 font-weight-bold">Rp.<?php echo number_format($saldo_terlepas); ?></span>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col">
                                Lihat penjelasan saldo <a href="#" class="text-info">disini</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-7">
        <div class="card bg-shadow">
            <div class="card-body p-0">
                <div class="media p-3">
                    <div class="media-body">
                        <div class="row" style="border-bottom: 1px solid #e6e6e6;">
                            <div class="col">
                                <h3>Riwayat Saldo</h3>
                            </div>
                            <div class="col text-right">
                                <div class="btn-group dropdown">
                                    <!-- <button class="btn btn-soft-dark dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-download mr-2"></i>Ekspor Riwayat
                                    </button> -->
                                    <form class="dropdown-menu dropdown-lg p-3 dropdown-menu-right box-shadow" style="width: 500px; border: 1px solid #e6e6e6;">
                                        <div class="form-group">
                                            <label for="tanggal_unduh">Rentang Tanggal</label>
                                            <input type="text" class="form-control daterangepicker" placeholder="Tanggal Transaksi Saldo" onchange="show_button(this.value, '.download_data');">
                                        </div>
                                        <br>
                                        <div class="row">
                                            <div class="col">
                                                <button type="button" class="btn btn-danger btn-block download_data" disabled><i class="fas fa-file-pdf mr-2"></i>Format PDF</button>
                                            </div>
                                            <div class="col">
                                                <button type="button" class="btn btn-success btn-block download_data" disabled><i class="fas fa-file-excel mr-2"></i>Format Excel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <div class="mb-3">
                                <ul class="nav nav-pills navtab-bg nav-justified">
                                    <li class="nav-item">
                                        <a href="#" data-toggle="tab" aria-expanded="false" class="nav-link active status_load" data-load="all" onclick="load_table('all');">
                                            Semua
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" data-toggle="tab" aria-expanded="true" class="nav-link status_load" data-load="tunggu" onclick="load_table('tunggu');">
                                            Akan Dilepas
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" data-toggle="tab" aria-expanded="true" class="nav-link status_load" data-load="setujui" onclick="load_table('setujui');">
                                            Sudah Terlepas
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="#" data-toggle="tab" aria-expanded="true" class="nav-link status_load" data-load="tolak" onclick="load_table('tolak');">
                                            Ditolak
                                        </a>
                                    </li>
                                </ul>
                                <br>

                                <!-- <div class="input-group mb-4">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text"><i class="fas fa-calendar-day"></i></div>
                                    </div>
                                    <input type="text" class="form-control daterangepicker" placeholder="Tanggal Transaksi Saldo" onchange="show_button(this.value, '#filter_data');">
                                    <button type="button" class="ml-3 mr-3 btn btn-info" id="filter_data" onclick="filter_data();" disabled><i class="fas fa-filter mr-2"></i>Filter</button>
                                    <button type="button" id="reset_data" onclick="reset_data();" class="btn btn-danger hide-element"><i class="fas fa-sync-alt mr-2"></i>Reset</button>
                                </div> -->

                                <?php for ($i = 0; $i < 3; $i++) { ?>
                                    <div class="ph-item loading-payouts">
                                        <div class="ph-col-2">
                                            <div class="ph-row">
                                                <div class="ph-col-8"></div>
                                            </div>
                                        </div>

                                        <div class="ph-col-3">
                                            <div class="ph-row">
                                                <div class="ph-col-10"></div>
                                                <div class="ph-col-12"></div>
                                                <div class="ph-col-12"></div>
                                            </div>
                                        </div>

                                        <div class="ph-col-3">
                                            <div class="ph-row">
                                                <div class="ph-col-9"></div>
                                                <div class="ph-col-10"></div>
                                                <div class="ph-col-10"></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="table-responsive after-loading-payouts" style="display: none;">
                                    <table id="datatable_payouts" width="100%" class="table table-borderless">
                                        <thead class="table-info">
                                            <tr>
                                                <td>Rekening</td>
                                                <td>Nominal</td>
                                                <td>Status</td>
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
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="daftar_rekening" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content p-3">
            <div class="modal-header">
                <h5 class="modal-title">Daftar Rekening Toko :<?= $core['seller']->nama_toko ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="datatable_rekening" class="table table-bordered table-hover" style="width: 100%;">
                        <thead class="table-info">
                            <tr>
                                <td>No.</td>
                                <td>Nama Bank</td>
                                <td>Nama Pemilik</td>
                                <td>Nomor Rekening</td>
                                <td>Opsi</td>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-soft-dark mr-2" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-info" onclick="modal_rekening('tambah');"><i class="fas fa-plus mr-2"></i>Tambah No. Rekening</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="masukkan_pin" tabindex="-1" role="dialog" aria-hidden="true" width="30%">
    <div class="modal-dialog " style="min-width:30%;">
        <div class="modal-content p-3">
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title">Masukkan PIN Anda</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group row" align="right">
                                <label class="col-lg-3 col-form-label" align="right">PIN <span class="text-danger">*</span></label>
                                <div class="col-lg-5">
                                    <input type="password" id="pin" name="pin" class="form-control" maxlength="6" size="6"><br><br>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger mr-2" data-dismiss="modal">Batal</button>
                    <button type="submit" name="verifikasi" value="verifikasi" class="btn btn-info">Verifikasi</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="tambah_rekening" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-3">
            <form action="<?= base_url() ?>dompetku" method="post" enctype="multipart/form-data" name="rekening_toko">

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Rekening Toko : </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="modal_rekening('daftar');">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
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
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Nama Pemilik <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" name="name" class="form-control" placeholder="Nama Pemilik Rekening" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger mr-2" data-dismiss="modal" onclick="modal_rekening('daftar');">Batal</button>
                    <button type="submit" name="tambah" value="tambah" class="btn btn-info">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="edit_rekening" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content p-3">
            <form action="<?= base_url() ?>dompetku" method="post" enctype="multipart/form-data" name="rekening_toko">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Rekening Toko : </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="modal_rekening('daftar');">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
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
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Nama Pemilik <span class="text-danger">*</span></label>
                                <div class="col-lg-9">
                                    <input type="text" name="name" class="form-control" placeholder="Nama Pemilik Rekening" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id_data">
                    <button type="button" class="btn btn-danger mr-2" data-dismiss="modal" onclick="modal_rekening('daftar');">Batal</button>
                    <button type="submit" name="edit" value="edit" class="btn btn-info">Edit</button>
                </div>
            </form>
        </div>
    </div>
</div>