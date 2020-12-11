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

                    Swal.mixin({
                        toast: true,
                        position: "top",
                        showCloseButton: !0,
                        showConfirmButton: true,
                        showCancelButton: true
                    }).fire({
                        icon: "warning",
                        title: "Anda yakin ingin melakukan pengaturan ini ?"
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