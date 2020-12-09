<script>
    $(document).ready(function() {
        $('form[name="login"]').submit(function(e) {
            e.preventDefault();

            var active_element = $(document.activeElement);

            $('button[name="' + active_element.val() + '"]').attr('disabled', 'true');
            $('button[name="' + active_element.val() + '"]').html('<i class="fas fa-circle-notch fa-spin"></i>');

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

                        $('button[name="' + active_element.val() + '"]').removeAttr('disabled');
                        $('button[name="' + active_element.val() + '"]').html('Login');
                    } else {
                        Swal.mixin({
                            toast: true,
                            position: "top",
                            showCloseButton: !0,
                            showConfirmButton: false,
                            timer: 2000,
                            onOpen: (toast) => {
                                toast.addEventListener("mouseenter", Swal.stopTimer)
                                toast.addEventListener("mouseleave", Swal.resumeTimer)
                            }
                        }).fire({
                            icon: response.type,
                            title: response.message
                        }).then(function() {
                            window.location = response.callback;
                        });
                    }
                }
            });
        });
    });
</script>