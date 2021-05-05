<!-- jQuery  -->
<script src="<?= base_url('assets/annexadmin'); ?>/js/jquery.min.js"></script>
<script src="<?= base_url('assets/annexadmin'); ?>/js/popper.min.js"></script>
<script src="<?= base_url('assets/annexadmin'); ?>/js/bootstrap.min.js"></script>
<script src="<?= base_url('assets/annexadmin'); ?>/js/modernizr.min.js"></script>
<script src="<?= base_url('assets/annexadmin'); ?>/js/waves.js"></script>
<script src="<?= base_url('assets/annexadmin'); ?>/js/jquery.slimscroll.js"></script>
<script src="<?= base_url('assets/annexadmin'); ?>/js/jquery.nicescroll.js"></script>
<script src="<?= base_url('assets/annexadmin'); ?>/js/jquery.scrollTo.min.js"></script>

<!-- Plugins js -->
<script src="<?= base_url('assets/annexadmin'); ?>/plugins/timepicker/bootstrap-material-datetimepicker.js"></script>
<script src="<?= base_url('assets/annexadmin'); ?>/plugins/select2/select2.min.js" type="text/javascript"></script>

<script src="<?= base_url('assets/annexadmin'); ?>/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

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
    $(function() {
        $(
            "input[name='statReq']:checkbox, input[name='akunUser']:checkbox, input[name='detailAset[]']:checkbox, input[name='folderSharing']:checkbox").on('click', function() {
            var $box = $(this);
            if ($box.is(":checked")) {
                var group = "input:checkbox[name='" + $box.attr("name") + "']";
                $(group).prop("checked", false);
                $box.prop("checked", true);
            } else {
                $box.prop("checked", false);
            }
        });
    });

    $(document).ready(function() {
        $('.formsimpan').submit(function(e) {
            $("#btnSubmit").attr("disabled", true);
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        $('.pesan').html(response.error).show();
                    }

                    if (response.sukses) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            html: 'Loading <b></b> \n milliseconds. \n redirect to ticket page',
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
                                location.href = ('<?= site_url('tiket'); ?>');
                            }
                        })
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
            return false;
            $("#btnSubmit").attr("disabled", false);
        });
    });

    jQuery('#tglDibutuhkan').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        todayHighlight: true
    });

    $("input[name='detailAset']").change(function() {
        if ($('#checkLainnyaDetailAset').is(':checked')) {
            $("#lainnyaDetailAset").prop("disabled", false);
        } else {
            $("#lainnyaDetailAset").prop("disabled", true);
            $("#lainnyaDetailAset").val("");

        }
    });

    $("input[name='detailPeralatan[]']").change(function() {
        if ($('#checkLainnyadetailPeralatan').is(':checked')) {
            $("#lainnyaDetailPeralatan").prop("disabled", false);
        } else {
            $("#lainnyaDetailPeralatan").prop("disabled", true);
            $("#lainnyaDetailPeralatan").val("");
        }
    });

    $("input[name='folderSharing']").change(function() {
        if ($("input[name='folderSharing']").is(':checked')) {
            $("#filePath").prop("disabled", false);
        } else {
            $("#filePath").prop("disabled", true);
            $("#filePath").val("");
        }
    });

    $("input[name='softwares[]']").change(function() {
        if ($('#checkLainnyaSofware1').is(':checked')) {
            $("#lainnyaSoftwares1").prop("disabled", false);
        } else {
            $("#lainnyaSoftwares1").prop("disabled", true);
            $("#lainnyaSoftwares1").val("");
        }

        if ($('#checkLainnyaSofware2').is(':checked')) {
            $("#lainnyaSoftwares2").prop("disabled", false);
        } else {
            $("#lainnyaSoftwares2").prop("disabled", true);
            $("#lainnyaSoftwares2").val("");
        }

        if ($('#checkLainnyaSofware3').is(':checked')) {
            $("#lainnyaSoftwares3").prop("disabled", false);
        } else {
            $("#lainnyaSoftwares3").prop("disabled", true);
            $("#lainnyaSoftwares3").val("");
        }

        if ($('#checkLainnyaSofware4').is(':checked')) {
            $("#lainnyaSoftwares4").prop("disabled", false);
        } else {
            $("#lainnyaSoftwares4").prop("disabled", true);
            $("#lainnyaSoftwares4").val("");
        }

        if ($('#checkLainnyaSofware5').is(':checked')) {
            $("#lainnyaSoftwares5").prop("disabled", false);
        } else {
            $("#lainnyaSoftwares5").prop("disabled", true);
            $("#lainnyaSoftwares5").val("");
        }

        if ($('#checkLainnyaSofware6').is(':checked')) {
            $("#lainnyaSoftwares6").prop("disabled", false);
        } else {
            $("#lainnyaSoftwares6").prop("disabled", true);
            $("#lainnyaSoftwares6").val("");
        }

        if ($('#checkLainnyaSofware7').is(':checked')) {
            $("#lainnyaSoftwares7").prop("disabled", false);
        } else {
            $("#lainnyaSoftwares7").prop("disabled", true);
            $("#lainnyaSoftwares7").val("");
        }
    });
</script>