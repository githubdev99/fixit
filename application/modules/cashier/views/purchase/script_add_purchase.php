<script>
    let detail = [];
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
            url: '<?= base_url() ?>cashier/item/option',
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                if (response.error == false) {
                    $('#item_option').html(response.html);
                } else {
                    show_alert();
                }
            }
        });

        if (localStorage.detail) {
            detail = JSON.parse(localStorage.detail);
            show_detail();
        }

        $('.add_detail').click(function(e) {
            e.preventDefault();

            var checking = true;
            var item_id = $('#item_option').val();
            var qty = $('#qty').val();

            if (!item_id) {
                checking = false;
                show_alert({
                    type: 'info',
                    message: 'Barang belum dipilih!'
                });
            } else if (!qty) {
                checking = false;
                show_alert({
                    type: 'info',
                    message: 'Kuantitas belum diinput!'
                });
            }

            if (checking == true) {
                $.ajax({
                    type: 'get',
                    url: '<?= $core['url_api'] ?>item/' + item_id,
                    dataType: 'json',
                    success: function(response) {
                        var data = response.data;

                        save_detail({
                            id: data.id,
                            name: data.name,
                            price: data.price,
                            price_currency_format: data.price_currency_format,
                            stock: data.stock,
                            qty: qty
                        });
                    },
                    error: function() {
                        show_alert();
                    }
                });
            }
        });
    });

    function save_detail(data) {
        for (let i in detail) {
            if (detail[i].name == data.name) {
                show_alert({
                    type: 'warning',
                    message: 'Barang sudah ditambah!'
                });

                // show_detail();
                return;
            }
        }

        detail.push({
            id: data.id,
            name: data.name,
            price: data.price,
            price_currency_format: data.price_currency_format,
            stock: data.stock,
            qty: data.qty,
            subprice: data.price * data.qty
        });

        // show_detail();
        show_alert({
            type: 'success',
            message: 'Barang berhasil ditambahkan'
        });
    }
</script>