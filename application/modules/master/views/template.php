<?= $this->load->view('layout/header'); ?>
<div id="wrapper">
    <?php if ($this->uri->segment(1) == 'auth') : ?>
        <div class="container-fluid">
            <?php
            if (!empty($get_view)) {
                $this->load->view($get_view);
            }
            ?>
        </div>
    <?php else : ?>
        <?= $this->load->view('layout/navbar'); ?>
        <?= $this->load->view('layout/sidebar'); ?>

        <div class="content-page">
            <div class="content">
                <div class="container-fluid">
                    <?php
                    if (!empty($get_view)) {
                        $this->load->view($get_view);
                    }
                    ?>
                </div>
            </div>

            <div style="margin-bottom: 50px;"></div>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            &copy; 2020 <?= $core['app_name'] ?> All Rights Reserved.
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    <?php endif ?>
</div>
<?= $this->load->view('layout/footer'); ?>