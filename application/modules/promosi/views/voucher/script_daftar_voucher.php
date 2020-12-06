<script>
    $(document).ready(function () {
        load_table({
            status_aktif: 'all'
        });

        $('#filter_data').click(function() {
            $('#clear_filter').show();

            refresh_table();
        });

        $('#clear_filter').click(function() {
            $('#clear_filter').hide();
            $('#jenis_potongan').val(null).trigger('change');
            
            refresh_table();
        });
    });

    function load_table(params) {
        $('.loading-voucher').show();
        $('.after-loading-voucher').hide();
        setTimeout(() => {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                pagingType: "full_numbers",
                destroy: true,
                order: [],
                columnDefs: [
                {
                    targets: [0,7],
                    orderable: false
                },
                {
                    targets: 1,
                    createdCell:  function (td, cellData, rowData, row, col) {
                        $(td).attr({
                            style: 'width: 230px; white-space: nowrap;'
                        });
                    }
                },
                {
                    targets: [1,2,3,4,5],
                    createdCell:  function (td, cellData, rowData, row, col) {
                        $(td).attr({
                            style: 'white-space: nowrap;'
                        });
                    }
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
                        type:"image", closeOnContentClick:!0, closeBtnInside:!1, fixedContentPos:!0, mainClass:"mfp-no-margins mfp-with-zoom",
                        image: {
                            verticalFit: !0
                        },
                        zoom: {
                            enabled: !0, duration: 300
                        }
                    });
                },
                ajax: {
                    url: "<?= base_url() ?>json/list_voucher",
                    type: "POST",
                    data: {
                        params: params
                    },
                    dataType: "json",
                    complete: function () {
                        $('.after-loading-voucher').show();
                        $('.loading-voucher').hide();
                    },
                    error: function () {
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

        load_count();
    }

    function refresh_table() {
        $('.status_load').each(function () {
            if ($(this).hasClass('active')) {
                load_table({
                    status_aktif: $(this).data('load'),
                    jenis_potongan: $('#jenis_potongan').val()
                });
            }
        });

        load_count();
    }

    function load_count() {
        $.ajax({
            url: '<?= base_url() ?>json/count_voucher',
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                $('[data-load="all"] span').text('('+response.all+')');
                $('[data-load="Aktif"] span').text('('+response.aktif+')');
                $('[data-load="Tidak Aktif"] span').text('('+response.tidak_aktif+')');
            }
        });
    }

    function confirm_data(id_promo, submit) {
        $.ajax({
            url: '<?= base_url() ?>json/get_voucher',
            type: 'POST',
            data: {
                id_promo: id_promo
            },
            dataType: 'json',
            success: function (response) {
                var data = response.data;

                if (response.error == false) {
                    if (submit == 'delete') {
                        Swal.fire({
                            title: 'Konfirmasi!',
                            html: "Anda yakin ingin menghapus voucher "+data.kode_promo+" ?",
                            icon: 'warning',
                            showCloseButton: true,
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#64B0C9',
                            confirmButtonText: 'Hapus Voucher',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: '<?= base_url() ?>promosi/voucher',
                                    type: 'POST',
                                    data: {
                                        submit: submit,
                                        id_promo: id_promo,
                                        post_data: data
                                    },
                                    dataType: 'json',
                                    success: function (response) {
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

                                            refresh_table();
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
                    } else if (submit == 'Tidak Aktif') {
                        Swal.fire({
                            title: 'Konfirmasi!',
                            html: "Anda yakin ingin menonaktifkan voucher "+data.kode_promo+" ?",
                            icon: 'warning',
                            showCloseButton: true,
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#64B0C9',
                            confirmButtonText: 'Nonaktifkan',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: '<?= base_url() ?>promosi/voucher',
                                    type: 'POST',
                                    data: {
                                        submit: submit,
                                        id_promo: id_promo,
                                        post_data: data
                                    },
                                    dataType: 'json',
                                    success: function (response) {
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

                                            refresh_table();
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
                    } else if (submit == 'Aktif') {
                        Swal.fire({
                            title: 'Konfirmasi!',
                            html: "Anda yakin ingin mengaktifkan voucher "+data.kode_promo+" ?",
                            icon: 'warning',
                            showCloseButton: true,
                            showCancelButton: true,
                            confirmButtonColor: '#64B0C9',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Aktifkan',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: '<?= base_url() ?>promosi/voucher',
                                    type: 'POST',
                                    data: {
                                        submit: submit,
                                        id_promo: id_promo,
                                        post_data: data
                                    },
                                    dataType: 'json',
                                    success: function (response) {
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

                                            refresh_table();
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