<script>
    $(document).ready(function() {
        load_kurir();

        select2_ajax({
            selector: 'select[name="provinsi"]',
            url: '<?= base_url() ?>json/option_provinsi'
        });

        <?php if (!empty($core['seller']->provinsi)) : ?>
            $('select[name="provinsi"]').html(new Option('<?= $core['seller']->nama_provinsi ?>', '<?= encrypt_text($core['seller']->provinsi) ?>', false, false)).trigger('change');
        <?php endif ?>

        <?php if (!empty($core['seller']->kota_kabupaten)) : ?>
            select2_ajax({
                selector: 'select[name="kota_kabupaten"]',
                url: '<?= base_url() ?>json/option_kota',
                data: {
                    province_id: '<?= encrypt_text($core['seller']->provinsi) ?>'
                }
            });

            $('select[name="kota_kabupaten"]').html(new Option('<?= $core['seller']->nama_kota ?>', '<?= encrypt_text($core['seller']->kota_kabupaten) ?>', false, false)).trigger('change');
        <?php endif ?>

        <?php if (!empty($core['seller']->kecamatan)) : ?>
            select2_ajax({
                selector: 'select[name="kecamatan"]',
                url: '<?= base_url() ?>json/option_kecamatan',
                data: {
                    city_id: '<?= encrypt_text($core['seller']->kota_kabupaten) ?>'
                }
            });

            $('select[name="kecamatan"]').html(new Option('<?= $core['seller']->nama_kecamatan ?>', '<?= encrypt_text($core['seller']->kecamatan) ?>:<?= encrypt_text($core['seller']->kecamatan_kd) ?>', false, false)).trigger('change');
        <?php endif ?>

        <?php if (!empty($core['seller']->kelurahan)) : ?>
            select2_ajax({
                selector: 'select[name="kelurahan"]',
                url: '<?= base_url() ?>json/option_kelurahan',
                data: {
                    kd_kec: '<?= encrypt_text($core['seller']->kecamatan_kd) ?>'
                }
            });

            $('select[name="kelurahan"]').html(new Option('<?= $core['seller']->nama_kelurahan ?>', '<?= encrypt_text($core['seller']->kelurahan) ?>', false, false)).trigger('change');
        <?php endif ?>

        $('select[name="provinsi"]').change(function(e) {
            e.preventDefault();

            if ($(this).val()) {
                $('select[name="kota_kabupaten"]').html(new Option('Pilih Kota/Kabupaten', '', false, false)).trigger('change');
                $('select[name="kecamatan"]').html(new Option('Pilih Kecamatan', '', false, false)).trigger('change').select2();
                $('select[name="kelurahan"]').html(new Option('Pilih Kelurahan', '', false, false)).trigger('change').select2();

                select2_ajax({
                    selector: 'select[name="kota_kabupaten"]',
                    url: '<?= base_url() ?>json/option_kota',
                    data: {
                        province_id: $(this).val()
                    }
                });
            }
        });

        $('select[name="kota_kabupaten"]').change(function(e) {
            e.preventDefault();

            if ($(this).val()) {
                $('select[name="kecamatan"]').html(new Option('Pilih Kecamatan', '', false, false)).trigger('change');
                $('select[name="kelurahan"]').html(new Option('Pilih Kelurahan', '', false, false)).trigger('change').select2();

                select2_ajax({
                    selector: 'select[name="kecamatan"]',
                    url: '<?= base_url() ?>json/option_kecamatan',
                    data: {
                        city_id: $(this).val()
                    }
                });
            }
        });

        $('select[name="kecamatan"]').change(function(e) {
            e.preventDefault();

            if ($(this).val()) {
                $('select[name="kelurahan"]').html(new Option('Pilih Kelurahan', '', false, false)).trigger('change');

                select2_ajax({
                    selector: 'select[name="kelurahan"]',
                    url: '<?= base_url() ?>json/option_kelurahan',
                    data: {
                        kd_kec: $(this).val().split(':')[1]
                    }
                });
            }
        });

        <?php if (!empty($core['seller']->foto)) : ?>
            $('#plus_1').hide();
            $('#FileProduct_1').attr('disabled', 'true');
            $('#output_1').css('display', 'block');
            $('#output_1').attr('src', '<?= $core['folder_upload_other'] ?>file/<?= $core['seller']->foto ?>');
            $('#BottomProduk_1').show();
            document.getElementById('CekFoto_1').value = 'Y';

        <?php endif ?>

        <?php if (!empty($core['seller']->banner)) : ?>
            $('#plus_2').hide();
            $('#FileProduct_2').attr('disabled', 'true');
            $('#output_2').css('display', 'block');
            $('#output_2').attr('src', '<?= $core['folder_upload_other'] ?>file/<?= $core['seller']->banner ?>');
            $('#BottomProduk_2').show();
            document.getElementById('CekFoto_2').value = 'Y';
        <?php endif ?>

        <?php if (!empty($core['seller']->banner_1)) : ?>
            $('#plus_3').hide();
            $('#FileProduct_3').attr('disabled', 'true');
            $('#output_3').css('display', 'block');
            $('#output_3').attr('src', '<?= $core['folder_upload_other'] ?>file/<?= $core['seller']->banner_1 ?>');
            $('#BottomProduk_3').show();
            document.getElementById('CekFoto_3').value = 'Y';
        <?php endif ?>

        <?php if (!empty($core['seller']->banner_2)) : ?>
            $('#plus_4').hide();
            $('#FileProduct_4').attr('disabled', 'true');
            $('#output_4').css('display', 'block');
            $('#output_4').attr('src', '<?= $core['folder_upload_other'] ?>file/<?= $core['seller']->banner_2 ?>');
            $('#BottomProduk_4').show();
            document.getElementById('CekFoto_4').value = 'Y';
        <?php endif ?>

        <?php if (!empty($core['seller']->banner_3)) : ?>
            $('#plus_5').hide();
            $('#FileProduct_5').attr('disabled', 'true');
            $('#output_5').css('display', 'block');
            $('#output_5').attr('src', '<?= $core['folder_upload_other'] ?>file/<?= $core['seller']->banner_3 ?>');
            $('#BottomProduk_5').show();
            document.getElementById('CekFoto_5').value = 'Y';
        <?php endif ?>

        <?php if (!empty($core['seller']->banner_4)) : ?>
            $('#plus_6').hide();
            $('#FileProduct_6').attr('disabled', 'true');
            $('#output_6').css('display', 'block');
            $('#output_6').attr('src', '<?= $core['folder_upload_other'] ?>file/<?= $core['seller']->banner_4 ?>');
            $('#BottomProduk_6').show();
            document.getElementById('CekFoto_6').value = 'Y';
        <?php endif ?>

        <?php if (!empty($core['seller']->banner_5)) : ?>
            $('#plus_7').hide();
            $('#FileProduct_7').attr('disabled', 'true');
            $('#output_7').css('display', 'block');
            $('#output_7').attr('src', '<?= $core['folder_upload_other'] ?>file/<?= $core['seller']->banner_5 ?>');
            $('#BottomProduk_7').show();
            document.getElementById('CekFoto_7').value = 'Y';
        <?php endif ?>


        $('form[name="profil_toko"]').submit(function(e) {
            e.preventDefault();

            var active_element = $(document.activeElement);

            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: $(this).serialize() + '&submit=' + active_element.val(),
                dataType: "json",
                success: function(response) {
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
                            timer: 2000
                        }).fire({
                            icon: response.type,
                            title: response.message
                        }).then(function() {
                            window.location = response.callback;
                        });

                        $('button[name="' + active_element.val() + '"]').attr('disabled', 'true');
                        $('button[name="' + active_element.val() + '"]').html('<i class="fas fa-circle-notch fa-spin"></i>');
                    }
                }
            });
        });

        $('form[name="banner_toko"]').submit(function(e) {
            e.preventDefault();

            var active_element = $(document.activeElement);

            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: $(this).serialize() + '&submit=' + active_element.val(),
                dataType: "json",
                success: function(response) {
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
                            timer: 2000
                        }).fire({
                            icon: response.type,
                            title: response.message
                        }).then(function() {
                            window.location = response.callback;
                        });

                        $('button[name="' + active_element.val() + '"]').attr('disabled', 'true');
                        $('button[name="' + active_element.val() + '"]').html('<i class="fas fa-circle-notch fa-spin"></i>');
                    }
                }
            });
        });

        $('form[name="ubah_alamat"]').submit(function(e) {
            e.preventDefault();

            var active_element = $(document.activeElement);

            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data: $(this).serialize() + '&submit=' + active_element.val(),
                dataType: "json",
                success: function(response) {
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
                            timer: 2000
                        }).fire({
                            icon: response.type,
                            title: response.message
                        }).then(function() {
                            window.location = response.callback;
                        });

                        $('button[name="' + active_element.val() + '"]').attr('disabled', 'true');
                        $('button[name="' + active_element.val() + '"]').html('<i class="fas fa-circle-notch fa-spin"></i>');
                    }
                }
            });
        });
    });

    function load_kurir() {
        $.ajax({
            type: "post",
            url: "<?= base_url() ?>pengaturan/toko/get_kurir",
            dataType: "json",
            success: function(data) {
                $('#kurir_toko').html(data.html);
                $('#kurir').val(data.pilihan_kurir);
            }
        });
    }

    function check_kurir(kurir, id_data) {
        var array = $('#kurir').val().split(':');

        if ($('#pilih_kurir' + id_data).is(':checked')) {
            array.push(kurir);
        } else {
            array = $.grep(array, function(value) {
                return value != kurir;
            });
        }

        $.ajax({
            type: "post",
            url: "<?= base_url() ?>pengaturan/toko/update_kurir",
            data: {
                pilihan_kurir: array.join(':')
            },
            success: function() {
                load_kurir();
            }
        });
    }

    function Croppie(id) {
        $('#LayoutCroppie').croppie("destroy");
        var DataFoto = document.getElementById('outputModal').src;
        var id = document.getElementById('DataID').value;
        var basic = $('#LayoutCroppie').croppie({
            viewport: {
                width: 462,
                height: 462
            },
            enableOrientation: true,
            enforceBoundary: false,
            enableExif: true
        });

        basic.croppie('bind', {
            url: DataFoto
        }).then(function() {
            $('.cr-slider').attr({
                min: 0.2,
                max: 7
            });
        });
        $('.crop_image').click(function(event) {
            var id = document.getElementById('DataID').value;
            basic.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function(response) {
                document.getElementById('outputModal').src = response;
                document.getElementById('output_' + id).src = response;
                document.getElementById('FileProductFix_' + id).value = response;
                $('#ModalCroping').modal('hide');
            })
        });
    }

    var loadFile = function(event, id, value) {
        $('#plus_' + id).hide();
        $('#LayoutCroppie').croppie("destroy");
        $('#BottomProduk_' + id).hide();
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('output_' + id);
            output.src = reader.result;
            document.getElementById('FileProductFix_' + id).value = reader.result;
            $('#FileProduct_' + id).attr('disabled', 'true');
            $('#output_' + id).css('display', 'block');
            $('#BottomProduk_' + id).show();
            document.getElementById('CekFoto_' + id).value = 'Y';
        };
        reader.readAsDataURL(event.target.files[0]);
    };

    function ShowCroping(id) {
        var DataFoto = document.getElementById('output_' + id).src;
        var OutputModal = document.getElementById('outputModal');
        var DataID = document.getElementById('DataID');
        OutputModal.src = DataFoto;
        DataID.value = id;
    }

    function RemovePhoto(id) {
        $('#output_' + id).attr('src', null);
        $('#BottomProduk_' + id).hide();
        $('input[name="foto_old"]').val('');
        document.getElementById('FileProduct_' + id).value = '';
        document.getElementById('FileProductFix_' + id).value = '';
        document.getElementById('CekFoto_' + id).value = 'T';
        $('#output_' + id).css('display', 'none');

        $('#plus_' + id).show();
        $('#FileProduct_' + id).removeAttr('disabled');
    }

    function RemovePhotoBanner(id, old) {
        $('#output_' + id).attr('src', null);
        $('#BottomProduk_' + id).hide();
        $('input[name="' + old + '"]').val('');
        document.getElementById('FileProduct_' + id).value = '';
        document.getElementById('FileProductFix_' + id).value = '';
        document.getElementById('CekFoto_' + id).value = 'T';
        $('#output_' + id).css('display', 'none');

        $('#plus_' + id).show();
        $('#FileProduct_' + id).removeAttr('disabled');
    }

    function Croppie1(id) {
        $('#LayoutCroppie1').croppie("destroy");
        var DataFoto = document.getElementById('outputModal').src;
        var id = document.getElementById('DataID').value;
        var basic = $('#LayoutCroppie1').croppie({
            viewport: {
                width: 1110,
                height: 260
            },
            enableOrientation: true,
            enforceBoundary: false,
            enableExif: true
        });

        basic.croppie('bind', {
            url: DataFoto
        }).then(function() {
            $('.cr-slider').attr({
                min: 0.2,
                max: 7
            });
        });
        $('.crop_image').click(function(event) {
            var id = document.getElementById('DataID').value;
            basic.croppie('result', {
                type: 'canvas',
                size: 'viewport'
            }).then(function(response) {
                document.getElementById('outputModal').src = response;
                document.getElementById('output_' + id).src = response;
                document.getElementById('FileProductFix_' + id).value = response;
                $('#ModalCroping1').modal('hide');
            })
        });
    }

    function ShowCroping1(id) {
        var DataFoto = document.getElementById('output_' + id).src;
        var OutputModal = document.getElementById('outputModal');
        var DataID = document.getElementById('DataID');
        OutputModal.src = DataFoto;
        DataID.value = id;
    }

    var loadFile1 = function(event, id, value) {
        $('#plus_' + id).hide();
        $('#LayoutCroppie1').croppie("destroy");
        $('#BottomProduk_' + id).hide();
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('output_' + id);
            output.src = reader.result;
            document.getElementById('FileProductFix_' + id).value = reader.result;
            $('#FileProduct_' + id).attr('disabled', 'true');
            $('#output_' + id).css('display', 'block');
            $('#BottomProduk_' + id).show();
            document.getElementById('CekFoto_' + id).value = 'Y';
        };
        reader.readAsDataURL(event.target.files[0]);
    };
</script>