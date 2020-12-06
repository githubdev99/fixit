<div class="left-side-menu">
    <div class="media user-profile mt-2 mb-2">
        <?php if (!empty($core['seller']->foto)): ?>
            <img src="<?= base_url() ?>asset/front/images/file/<?= $core['seller']->foto ?>" class="avatar-sm rounded-circle mr-2" alt="user-image" />
            <img src="<?= base_url() ?>asset/front/images/file/<?= $core['seller']->foto ?>" class="avatar-xs rounded-circle mr-2" alt="user-image" />
        <?php else: ?>
            <img src="<?= base_url() ?>asset/images/toko-default.png" class="avatar-sm rounded-circle mr-2" alt="user-image" />
            <img src="<?= base_url() ?>asset/images/toko-default.png" class="avatar-xs rounded-circle mr-2" alt="user-image" />
        <?php endif ?>

        <div class="media-body">
            <?php
            if ($core['seller']->kategori_seller == 'Bronze') {
                $style_kategori_seller = 'background-color: #A97142;';
            } elseif ($core['seller']->kategori_seller == 'Platinum') {
                $style_kategori_seller = 'background-color: #CDD5E0;';
            } elseif ($core['seller']->kategori_seller == 'Diamond') {
                $style_kategori_seller = 'background-color: #70D1F4;';
            }
            ?>
            <h6 class="pro-user-name mt-0 mb-0"><?= $core['seller']->nama_toko ?></h6>
            <span class="pro-user-desc badge text-white" style="<?= $style_kategori_seller ?>"><?= $core['seller']->kategori_seller ?></span>
        </div>
        <div class="dropdown align-self-center profile-dropdown-menu">
            <a class="dropdown-toggle mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                aria-expanded="false">
                <span data-feather="chevron-down"></span>
            </a>
            <div class="dropdown-menu profile-dropdown">
                <a href="https://jaja.id/" class="dropdown-item notify-item">
                    <i data-feather="arrow-up-left" class="icon-dual icon-xs mr-2"></i>
                    <span>Kembali ke Jaja.id</span>
                </a>

                <a href="javascript:void(0);" class="dropdown-item notify-item">
                    <i data-feather="settings" class="icon-dual icon-xs mr-2"></i>
                    <span>Pengaturan Akun</span>
                </a>
            </div>
        </div>
    </div>
    <div class="sidebar-content">
        <!--- Sidemenu -->
        <div id="sidebar-menu" class="slimscroll-menu">
            <ul class="metismenu" id="menu-bar">
                <li class="dompetku text-center mb-2" onclick="go_to('<?= base_url() ?>dompetku');">
                    <span class="btn <?= ($this->uri->segment(1) == 'dompetku') ? 'btn-info' : 'btn-outline-info' ; ?> btn-dompetku">
                        <i class="fas fa-wallet fa-3x float-left mr-3"></i>
                        <div class="text-left">
                            Dompetku
                            <br>
                            Rp. 500.000
                        </div>
                    </span>
                </li>

                <li class="<?= ($this->uri->segment(1) == 'home' || $this->uri->segment(1) == '') ? 'mm-active' : ''; ?>">
                    <a href="<?= base_url() ?>home">
                        <i data-feather="home"></i>
                        <span> Beranda </span>
                    </a>
                </li>

                <li class="<?= ($this->uri->segment(1) == 'penjualan') ? 'mm-active' : ''; ?>">
                    <a>
                        <i data-feather="file-text"></i>
                        <span> Penjualan </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="nav-second-level" aria-expanded="false">
                        <li class="<?= ($this->uri->segment(2) == 'pesanan') ? 'mm-active' : ''; ?>">
                            <a href="<?= base_url() ?>penjualan/pesanan" class="<?= ($this->uri->segment(2) == 'pesanan') ? 'active' : ''; ?>">Pesanan</a>
                        </li>
                        <li class="<?= ($this->uri->segment(2) == 'resolusi') ? 'mm-active' : ''; ?>">
                            <a href="<?= base_url() ?>penjualan/resolusi" class="<?= ($this->uri->segment(2) == 'resolusi') ? 'active' : ''; ?>">Pusat Resolusi</a>
                        </li>
                    </ul>
                </li>
                
                <li class="<?= ($this->uri->segment(1) == 'produk') ? 'mm-active' : ''; ?>">
                    <a>
                        <i data-feather="package"></i>
                        <span> Produk </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="nav-second-level" aria-expanded="false">
                        <li class="<?= ($this->uri->segment(2) == 'produk') ? 'mm-active' : ''; ?>">
                            <a href="<?= base_url() ?>produk" class="<?= ($this->uri->segment(2) == 'produk') ? 'active' : ''; ?>">Daftar Produk</a>
                        </li>
                        <li class="<?= ($this->uri->segment(2) == 'tambah') ? 'mm-active' : ''; ?>">
                            <a href="<?= base_url() ?>produk/tambah" class="<?= ($this->uri->segment(2) == 'tambah') ? 'active' : ''; ?>">Tambah Produk</a>
                        </li>
                    </ul>
                </li>

                <li class="<?= ($this->uri->segment(1) == 'promosi') ? 'mm-active' : ''; ?>">
                    <a>
                        <i data-feather="percent"></i>
                        <span> Promosi </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="nav-second-level" aria-expanded="false">
                        <li class="<?= ($this->uri->segment(2) == 'voucher') ? 'mm-active' : ''; ?>">
                            <a href="<?= base_url() ?>promosi/voucher" class="<?= ($this->uri->segment(2) == 'voucher' || $this->uri->segment(3) == 'tambah') ? 'active"' : ''; ?>">Voucher Toko</a>
                        </li>
                        <li class="<?= ($this->uri->segment(2) == 'diskon') ? 'mm-active' : ''; ?>">
                            <a href="<?= base_url() ?>promosi/diskon" class="<?= ($this->uri->segment(2) == 'diskon') ? 'active' : ''; ?>">Pengaturan Diskon</a>
                        </li>
                        <!-- <li class="<?= ($this->uri->segment(2) == 'flashsale') ? 'mm-active' : ''; ?>">
                            <a href="<?= base_url() ?>promosi/flashsale" class="<?= ($this->uri->segment(2) == 'flashsale') ? 'active' : ''; ?>">Flashsale</a>
                        </li> -->
                        <!-- <li class="<?= ($this->uri->segment(2) == 'flashsale') ? 'mm-active' : ''; ?>">
                            <a href="#" class="<?= ($this->uri->segment(2) == 'flashsale') ? 'active' : ''; ?>">Flashsale <br><span class="ml-2 badge badge-warning text-white" style="font-size: 12px;">Coming Soon</span></a>
                        </li> -->
                
                    </ul>
                </li>

                <!-- <li>
                    <a>
                        <i data-feather="trending-up"></i>
                        <span> Statistik </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="nav-second-level" aria-expanded="false">
                        <li>
                            <a href="<?= base_url() ?>statistik/datatoko">Data Toko</a>
                        </li>
                        <li>
                            <a href="#">Data Pasar</a>
                        </li>
                    </ul>
                </li> -->

                <li class="<?= ($this->uri->segment(1) == 'review') ? 'mm-active' : ''; ?>">
                    <a>
                        <i class="fas fa-smile"></i>
                        <span> Review </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="nav-second-level" aria-expanded="false">
                        <li class="<?= ($this->uri->segment(2) == 'rating') ? 'mm-active' : ''; ?>">
                            <a href="<?= base_url() ?>review/rating" class="<?= ($this->uri->segment(2) == 'rating') ? 'active"' : ''; ?>">Rating Produk</a>
                        </li>
                        <li class="<?= ($this->uri->segment(2) == 'report') ? 'mm-active' : ''; ?>">
                            <a href="<?= base_url() ?>review/report" class="<?= ($this->uri->segment(2) == 'report') ? 'active' : ''; ?>">Report Produk</a>
                        </li>
                    </ul>


                </li>

                <li class="<?= ($this->uri->segment(1) == 'pengaturan') ? 'mm-active' : ''; ?>">
                    <a>
                        <i data-feather="settings"></i>
                        <span> Pengaturan </span>
                        <span class="menu-arrow"></span>
                    </a>

                    <ul class="nav-second-level" aria-expanded="false">
                        <li class="<?= ($this->uri->segment(2) == 'toko') ? 'mm-active' : ''; ?>">
                            <a href="<?= base_url() ?>pengaturan/toko" class="<?= ($this->uri->segment(2) == 'toko') ? 'active"' : ''; ?>">Pengaturan Toko</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>
    </div>
    <!-- Sidebar -left -->
</div>