<script>
    $(document).ready(function() {
        trigger_enter({
            selector: '.add',
            target: 'button[name="add"]'
        });

        $('form[name="add"]').submit(function(e) {
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
                        $('button[name="' + active_element.val() + '"]').html('Simpan');
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
            dataType: 'json',
            success: function(response) {
                if (response.error == false) {
                    $('select[name="vehicle_id"]').html(response.html);
                } else {
                    show_alert();
                }
            }
        });

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

        $.ajax({
            url: '<?= base_url() ?>admin/vehicle/option',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.error == false) {
                    $('select[name="vehicle_id"]').html(response.html);
                } else {
                    show_alert();
                }
            }
        });

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