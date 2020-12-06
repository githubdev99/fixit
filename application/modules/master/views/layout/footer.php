    <!-- Main JS -->
    <script src="<?= base_url() ?>asset/js/vendor.min.js"></script>

    <?php if (!empty($plugin)) : ?>
        <!-- Plugin JS -->

        <?php if (array_search('moment', $plugin) !== false) : ?>
            <script src="<?= base_url() ?>asset/libs/moment/moment.min.js"></script>
        <?php endif ?>

        <?php if (array_search('apexcharts', $plugin) !== false) : ?>
            <script src="<?= base_url() ?>asset/libs/apexcharts/apexcharts.min.js"></script>
        <?php endif ?>

        <?php if (array_search('flatpickr', $plugin) !== false) : ?>
            <script src="<?= base_url() ?>asset/libs/flatpickr/flatpickr.min.js"></script>
        <?php endif ?>

        <?php if (array_search('datatables', $plugin) !== false) : ?>
            <script src="<?= base_url() ?>asset/libs/datatables/jquery.dataTables.min.js"></script>
            <script src="<?= base_url() ?>asset/libs/datatables/dataTables.bootstrap4.min.js"></script>
            <script src="<?= base_url() ?>asset/libs/datatables/dataTables.responsive.min.js"></script>
            <script src="<?= base_url() ?>asset/libs/datatables/responsive.bootstrap4.min.js"></script>
        <?php endif ?>

        <?php if (array_search('tagsinput', $plugin) !== false) : ?>
            <script src="<?= base_url() ?>asset/libs/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
        <?php endif ?>

        <?php if (array_search('multiselect', $plugin) !== false) : ?>
            <script src="<?= base_url() ?>asset/libs/multiselect/jquery.multi-select.js"></script>
        <?php endif ?>

        <?php if (array_search('touchspin', $plugin) !== false) : ?>
            <script src="<?= base_url() ?>asset/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
        <?php endif ?>

        <?php if (array_search('magnific-popup', $plugin) !== false) : ?>
            <script src="<?= base_url() ?>asset/libs/magnific-popup/jquery.magnific-popup.min.js"></script>
        <?php endif ?>

        <?php if (array_search('select2', $plugin) !== false) : ?>
            <script src="<?= base_url() ?>asset/libs/select2/select2.min.js"></script>
        <?php endif ?>

        <?php if (array_search('croppie', $plugin) !== false) : ?>
            <script src="<?= base_url() ?>asset/libs/croppie/croppie.min.js"></script>
        <?php endif ?>
    <?php endif ?>

    <script src="<?= base_url() ?>asset/libs/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="<?= base_url() ?>asset/js/app.min.js"></script>

    <!-- Custom JS -->
    <script src="<?= base_url() ?>asset/custom/custom.js"></script>

    <script src="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>

    <script>
        // Alert
        $(document).ready(function() {
            <?php if (!empty($this->session->flashdata('success'))) : ?>
                <?= $this->session->flashdata('success'); ?>
            <?php elseif (!empty($this->session->flashdata('failed'))) : ?>
                <?= $this->session->flashdata('failed'); ?>
            <?php endif ?>
        });
    </script>

    <?php if (!empty($get_script)) : ?>
        <?= $this->load->view($get_script); ?>
    <?php endif ?>
    </body>

    </html>