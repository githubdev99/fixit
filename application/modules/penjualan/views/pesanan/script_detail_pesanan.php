

<script>
   $(document).ready(function () {

        $('#ModalTerima').modal('hide');
        load_table({
            status_transaksi : 'all'
        });
    });


    function load_table(params) {
        $('#datatable').DataTable({
            processing: true,
			serverSide: true,
            pagingType: "full_numbers",
            destroy: true,
            order: [],
            columnDefs: [
			{
				targets: 0,
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
				url: "<?= base_url() ?>json/list_pesanan",
				type: "POST",
                data: {
                    params: params
                },
				dataType: "json",
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

    }

    function refresh_table() {
        $('.status_load').each(function () {
            if ($(this).hasClass('active')) {
                load_table({
                    status_transaksi: $(this).data('load')
                });
            }
        });

    }



</script>
<script type="text/javascript" language="javascript" >

        function TerimaPesanan(id_data){
            $.ajax({
                type: "POST",
                method: "POST",
                url: "<?= base_url() ?>json/get_pesanan",
                data: {id_data: id_data},
                success: function(response){
                    
                    var data = JSON.parse(response);
                    if(data.error == false){
                        $('input[name="status_pembatalan"]').val(data.status_pembatalan);
                        $('input[name="terima_pesanan"]').val(data.terima_pesanan);
                        $('input[name="id_data"]').val(data.id_data);
                        $('#ModalTerima').modal('show');
                        
                    }else{
                        swal('Gagal', 'Mohon maaf terjadi kesalahan', 'error');
                    }
                },
                error: function(){ 

                }
            });
        }

        function TolakPesanan(id_data){
            $.ajax({
                type: "POST",
                method: "POST",
                url: "<?= base_url() ?>json/get_pesanan",
                data: {id_data: id_data},
                success: function(response){
                    var data = JSON.parse(response);
                    if(data.error == false){
                        $('input[name="status_pembatalan"]').val(data.status_pembatalan);
                        $('input[name="terima_pesanan"]').val(data.terima_pesanan);
                        $('input[name="status_pesanan"]').val(data.status_pesanan);
                        $('input[name="status_transaksi"]').val(data.status_transaksi);
                        $('input[name="id_data"]').val(data.id_data);
                        $('input[name="invoice"]').val(data.invoice);
                        document.getElementById('invoice').innerHTML = data.invoice;
                        $('#ModalTolak').modal('show');
                    }else{
                        swal('Gagal', 'Mohon maaf terjadi kesalahan', 'error');
                    }
                },
                error: function(){ 

                }
            });
        }


        function confirm_data(id_data,submit) {
        $.ajax({
            url: '<?= base_url() ?>json/get_pesanan',
            type: 'POST',
            data: {
                id_data: id_data,
            },
            dataType: 'json',
            success: function (response) {
                var data = response.data;
                if (response.error == false) {
                    if (submit == 'terima') {
                        Swal.fire({
                            title: 'Konfirmasi!',
                            html: "Terima Pesanan Ini ?",
                            icon: 'warning',
                            showCloseButton: true,
                            showCancelButton: true,
                            confirmButtonColor: '#64B0C9',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Terima',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.value) {
                                $.ajax({
                                    url: '<?= base_url() ?>penjualan/pesanan',
                                    type: 'POST',
                                    data: {
                                        submit: submit,
                                        id_data: id_data,
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
                    } } 
                else {
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


<!-- <script>

    
    $(document).ready(function () {
        $('#datatable').DataTable({
            pagingType: "full_numbers",
            destroy: true,
            order: [],
            columnDefs: [
			{
				targets: '_all',
				orderable: false
			}],
            language: {
                paginate: {
                    previous: "<i class='uil uil-angle-left'>",
                    next: "<i class='uil uil-angle-right'>"
                }
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
            }
        });
    });

    function filter_data() {
        $('#reset_data').show();
    }

    function reset_data() {
        $('#reset_data').hide();
    }
</script> -->
