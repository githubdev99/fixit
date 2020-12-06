<script>
   $(document).ready(function () {
        load_table({
            status_transaksi: 'all'
            
        });
    

        $('#filter_data').click(function() {
            $('#clear_filter').show();
            refresh_table();
        });

        $('#clear_filter').click(function() {
            $('#clear_filter').hide();
            $('#filter_tgl_transaksi').val('');
            
            refresh_table();
        });

        $('form[name="update_resi"]').submit(function (e) {
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
                        
                        $('#IsiNomorResi [name="get_nomor_resi"]').attr('readonly');

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
                            $('button[name="'+active_element.val()+'"]').html('Update');

                            $('#IsiNomorResi [name="get_nomor_resi"]').removeAttr('readonly');
                            $('#IsiNomorResi [name="get_nomor_resi"]').val('');
                            $('#IsiNomorResi [name="get_id_data_order"]').val('');
                            $('#IsiNomorResi [name="get_id_data"]').val('');
                            $('#IsiNomorResi [name="invoice"]').val('');
                            $('#IsiNomorResi').modal('hide');

                            refresh_table();
                        }, 600);
                    }
                }
            });
        });

        $('form[name="tolak_pesanan"]').submit(function (e) {
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
                        
                        $('#ModalTolak [name="alasan_tolak"]').attr('readonly');

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
                            $('button[name="'+active_element.val()+'"]').html('Tolak');

                            $('#ModalTolak [name="alasan_tolak"]').removeAttr('readonly');
                            $('#ModalTolak [name="alasan_tolak"]').val('');
                            $('#ModalTolak [name="get_id_data_inv"]').val('');
                            $('#ModalTolak').modal('hide');

                            refresh_table();
                        }, 600);
                    }
                }
            });
        });
    });

    


    function load_table(params) {
        $('.loading-pesanan').show();
        $('.after-loading-pesanan').hide();
        setTimeout(() => {
            $('#datatable1').DataTable({
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
                    complete: function () {
                        $('.after-loading-pesanan').show();
                        $('.loading-pesanan').hide();
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
    }

    function refresh_table() {
        $('.status_load').each(function () {
            if ($(this).hasClass('active')) {
                load_table({
                    status_transaksi: $(this).data('load'),
                    tgl_transaksi: $('#filter_tgl_transaksi').val()
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





        // function TolakPesanan(id_data){
        //     $.ajax({
        //         type: "POST",
        //         method: "POST",
        //         url: "<?= base_url() ?>json/get_pesanan",
        //         data: {id_data: id_data},
        //         success: function(response){
        //             var data = JSON.parse(response);
        //             if(data.error == false){
        //                 $('input[name="status_pembatalan"]').val(data.status_pembatalan);
        //                 $('input[name="alasan_tolak"]').val(data.alasan_tolak);
        //                 $('input[name="terima_pesanan"]').val(data.terima_pesanan);
        //                 $('input[name="status_pesanan"]').val(data.status_pesanan);
        //                 $('input[name="status_transaksi"]').val(data.status_transaksi);
        //                 $('input[name="id_data"]').val(data.id_data);
        //                 $('input[name="invoice"]').val(data.invoice);
        //                 document.getElementById('invoice').innerHTML = data.invoice;
        //                 $('#ModalTolak').modal('show');
        //             }else{
        //                 swal('Gagal', 'Mohon maaf terjadi kesalahan', 'error');
        //             }
        //         },
        //         error: function(){ 

        //         }
        //     });
        // }

        


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
                    }  
                } 
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

    function TolakPesanan(id_data){       
        $.ajax({
            type: "POST",
            method: "POST",
            url: "<?= base_url() ?>json/get_pesanan",
            data: {
                id_data: id_data
            },
            success: function(output) {
                var data = JSON.parse(output);
                if(data.error == false){                 
                    $('#alasan_tolak').val(data.data.alasan_tolak);
                    $('#get_id_data_inv').val(data.data.id_data);
                    $('[name="get_id_data_inv"]').val(id_data);
                    $('#ModalTolak').modal('show');

                }else{
                    swal('Gagal', 'Mohon maaf terjadi kesalahan', 'error');
                }
            }
        });
    }

    
    function IsiNomorResi(id_data){       
        $.ajax({
            type: "POST",
            method: "POST",
            url: "<?= base_url() ?>json/get_pesanan",
            data: {
                id_data: id_data
            },
            success: function(output) {
                var data = JSON.parse(output);
                if(data.error == false){                 
                    $('#get_nomor_resi').val(data.data.nomor_resi);
                    $('#get_id_data').val(data.data.id_data);
                    $('[name="get_id_data_order"]').val(id_data);
                    $('#invoice').val(data.data.invoice);
                    $('#get_invoice').html(data.data.invoice);
                    $('#get_biaya_ongkir').html(data.data.biaya_ongkir);
                    $('#get_pengiriman').html(data.data.pengiriman);
                    $('#IsiNomorResi').modal('show');

                }else{
                    swal('Gagal', 'Mohon maaf terjadi kesalahan', 'error');
                }
            }
        });
    }

    function track(invoice) {
        var href = '<?= base_url() ?>penjualan/pesanan/track/'+invoice;

		$.ajax({
			type: "get",
			url: href,
			dataType: "html",
			success: function (response) {
				$('#list-track').html(response);
			}
		});
    }

    function Cetak(invoice) {
        var href = '<?= base_url() ?>penjualan/pesanan/track/'+invoice;

		$.ajax({
			type: "get",
			url: href,
			dataType: "html",
			success: function (response) {
				$('#list-track').html(response);
			}
		});
    }

    function read($invoice) {		
		$.ajax({
            type: "get",
			url: '<?= base_url() ?>penjualan/pesanan/read/'+invoice,
			success: function () {				
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
