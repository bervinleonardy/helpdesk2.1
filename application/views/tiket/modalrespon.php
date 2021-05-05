<!-- Modal -->
<div class="modal fade" id="modalrespon" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalrespon" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalrespon">Edit Ticket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="pesan" style="display: none;"></div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-1 col-form-label">Ticket : </label>
                    <label class="col-sm-3 col-form-label"><?= $tiket; ?></label>
                    <label class="col-sm-2 col-form-label">Created Date : </label>
                    <label class="col-sm-2 col-form-label"><?= date('d F Y', strtotime($created_date)); ?></label>
                    <label class="col-sm-2 col-form-label">Severity : </label>
                    <label class="col-sm-2 col-form-label"><?= $severity; ?></label>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">
                        <h6 class="text-muted fw-400">Category</h6>
                        <input type="text" name="category" id="category" class="form-control" value="<?= $category; ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted fw-400">Subcategory</h6>
                        <input type="text" name="subcategory" id="subcategory" class="form-control" value="<?= $subcategory; ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted fw-400">Case</h6>
                        <input type="text" name="case" id="case" class="form-control" value="<?= $case; ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="subject" class="col-sm-2 col-form-label">Subject :</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" id="subject" name="subject" value="<?= $subject; ?>" readonly>
                    </div>
                    <label for="subject" class="col-sm-1 col-form-label">To :</label>
                    <div class="col-sm-3">
                        <input class="form-control" type="text" id="karyawan" name="karyawan" value="<?= $karyawan; ?>" readonly>
                    </div>
                </div>
                <!-- Divider -->
                <hr class="sidebar-divider d-none d-md-block">
                <div class="form-group row">
                    <div class="col-md-12">
                        <h6 class="text-muted fw-400">Description Employee :</h6>
                        <textarea id="keteranganEmployee" name="keteranganEmployee" class="summernote"><?= $keteranganEmployee; ?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <h6 class="text-muted fw-400">Description Manager :</h6>
                        <textarea id="keteranganManager" name="keteranganManager" class="summernote"><?= $keteranganManager; ?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <h6 class="text-muted fw-400">Description :</h6>
                        <textarea id="keterangan" name="keterangan" class="summernote"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btnClarified" name="btnClarified" value="5" class="btn btn-warning" onclick="return clarified('<?= $id; ?>');">Clarified</button>
                <button type="button" id="btnUat" name="btnUat" value="11" class="btn btn-warning" onclick="return uat('<?= $id; ?>');">UAT</button>
                <button type="button" id="btnClosed" name="btnClosed" value="8" class="btn btn-success" onclick="return closed('<?= $id; ?>');">Closed</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2()

        $('#keterangan').summernote({
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

        $('#keteranganEmployee, #keteranganManager').summernote({
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
                url: '<?php echo site_url('tiket/upload_image') ?>',
                cache: false,
                contentType: false,
                processData: false,
                data: data,
                type: "post",
                success: function(url) {
                    var image = $('<img>').attr('src', url);
                    $('#keterangan').summernote("insertNode", image[0]);
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
                url: "<?php echo site_url('tiket/delete_image') ?>",
                cache: false,
                success: function(response) {
                    console.log(response);
                }

            });
        }

        var status = <?= $status_id ?>

        if (status == 4) {
            $('#btnUat, #btnClosed').hide()
            $('#btnClarified').show()
        } else if (status != 6) {
            $('#btnClarified').hide()
            $('#btnUAT, #btnClosed').show()
        } else {
            $('#btnUAT, #btnClosed').hide()
            $('#btnClarified').show()
        }

    });

    function clarified(id) {
        $("#btnClarified, #btnUat, #btnClosed").attr("disabled", true);
        Swal.fire({
            title: 'Do you want to clarified this ticket ?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: `Clarified`,
            denyButtonText: `Don't Clarified`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                var ticket = '<?= $tiket; ?>';
                var keterangan = $('#keterangan').val();
                var status = $('#btnClarified').val();
                var datanya =
                    "&id=" + id +
                    "&ticket=" + ticket +
                    "&keterangan=" + keterangan +
                    "&status=" + status;
                $.ajax({
                    type: "POST",
                    url: "<?= site_url('tiket/update') ?>",
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
                                title: 'Clarified',
                                text: response.sukses
                            });
                            tampildatatiket();
                            $('#modalrespon').modal('hide');
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
        $("#btnClarified, #btnUat, #btnClosed").attr("disabled", false);
    }

    function uat(id) {
        $("#btnClarified, #btnUat, #btnClosed").attr("disabled", true);
        Swal.fire({
            title: 'Do you want to UAT again this ticket ?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: `Yes`,
            denyButtonText: `No`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                var ticket = '<?= $tiket; ?>';
                var keterangan = $('#keterangan').val();
                var status = $('#btnUat').val();
                var datanya =
                    "&id=" + id +
                    "&ticket=" + ticket +
                    "&keterangan=" + keterangan +
                    "&status=" + status;
                $.ajax({
                    type: "POST",
                    url: "<?= site_url('tiket/update') ?>",
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
                                title: 'UAT',
                                text: response.sukses
                            });
                            tampildatatiket();
                            $('#modalrespon').modal('hide');
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
        $("#btnClarified, #btnUat, #btnClosed").attr("disabled", false);
    }

    function closed(id) {
        $("#btnClarified, #btnUat, #btnClosed").attr("disabled", true);
        Swal.fire({
            title: 'Do you want to closed this ticket ?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: `Closed`,
            denyButtonText: `Don't Closed`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                var ticket = '<?= $tiket; ?>';
                var keterangan = $('#keterangan').val();
                var status = $('#btnClosed').val();
                var datanya =
                    "&id=" + id +
                    "&ticket=" + ticket +
                    "&keterangan=" + keterangan +
                    "&status=" + status;
                $.ajax({
                    type: "POST",
                    url: "<?= site_url('tiket/update') ?>",
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
                                title: 'Closed',
                                text: response.sukses
                            });
                            tampildatatiket();
                            $('#modalrespon').modal('hide');
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
        $("#btnClarified, #btnUat, #btnClosed").attr("disabled", false);
    }
</script>