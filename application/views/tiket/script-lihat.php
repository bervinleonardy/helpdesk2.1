    <!-- jQuery  -->
    <script src="<?= base_url('assets/annexadmin'); ?>/js/jquery.min.js"></script>
    <script src="<?= base_url('assets/annexadmin'); ?>/js/popper.min.js"></script>
    <script src="<?= base_url('assets/annexadmin'); ?>/js/bootstrap.min.js"></script>
    <script src="<?= base_url('assets/annexadmin'); ?>/js/modernizr.min.js"></script>
    <script src="<?= base_url('assets/annexadmin'); ?>/js/waves.js"></script>
    <script src="<?= base_url('assets/annexadmin'); ?>/js/jquery.slimscroll.js"></script>
    <script src="<?= base_url('assets/annexadmin'); ?>/js/jquery.nicescroll.js"></script>
    <script src="<?= base_url('assets/annexadmin'); ?>/js/jquery.scrollTo.min.js"></script>

    <!-- Buttons examples -->
    <script src="<?= base_url('assets/annexadmin'); ?>/plugins/datatables/dataTables.buttons.min.js"></script>
    <script src="<?= base_url('assets/annexadmin'); ?>/plugins/datatables/buttons.bootstrap4.min.js"></script>
    <script src="<?= base_url('assets/annexadmin'); ?>/plugins/datatables/jszip.min.js"></script>
    <script src="<?= base_url('assets/annexadmin'); ?>/plugins/datatables/pdfmake.min.js"></script>
    <script src="<?= base_url('assets/annexadmin'); ?>/plugins/datatables/vfs_fonts.js"></script>
    <script src="<?= base_url('assets/annexadmin'); ?>/plugins/datatables/buttons.html5.min.js"></script>
    <script src="<?= base_url('assets/annexadmin'); ?>/plugins/datatables/buttons.print.min.js"></script>
    <script src="<?= base_url('assets/annexadmin'); ?>/plugins/datatables/buttons.colVis.min.js"></script>

    <!-- Required datatable js -->
    <script src="<?= base_url('assets/annexadmin'); ?>/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url('assets/annexadmin'); ?>/plugins/datatables/dataTables.bootstrap4.min.js"></script>

    <!-- Responsive Data Table  -->
    <script src="<?= base_url('assets/annexadmin'); ?>/plugins/datatables/dataTables.responsive.min.js"></script>
    <script src="<?= base_url('assets/annexadmin'); ?>/plugins/datatables/responsive.bootstrap4.min.js"></script>

    <!-- Datatable init js -->
    <script src="<?= base_url('assets/annexadmin'); ?>/pages/datatables.init.js"></script>

    <!-- Select 2 -->
    <script src="<?= base_url('assets/annexadmin'); ?>/plugins/select2/select2.min.js" type="text/javascript"></script>

    <!--Summernote js-->
    <script src="<?= base_url('assets/annexadmin'); ?>/plugins/summernote/summernote-bs4.min.js"></script>

    <!-- App js -->
    <script src="<?= base_url('assets/annexadmin'); ?>/js/app.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('.summernote').summernote({
                toolbar: [
                    ['view', ['fullscreen']],
                ],
                height: ($(window).height() - 600)

            }).next().
            find(".note-editable").
            attr("contenteditable", false).
            css("background-color", "#FFF");
        });

        $('#btnLogout').on('click', function() {
            let timerInterval
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success waves-effect waves-light',
                    cancelButton: 'btn btn-danger waves-effect waves-light'
                },
                buttonsStyling: true
            })

            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "You want logout from this ?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Logout',
                cancelButtonText: 'No',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "POST",
                        url: "<?= site_url('auth/signOut') ?>",
                        data: {
                            res: '1'
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.sukses) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'You signed out successfully !',
                                    html: 'Loading <b></b> \n milliseconds.',
                                    timer: 1500,
                                    timerProgressBar: true,
                                    didOpen: () => {
                                        Swal.showLoading()
                                        timerInterval = setInterval(() => {
                                            const content = Swal.getContent()
                                            if (content) {
                                                const b = content.querySelector('b')
                                                if (b) {
                                                    b.textContent = Swal.getTimerLeft()
                                                }
                                            }
                                        }, 100)
                                    },
                                    willClose: () => {
                                        clearInterval(timerInterval)
                                    }
                                }).then((result) => {
                                    if (result.dismiss === Swal.DismissReason.timer) {
                                        location.href = ('<?= site_url('auth'); ?>');
                                    }
                                })
                            }
                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                        }
                    });
                    return false;
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'You canceled logout',
                        'error'
                    )
                }
            })
        });

        function back() {
            location.href = ('<?= site_url('tiket') ?>');
        }
    </script>