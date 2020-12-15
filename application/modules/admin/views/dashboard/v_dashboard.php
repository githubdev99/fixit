<div class="row page-title">
    <div class="col-12">
        <h4 class="mb-1 mt-0"></h4>
    </div>
</div>

<div class="row">
    <div class="col-sm-4 col-md-4 col-lg-4">
        <a href="<?= base_url() ?>admin/transaction">
            <div class="card card-dashboard">
                <div class="card-body p-0">
                    <div class="media p-3">
                        <div class="media-body">
                            <span class="text-muted text-uppercase font-size-11 font-weight-bold">Penjualan</span>
                            <h5 class="mb-0"><?= rupiah($core['total_data']['transaction']) ?></h5>
                        </div>
                        <div class="align-self-center">
                            <span class="text-warning font-weight-bold font-size-13"><i class="fas fa-dollar-sign fa-3x"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-4 col-md-4 col-lg-4">
        <a href="<?= base_url() ?>admin/purchase">
            <div class="card card-dashboard">
                <div class="card-body p-0">
                    <div class="media p-3">
                        <div class="media-body">
                            <span class="text-muted text-uppercase font-size-11 font-weight-bold">Pembelian</span>
                            <h5 class="mb-0"><?= rupiah($core['total_data']['purchase']) ?></h5>
                        </div>
                        <div class="align-self-center">
                            <span class="text-primary font-weight-bold font-size-13"><i class="fas fa-shopping-cart fa-3x"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-4 col-md-4 col-lg-4">
        <a href="<?= base_url() ?>admin/item">
            <div class="card card-dashboard">
                <div class="card-body p-0">
                    <div class="media p-3">
                        <div class="media-body">
                            <span class="text-muted text-uppercase font-size-11 font-weight-bold">Barang</span>
                            <h5 class="mb-0"><?= $core['total_data']['item'] ?></h5>
                        </div>
                        <div class="align-self-center">
                            <span class="text-success font-weight-bold font-size-13"><i class="fas fa-box-open fa-3x"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-sm-4 col-md-4 col-lg-4">
        <a href="<?= base_url() ?>admin/cashier">
            <div class="card card-dashboard">
                <div class="card-body p-0">
                    <div class="media p-3">
                        <div class="media-body">
                            <span class="text-muted text-uppercase font-size-11 font-weight-bold">Kasir</span>
                            <h5 class="mb-0"><?= $core['total_data']['cashier'] ?></h5>
                        </div>
                        <div class="align-self-center">
                            <span class="text-info font-weight-bold font-size-13"><i class="fas fa-user-tag fa-3x"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-4 col-md-4 col-lg-4">
        <a href="<?= base_url() ?>admin/vehicle">
            <div class="card card-dashboard">
                <div class="card-body p-0">
                    <div class="media p-3">
                        <div class="media-body">
                            <span class="text-muted text-uppercase font-size-11 font-weight-bold">Tipe Kendaraan</span>
                            <h5 class="mb-0"><?= $core['total_data']['vehicle'] ?></h5>
                        </div>
                        <div class="align-self-center">
                            <span class="text-danger font-weight-bold font-size-13"><i class="fas fa-motorcycle fa-3x"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-4 col-md-4 col-lg-4">
        <a href="<?= base_url() ?>admin/service">
            <div class="card card-dashboard">
                <div class="card-body p-0">
                    <div class="media p-3">
                        <div class="media-body">
                            <span class="text-muted text-uppercase font-size-11 font-weight-bold">Servis</span>
                            <h5 class="mb-0"><?= $core['total_data']['service'] ?></h5>
                        </div>
                        <div class="align-self-center">
                            <span class="font-weight-bold font-size-13" style="color: #CDD5E0;"><i class="fas fa-wrench fa-3x"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>