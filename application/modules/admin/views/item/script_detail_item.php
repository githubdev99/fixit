<script>
    function show_modal(params) {
        if (params) {
            if (params.id) {
                $.ajax({
                    type: 'get',
                    url: '<?= $core['url_api'] ?>item/' + params.id,
                    dataType: 'json',
                    success: function(response) {
                        var data = response.data;

                        if (data.vehicle) {
                            var vehicle_id = data.vehicle.id;

                            if (data.vehicle.children) {
                                var vehicle_children_id = data.vehicle.children.id;
                            } else {
                                var vehicle_children_id = null;
                            }
                        } else {
                            var vehicle_id = null;
                        }

                        if (params.modal == 'delete') {
                            Swal.fire({
                                title: 'Konfirmasi!',
                                html: `Anda yakin ingin menghapus data barang ${data.name} ?`,
                                icon: 'warning',
                                showCloseButton: true,
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                confirmButtonText: 'OK',
                                cancelButtonText: 'Batal'
                            }).then((result) => {
                                if (result.value) {
                                    $.ajax({
                                        type: 'delete',
                                        url: '<?= $core['url_api'] ?>item/' + params.id,
                                        dataType: 'json',
                                        success: function(response2) {
                                            var data2 = response2.data;

                                            if (response2.status.code == 200) {
                                                show_alert({
                                                    type: 'success',
                                                    message: `Data barang ${data2.name} berhasil di hapus`,
                                                    callback: '<?= base_url() ?>admin/item'
                                                });

                                                load_table();
                                            } else {
                                                if (response2.status.code == 404) {
                                                    show_alert({
                                                        type: 'warning',
                                                        message: `Data tidak ditemukan`
                                                    });
                                                } else {
                                                    show_alert({
                                                        type: 'success',
                                                        message: `Data barang ${data2.name} gagal di hapus`
                                                    });
                                                }
                                            }
                                        }
                                    });
                                }
                            });
                        } else if (params.modal == 'active') {
                            $.ajax({
                                type: 'put',
                                url: '<?= $core['url_api'] ?>item/' + params.id,
                                data: {
                                    vehicle_id: vehicle_id,
                                    vehicle_children_id: vehicle_children_id,
                                    name: data.name,
                                    price: data.price,
                                    stock: data.stock,
                                    in_active: 1
                                },
                                dataType: 'json',
                                success: function(response2) {
                                    var data2 = response2.data;

                                    if (response2.status.code == 200) {
                                        show_alert({
                                            type: 'success',
                                            message: `Data barang ${data2.name} berhasil di aktifkan`,
                                            callback: '<?= site_url(uri_string()) ?>'
                                        });
                                    } else {
                                        if (response2.status.code == 404) {
                                            show_alert({
                                                type: 'warning',
                                                message: `Data tidak ditemukan`
                                            });
                                        } else {
                                            show_alert({
                                                type: 'success',
                                                message: `Data barang ${data2.name} gagal di aktifkan`
                                            });
                                        }
                                    }
                                }
                            });
                        } else if (params.modal == 'not_active') {
                            $.ajax({
                                type: 'put',
                                url: '<?= $core['url_api'] ?>item/' + params.id,
                                data: {
                                    vehicle_id: vehicle_id,
                                    vehicle_children_id: vehicle_children_id,
                                    name: data.name,
                                    price: data.price,
                                    stock: data.stock,
                                    in_active: 0
                                },
                                dataType: 'json',
                                success: function(response2) {
                                    var data2 = response2.data;

                                    if (response2.status.code == 200) {
                                        show_alert({
                                            type: 'success',
                                            message: `Data barang ${data2.name} berhasil di nonaktifkan`,
                                            callback: '<?= site_url(uri_string()) ?>'
                                        });
                                    } else {
                                        if (response2.status.code == 404) {
                                            show_alert({
                                                type: 'warning',
                                                message: `Data tidak ditemukan`
                                            });
                                        } else {
                                            show_alert({
                                                type: 'success',
                                                message: `Data barang ${data2.name} gagal di nonaktifkan`
                                            });
                                        }
                                    }
                                }
                            });
                        }
                    },
                    error: function() {
                        show_alert();
                    }
                });
            } else {
                show_alert();
            }
        } else {
            show_alert();
        }
    }
</script>