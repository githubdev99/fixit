<script>
    $(document).ready(function() {
        load_table({
            in_active: 'all'
        });

        trigger_enter({
            selector: '.add',
            target: 'button[name="add"]'
        });

        trigger_enter({
            selector: '.edit',
            target: 'button[name="edit"]'
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
                            message: response.message
                        });

                        $('button[name="' + active_element.val() + '"]').removeAttr('disabled');
                        $('button[name="' + active_element.val() + '"]').html('Simpan');

                        refresh_table();
                        $('#add').modal('hide');

                        $('#add [name="name"]').val('');
                    }
                }
            });
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
                            message: response.message
                        });

                        $('button[name="' + active_element.val() + '"]').removeAttr('disabled');
                        $('button[name="' + active_element.val() + '"]').html('Edit');

                        refresh_table();
                        $('#edit').modal('hide');
                    }
                }
            });
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
                    targets: 5,
                    orderable: false
                },
                {
                    targets: 5,
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
                url: "<?= $core['url_api'] ?>datatable/vehicle",
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
                    url: '<?= $core['url_api'] ?>vehicle/' + params.id,
                    dataType: 'json',
                    success: function(response) {
                        var data = response.data;

                        if (params.modal == 'delete') {
                            Swal.fire({
                                title: 'Konfirmasi!',
                                html: `Anda yakin ingin menghapus data kendaraan ${data.name} ?`,
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
                                        url: '<?= $core['url_api'] ?>vehicle/' + params.id,
                                        dataType: 'json',
                                        success: function(response2) {
                                            var data2 = response2.data;

                                            if (response2.status.code == 200) {
                                                show_alert({
                                                    type: 'success',
                                                    message: `Data kendaraan ${data2.name} berhasil di hapus`
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
                                                        message: `Data kendaraan ${data2.name} gagal di hapus`
                                                    });
                                                }
                                            }
                                        }
                                    });
                                }
                            });
                        } else if (params.modal == 'edit') {
                            $('#edit [name="id"]').val(data.id);
                            $('#edit [name="in_active"]').val(data.in_active);
                            $('#edit [name="name"]').val(data.name);

                            console.log(data);

                            $('#edit').modal({
                                backdrop: 'static',
                                keyboard: true,
                                show: true
                            });
                        } else if (params.modal == 'active') {
                            $.ajax({
                                type: 'put',
                                url: '<?= $core['url_api'] ?>vehicle/' + params.id,
                                data: {
                                    name: data.name,
                                    in_active: 1
                                },
                                dataType: 'json',
                                success: function(response2) {
                                    var data2 = response2.data;

                                    if (response2.status.code == 200) {
                                        show_alert({
                                            type: 'success',
                                            message: `Data kendaraan ${data2.name} berhasil di aktifkan`
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
                                                message: `Data kendaraan ${data2.name} gagal di aktifkan`
                                            });
                                        }
                                    }
                                }
                            });
                        } else if (params.modal == 'not_active') {
                            $.ajax({
                                type: 'put',
                                url: '<?= $core['url_api'] ?>vehicle/' + params.id,
                                data: {
                                    name: data.name,
                                    in_active: 0
                                },
                                dataType: 'json',
                                success: function(response2) {
                                    var data2 = response2.data;

                                    if (response2.status.code == 200) {
                                        show_alert({
                                            type: 'success',
                                            message: `Data kendaraan ${data2.name} berhasil di nonaktifkan`
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
                                                message: `Data kendaraan ${data2.name} gagal di nonaktifkan`
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