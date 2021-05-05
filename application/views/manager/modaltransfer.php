<!-- Modal -->
<div class="modal fade" id="modaltransfer" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modaltransferLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modaltransferLabel">Edit Ticket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="pesan" style="display: none;"></div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-1 col-form-label">Ticket :</label>
                    <label class="col-sm-2 col-form-label"><?= $tiket; ?></label>
                    <label class="col-sm-2 col-form-label">Created Date :</label>
                    <label class="col-sm-2 col-form-label"><?= date('d F Y', strtotime($created_date)); ?></label>
                    <label class="col-sm-1 col-form-label">Severity :</label>
                    <label class="col-sm-2 col-form-label"><?= $severity; ?></label>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">
                        <h6 class="text-muted fw-400">Category :</h6>
                        <input type="text" name="category" id="category" class="form-control" value="<?= $category; ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted fw-400">Subcategory :</h6>
                        <input type="text" name="subcategory" id="subcategory" class="form-control" value="<?= $subcategory; ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted fw-400">Case :</h6>
                        <input type="text" name="case" id="case" class="form-control" value="<?= $case; ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <h6 class="text-muted fw-400">Date set :</h6>
                        <div class="input-group">
                            <input type="text" id="startDate" name="startDate" class="form-control startdate datetimepicker-input" value="<?= date('d F Y', strtotime($startDate)); ?>" readonly />
                            <div class="input-group-append">
                                <span class="input-group-text">s/d</span>
                            </div>
                            <input type="text" id="endDate" name="endDate" class="form-control enddate datetimepicker-input" value="<?= date('d F Y', strtotime($endDate)); ?>" readonly />
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-12">
                        <div class="card py-3 border-left-info">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h3 class="text-muted fw-400">Transfer Request</h3>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <h6 class="text-muted fw-400">From :</h6>
                                        <input type="hidden" name="karyawanRequestedId" id="karyawanRequestedId" class="form-control" value="<?= $karyawanRequestedId; ?>" readonly>
                                        <input type="text" name="karyawanRequested" id="karyawanRequested" class="form-control" value="<?= $karyawanRequested; ?>" readonly>
                                    </div>
                                    <div class="col-sm-6">
                                        <h6 class="text-muted fw-400">To :</h6>
                                        <input type="hidden" name="karyawanId" id="karyawanId" class="form-control" value="<?= $karyawanId; ?>" readonly>
                                        <input type="text" name="karyawan" id="karyawan" class="form-control" value="<?= $karyawan; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="subject" class="col-sm-1 col-form-label">Subject :</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" id="subject" name="subject" value="<?= $subject; ?>" readonly>
                    </div>
                    <label for="subject" class="col-sm-1 col-form-label">Creator :</label>
                    <div class="col-sm-4">
                        <input class="form-control" type="text" id="creator" name="creator" value="<?= $creator; ?>" readonly>
                    </div>
                </div>
                <!-- Divider -->
                <hr class="sidebar-divider d-none d-md-block">
                <div class="form-group row">
                    <div class="col-md-12">
                        <h6 class="text-muted fw-400">Description :</h6>
                        <textarea id="keterangan" name="keterangan" class="summernote"><?= $keterangan; ?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <h6 class="text-muted fw-400">Description Manager :</h6>
                        <textarea id="keteranganManager" name="keteranganManager" class="summernote"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btnDecline" name="btnDecline" value="3" class="btn btn-danger" onclick="return decline('<?= $id; ?>');">Decline</button>
                <button type="button" id="btnApprove" name="btnApprove" value="3" class="btn btn-success" onclick="return approve('<?= $id; ?>');">Approve</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $('.select2').select2()

        $('#keteranganManager').summernote({
            height: ($(window).height() - 700),
            callbacks: {
                onImageUpload: function(image) {
                    uploadImage(image[0]);
                },
                onMediaDelete: function(target) {
                    deleteImage(target[0].src);
                }
            }
        });

        $('#keterangan, #keteranganEmployee').summernote({
            toolbar: [
                ['view', ['fullscreen']],
            ],
            height: ($(window).height() - 600)

        }).next().
        find(".note-editable").
        attr("contenteditable", false).
        css("background-color", "#FFF");

        function uploadImage(image) {
            var data = new FormData();
            data.append("image", image);
            $.ajax({
                url: '<?php echo site_url('manager/upload_image') ?>',
                cache: false,
                contentType: false,
                processData: false,
                data: data,
                type: "post",
                success: function(url) {
                    var image = $('<img>').attr('src', url);
                    $('#keteranganManager').summernote("insertNode", image[0]);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        function deleteImage(src) {
            var datanya = "&src=" + src;
            $.ajax({
                data: datanya,
                type: "POST",
                url: "<?php echo site_url('manager/delete_image') ?>",
                cache: false,
                success: function(response) {
                    console.log(response);
                }

            });
        }
    });

    function decline(id) {
        $("#btnDecline, #btnApprove").attr("disabled", true);
        Swal.fire({
            title: 'Do you want to reject this request transfer ticket to ?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: `Reject`,
            denyButtonText: `Don't Reject`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                var ticket = '<?= $tiket; ?>';
                var keteranganManager = $('#keteranganManager').val();
                var karyawan = $('#karyawanRequestedId').val();
                var karyawanNama = $('#karyawanRequested').val();
                var status = $('#btnDecline').val();
                var datanya =
                    "&id=" + id +
                    "&ticket=" + ticket +
                    "&keteranganManager=" + keteranganManager +
                    "&karyawan=" + karyawan +
                    "&karyawanNama=" + karyawanNama +
                    "&status=" + status;
                $.ajax({
                    type: "POST",
                    url: "<?= site_url('manager/declineTransfer') ?>",
                    data: datanya,
                    dataType: 'json',
                    cache: false,
                    success: function(response) {
                        if (response.error) {
                            $('.pesan').html(response.error).show();
                        }

                        if (response.sukses) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Decline',
                                text: response.sukses
                            });
                            tampildata();
                            $('#modaltransfer').modal('hide');
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                });
                return false;
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        })
        $("#btnDecline, #btnApprove").attr("disabled", false);
    }

    function approve(id) {
        $("#btnDecline, #btnApprove").attr("disabled", true);
        var karyawan = $('#karyawan').val();
        Swal.fire({
            title: 'Do you approve transfer ticket to ' +
                karyawan + ' ?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: `Yes`,
            denyButtonText: `Cancel`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                var ticket = '<?= $tiket; ?>';
                var keteranganManager = $('#keteranganManager').val();
                var karyawan = $('#karyawanId').val();
                var karyawanNama = $('#karyawan').val();
                var status = $('#btnDecline').val();
                var datanya =
                    "&id=" + id +
                    "&ticket=" + ticket +
                    "&keteranganManager=" + keteranganManager +
                    "&karyawan=" + karyawan +
                    "&karyawanNama=" + karyawanNama +
                    "&status=" + status;
                $.ajax({
                    type: "POST",
                    url: "<?= site_url('manager/approveTransfer') ?>",
                    data: datanya,
                    dataType: 'json',
                    cache: false,
                    success: function(response) {
                        if (response.error) {
                            $('.pesan').html(response.error).show();
                        }

                        if (response.sukses) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Assign',
                                text: response.sukses
                            });
                            tampildata();
                            $('#modaltransfer').modal('hide');
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                });
                return false;
            } else if (result.isDenied) {
                Swal.fire('Changes are not saved', '', 'info')
            }
        })
        $("#btnDecline, #btnApprove").attr("disabled", false);
    }
</script>