<script>
    let detail_transaction = [];
    $(document).ready(function() {
        load_table_detail('load');

        $('input[name="item_data"]').val(JSON.stringify(detail_transaction));

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

                        localStorage.removeItem('detail_transaction');
                    }
                }
            });
        });

        $.ajax({
            url: '<?= base_url() ?>cashier/transaction/option_vehicle',
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
                    url: '<?= base_url() ?>cashier/transaction/option_vehicle_children/' + $(this).val(),
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.error == false) {
                            $('select[name="vehicle_children_id"]').html(response.html);
                        } else {
                            show_alert();
                        }
                    }
                });

                $.ajax({
                    url: '<?= base_url() ?>cashier/transaction/option_service',
                    type: 'POST',
                    dataType: 'json',
                    success: function(response) {
                        if (response.error == false) {
                            $('select[name="service_data"]').html(response.html);
                        } else {
                            show_alert();
                        }
                    }
                });
            }
        });

        $.ajax({
            url: '<?= base_url() ?>cashier/transaction/option_item',
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

        if (localStorage.detail_transaction) {
            detail_transaction = JSON.parse(localStorage.detail_transaction);
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

                        if (qty > data.stock) {
                            show_alert({
                                type: 'warning',
                                message: 'Kuantitas melebihi stok!'
                            });
                        } else {
                            save_detail({
                                id: data.id,
                                name: data.name,
                                price: data.price,
                                price_currency_format: data.price_currency_format,
                                stock: data.stock,
                                qty: qty
                            });

                            $('#item_option').val(null).trigger('change');
                            $('#qty').val('');
                        }
                    },
                    error: function() {
                        show_alert();
                    }
                });
            }
        });
    });

    function save_detail(data) {
        for (let i in detail_transaction) {
            if (detail_transaction[i].name == data.name) {
                show_alert({
                    type: 'warning',
                    message: 'Barang sudah ditambah!'
                });

                show_detail();
                return;
            }
        }

        detail_transaction.push({
            id: data.id,
            name: data.name,
            price: data.price,
            price_currency_format: data.price_currency_format,
            stock: data.stock,
            qty: data.qty,
            subprice: data.price * data.qty,
            subprice_currency_format: rupiah(data.price * data.qty)
        });

        show_detail();

        show_alert({
            type: 'success',
            message: 'Barang berhasil ditambahkan'
        });
    }

    function show_detail() {
        if (window.localStorage) {
            localStorage.detail_transaction = JSON.stringify(detail_transaction);
        }

        load_table_detail('clear');

        var total_qty = 0;
        var total_price = 0;
        for (let i in detail_transaction) {
            let no = i;
            no++;

            load_table_detail('create').row.add([
                no,
                `
                Nama : ${detail_transaction[i].name}<br>
                Jenis : ${detail_transaction[i].name}<br>
                Harga : ${detail_transaction[i].price_currency_format}<br>
                Stok Tersedia : ${detail_transaction[i].stock}<br>
                `,
                detail_transaction[i].qty,
                detail_transaction[i].subprice_currency_format,
                `<button type="button" class="btn btn-danger btn-sm" onclick="delete_detail(${i});"><i class="fas fa-times"></i></button>`
            ]).draw(false);

            total_qty += parseInt(detail_transaction[i].qty);
            total_price += parseInt(detail_transaction[i].subprice);
        }

        $('#total_qty').html(total_qty);
        $('#total_price').html(rupiah(total_price));
        $('input[name="item_data"]').val(JSON.stringify(detail_transaction));
    }

    function delete_detail(index) {
        detail_transaction.splice(index, 1);
        show_detail();
    }

    function load_table_detail(params) {
        if (params == 'load') {
            $('#datatable').DataTable({
                pagingType: "full_numbers",
                destroy: true,
                columnDefs: [{
                    targets: 4,
                    orderable: false
                }],
                drawCallback: function() {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        } else if (params == 'clear') {
            $('#datatable').DataTable().clear().draw();
        } else {
            return $('#datatable').DataTable({
                pagingType: "full_numbers",
                destroy: true,
                columnDefs: [{
                    targets: 4,
                    orderable: false
                }],
                drawCallback: function() {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
        }
    }
</script>