<script>
    $(document).ready(function () {
        load_table({
            status_diskon: 'all'
        });

        $('.periode_diskon').change(function (e) {
            e.preventDefault();

            if ($('input[name="tgl_mulai_diskon"]').val() != '' && $('input[name="tgl_berakhir_diskon"]').val() != '') {
                $('button[name="atur_diskon"]').removeAttr('disabled');
            } else {
                $('button[name="atur_diskon"]').attr('disabled', 'true');
            }
        });

        $('#atur_diskon input[name="presentase_diskon"]').keyup(function (e) {
            var harga_normal = $('#atur_diskon input[name="harga_normal"]').val();
            var diskon = (harga_normal * this.value) / 100;
            var harga_variasi = (this.value != 0) ? harga_normal - diskon : '';
            var rp_harga_variasi = (this.value != 0) ? rupiah(harga_normal - diskon) : 'Belum ada diskon';

            $('#atur_diskon #harga_variasi').html(rp_harga_variasi);
            $('#atur_diskon input[name="harga_variasi"]').val(harga_variasi);
            $('#atur_diskon input[name="nominal_diskon"]').val(diskon);
        });

        $('#ubah_diskon input[name="presentase_diskon"]').keyup(function (e) {
            var harga_normal = $('#ubah_diskon input[name="harga_normal"]').val();
            var diskon = (harga_normal * this.value) / 100;
            var harga_variasi = (this.value != 0) ? harga_normal - diskon : '';
            var rp_harga_variasi = (this.value != 0) ? rupiah(harga_normal - diskon) : 'Belum ada diskon';

            $('#ubah_diskon #harga_variasi').html(rp_harga_variasi);
            $('#ubah_diskon input[name="harga_variasi"]').val(harga_variasi);
            $('#ubah_diskon input[name="nominal_diskon"]').val(diskon);
        });

        $('form[name="diskon"]').submit(function (e) {
            e.preventDefault();
            
            var active_element = $(document.activeElement);

            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: $(this).serialize()+'&submit='+active_element.val(),
                dataType: "json",
                success: function (response) {
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
                        $('button[name="'+active_element.val()+'"]').attr('disabled', 'true');
                        $('button[name="'+active_element.val()+'"]').html('<i class="fas fa-circle-notch fa-spin"></i>');

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

                            $('button[name="'+active_element.val()+'"]').removeAttr('disabled');
                            $('button[name="'+active_element.val()+'"]').html((active_element.val() == 'atur_diskon') ? 'Simpan' : 'Ubah');

                            if (active_element.val() == 'atur_diskon') {
                                $('#atur_diskon #harga_variasi').html('Belum ada diskon');
                                $('#atur_diskon input[name="presentase_diskon"]').val('');
                                $('#atur_diskon input[name="tgl_mulai_diskon"]').val('');
                                $('#atur_diskon input[name="tgl_berakhir_diskon"]').val('');
                                $('#atur_diskon input[name="harga_variasi"]').val('');
                                $('#atur_diskon input[name="nominal_diskon"]').val('');

                                $('#atur_diskon').modal('hide');
                            } else {
                                $('#ubah_diskon').modal('hide');
                            }

                            refresh_table();
                        }, 600);
                    }
                }
            });
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
                            style: 'width: 400px;'
                        });
                    }
                },
                {
                    targets: [2,3,5],
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
                    url: "<?= base_url() ?>json/list_produk_diskon",
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
                    status_diskon: $(this).data('load')
                });
            }
        });

        load_count();
    }

    function load_count() {
        $.ajax({
            url: '<?= base_url() ?>json/count_produk_diskon',
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                $('[data-load="all"] span').text('('+response.all+')');
                $('[data-load="aktif"] span').text('('+response.aktif+')');
                $('[data-load="tidak aktif"] span').text('('+response.tidak_aktif+')');
            }
        });
    }

    function modal_diskon(params) {
        if (params.modal == 'atur') {
            $('#atur_diskon').modal({
                backdrop: 'static',
                keyboard: true,
                show: true
            });

            $('#atur_diskon button[name="atur_diskon"]').attr('disabled', 'true');
            $('#atur_diskon #harga_variasi').html('Belum ada diskon');
            $('#atur_diskon input[name="presentase_diskon"]').val('');
            $('#atur_diskon input[name="tgl_mulai_diskon"]').val('');
            $('#atur_diskon input[name="tgl_berakhir_diskon"]').val('');
            $('#atur_diskon input[name="harga_variasi"]').val('');
            $('#atur_diskon input[name="nominal_diskon"]').val('');

            $.ajax({
                url: '<?= base_url() ?>json/get_produk_diskon',
                type: 'POST',
                data: {
                    params: params
                },
                dataType: 'json',
                success: function (response) {
                    var data = response.data;

                    if (response.error == false) {
                        $('#atur_diskon #foto').attr('src', data.foto);
                        $('#atur_diskon #nama_produk').html(data.nama_produk);
                        $('#atur_diskon #kode_sku').html('SKU : '+data.kode_sku);
                        $('#atur_diskon #kategori_produk').html('Kategori : '+data.kategori_produk);
                        $('#atur_diskon #harga_normal').html(rupiah(data.harga_normal));
                        $('#atur_diskon #stok').html('Stok Tersedia : '+data.stok);

                        $('#atur_diskon input[name="id_data"]').val(params.id_data);
                        $('#atur_diskon input[name="harga_normal"]').val(data.harga_normal);
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
            $('#ubah_diskon').modal({
                backdrop: 'static',
                keyboard: true,
                show: true
            });

            $.ajax({
                url: '<?= base_url() ?>json/get_produk_diskon',
                type: 'POST',
                data: {
                    params: params
                },
                dataType: 'json',
                success: function (response) {
                    var data = response.data;

                    if (response.error == false) {
                        $('#ubah_diskon #foto').attr('src', data.foto);
                        $('#ubah_diskon #nama_produk').html(data.nama_produk);
                        $('#ubah_diskon #kode_sku').html('SKU : '+data.kode_sku);
                        $('#ubah_diskon #kategori_produk').html('Kategori : '+data.kategori_produk);
                        $('#ubah_diskon #harga_normal').html(rupiah(data.harga_normal));
                        $('#ubah_diskon #harga_variasi').html(rupiah(data.harga_variasi));
                        $('#ubah_diskon #stok').html('Stok Tersedia : '+data.stok);

                        $('#ubah_diskon input[name="id_data"]').val(params.id_data);
                        $('#ubah_diskon input[name="harga_normal"]').val(data.harga_normal);
                        $('#ubah_diskon input[name="harga_variasi"]').val(data.harga_variasi);
                        $('#ubah_diskon input[name="presentase_diskon"]').val(data.presentase_diskon);
                        $('#ubah_diskon input[name="tgl_mulai_diskon"]').val(data.tgl_mulai_diskon);
                        $('#ubah_diskon input[name="tgl_berakhir_diskon"]').val(data.tgl_berakhir_diskon);
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
    }

    function hapus_diskon() {
        Swal.fire({
            title: 'Konfirmasi!',
            html: "Anda yakin ingin menghapus diskon ini ?",
            icon: 'warning',
            showCloseButton: true,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#64B0C9',
            confirmButtonText: 'Hapus Diskon',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '<?= base_url() ?>promosi/diskon',
                    type: 'POST',
                    data: {
                        submit: 'delete',
                        id_data: $('#ubah_diskon input[name="id_data"]').val()
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
                            $('#ubah_diskon').modal('hide');
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
</script>