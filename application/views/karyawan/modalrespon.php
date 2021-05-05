<!-- Modal -->
<div class="modal fade" id="modalrespon" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalrespon" aria-hidden="true">
    <div class="modal-dialog modal-xl">
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
                    <label class="col-sm-1 col-form-label fw-400">Transfer ?</label>
                    <div class="col-md-2">
                        <fieldset id="btnTfGroup">
                            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-primary btn-sm active">
                                    <input type="radio" name="radTransfer" id="radTransfer1" autocomplete="off" checked> No
                                </label>
                                <label class="btn btn-primary btn-sm">
                                    <input type="radio" name="radTransfer" id="radTransfer2" autocomplete="off" class="enable_tb"> Yes
                                </label>
                            </div>
                        </fieldset>
                    </div>
                    <label class="col-sm-1 col-form-label">To :</label>
                    <div class="col-sm-4 input-group">
                        <select id="karyawan" name="karyawan" class="select2 form-control mb-3 custom-select" style="width: 100%; height:36px;" disabled>
                            <?php foreach ($karyawanList as $row) : ?>
                                <option value="<?php echo $row->id; ?>" <?php if ($row->id == $karyawanId) echo 'selected'; ?>><?php echo $row->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="subject" class="col-sm-1 col-form-label">Subject :</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" id="subject" name="subject" value="<?= $subject; ?>" readonly>
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
                        <textarea id="keteranganManager" name="keteranganManager" class="summernote"><?= $keteranganManager; ?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <h6 class="text-muted fw-400">Description Employee :</h6>
                        <textarea id="keteranganEmployee" name="keteranganEmployee" class="summernote"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btnTransfer" name="btnTransfer" value="10" class="btn btn-danger" onclick="return transfer('<?= $id; ?>');">Transfer</button>
                <button type="button" id="btnClarify" name="btnClarify" value="4" class="btn btn-warning" onclick="return clarify('<?= $id; ?>');">Clarify</button>
                <button type="button" id="btnDevelop" name="btnDevelop" value="6" class="btn btn-success" onclick="return develop('<?= $id; ?>');">Develop</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        $("#btnTransfer").hide();

        $('.select2').select2()

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

        $('input:radio').click(function() {
            $("#karyawan").prop("disabled", true);
            if ($(this).hasClass('enable_tb')) {
                $("#karyawan").prop("disabled", false);
                $("#btnTransfer").show();
                $("#btnClarify, #btnDevelop").hide();
            } else {
                $("#btnTransfer").hide();
                $("#btnClarify, #btnDevelop").show();
            }
        });

        $('#karyawan').change(function(e) {
            var el = document.getElementById('karyawan');
            var label = $(this.options[this.selectedIndex]).closest('optgroup').prop('label');
            $("#group").val(label);
        });
    });

    function clarify(id) {
        $("#btnTransfer, #btnClarify, #btnDevelop").attr("disabled", true);
        Swal.fire({
            title: 'Do you want to clarify this ticket ?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: `Clarify`,
            denyButtonText: `Don't Clarify`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                var ticket = '<?= $tiket; ?>';
                var keteranganEmployee = $('#keteranganEmployee').val();
                var status = $('#btnClarify').val();
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
                                title: 'Clarify',
                                text: response.sukses
                            });
                            tampildata();
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
        $("#btnTransfer, #btnClarify, #btnDevelop").attr("disabled", false);
    }

    function develop(id) {
        $("#btnTransfer, #btnClarify, #btnDevelop").attr("disabled", true);
        Swal.fire({
            title: 'Do you want to start development this ticket ?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: `Yes`,
            denyButtonText: `No`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                var ticket = '<?= $tiket; ?>';
                var keteranganEmployee = $('#keteranganEmployee').val();
                var status = $('#btnDevelop').val();
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
                                title: 'Development',
                                text: response.sukses
                            });
                            tampildata();
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
        $("#btnTransfer, #btnClarify, #btnDevelop").attr("disabled", false);
    }

    function transfer(id) {
        $("#btnTransfer, #btnClarify, #btnDevelop").attr("disabled", true);
        var karyawanName = $('#karyawan option:selected').text();
        Swal.fire({
            title: 'Do you want to transfer this ticket ' +
                karyawanName + ' ?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: `Transfer`,
            denyButtonText: `Don't Transfer`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                var ticket = '<?= $tiket; ?>';
                var karyawan = $('#karyawan').val();
                var keteranganEmployee = $('#keteranganEmployee').val();
                var status = $('#btnTransfer').val();
                var datanya =
                    "&id=" + id +
                    "&ticket=" + ticket +
                    "&karyawan=" + karyawan +
                    "&karyawanName=" + karyawanName +
                    "&keteranganEmployee=" + keteranganEmployee +
                    "&status=" + status;
                $.ajax({
                    type: "POST",
                    url: "<?= site_url('karyawan/transfer') ?>",
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
                                title: 'Transfer',
                                text: response.sukses
                            });
                            tampildata();
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
        $("#btnTransfer, #btnClarify, #btnDevelop").attr("disabled", false);
    }
</script>