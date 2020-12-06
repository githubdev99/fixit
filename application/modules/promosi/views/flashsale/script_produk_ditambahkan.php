<script>
 $(document).ready(function () {
        load_table({
            status_produk: 'all'
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
        } else {
            $('#tambah_rekening').modal({
                backdrop: 'static',
                keyboard: true,
                show: true
            });

            $('#tambah_produk').modal('hide');
        }
    }


</script>


<script>
    $(document).ready(function() {
   var table = $('#example').DataTable({
      'ajax': '<?= base_url() ?>json/get_produk1',
      'columnDefs': [
         {
            'targets': 0,
            'checkboxes': {
               'selectRow': true
            }
         }
      ],
      'select': {
         'style': 'multi'
      },
      'order': [[1, 'asc']]
   });
   
   $('#frm-example').on('submit', function(e){
      var form = this;
      
      var rows_selected = table.column(0).checkboxes.selected();     

      $.each(rows_selected, function(index, rowId){

         $(form).append(
             $('<input>')
                .attr('type', 'hidden')
                .attr('name', 'id[]')
                .val(rowId)
         );
      });

      $('#example-console-rows').text(rows_selected.join(","));
      
      $('#example-console-form').text($(form).serialize());
       
      $('input[name="id\[\]"]', form).remove();




   });   
});

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
					status_pilih: status_pilih
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

                         var button_flashsale = '<a href="<?= base_url('promosi/flashsale/produk_ditambahkan'); ?>" id="prdFlashsale" class="btn btn-info flashsale_button"></i> Konfirmasi</a>';

                         
				
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
     function confirm_data(id_flashsale, submit) {
        $.ajax({
            url: '<?= base_url() ?>json/get_produk_flashsale',
            type: 'POST',
            data: {
                id_flashsale: id_flashsale
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
                                    url: '<?= base_url() ?>promosi/flashsale/produk_ditambahkan',
                                    type: 'POST',
                                    data: {
                                        submit: submit,
                                        id_flashsale: id_flashsale,
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




