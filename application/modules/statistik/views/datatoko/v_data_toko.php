<div class="row page-title">
    <div class="col-12">
        <h4 class="mb-1 mt-0">Statistik Data Toko</h4>
    </div>
</div>

<div class="row">
    <div class="col-md-3 col-sm-6">
        <a href="#">
            <div class="card card-dashboard">
                <div class="card-body p-0">
                    <div class="media p-3">
                        <div class="media-body">
                            <span class="text-muted text-uppercase font-size-11 font-weight-bold">Penjualan</span>
                            <h5 class="mb-0"><?= rupiah($jumlah_penjualan) ?></h5>
                        </div>
                        <div class="align-self-center">
                            <span class="text-warning font-weight-bold font-size-13"><i class="fas fa-dollar-sign fa-4x"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3 col-sm-6">
        <a href="">
            <div class="card card-dashboard">
                <div class="card-body p-0">
                    <div class="media p-3">
                        <div class="media-body">
                            <span class="text-muted text-uppercase font-size-11 font-weight-bold">Total Pesanan</span>
                            <h5 class="mb-0"><?= $jumlah_pesanan ?></h5>
                        </div>
                        <div class="align-self-center">
                            <span class="text-primary font-weight-bold font-size-13"><i class="fas fa-shopping-cart fa-4x"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>


    <div class="col-md-3 col-sm-6">
        <a href="">
            <div class="card card-dashboard">
                <div class="card-body p-0">
                    <div class="media p-3">
                        <div class="media-body">
                            <span class="text-muted text-uppercase font-size-11 font-weight-bold">Total Pengunjung</span>
                            <h5 class="mb-0"><?= $jumlah_pengunjung ?></h5>
                        </div>
                        <div class="align-self-center">
                            <span class="text-success font-weight-bold font-size-13"><i class="fas fa-users fa-4x"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-md-3 col-sm-6">
        <a href="">
            <div class="card card-dashboard">
                <div class="card-body p-0">
                    <div class="media p-3">
                        <div class="media-body">
                            <span class="text-muted text-uppercase font-size-11 font-weight-bold">Produk Dilihat</span>
                            <h5 class="mb-0"><?= $jumlah_produk_dilihat ?></h5>
                            
                        </div>
                        <div class="align-self-center">
                            <span class="text-info font-weight-bold font-size-13"><i class="fas fa-eye fa-4x"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>



    <div class="col-xl-8">
        <div class="card">
            <div class="card-body pb-0">
        
                <h5 class="card-title mb-0 header-title">Grafik Penjualan</h5>

                <div id="revenue-chart" class="apex-charts mt-3"  dir="ltr"></div> 
            </div>
        </div>
    </div>

    
                        <!-- activities -->
                        <div class="col-xl-4">
                            <div class="card">
                                <div class="card-body pt-2 pb-3">
                                   
                                    <h6 class="header-title mb-4">Produk Terlaris di Toko mu</h6>
                                    <?php  foreach ($get_data_produk as $key) {?>
                                    <div class="media">
                                        <img src="https://seller.jaja.id/asset/images/products/<?= $key->foto ?>" class="mr-3 avatar rounded-circle" alt="shreyu" />
                                        <div class="media-body">
                                            <h6 class="mt-0 mb-0 font-size-15 font-weight-normal">
                                                <a href="" class="font-weight-bold"> <?= $key->nama_produk ?></a> 
                                            </h6>
                                            <p class="text-muted">Rp. <?= number_format($key->harga_aktif) ?>
                                            <br>Terjual : <b><?= $key->total ?></b></p>
                                        </div>
                                    </div>
                                    <?php } ?>
           

                                </div>
                            </div>
                        </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-body pb-0">
        
                <h5 class="card-title mb-0 header-title">Jenis Kelamin Pembeli</h5>

                <div id="chart_jenis_kelamin" class="apex-charts mt-3"  dir="ltr"></div> 
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="card">
            <div class="card-body pb-0">
        
                <h5 class="card-title mb-0 header-title">Tipe Pembeli</h5>

                <div id="chart_tipe_pembeli" class="apex-charts mt-3"  dir="ltr"></div> 
            </div>
        </div>
    </div>

</div>