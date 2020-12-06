<script>
    $(document).ready(function () {
        load_table('all');

        $('form[name="form_respon"]').submit(function (e) {
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
                        
                        $('#ModalRespon [name="respon"]').attr('readonly');

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

                            $('#ModalRespon [name="respon"]').removeAttr('readonly');
                            $('#ModalRespon [name="respon"]').val('');
                            $('#ModalRespon [name="rating_id"]').val('');
                            $('#ModalRespon').modal('hide');

                            refresh_table();
                        }, 600);
                    }
                }
            });
        });
    });

    function load_table() {
        $('#datatable1').DataTable({
            processing: true,
			serverSide: true,
            pagingType: "full_numbers",
            destroy: true,
            order: [],
            columnDefs: [
			{
				targets: 3,
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
				url: "<?= base_url() ?>json/list_detail_rating",
				type: "POST",
               
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

        $('#datatable11').DataTable({
            processing: true,
			serverSide: true,
            pagingType: "full_numbers",
            destroy: true,
            order: [],
            columnDefs: [
			{
				targets: 1,
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
				url: "<?= base_url() ?>json/list_detail_rating1",
				type: "POST",
                data:{
                    id_produk: <?= $get_data->id_produk ?>
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

    function filter_data() {
        $('#reset_data').show();
    }

    function reset_data() {
        $('#reset_data').hide();
    }

    function refresh_table() {

        load_table('all');
    }

    
</script>

<script>

function Respon(rating_id){       
        $.ajax({
            type: "POST",
            method: "POST",
            url: "<?= base_url() ?>json/get_respon",
            data: {
                rating_id: rating_id
            },
            success: function(output) {
                var data = JSON.parse(output);
                if(data.error == false){                 
                    $('#comment').val(data.data.comment);
                    $('#respon').val(data.data.respon);
                    $('#rating_id').val(data.data.rating_id);
                    $('[name="rating_id"]').val(rating_id);
                    $('#ModalRespon').modal('show');

                }else{
                    swal('Gagal', 'Mohon maaf terjadi kesalahan', 'error');
                }
            }
        });
    }

function myFunction(id_data) {
//   document.getElementById("demo").innerHTML = "Hello World";
        $.ajax({
            type: "POST",
            method: "POST",
            url: "<?= base_url() ?>review/rating/update_respon"+id_data,

            data: {
                id_data: id_data
            },
			success: function(output) {
                var data = JSON.parse(output);
                if(data.error == false){                 
         
                 
                }else{
                    swal('Gagal', 'Mohon maaf terjadi kesalahan', 'error');
                }
            }
		});
}
</script>