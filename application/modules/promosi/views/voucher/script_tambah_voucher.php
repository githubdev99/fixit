<script>
    $(document).ready(function () {
        $.ajax({
            url: '<?= base_url() ?>json/option_kategori_produk',
            type: 'POST',
            dataType: 'json',
            success: function (response) {
                var data = response.data;

                if (response.error == false) {
                    $('select[name="id_kategori"]').html(data.html);
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

        $('#kategori').change(function (e) {
			e.preventDefault();

			if ($(this).val()) {
                $.ajax({
                    url: '<?= base_url() ?>json/option_sub_kategori_produk',
                    type: 'POST',
                    data: {id_parent: $(this).val()},
                    dataType: 'json',
                    success: function (response) {
                        var data = response.data;

                        if (response.error == false) {
                            $('#subKategori').show();
                            $('#sub_kategori').html(data.html);
                            $('#sub_kategori').attr('required', 'true');
                        } else {
                            if (response.type == 'warning') {
                                $('#subKategori').hide();
                                $('#sub_kategori').html('<option></option>');
                                $('#sub_kategori').val(null).trigger('change');
                                $('#sub_kategori').removeAttr('required');
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
                });
            }
        });

        $('form[name="voucher"]').submit(function (e) {
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
    });

    function show_target_voucher(value) {
        if (value == 'yes') {
            $('#tipe_promo_choice').show();
            $('[name="id_kategori"]').attr('required');
        } else {
            $('#tipe_promo_choice').hide();
            $('[name="id_kategori"]').removeAttr('required');
            $('[name="id_kategori"]').val(null).trigger('change');
            
            $('#subKategori').hide();
            $('#sub_kategori').html('<option></option>');
            $('#sub_kategori').val(null).trigger('change');
            $('#sub_kategori').removeAttr('required');
        }
    }

    function show_tipe_diskon(value) {
        if (value == 'nominal') {
            $('#dalam_rupiah').show();
            $('[name="nominal_diskon"]').attr('required', '');

            $('#dalam_persentase').hide();
            $('[name="persentase_diskon"]').removeAttr('required');
        } else {
            $('#dalam_rupiah').hide();
            $('[name="nominal_diskon"]').removeAttr('required');

            $('#dalam_persentase').show();
            $('[name="persentase_diskon"]').attr('required', '');
        }
    }

    function Croppie(id) {
	    $('#LayoutCroppie').croppie("destroy");
	    var DataFoto = document.getElementById('outputModal').src;
	    var id = document.getElementById('DataID').value;
	    var basic = $('#LayoutCroppie').croppie({
	  	    viewport: {
                width: 600,
	  		    height: 300
            },
            enableOrientation: true,
            enforceBoundary: false,
            enableExif: true
        });
        
	    basic.croppie('bind', {
	  	    url: DataFoto
	    }).then(function(){
            $('.cr-slider').attr({
                min: 0.2,
                max: 3
            });
        });
	    $('.crop_image').click(function(event){
            var id = document.getElementById('DataID').value;
            basic.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function(response){
                document.getElementById('outputModal').src = response;
                document.getElementById('output_'+id).src = response;
                document.getElementById('FileProductFix_'+id).value = response;
                $('#ModalCroping').modal('hide');
            })
	    });
    }

    var loadFile = function(event,id,value) {
        $('#plus_'+id).hide();
		$('#LayoutCroppie').croppie("destroy");
		$('#BottomProduk_'+id).hide();
		var reader = new FileReader();
		reader.onload = function(){
			var output = document.getElementById('output_'+id);
			output.src = reader.result;
			document.getElementById('FileProductFix_'+id).value = reader.result;
            $('#FileProduct_'+id).attr('disabled', 'true');
			$('#output_'+id).css('display','block');
			$('#BottomProduk_'+id).show();
			document.getElementById('CekFoto_'+id).value = 'Y';
		};
		reader.readAsDataURL(event.target.files[0]);
    };

    function ShowCroping(id) {
		var DataFoto = document.getElementById('output_'+id).src;
		var OutputModal = document.getElementById('outputModal');
		var DataID = document.getElementById('DataID');
		OutputModal.src = DataFoto;
		DataID.value = id;
    }
    
    function RemovePhoto(id) {
		$('#output_'+id).attr('src', null);
		$('#BottomProduk_'+id).hide();
		document.getElementById('FileProduct_'+id).value = '';
		document.getElementById('FileProductFix_'+id).value = '';
		document.getElementById('CekFoto_'+id).value = 'T';
        $('#output_'+id).css('display','none');

        $('#plus_'+id).show();
        $('#FileProduct_'+id).removeAttr('disabled');
	}
</script>