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
				url: "<?= base_url() ?>json/list_produk_flash",
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
       
      e.preventDefault();
   });   
});

</script>




