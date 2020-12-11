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
                    targets: [0, 7],
                    orderable: false
                },
                {
                    targets: 7,
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
                url: "<?= $core['url_api'] ?>datatable/mechanic",
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
                $.ajax({
                    type: 'get',
                    url: '<?= $core['url_api'] ?>mechanic/' + params.id,
                    dataType: 'json',
                    success: function(response) {
                        var data = response.data;

                        if (params.modal == 'delete') {
                            Swal.fire({
                                title: 'Konfirmasi!',
                                html: `Anda yakin ingin menghapus data mekanik <br> dengan username <b>${data.username}</b> ?`,
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
                                        url: '<?= $core['url_api'] ?>mechanic/' + params.id,
                                        dataType: 'json',
                                        success: function(response2) {
                                            var data2 = response2.data;

                                            if (response2.status.code == 200) {
                                                show_alert({
                                                    type: 'success',
                                                    message: `Data mekanik <b>${data2.username}</b> berhasil di hapus`
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
                                                        message: `Data mekanik <b>${data2.username}</b> gagal di hapus`
                                                    });
                                                }
                                            }
                                        }
                                    });
                                }
                            });
                        }
                    },
                    error: function() {
                        show_alert();
                    }
                });
            } else {
                if (params.modal == 'add') {
                    $('#add').modal({
                        backdrop: 'static',
                        keyboard: true,
                        show: true
                    });
                }
            }
        } else {
            show_alert();
        }
    }
</script>