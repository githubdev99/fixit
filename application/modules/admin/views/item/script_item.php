<script>
    $(document).ready(function() {
        load_table({
            in_active: 'all'
        });
    });

    function load_table(params) {
        $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            pagingType: "full_numbers",
            destroy: true,
            order: [],
            columnDefs: [{
                    targets: [0, 6],
                    orderable: false
                },
                {
                    targets: 6,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr({
                            style: 'white-space: nowrap;'
                        });
                    }
                }
            ],
            language: {
                paginate: {
                    previous: "<i class='uil uil-angle-left'>",
                    next: "<i class='uil uil-angle-right'>"
                },
                infoFiltered: ""
            },
            drawCallback: function() {
                $(".dataTables_paginate > .pagination").addClass("pagination-rounded");
                $('[data-toggle="tooltip"]').tooltip();
                $('.image-popup').magnificPopup({
                    type: "image",
                    closeOnContentClick: !0,
                    closeBtnInside: !1,
                    fixedContentPos: !0,
                    mainClass: "mfp-no-margins mfp-with-zoom",
                    image: {
                        verticalFit: !0
                    },
                    zoom: {
                        enabled: !0,
                        duration: 300
                    }
                });
            },
            ajax: {
                url: "<?= $core['url_api'] ?>datatable/item",
                type: "POST",
                data: {
                    params: params
                },
                dataType: "json",
                error: function() {
                    show_alert();
                }
            }
        });
    }

    function refresh_table() {
        $('.status_load').each(function() {
            if ($(this).hasClass('active')) {
                load_table({
                    in_active: $(this).data('load')
                });
            }
        });
    }

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
                            var vehicle_children_id = null;
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
                                                    message: `Data barang ${data2.name} berhasil di hapus`
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
                                            message: `Data barang ${data2.name} berhasil di aktifkan`
                                        });

                                        refresh_table();
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
                                            message: `Data barang ${data2.name} berhasil di nonaktifkan`
                                        });

                                        refresh_table();
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