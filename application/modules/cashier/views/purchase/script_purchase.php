<script>
    $(document).ready(function() {
        load_table();
    });

    function load_table(params) {
        $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            pagingType: "full_numbers",
            destroy: true,
            order: [],
            columnDefs: [{
                    targets: [0, 6],
                    orderable: false
                },
                {
                    targets: 6,
                    createdCell: function(td, cellData, rowData, row, col) {
                        $(td).attr({
                            style: 'white-space: nowrap;'
                        });
                    }
                }
            ],
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
                    type: "image",
                    closeOnContentClick: !0,
                    closeBtnInside: !1,
                    fixedContentPos: !0,
                    mainClass: "mfp-no-margins mfp-with-zoom",
                    image: {
                        verticalFit: !0
                    },
                    zoom: {
                        enabled: !0,
                        duration: 300
                    }
                });
            },
            ajax: {
                url: "<?= $core['url_api'] ?>datatable/purchase",
                type: "POST",
                dataType: "json",
                error: function() {
                    show_alert();
                }
            }
        });
    }

    function show_modal(params) {
        if (params) {
            if (params.id) {
                $.ajax({
                    type: 'get',
                    url: '<?= $core['url_api'] ?>purchase/detail?from_parent=' + params.id,
                    dataType: 'json',
                    success: function(response) {
                        var data = response.data;

                        if (params.modal == 'detail') {
                            $('#detail [name="id"]').val(data.id);
                            $('#detail [name="in_active"]').val(data.in_active);
                            $('#detail [name="name"]').val(data.name);

                            $('#detail').modal({
                                backdrop: 'static',
                                keyboard: true,
                                show: true
                            });
                        }
                    },
                    error: function() {
                        show_alert();
                    }
                });
            } else {
                show_alert();
            }
        } else {
            show_alert();
        }
    }
</script>