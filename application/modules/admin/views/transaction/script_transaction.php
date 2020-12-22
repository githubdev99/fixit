<script>
    $(document).ready(function() {
        load_table();
    });

    function load_table() {
        $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            pagingType: "full_numbers",
            destroy: true,
            order: [],
            columnDefs: [{
                targets: [0, 5, 7],
                orderable: false
            }],
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
                url: "<?= $core['url_api'] ?>datatable/transaction",
                type: "POST",
                dataType: "json",
                error: function() {
                    show_alert();
                }
            }
        });
    }

    function show_modal(params) {
        if (params) {
            if (params.id) {
                if (params.modal == 'detail') {
                    $('#datatable_detail').DataTable({
                        processing: true,
                        serverSide: true,
                        pagingType: "full_numbers",
                        destroy: true,
                        order: [],
                        columnDefs: [{
                                targets: 0,
                                orderable: false
                            },
                            {
                                targets: 3,
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
                            url: "<?= $core['url_api'] ?>datatable/transaction_detail",
                            type: "POST",
                            data: {
                                params: {
                                    transaction_id: params.id
                                }
                            },
                            dataType: "json",
                            error: function() {
                                show_alert();
                            }
                        }
                    });

                    $('#detail').modal({
                        backdrop: 'static',
                        keyboard: true,
                        show: true
                    });
                } else if (params.modal == 'delete') {
                    $.ajax({
                        type: 'get',
                        url: '<?= $core['url_api'] ?>transaction/' + params.id,
                        dataType: 'json',
                        success: function(response) {
                            var data = response.data;

                            Swal.fire({
                                title: 'Konfirmasi!',
                                html: `Anda yakin ingin menghapus data transaksi ${data.queue} <br> oleh pelanggan ${data.customer_name} ?`,
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
                                        url: '<?= $core['url_api'] ?>transaction/' + params.id,
                                        dataType: 'json',
                                        success: function(response2) {
                                            var data2 = response2.data;

                                            if (response2.status.code == 200) {
                                                show_alert({
                                                    type: 'success',
                                                    message: `Data transaksi ${data2.queue} - ${data.customer_name} berhasil di hapus`
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
                                                        message: `Data transaksi ${data2.queue} - ${data.customer_name} gagal di hapus`
                                                    });
                                                }
                                            }
                                        }
                                    });
                                }
                            });
                        },
                        error: function() {
                            show_alert();
                        }
                    });
                }
            } else {
                show_alert();
            }
        } else {
            show_alert();
        }
    }
</script>