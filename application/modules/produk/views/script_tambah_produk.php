<script>
    $(document).ready(function() {
        $('input[name="produk_variasi_harga"]').val('T');

        $('#kategori').change(function(e) {
            e.preventDefault();

            $.ajax({
                url: '<?= base_url() ?>json/option_sub_kategori_produk',
                type: 'POST',
                data: {
                    id_parent: $(this).val()
                },
                dataType: 'json',
                success: function(response) {
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
        });

        $('form[name="produk"]').submit(function(e) {
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
                            timer: 1500
                        }).fire({
                            icon: response.type,
                            title: response.message
                        }).then(function() {
                            window.location = response.callback;
                        });

                        $('button[name="' + active_element.val() + '"]').addClass('disabled');
                        $('button[name="' + active_element.val() + '"]').html('<i class="fas fa-circle-notch fa-spin"></i>');
                    }
                }
            });
        });

        load_all_option();
    });

    function show_pre_order_choice(value) {
        if (value == 'Y') {
            $('#pre_order_choice').show();
            $('[name="masa_pengemasan"]').attr('required', 'true');
        } else {
            $('#pre_order_choice').hide();
            $('[name="masa_pengemasan"]').val(null).trigger('change');
            $('[name="masa_pengemasan"]').removeAttr('required');
        }
    }

    function Croppie(id) {
        $('#LayoutCroppie').croppie("destroy");
        var DataFoto = document.getElementById('outputModal').src;
        var id = document.getElementById('DataID').value;
        var basic = $('#LayoutCroppie').croppie({
            viewport: {
                width: 400,
                height: 400
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
                max: 3
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
        $('#FileProduct_' + id).attr('disabled', 'true');
        $('#plus_' + id).hide();

        $('#LayoutCroppie').croppie("destroy");
        $('#BottomProduk_' + id).hide();
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('output_' + id);
            output.src = reader.result;
            document.getElementById('FileProductFix_' + id).value = reader.result;

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
        document.getElementById('FileProduct_' + id).value = '';
        document.getElementById('FileProductFix_' + id).value = '';
        document.getElementById('CekFoto_' + id).value = 'T';
        $('#output_' + id).css('display', 'none');

        $('#plus_' + id).show();
        $('#FileProduct_' + id).removeAttr('disabled');
    }

    function variasi_config(config) {
        if (config == true) {
            $('#harga_normal').animate({
                height: 'toggle'
            });

            $('#variasi').slideDown('400');
            $('input[name="produk_variasi_harga"]').val('Y');
            $('input[id="counter_variasi"]').val(1);

            $('input[name="kode_sku_single"]').removeAttr('required');
            $('input[name="harga_single"]').removeAttr('required');
            $('input[name="stok_single"]').removeAttr('required');
        } else {
            $('#harga_normal').animate({
                height: 'toggle'
            });

            $('#variasi').slideUp('400');
            $('input[name="produk_variasi_harga"]').val('T');

            $('input[name="kode_sku_single"]').attr('required', 'true');
            $('input[name="harga_single"]').attr('required', 'true');
            $('input[name="stok_single"]').attr('required', 'true');
        }
    }

    function copying_variasi(param, value) {
        $('#read_nama_' + param).html(value);
    }

    function show_model(param, value) {
        $('#daftar_model_' + param + ' select').html('<option></option>');
        $('#read_nama_' + param).html('');
        if (value != 'model') {
            $('#daftar_model_' + param).show();
            $('#daftar_model_' + param + ' select').attr('name', 'nama[]');
            $('#not_daftar_model_' + param).hide();
            $('#not_daftar_model_' + param + ' input').removeAttr('name');

            select2_ajax({
                selector: '#daftar_model_' + param + ' select',
                url: '<?= base_url() ?>json/option_daftar_model',
                data: {
                    jenis: value
                },
                normal: true
            });
        } else {
            $('#daftar_model_' + param).hide();
            $('#daftar_model_' + param + ' select').removeAttr('name');
            $('#not_daftar_model_' + param).show();
            $('#not_daftar_model_' + param + ' input').attr('name', 'nama[]');
        }
    }

    function add_variasi() {
        var counter = parseInt($('input[id="counter_variasi"]').val());
        counter++;

        $('#col_variasi').append('' +
            '<div class="col-12 mt-4" id="form_variasi_' + counter + '">' +
            '<div class="form-group row">' +
            '<label class="col-lg-2 col-form-label">Tipe Variasi</label>' +
            '<div class="col-lg-10">' +
            '<select class="form-control select2" name="pilihan[]" data-placeholder="Pilih Salah Satu" onchange="show_model(' + counter + ', this.value);">' +
            '<option></option>' +
            '<option value="model">Model</option>' +
            '<option value="warna">Warna</option>' +
            '<option value="ukuran">Ukuran</option>' +
            '</select>' +
            '</div>' +
            '</div>' +
            '<div class="form-group row">' +
            '<label class="col-lg-2 col-form-label">Nama Variasi</label>' +
            '<div class="col-lg-10">' +
            '<div id="daftar_model_' + counter + '" style="display: none;">' +
            '<select class="form-control select2" data-placeholder="Pilih Salah Satu" onchange="copying_variasi(' + counter + ', this.value);">' +
            '</select>' +
            '</div>' +
            '<div id="not_daftar_model_' + counter + '">' +
            '<input type="text" name="nama[]" id="text_nama_' + counter + '" class="form-control" placeholder="Masukkan nama variasi sesuai tipe, Contoh: Biru" onkeyup="copying_variasi(' + counter + ', this.value);">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '');

        $('#table_variasi').append('' +
            '<tr id="tr_variasi_' + counter + '">' +
            '<td style="border: 1px solid;" id="read_nama_' + counter + '"></td>' +
            '<td style="border: 1px solid;">' +
            '<div class="input-group">' +
            '<div class="input-group-prepend">' +
            '<div class="input-group-text">Rp.</div>' +
            '</div>' +
            '<input type="text" name="harga[]" class="form-control" placeholder="0" onkeypress="number_only(event)" onkeyup="running_rupiah_array(' + "'#tr_variasi_" + counter + "'" + ', ' + "'harga[]'" + ', this.value)">' +
            '</div>' +
            '</td>' +
            '<td style="border: 1px solid;">' +
            '<input type="text" name="stok[]" class="form-control" placeholder="0" onkeypress="number_only(event)">' +
            '</td>' +
            '<td style="border: 1px solid;">' +
            '<input type="text" name="sku[]" class="form-control" placeholder="SKU">' +
            '</td>' +
            '</tr>' +
            '');

        $('input[id="counter_variasi"]').val(counter);
        $('#remove_button').show();

        $('.select2').each(function() {
            $(this).select2();
        });
    }

    function remove_variasi() {
        var counter = parseInt($('input[id="counter_variasi"]').val());

        $('#form_variasi_' + counter).remove();
        $('#tr_variasi_' + counter).remove();

        counter--;

        $('input[id="counter_variasi"]').val(counter);

        if (counter == 1) {
            $('#remove_button').hide();
        }
    }

    function load_all_option() {
        select2_ajax({
            selector: 'select[name="id_kategori"]',
            url: '<?= base_url() ?>json/option_kategori_produk',
            normal: true
        });

        select2_ajax({
            selector: 'select[name="merek"]',
            url: '<?= base_url() ?>json/option_brand'
        });

        select2_ajax({
            selector: 'select[name="asal_produk"]',
            url: '<?= base_url() ?>json/option_asal_produk'
        });
    }
</script>