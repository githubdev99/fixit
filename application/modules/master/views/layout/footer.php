    <!-- Main JS -->
    <script src="<?= base_url() ?>assets/js/vendor.min.js"></script>

    <!-- Plugin JS -->
    <script src="<?= base_url() ?>assets/libs/moment/moment.min.js"></script>
    <script src="<?= base_url() ?>assets/libs/apexcharts/apexcharts.min.js"></script>
    <script src="<?= base_url() ?>assets/libs/flatpickr/flatpickr.min.js"></script>
    <script src="<?= base_url() ?>assets/libs/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url() ?>assets/libs/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="<?= base_url() ?>assets/libs/datatables/dataTables.responsive.min.js"></script>
    <script src="<?= base_url() ?>assets/libs/datatables/responsive.bootstrap4.min.js"></script>
    <script src="<?= base_url() ?>assets/libs/bootstrap-tagsinput/bootstrap-tagsinput.min.js"></script>
    <script src="<?= base_url() ?>assets/libs/multiselect/jquery.multi-select.js"></script>
    <script src="<?= base_url() ?>assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    <script src="<?= base_url() ?>assets/libs/magnific-popup/jquery.magnific-popup.min.js"></script>
    <script src="<?= base_url() ?>assets/libs/select2/select2.min.js"></script>
    <script src="<?= base_url() ?>assets/libs/croppie/croppie.min.js"></script>

    <script src="<?= base_url() ?>assets/libs/sweetalert2/dist/sweetalert2.min.js"></script>
    <script src="<?= base_url() ?>assets/js/app.min.js"></script>

    <!-- Custom JS -->
    <script src="<?= base_url() ?>assets/custom/custom.js"></script>

    <script>
        // Alert
        $(document).ready(function() {
            <?php if (!empty($this->session->flashdata('show_alert'))) : ?>
                <?= $this->session->flashdata('show_alert'); ?>
            <?php endif ?>

            <?php if ($this->session->has_userdata('admin') && empty($core['setting']) && $this->uri->segment(2) != 'setting') : ?>
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan!',
                    text: 'Silahkan melakukan pengaturan terlebih dahulu!'
                }).then(() => {
                    window.location = '<?= base_url() . 'admin/setting' ?>'
                });
            <?php endif ?>
        });
    </script>

    <?php if (!empty($get_script)) : ?>
        <?= $this->load->view($get_script); ?>
    <?php endif ?>
    </body>

    </html>