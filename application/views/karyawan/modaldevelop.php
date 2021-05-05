<!-- Modal -->
<div class="modal fade" id="modaldevelop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modaldevelop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modaldevelop">Edit Ticket</h5>
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
                    <div class="col-sm-4">
                        <h6 class="text-muted fw-400">From :</h6>
                        <input type="text" name="creator" id="creator" class="form-control" value="<?= $creator; ?>" readonly>
                    </div>
                    <div class="col-sm-4">
                        <h6 class="text-muted fw-400">To :</h6>
                        <input type="text" name="karyawan" id="karyawan" class="form-control" value="<?= $karyawan; ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <h6 class="text-muted fw-400">Subject :</h6>
                        <input type="text" name="subject" id="subject" class="form-control" value="<?= $subject; ?>" readonly>
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
                        <h6 class="text-muted fw-400">Description :</h6>
                        <textarea id="keteranganManager" name="keteranganManager" class="summernote"><?= $keteranganManager; ?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <h6 class="text-muted fw-400">Description Employee :</h6>
                        <textarea id="keteranganEmployee" name="keteranganEmployee" class="summernote"></textarea>
                    </div>
                </div>

                <?php if ($status_id != 11 && $status_id != 7) : ?>
                    <!-- Divider -->
                    <hr class="sidebar-divider d-none d-md-block">

                    <div class="progressBar form-group row">
                        <div class="col-md-12">
                            <h6 class="text-muted fw-400">Progress :</h6>
                            <input type="text" id="progress" name="progress" value="<?= $progress; ?>" />
                        </div>
                    </div>
                <?php endif ?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btnDevelop" name="btnDevelop" class="btn btn-success" onclick="return develop('<?= $id; ?>');">Update</button>
                <button type="button" id="btnUat" name="btnUat" value="7" class="btn btn-success" onclick="return uat('<?= $id; ?>');">UAT</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        const status_id = '<?= $status_id ?>';

        $('#keteranganEmployee').summernote({
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

        $('#keterangan, #keteranganManager').summernote({
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
                url: '<?php echo site_url('karyawan/upload_image') ?>',
                cache: false,
                contentType: false,
                processData: false,
                data: data,
                type: "post",
                success: function(url) {
                    var image = $('<img>').attr('src', url);
                    $('#keteranganEmployee').summernote("insertNode", image[0]);
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
                url: "<?php echo site_url('karyawan/delete_image') ?>",
                cache: false,
                success: function(response) {
                    console.log(response);
                }

            });
        }

        $("#progress").ionRangeSlider({
            min: 0,
            max: 100,
            grid: true
        });

        if (status_id == 11 || status_id == 7) {
            $("#btnDevelop").hide();
            $("#btnUat").show();
        } else {
            $("#btnUat").hide();
            $("#btnDevelop").show();
        }

    });

    function develop(id) {
        $("#btnDevelop, #btnUat").attr("disabled", true);
        var progress = $('#progress').val();
        Swal.fire({
            title: 'Are you sure want update this ticket ' + progress + '% ?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: `Yes`,
            denyButtonText: `No`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                var ticket = '<?= $tiket; ?>';
                var keteranganEmployee = $('#keteranganEmployee').val();
                var status = 0;
                if (progress == 100) {
                    status = 8;
                } else {
                    status = 6;
                }
                var datanya =
                    "&id=" + id +
                    "&ticket=" + ticket +
                    "&keteranganEmployee=" + keteranganEmployee +
                    "&status=" + status +
                    "&progress=" + progress;
                $.ajax({
                    type: "POST",
                    url: "<?= site_url('karyawan/progress') ?>",
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
                                title: 'Development',
                                text: response.sukses
                            });
                            tampildata();
                            $('#modaldevelop').modal('hide');
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
        $("#btnDevelop, #btnUat").attr("disabled", false);
    }

    function uat(id) {
        $("#btnDevelop, #btnUat").attr("disabled", true);
        Swal.fire({
            title: 'Do you want to UAT again this ticket ?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: `UAT`,
            denyButtonText: `Don't UAT`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                var ticket = '<?= $tiket; ?>';
                var keteranganEmployee = $('#keteranganEmployee').val();
                var status = $('#btnUat').val()
                var datanya =
                    "&id=" + id +
                    "&ticket=" + ticket +
                    "&keteranganEmployee=" + keteranganEmployee +
                    "&status=" + status;
                $.ajax({
                    type: "POST",
                    url: "<?= site_url('karyawan/update') ?>",
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
                            tampildata();
                            $('#modaldevelop').modal('hide');
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
        $("#btnDevelop, #btnUat").attr("disabled", false);
    }
</script>