<script>
    $(document).ready(function() {
        $('#in_active').change(function(e) {
            e.preventDefault();

            if ($(this).is(':checked')) {
                $('input[name="in_active"]').val(1);
            } else {
                $('input[name="in_active"]').val(0);
            }
        });

        trigger_enter({
            selector: '.edit',
            target: 'button[name="edit"]'
        });

        $('form[name="edit"]').submit(function(e) {
            e.preventDefault();

            var active_element = $(document.activeElement);

            $('button[name="' + active_element.val() + '"]').attr('disabled', 'true');
            $('button[name="' + active_element.val() + '"]').html('<i class="fas fa-circle-notch fa-spin"></i>');

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
                        $('button[name="' + active_element.val() + '"]').html('Edit');
                    } else {
                        show_alert({
                            type: response.type,
                            message: response.message,
                            callback: response.callback
                        });
                    }
                }
            });
        });

        $.ajax({
            url: '<?= base_url() ?>admin/vehicle/option',
            type: 'POST',
            data: {
                id: '<?= (!empty($get_data['vehicle'])) ? $get_data['vehicle']['id'] : null; ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.error == false) {
                    <?php if (!empty($get_data['vehicle'])) : ?>
                        $('#jenis_choice').show();
                        $('[name="vehicle_id"]').attr('required', 'true');
                    <?php endif ?>

                    $('select[name="vehicle_id"]').html(response.html);
                } else {
                    show_alert();
                }
            }
        });

        <?php if (!empty($get_data['vehicle'])) : ?>
            $.ajax({
                url: '<?= base_url() ?>admin/vehicle/option_children/<?= $get_data['vehicle']['id'] ?>',
                type: 'POST',
                data: {
                    id: '<?= (!empty($get_data['vehicle']['children'])) ? $get_data['vehicle']['children']['id'] : null; ?>'
                },
                dataType: 'json',
                success: function(response) {
                    if (response.error == false) {
                        $('select[name="vehicle_children_id"]').html(response.html);
                    } else {
                        show_alert();
                    }
                },
            });
        <?php endif ?>

        $('select[name="vehicle_id"]').change(function(e) {
            e.preventDefault();

            if ($(this).val()) {
                $.ajax({
                    url: '<?= base_url() ?>admin/vehicle/option_children/' + $(this).val(),
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.error == false) {
                            $('select[name="vehicle_children_id"]').html(response.html);
                        } else {
                            show_alert();
                        }
                    },
                });
            }
        });
    });

    function show_jenis(value) {
        if (value == 'yes') {
            $('#jenis_choice').show();
            $('[name="vehicle_id"]').attr('required', 'true');
        } else {
            $('#jenis_choice').hide();
            $('[name="vehicle_id"]').removeAttr('required');
            $('[name="vehicle_id"]').val(null).trigger('change');
            $('[name="vehicle_children_id"]').val(null).trigger('change');
        }
    }
</script>