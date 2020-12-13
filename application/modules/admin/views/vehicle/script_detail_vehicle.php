<script>
    $(document).ready(function() {
        load_table({
            in_active: 'all',
            vehicle_id: '<?= encrypt_text($get_data['vehicle_id']) ?>'
        });

        trigger_enter({
            selector: '.add_child',
            target: 'button[name="add_child"]'
        });

        trigger_enter({
            selector: '.edit_child',
            target: 'button[name="edit_child"]'
        });

        $('form[name="add_child"]').submit(function(e) {
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
                        $('#add_child').modal('hide');

                        $('#add_child [name="name"]').val('');
                    }
                }
            });
        });

        $('form[name="edit_child"]').submit(function(e) {
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
                        $('#edit_child').modal('hide');
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
                            message: response.message,
                            callback: '<?= site_url(uri_string()) ?>'
                        });

                        $('button[name="' + active_element.val() + '"]').removeAttr('disabled');
                        $('button[name="' + active_element.val() + '"]').html('Edit');

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

    function show_modal_child(params) {
        if (params) {
            if (params.id) {
                $.ajax({
                    type: 'get',
                    url: '<?= $core['url_api'] ?>vehicle/children/' + params.id,
                    dataType: 'json',
                    success: function(response) {
                        var data = response.data;

                        if (params.modal == 'delete_child') {
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
                                        url: '<?= $core['url_api'] ?>vehicle/children/' + params.id,
                                        dataType: 'json',
                                        success: function(response2) {
                                            var data2 = response2.data;

                                            if (response2.status.code == 200) {
                                                show_alert({
                                                    type: 'success',
                                                    message: `Data kendaraan ${data2.name} berhasil di hapus`,
                                                    callback: '<?= base_url() ?>admin/vehicle'
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
                                                        message: `Data kendaraan ${data2.name} gagal di hapus`
                                                    });
                                                }
                                            }
                                        }
                                    });
                                }
                            });
                        } else if (params.modal == 'edit_child') {
                            $('#edit_child [name="id"]').val(data.id);
                            $('#edit_child [name="in_active"]').val(data.in_active);
                            $('#edit_child [name="name"]').val(data.name);

                            $('#edit_child').modal({
                                backdrop: 'static',
                                keyboard: true,
                                show: true
                            });
                        } else if (params.modal == 'active_child') {
                            $.ajax({
                                type: 'put',
                                url: '<?= $core['url_api'] ?>vehicle/children/' + params.id,
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
                                            message: `Data kendaraan ${data2.name} berhasil di aktifkan`,
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
                                                message: `Data kendaraan ${data2.name} gagal di aktifkan`
                                            });
                                        }
                                    }
                                }
                            });
                        } else if (params.modal == 'not_active_child') {
                            $.ajax({
                                type: 'put',
                                url: '<?= $core['url_api'] ?>vehicle/children/' + params.id,
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
                                            message: `Data kendaraan ${data2.name} berhasil di nonaktifkan`,
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
                show_alert();
            }
        } else {
            show_alert();
        }
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
                                                    message: `Data kendaraan ${data2.name} berhasil di hapus`,
                                                    callback: '<?= base_url() ?>admin/vehicle'
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
                                            message: `Data kendaraan ${data2.name} berhasil di aktifkan`,
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
                                            message: `Data kendaraan ${data2.name} berhasil di nonaktifkan`,
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
                show_alert();
            }
        } else {
            show_alert();
        }
    }
</script>