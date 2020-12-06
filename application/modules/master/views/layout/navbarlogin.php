<div class="navbar navbar-expand flex-column flex-md-row navbar-custom">
    <div class="container-fluid">
        <!-- LOGO -->
        <a href="<?= base_url() ?>" class="navbar-brand mr-0 mr-md-2 logo">
            <span class="logo-lg">
                <img src="<?= $core['full_logo'] ?>" alt="" height="70" />
                <span class="d-inline h6 ml-1 text-logo" style="font-style: italic;">Seller Center</span>
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

                        <div class="media-body text-left">

                        </div>
                        <span data-feather="chevron-down" class="ml-2 align-self-center"></span>
                    </div>
                </a>
                <div class="dropdown-menu profile-dropdown-items dropdown-menu-right">
                    <a href="https://jaja.id/" class="dropdown-item notify-item">
                        <i data-feather="arrow-up-left" class="icon-dual icon-xs mr-2"></i>
                        <span>Kembali ke Jaja.id</span>
                    </a>

                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                        <i data-feather="settings" class="icon-dual icon-xs mr-2"></i>
                        <span>Pengaturan Akun</span>
                    </a>
                </div>
            </li>


        </ul>
    </div>

</div>