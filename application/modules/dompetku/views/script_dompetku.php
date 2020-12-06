<script>
    $(document).ready(function() {
        refresh_table({
            table: 'rekening'
        });
        refresh_table({
            table: 'payouts'
        });
        load_all_option();

        $('#filter_data').click(function() {
            $('#clear_filter').show();
            refresh_table({
                table: 'payouts'
            });
        });

        $('#clear_filter').click(function() {
            $('#clear_filter').hide();
            $('#filter_tgl_tarik').val('');

            refresh_table({
                table: 'payouts'
            });
        });

        $('form[name="rekening_toko"]').submit(function(e) {
            e.preventDefault();

            var active_element = $(document.activeElement);

            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: $(this).serialize() + '&submit=' + active_element.val(),
                dataType: "json",
                success: function(response) {
                    if (response.error == true) {
                        Swal.mixin({
                            toast: true,
                            position: "top",
                            showCloseButton: !0,
                            showConfirmButton: false,
                            timer: 4000,
                            onOpen: (toast) => {
                                toast.addEventListener("mouseenter", Swal.stopTimer)
                                toast.addEventListener("mouseleave", Swal.resumeTimer)
                            }
                        }).fire({
                            icon: response.type,
                            title: response.message
                        });
                    } else {
                        $('button[name="' + active_element.val() + '"]').attr('disabled', 'true');
                        $('button[name="' + active_element.val() + '"]').html('<i class="fas fa-circle-notch fa-spin"></i>');

                        setTimeout(() => {
                            Swal.mixin({
                                toast: true,
                                position: "top",
                                showCloseButton: !0,
                                showConfirmButton: false,
                                timer: 4000,
                                onOpen: (toast) => {
                                    toast.addEventListener("mouseenter", Swal.stopTimer)
                                    toast.addEventListener("mouseleave", Swal.resumeTimer)
                                }
                            }).fire({
                                icon: response.type,
                                title: response.message
                            });

                            refresh_table({
                                table: 'rekening'
                            });

                            $('button[name="' + active_element.val() + '"]').removeAttr('disabled');
                            $('button[name="' + active_element.val() + '"]').html((active_element.val() == 'tambah') ? 'Simpan' : 'Edit');

                            if (active_element.val() == 'tambah') {
                                $('#tambah_rekening select[name="bank"]').val(null).trigger('change');
                                $('#tambah_rekening input[name="account"]').val('');
                                $('#tambah_rekening input[name="name"]').val('');
                            } else {
                                $('#edit_rekening select[name="bank"]').val(null).trigger('change');
                                $('#edit_rekening input[name="account"]').val('');
                                $('#edit_rekening input[name="name"]').val('');
                            }

                            modal_rekening('daftar');
                        }, 1000);
                    }
                }
            });
        });
    });

    function load_table(params) {
        if (params.table) {
            if (params.table == 'payouts') {
                $('.loading-payouts').show();
                $('.after-loading-payouts').hide();
                setTimeout(() => {
                    $('#datatable_payouts').DataTable({
                        processing: true,
                        serverSide: true,
                        pagingType: "full_numbers",
                        destroy: true,
                        order: [],
                        columnDefs: [{
                                targets: 0,
                                createdCell: function(td, cellData, rowData, row, col) {
                                    $(td).attr({
                                        style: 'white-space: nowrap;'
                                    });
                                }
                            },
                            {
                                targets: 1,
                                createdCell: function(td, cellData, rowData, row, col) {
                                    $(td).attr({
                                        style: 'text-align: right; white-space: nowrap;'
                                    });
                                }
                            },
                            {
                                targets: 2,
                                createdCell: function(td, cellData, rowData, row, col) {
                                    $(td).attr({
                                        style: 'text-align: center;'
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
                            url: "<?= base_url() ?>json/list_payouts",
                            type: "POST",
                            data: {
                                params: params
                            },
                            dataType: "json",
                            complete: function() {
                                $('.after-loading-payouts').show();
                                $('.loading-payouts').hide();
                            },
                            error: function() {
                                Swal.mixin({
                                    toast: true,
                                    position: "top",
                                    showCloseButton: !0,
                                    showConfirmButton: false,
                                    timer: 4000,
                                    onOpen: (toast) => {
                                        toast.addEventListener("mouseenter", Swal.stopTimer)
                                        toast.addEventListener("mouseleave", Swal.resumeTimer)
                                    }
                                }).fire({
                                    icon: "error",
                                    title: "Ada kesalahan teknis"
                                });
                            }
                        }
                    });
                }, 600);
            } else if (params.table == 'rekening') {
                $('#datatable_rekening').DataTable({
                    processing: true,
                    serverSide: true,
                    pagingType: "full_numbers",
                    destroy: true,
                    order: [],
                    columnDefs: [{
                            targets: [0, 4],
                            orderable: false
                        },
                        {
                            targets: 4,
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
                        url: "<?= base_url() ?>json/list_rekening",
                        type: "POST",
                        dataType: "json",
                        complete: function() {
                            $('.after-loading-rekening').show();
                            $('.loading-rekening').hide();
                        },
                        error: function() {
                            Swal.mixin({
                                toast: true,
                                position: "top",
                                showCloseButton: !0,
                                showConfirmButton: false,
                                timer: 4000,
                                onOpen: (toast) => {
                                    toast.addEventListener("mouseenter", Swal.stopTimer)
                                    toast.addEventListener("mouseleave", Swal.resumeTimer)
                                }
                            }).fire({
                                icon: "error",
                                title: "Ada kesalahan teknis"
                            });
                        }
                    }
                });
            } else {
                Swal.mixin({
                    toast: true,
                    position: "top",
                    showCloseButton: !0,
                    showConfirmButton: false,
                    timer: 4000,
                    onOpen: (toast) => {
                        toast.addEventListener("mouseenter", Swal.stopTimer)
                        toast.addEventListener("mouseleave", Swal.resumeTimer)
                    }
                }).fire({
                    icon: "error",
                    title: "Ada kesalahan teknis"
                });
            }
        } else {
            Swal.mixin({
                toast: true,
                position: "top",
                showCloseButton: !0,
                showConfirmButton: false,
                timer: 4000,
                onOpen: (toast) => {
                    toast.addEventListener("mouseenter", Swal.stopTimer)
                    toast.addEventListener("mouseleave", Swal.resumeTimer)
                }
            }).fire({
                icon: "error",
                title: "Ada kesalahan teknis"
            });
        }
    }

    function refresh_table(params) {
        if (params.table) {
            if (params.table == 'payouts') {
                $('.status_load').each(function() {
                    if ($(this).hasClass('active')) {
                        load_table({
                            status: $(this).data('load'),
                            tgl_tarik: $('#filter_tgl_tarik').val(),
                            table: params.table
                        });
                    }
                });
            } else if (params.table == 'rekening') {
                load_table({
                    table: params.table
                });
            } else {
                Swal.mixin({
                    toast: true,
                    position: "top",
                    showCloseButton: !0,
                    showConfirmButton: false,
                    timer: 4000,
                    onOpen: (toast) => {
                        toast.addEventListener("mouseenter", Swal.stopTimer)
                        toast.addEventListener("mouseleave", Swal.resumeTimer)
                    }
                }).fire({
                    icon: "error",
                    title: "Ada kesalahan teknis"
                });
            }
        } else {
            Swal.mixin({
                toast: true,
                position: "top",
                showCloseButton: !0,
                showConfirmButton: false,
                timer: 4000,
                onOpen: (toast) => {
                    toast.addEventListener("mouseenter", Swal.stopTimer)
                    toast.addEventListener("mouseleave", Swal.resumeTimer)
                }
            }).fire({
                icon: "error",
                title: "Ada kesalahan teknis"
            });
        }
    }

    function filter_data() {
        $('#reset_data').show();
    }

    function reset_data() {
        $('#reset_data').hide();
    }

    function modal_rekening(param) {
        if (typeof param == 'object') {
            $('#edit_rekening').modal({
                backdrop: 'static',
                keyboard: true,
                show: true
            });

            $('#daftar_rekening').modal('hide');

            $('#edit_rekening select[name="bank"]').val(null).trigger('change');
            $('#edit_rekening input[name="account"]').val('');
            $('#edit_rekening input[name="name"]').val('');

            $.ajax({
                url: '<?= base_url() ?>json/get_rekening',
                type: 'POST',
                data: {
                    id_data: param.id
                },
                dataType: 'json',
                success: function(response) {
                    var data = response.data;

                    if (response.error == false) {
                        select2_ajax({
                            selector: '#edit_rekening select[name="bank"]',
                            url: '<?= base_url() ?>json/option_bank',
                            data: {
                                selected: data.bank_code + ':' + data.bank_name
                            },
                            normal: true
                        });

                        $('#edit_rekening input[name="id_data"]').val(param.id);
                        $('#edit_rekening input[name="account"]').val(data.account);
                        $('#edit_rekening input[name="account"]').val(data.account);
                        $('#edit_rekening input[name="name"]').val(data.name);
                    } else {
                        Swal.mixin({
                            toast: true,
                            position: "top",
                            showCloseButton: !0,
                            showConfirmButton: false,
                            timer: 4000,
                            onOpen: (toast) => {
                                toast.addEventListener("mouseenter", Swal.stopTimer)
                                toast.addEventListener("mouseleave", Swal.resumeTimer)
                            }
                        }).fire({
                            icon: response.type,
                            title: response.message
                        });
                    }
                }
            });
        } else {
            if (param == 'pin') {
                $('#masukkan_pin').modal({
                    backdrop: 'static',
                    keyboard: true,
                    show: true
                });

                $('#tambah_rekening').modal('hide');
                $('#edit_rekening').modal('hide');
            } else if (param == 'daftar') {
                $('#daftar_rekening').modal({
                    backdrop: 'static',
                    keyboard: true,
                    show: true
                });

                $('#tambah_rekening').modal('hide');
                $('#edit_rekening').modal('hide');
            } else if (param == 'tambah') {
                $('#tambah_rekening').modal({
                    backdrop: 'static',
                    keyboard: true,
                    show: true
                });

                $('#daftar_rekening').modal('hide');
            } else {
                Swal.mixin({
                    toast: true,
                    position: "top",
                    showCloseButton: !0,
                    showConfirmButton: false,
                    timer: 4000,
                    onOpen: (toast) => {
                        toast.addEventListener("mouseenter", Swal.stopTimer)
                        toast.addEventListener("mouseleave", Swal.resumeTimer)
                    }
                }).fire({
                    icon: response.type,
                    title: response.message
                });
            }
        }
    }

    function load_all_option() {
        select2_ajax({
            selector: 'select[name="bank"]',
            url: '<?= base_url() ?>json/option_bank',
            normal: true
        });
    }

    function confirm_data(id, submit) {
        $.ajax({
            url: '<?= base_url() ?>json/get_rekening',
            type: 'POST',
            data: {
                id_data: id
            },
            dataType: 'json',
            success: function(response) {
                var data = response.data;

                if (response.error == false) {
                    if (submit == 'delete') {
                        Swal.fire({
                            title: 'Konfirmasi!',
                            html: `Anda yakin ingin menghapus rekening ` + data.account + ` dengan nama pemilik  ` + data.name + ` ?`,
                            icon: 'warning',
                            showCloseButton: true,
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#64B0C9',
                            confirmButtonText: 'Hapus Data',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: '<?= base_url() ?>dompetku',
                                    type: 'POST',
                                    data: {
                                        submit: submit,
                                        id_data: id,
                                        post_data: data
                                    },
                                    dataType: 'json',
                                    success: function(response) {
                                        var data = response.data;

                                        if (response.error == false) {
                                            Swal.mixin({
                                                toast: true,
                                                position: "top",
                                                showCloseButton: !0,
                                                showConfirmButton: false,
                                                timer: 4000,
                                                onOpen: (toast) => {
                                                    toast.addEventListener("mouseenter", Swal.stopTimer)
                                                    toast.addEventListener("mouseleave", Swal.resumeTimer)
                                                }
                                            }).fire({
                                                icon: response.type,
                                                title: response.message
                                            });

                                            refresh_table({
                                                table: 'rekening'
                                            });
                                        } else {
                                            Swal.mixin({
                                                toast: true,
                                                position: "top",
                                                showCloseButton: !0,
                                                showConfirmButton: false,
                                                timer: 4000,
                                                onOpen: (toast) => {
                                                    toast.addEventListener("mouseenter", Swal.stopTimer)
                                                    toast.addEventListener("mouseleave", Swal.resumeTimer)
                                                }
                                            }).fire({
                                                icon: response.type,
                                                title: response.message
                                            });
                                        }
                                    }
                                });
                            }
                        });
                    }
                } else {
                    Swal.mixin({
                        toast: true,
                        position: "top",
                        showCloseButton: !0,
                        showConfirmButton: false,
                        timer: 4000,
                        onOpen: (toast) => {
                            toast.addEventListener("mouseenter", Swal.stopTimer)
                            toast.addEventListener("mouseleave", Swal.resumeTimer)
                        }
                    }).fire({
                        icon: response.type,
                        title: response.message
                    });
                }
            }
        });
    }
</script>