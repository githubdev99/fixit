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
} elseif (!empty($core['mechanic'])) {
    $type = 'Mechanic';
    $session = 'mechanic';
    $name = $core['mechanic']->name;
    $style = 'background-color: #A97142;';
    if ($core['mechanic']->gender == 'male') {
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
                <li>
                    <a href="<?= base_url() ?>admin/dashboard">
                        <i class="fas fa-home"></i>
                        <span> Dashboard </span>
                    </a>
                </li>
                <li>
                    <a href="<?= base_url() ?>admin/mechanic">
                        <i class="fas fa-users-cog"></i>
                        <span> Mechanic </span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>
    </div>
    <!-- Sidebar -left -->
</div>