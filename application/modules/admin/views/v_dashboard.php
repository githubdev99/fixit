<div class="row page-title">
    <div class="col-12">
        <h4 class="mb-1 mt-0"></h4>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-md-6 col-lg-3">
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
    </div>

    <div class="col-sm-6 col-md-6 col-lg-3">
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
    </div>


    <div class="col-sm-6 col-md-6 col-lg-3">
        <div class="card card-dashboard">
            <div class="card-body p-0">
                <div class="media p-3">
                    <div class="media-body">
                        <span class="text-muted text-uppercase font-size-11 font-weight-bold">Mekanik</span>
                        <h5 class="mb-0"><?= $core['total_data']['mechanic'] ?></h5>
                    </div>
                    <div class="align-self-center">
                        <span class="text-success font-weight-bold font-size-13"><i class="fas fa-users-cog fa-3x"></i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-md-6 col-lg-3">
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
    </div>

    <!-- <div class="col-12">
        <div class="card">
            <div class="card-body pb-0">
                <h5 class="card-title mb-0 header-title">Grafik Penjualan</h5>
                <div id="revenue-chart" class="apex-charts mt-3" dir="ltr"></div>
            </div>
        </div>
    </div> -->
</div>