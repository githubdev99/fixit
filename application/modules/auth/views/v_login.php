<div class="account-pages my-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="card">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col p-5">
                            <div class="mx-auto text-center">
                                <img src="<?= $core['full_logo'] ?>" alt="" height="36" />
                            </div>

                            <h6 class="h5 mb-0">Welcome back!</h6>
                            <p class="text-muted mt-1 mb-4">Enter your username and password to
                                access panel.</p>

                            <form action="<?= base_url() ?>auth/login" method="post" name="login">
                                <div class="form-group">
                                    <label class="form-control-label">Username</label>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="icon-dual" data-feather="user"></i>
                                            </span>
                                        </div>
                                        <input type="text" name="username" class="form-control" placeholder="Enter your username">
                                    </div>
                                </div>

                                <div class="form-group mt-4">
                                    <label class="form-control-label">Password</label>
                                    <div class="input-group input-group-merge">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="icon-dual" data-feather="lock"></i>
                                            </span>
                                        </div>
                                        <input type="password" name="password" class="form-control" id="password" placeholder="Enter your password">
                                    </div>
                                </div>

                                <div class="form-group mb-0 text-center">
                                    <button class="btn btn-primary btn-block" type="submit" name="login" value="login"> Login
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div> <!-- end card-body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end row -->
        <div class="text-center">
            &copy; 2020 <?= $core['app_name'] ?> All Rights Reserved.
        </div>
    </div>
    <!-- end container -->
</div>
<!-- end page -->