<script>
    $(document).ready(function() {
        load_layouts();
    });

    function load_layouts() {
        $.ajax({
            type: 'post',
            url: '<?= base_url() ?>admin/setting/layouts',
            dataType: 'json',
            success: function(response) {
                $('#layouts').html(response);

                $('form[name="setting"]').submit(function(e) {
                    e.preventDefault();

                    var active_element = $(document.activeElement);

                    if (active_element.val() != 'reset') {
                        var alert = 'Anda yakin ingin melakukan pengaturan ini ?';
                        var confirm_color = '#1aaac8';
                    } else {
                        var alert = 'Anda yakin ingin me-reset pengaturan ini ?';
                        var confirm_color = '#d33';
                    }

                    Swal.fire({
                        title: 'Konfirmasi!',
                        html: alert,
                        icon: 'warning',
                        showCloseButton: true,
                        showCancelButton: true,
                        confirmButtonColor: confirm_color,
                        confirmButtonText: 'OK',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                type: $(this).attr('method'),
                                url: $(this).attr('action'),
                                data: $(this).serialize() + '&submit=' + active_element.val(),
                                dataType: "json",
                                success: function(response) {
                                    if (response.error == true) {
                                        show_alert({
                                            type: response.type,
                                            message: response.message
                                        });

                                        $('button[name="' + active_element.val() + '"]').removeAttr('disabled');
                                        $('button[name="' + active_element.val() + '"]').html('Simpan');
                                    } else {
                                        show_alert({
                                            type: response.type,
                                            message: response.message
                                        });

                                        load_layouts();
                                    }
                                }
                            });
                        }
                    });
                });
            },
            error: function() {
                show_alert();
            }
        });
    }
</script>