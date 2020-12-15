<?php
if (!empty($core['admin'])) {
    $type = 'Admin';
    $session = 'admin';
    $name = $core['admin']->name;
    $style = 'background-color: #CDD5E0;';
    $img_src = base_url() . 'asset/images/avatar_male.png';
} elseif (!empty($core['cashier'])) {
    $type = 'Cashier';
    $session = 'cashier';
    $name = $core['cashier']->name;
    $style = 'background-color: #70D1F4;';
    if ($core['cashier']->gender == 'male') {
        $img_src = base_url() . 'asset/images/avatar_male.png';
    } else {
        $img_src = base_url() . 'asset/images/avatar_female.png';
    }
}
?>

<div class="left-side-menu">
    <div class="media user-profile mt-2 mb-2">
        <img src="<?= $img_src ?>" class="avatar-sm rounded-circle mr-2" alt="user-image" />
        <img src="<?= $img_src ?>" class="avatar-xs rounded-circle mr-2" alt="user-image" />

        <div class="media-body">
            <h6 class="pro-user-name mt-0 mb-0"><?= $name ?></h6>
            <span class="pro-user-desc badge text-white" style="<?= $style ?>"><?= $session ?></span>
        </div>
        <div class="dropdown align-self-center profile-dropdown-menu">
            <a class="dropdown-toggle mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                <span data-feather="chevron-down"></span>
            </a>
            <div class="dropdown-menu profile-dropdown">
                <a href="<?= base_url() ?>auth/logout" class="dropdown-item notify-item">
                    <i data-feather="log-out" class="icon-dual icon-xs mr-2"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </div>
    <div class="sidebar-content">
        <!--- Sidemenu -->
        <div id="sidebar-menu" class="slimscroll-menu">
            <ul class="metismenu" id="menu-bar">
                <?php if (!empty($core['admin'])) : ?>
                    <li class="<?= ($this->uri->segment(2) == 'dashboard') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/dashboard">
                            <i class="fas fa-home"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>
                    <li class="<?= ($this->uri->segment(2) == 'transaction') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/transaction">
                            <i class="fas fa-dollar-sign"></i>
                            <span> Transaksi </span>
                        </a>
                    </li>
                    <li class="<?= ($this->uri->segment(2) == 'purchase') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/purchase">
                            <i class="fas fa-shopping-cart"></i>
                            <span> Pembelian Supplier </span>
                        </a>
                    </li>
                    <li class="<?= ($this->uri->segment(2) == 'cashier') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/cashier">
                            <i class="fas fa-user-tag"></i>
                            <span> Kasir </span>
                        </a>
                    </li>
                    <li class="<?= ($this->uri->segment(2) == 'vehicle') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/vehicle">
                            <i class="fas fa-motorcycle"></i>
                            <span> Kendaraan </span>
                        </a>
                    </li>
                    <li class="<?= ($this->uri->segment(2) == 'service') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/service">
                            <i class="fas fa-wrench"></i>
                            <span> Servis </span>
                        </a>
                    </li>
                    <li class="<?= ($this->uri->segment(2) == 'item') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/item">
                            <i class="fas fa-box-open"></i>
                            <span> Barang </span>
                        </a>
                    </li>
                    <li class="<?= ($this->uri->segment(2) == 'setting') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url() ?>admin/setting">
                            <i class="fas fa-cog"></i>
                            <span> Pengaturan Admin </span>
                        </a>
                    </li>
                <?php elseif (!empty($core['cashier'])) : ?>
                    <li class="<?= ($this->uri->segment(2) == 'dashboard') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url() ?>cashier/dashboard">
                            <i class="fas fa-home"></i>
                            <span> Dashboard </span>
                        </a>
                    </li>
                    <li class="<?= ($this->uri->segment(2) == 'transaction') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url() ?>cashier/transaction">
                            <i class="fas fa-dollar-sign"></i>
                            <span> Transaksi </span>
                        </a>
                    </li>
                    <li class="<?= ($this->uri->segment(2) == 'purchase') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url() ?>cashier/purchase">
                            <i class="fas fa-shopping-cart"></i>
                            <span> Pembelian Supplier </span>
                        </a>
                    </li>
                    <li class="<?= ($this->uri->segment(2) == 'item') ? 'mm-active' : ''; ?>">
                        <a href="<?= base_url() ?>cashier/item">
                            <i class="fas fa-box-open"></i>
                            <span> Barang </span>
                        </a>
                    </li>
                <?php endif ?>
            </ul>
        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>
    </div>
    <!-- Sidebar -left -->
</div>