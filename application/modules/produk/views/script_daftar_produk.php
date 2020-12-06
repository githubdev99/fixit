<script>
    $(document).ready(function () {
        load_table({
            status_produk: 'all'
        });
    });

    function load_table(params) {
        $('.loading-produk').show();
        $('.after-loading-produk').hide();
        setTimeout(() => {
            $('#datatable').DataTable({
                processing: true,
                serverSide: true,
                pagingType: "full_numbers",
                destroy: true,
                order: [],
                columnDefs: [
                {
                    targets: 5,
                    orderable: false
                },
                {
                    targets: 1,
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
                    url: "<?= base_url() ?>json/list_produk",
                    type: "POST",
                    data: {
                        params: params
                    },
                    dataType: "json",
                    complete: function () {
                        $('.after-loading-produk').show();
                        $('.loading-produk').hide();
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
                    status_produk: $(this).data('load')
                });
            }
        });

        load_count();
    }

    function load_count() {
        $.ajax({
            url: '<?= base_url() ?>json/count_produk',
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                $('[data-load="all"] span').text('('+response.all+')');
                $('[data-load="menunggu konfirmasi"] span').text('('+response.konfirmasi+')');
                $('[data-load="live"] span').text('('+response.live+')');
                $('[data-load="habis"] span').text('('+response.habis+')');
                $('[data-load="arsipkan"] span').text('('+response.arsipkan+')');
                $('[data-load="ditolak"] span').text('('+response.ditolak+')');
                $('[data-load="blokir"] span').text('('+response.blokir+')');
            }
        });
    }

    function confirm_data(id_produk, submit) {
        $.ajax({
            url: '<?= base_url() ?>json/get_produk',
            type: 'POST',
            data: {
                id_produk: id_produk
            },
            dataType: 'json',
            success: function (response) {
                var data = response.data;

                if (response.error == false) {
                    if (submit == 'delete') {
                        Swal.fire({
                            title: 'Konfirmasi!',
                            html: "Anda yakin ingin menghapus produk "+data.nama_produk+" ?",
                            icon: 'warning',
                            showCloseButton: true,
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#64B0C9',
                            confirmButtonText: 'Hapus Produk',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: '<?= base_url() ?>produk',
                                    type: 'POST',
                                    data: {
                                        submit: submit,
                                        id_produk: id_produk,
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
                    } else if (submit == 'arsipkan') {
                        Swal.fire({
                            title: 'Konfirmasi!',
                            html: "Anda yakin ingin mengarsipkan produk "+data.nama_produk+" ?",
                            icon: 'warning',
                            showCloseButton: true,
                            showCancelButton: true,
                            confirmButtonColor: '#64B0C9',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Arsipkan Produk',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: '<?= base_url() ?>produk',
                                    type: 'POST',
                                    data: {
                                        submit: submit,
                                        id_produk: id_produk,
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
                    } else if (submit == 'live') {
                        Swal.fire({
                            title: 'Konfirmasi!',
                            html: "Anda yakin ingin menampilkan produk "+data.nama_produk+" ?",
                            icon: 'warning',
                            showCloseButton: true,
                            showCancelButton: true,
                            confirmButtonColor: '#64B0C9',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Tampilkan Produk',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: '<?= base_url() ?>produk',
                                    type: 'POST',
                                    data: {
                                        submit: submit,
                                        id_produk: id_produk,
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