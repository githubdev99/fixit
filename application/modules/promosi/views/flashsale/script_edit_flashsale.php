<script>
 $(document).ready(function () {
        load_table({
            status_produk: 'all',
            id_data: '<?= $get_data->id_data ?>'
        });
    });

    function load_table(params) {
        $('#datatable1').DataTable({
            processing: true,
			serverSide: true,
            pagingType: "full_numbers",
            destroy: true,
            order: [],
            columnDefs: [
			{
				targets: 5,
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
				url: "<?= base_url() ?>json/list_flashsale1",
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

 

 
</script>

<script>
   $(document).ready(function () {
        $.ajax({
            url: '<?= base_url() ?>json/option_produk',
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                var data = response.data;

                if (response.error == false) {
                    $('select[name="id_produk"]').html(data.html);
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
    });



   $('form[name="flashsale"]').submit(function (e) {
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
                        Swal.mixin({
                            toast: true,
                            position: "top",
                            showCloseButton: !0,
                            showConfirmButton: false,
                            timer: 1500
                        }).fire({
                            icon: response.type,
                            title: response.message
                        }).then(function() {
                            window.location = response.callback;
                        });

                        $('button[name="'+active_element.val()+'"]').addClass('disabled');
                        $('button[name="'+active_element.val()+'"]').html('<i class="fas fa-circle-notch fa-spin"></i>');
                    }
                }
            });
        });


    function modal(param) {
        if (param == 'tambah') {
            $('#tambah_produk').modal({
                    backdrop: 'static',
                    keyboard: true,
                    show: true
                });
        } 
    }
    

</script>




<script>
		function check_product(id_produk) {
			if ($('#tanda_perproduk_'+id_produk).is(':checked')) {
				var status_pilih = 'Y';
			} else {
				var status_pilih = 'T';
			}

			$.ajax({
				url: '<?= base_url() ?>promosi/flashsale/insert_produk_pilih',
				type: 'POST',
				dataType: 'json',
				data: {
					id : id_produk,
					status_pilih: status_pilih,
                    id_data: '<?= $get_data->id_data ?>'
				},
				success: function (data) {					
                    $('#getFlashsale').html(data.html);

                        //  var button_flashsale = '<a href="<?= base_url() ?>promosi/flashsale/produk_ditambahkan" class="btn btn-info  type="submit" name="flashsale_button" id="flashsale_button"></i> Konfirmasi</a>'

                        //  var button_flashsale = ' <button type="submit" class="btn btn-info btn-lg flashsale_button" name="flashsale_button" value="flashsale_button">Konfirmasi</button>'
				
                        // $('#konfirmasi').html(''+button_flashsale+'');
				}
			});
		}


     $(document).ready(function(){
        $("#flashsale_button").click(function(){
            var id = $("#id_produk").val();      
            var dataString = 'id_produk='+ id;
                $.ajax({
                    type: "POST",
                    url: '<?= base_url() ?>promosi/flashsale/insert_produk_pilih',
                    data: dataString,
                    cache: false,
                    success: function(result){
                        $('#getFlashsale').html(data.html);
                    }
                });            
            return false;
        });
    });

</script>


<script>

    $(document).ready(function() {
			function get_flash() {
				$.ajax({
					url: '<?= base_url() ?>promosi/flashsale/get_flash',
					type: 'POST',
					dataType: 'json',
					success: function (data) {
                        $('#getFlashsale').html(data.html);

                         var button_flashsale = '<a href="<?= base_url('promosi/flashsale/produk_flashsale'); ?>" id="prdFlashsale" class="btn btn-info flashsale_button"></i> Konfirmasi</a>';

                         
				
                        $('#konfirmasi').html(''+
							'<div class="Layout BlurOriginal Buled text-right" style="margin-top:2%;">'+
                              
								'<div class="LayoutContent">'+button_flashsale+
								'</div>'+
							'</div>'+
						'');

                        // $('#jumlahp').html(' Produk Dipilih : <span id="count_produk_pilih">'+data.count_produk+'</span> Produk ');
                        $('#count_produk_pilih').text(data.count_produk);
					}
				});
			}

			$(document).on('click', '.flashsale_button', function(e) {
				e.preventDefault();
				if ($(this).data('count-produk') == 0) {
					swal({
						title: 'Ayo!',
						text: 'Pilih Produk sebelum ke Flashsale',
						icon: 'warning',
						timer: 2000,
						showCancelButton: false,
						buttons: false,
						showConfirmButton: false
					});
				}

       
			});
			get_flash();

	});
</script>

<script>
    function confirm_data(id_flashsale,submit) {
        $.ajax({
            url: '<?= base_url() ?>json/get_produk_flashsale',
            type: 'POST',
            data: {
                id_flashsale: id_flashsale,
                // id_data: id_data
            },
            dataType: 'json',
            success: function (response) {
                var data = response.data;
                if (response.error == false) {
                    if (submit == 'delete') {
                        Swal.fire({
                            title: 'Konfirmasi!',
                            html: "Anda yakin ingin menghapus ?",
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
                                    url: '<?= base_url() ?>promosi/flashsale/produk_flashsale',
                                    type: 'POST',
                                    data: {
                                        submit: submit,
                                        id_flashsale: id_flashsale,
                                        // id_data: id_data,
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


    function ShowModalEdit(id_flashsale){
            $.ajax({
                type: "POST",
                method: "POST",
                url : '<?= base_url() ?>json/get_produk_flashsale',
                data: {id_flashsale: id_flashsale},
                success: function(response) {
                    if(response.error == false){
                        // $('input[name="id_flashsale"]').val(data.id_flashsale);
                        // $('input[name="id_data"]').val(data.id_data);
                        // $('input[name="diskon"]').val(data.diskon);
                        // $('input[name="jumlah_flashsale"]').val(data.jumlah_flashsale);

                        $('#edit_produk').modal('show');
                        
                    }else{
                        swal('Gagal', 'Mohon maaf terjadi kesalahan', 'error');
                    }
                }
            });
        }

    $('form[name="edit_produk_flash"]').submit(function (e) {
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
                        Swal.mixin({
                            toast: true,
                            position: "top",
                            showCloseButton: !0,
                            showConfirmButton: false,
                            timer: 1500
                        }).fire({
                            icon: response.type,
                            title: response.message
                        }).then(function() {
                            window.location = response.callback;
                        });

                        $('button[name="'+active_element.val()+'"]').addClass('disabled');
                        $('button[name="'+active_element.val()+'"]').html('<i class="fas fa-circle-notch fa-spin"></i>');
                    }
                }
            });
        });




    // function edit_data(id_flashsale,submit) {
    //     $.ajax({
    //         url: '<?= base_url() ?>json/get_produk_flashsale',
    //         type: 'POST',
    //         data: {
    //             id_flashsale: id_flashsale,
    //             // id_data: id_data
    //         },
    //         dataType: 'json',
    //         success: function (response) {
    //             var data = response.data;
    //             if (response.error == false) {
    //                 if (submit == 'edit') {
    //                     Swal.fire({
    //                         html: "<br/><form method='post' id='edit_produk-update' name='edit_produk'><label class='col-lg-1 col-form-label' > </label><label class='col-form-label' >Diskon<span class='text-danger'>*</span></label><div class='col-lg-3'><div class='input-group'><input type='text' name='diskon' class='form-control' maxlength='3' onkeypress='number_only(event)' onkeyup='running_rupiah('diskon', this.value)'><div class='input-group-prepend'><div class='input-group-text'>%</div></div></div></div><label class=' col-form-label'>Stok Flashsale  <span class='text-danger'>*</span></label><div class='col-lg-3'><input type='text' name='jumlah_flashsale' class='form-control' onkeypress='number_only(event)' ></div></form>",
    //                         showCloseButton: true,
    //                         showCancelButton: true,
    //                         confirmButtonColor: '#d33',
    //                         cancelButtonColor: '#64B0C9',
    //                         confirmButtonText: 'Hapus Produk',
    //                         cancelButtonText: 'Batal'
    //                     }).then((result) => {
    //                         if (result.value) {
    //                             $.ajax({
    //                                 url: '<?= base_url() ?>promosi/flashsale/produk_flashsale',
    //                                 type: 'POST',
    //                                 data: {
    //                                     submit: submit,
    //                                     id_flashsale: id_flashsale,
    //                                     // id_data: id_data,
    //                                     post_data: data
    //                                 },
    //                                 dataType: 'json',
    //                                 success: function (response) {
    //                                     var data = response.data;

    //                                     if (response.error == false) {
    //                                         Swal.mixin({
    //                                             toast: true,
    //                                             position: "top",
    //                                             showCloseButton: !0,
    //                                             showConfirmButton: false,
    //                                             timer: 4000,
    //                                             onOpen: (toast) => {
    //                                                 toast.addEventListener("mouseenter", Swal.stopTimer)
    //                                                 toast.addEventListener("mouseleave", Swal.resumeTimer)
    //                                             }
    //                                         }).fire({
    //                                             icon: response.type,
    //                                             title: response.message
    //                                         });

    //                                         refresh_table();
    //                                     } else {
    //                                         Swal.mixin({
    //                                             toast: true,
    //                                             position: "top",
    //                                             showCloseButton: !0,
    //                                             showConfirmButton: false,
    //                                             timer: 4000,
    //                                             onOpen: (toast) => {
    //                                                 toast.addEventListener("mouseenter", Swal.stopTimer)
    //                                                 toast.addEventListener("mouseleave", Swal.resumeTimer)
    //                                             }
    //                                         }).fire({
    //                                             icon: response.type,
    //                                             title: response.message
    //                                         });
    //                                     }
    //                                 }
    //                             });
    //                         }
    //                     });
    //                 } } 
    //             else {
    //                 Swal.mixin({
    //                     toast: true,
    //                     position: "top",
    //                     showCloseButton: !0,
    //                     showConfirmButton: false,
    //                     timer: 4000,
    //                     onOpen: (toast) => {
    //                         toast.addEventListener("mouseenter", Swal.stopTimer)
    //                         toast.addEventListener("mouseleave", Swal.resumeTimer)
    //                     }
    //                 }).fire({
    //                     icon: response.type,
    //                     title: response.message
    //                 });
    //             }
    //         }
    //     });
    // }

  
</script>


