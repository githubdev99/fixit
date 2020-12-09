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

<div class="navbar navbar-expand flex-column flex-md-row navbar-custom">
    <div class="container-fluid">
        <!-- LOGO -->
        <a href="<?= base_url() ?>" class="navbar-brand mr-0 mr-md-2 logo">
            <span class="logo-lg">
                <img src="<?= $core['full_logo'] ?>" alt="" height="70" />
                <span class="d-inline h6 ml-1 text-logo" style="font-style: italic;"><?= $type ?> Center</span>
            </span>
            <span class="logo-sm">
                <img src="<?= $core['mini_logo'] ?>" alt="" height="24">
            </span>
        </a>

        <ul class="navbar-nav bd-navbar-nav flex-row list-unstyled menu-left mb-0">
            <li class="">
                <button class="button-menu-mobile open-left disable-btn">
                    <i data-feather="menu" class="menu-icon"></i>
                    <i data-feather="x" class="close-icon"></i>
                </button>
            </li>
        </ul>

        <ul class="navbar-nav flex-row ml-auto d-flex list-unstyled topnav-menu float-right mb-0">
            <li class="dropdown notification-list align-self-center">
                <a class="nav-link dropdown-toggle nav-user mr-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                    <div class="media user-profile">
                        <img src="<?= $img_src ?>" alt="user-image" class="rounded-circle align-self-center" />
                        <div class="media-body text-left">
                            <h6 class="pro-user-name ml-2 my-0">
                                <span><?= $name ?></span>
                                <span class="pro-user-desc text-white d-block mt-1 badge" style="<?= $style ?>"><?= $session ?></span>
                            </h6>
                        </div>
                        <span data-feather="chevron-down" class="ml-2 align-self-center"></span>
                    </div>
                </a>
                <div class="dropdown-menu profile-dropdown-items dropdown-menu-right">
                    <a href="<?= base_url() ?>auth/logout" class="dropdown-item notify-item">
                        <i data-feather="log-out" class="icon-dual icon-xs mr-2"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </li>
        </ul>
    </div>

</div>