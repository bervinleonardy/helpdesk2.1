<!-- Modal -->
<div class="modal fade" id="modalrespon" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalresponLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalresponLabel">Edit Ticket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="pesan" style="display: none;"></div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="nama" class="col-sm-1 col-form-label">Ticket</label>
                    <div class="col-sm-4">
                        <input type="hidden" name="id" id="id" class="form-control" readonly value="<?= $id; ?>">
                        <input type="text" name="tiket" id="tiket" class="form-control" value="<?= $tiket; ?>" readonly>
                    </div>
                    <label for="nama" class="col-sm-2 col-form-label">Created Date</label>
                    <div class="col-sm-4">
                        <input type="text" name="createdDate" id="createdDate" class="form-control" value="<?= date('d F Y', strtotime($created_date)); ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">
                        <h6 class="text-muted fw-400">Severity</h6>
                        <select id="severity" name="severity" class="select2 form-control mb-3 custom-select" style="width: 100%; height:36px;">
                            <?php foreach ($severityList as $row) : ?>
                                <option value="<?php echo $row->id; ?>" <?php if ($row->id == $severityId) echo 'selected'; ?>><?php echo $row->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted fw-400">Date set :</h6>
                        <div class="input-group">
                            <input type="text" id="startDate" name="startDate" class="form-control startdate datetimepicker-input" data-toggle="datetimepicker" data-target=".startdate" />
                            <div class="input-group-append">
                                <span class="input-group-text">s/d</span>
                            </div>
                            <input type="text" id="endDate" name="endDate" class="form-control enddate datetimepicker-input" data-toggle="datetimepicker" data-target=".enddate" />
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">
                        <h6 class="text-muted fw-400">Category</h6>
                        <select id="category" name="category" class="select2 form-control mb-3 custom-select" style="width: 100%; height:36px;">
                            <?php
                            $department = '';
                            foreach ($categoryList as $row) {
                                if ($department != $row->department) {
                                    if ($department != '') {
                                        echo '</optgroup>';
                                    }
                                    echo '<optgroup label="' . ucfirst($row->department) . '">';
                                }
                                echo '<option value="' . $row->id . '" ' . ($row->id == $categoryId ? 'selected' : '') . '>' . htmlspecialchars($row->name) . '</option>';
                                $department = $row->department;
                            }
                            if ($department != '') {
                                echo '</optgroup>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted fw-400">Subcategory</h6>
                        <select id="subcategory" name="subcategory" class="select2 form-control mb-3 custom-select" style="width: 100%; height:36px;">
                            <option value="<?= $subcategoryId; ?>"><?= $subcategory; ?></option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted fw-400">Case</h6>
                        <select id="case" name="case" class="select2 form-control mb-3 custom-select" style="width: 100%; height:36px;">
                            <option value="<?= $caseId; ?>"><?= $case; ?></option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="subject" class="col-sm-1 col-form-label">Subject :</label>
                    <div class="col-sm-6">
                        <input class="form-control" type="text" id="subject" name="subject" value="<?= $subject; ?>" readonly>
                    </div>
                    <label class="col-sm-1 col-form-label">To :</label>
                    <div class="col-sm-4 input-group">
                        <select id="karyawan" name="karyawan" class="select2 form-control mb-3 custom-select" style="width: 100%; height:36px;">
                            <option value=''>Choose...</option>
                            <?php foreach ($karyawanList as $row) : ?>
                                <option value="<?php echo $row->id; ?>" <?php if ($row->id == $karyawanId) echo 'selected'; ?>><?php echo $row->name; ?></option>
                            <?php endforeach; ?>
                        </select>
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
                <button type="button" id="btnReject" name="btnReject" value="9" class="btn btn-danger" onclick="return reject('<?= $id; ?>');">Reject</button>
                <button type="button" id="btnAssign" name="btnAssign" value="3" class="btn btn-primary" onclick="return assign('<?= $id; ?>');">Assign</button>
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

        $('#category').change(function(e) {
            var id = $(this).val();
            var html = "<option value=''>Select</option>";
            var html2 = '';

            if (id == '') {
                html = "<option value=''>Select</option>";
                html2 = "<option value=''>Select</option>";
                $('#subcategory').html(html);
                $('#case').html(html2);
            } else {
                $.ajax({
                    url: "<?= site_url('manager/getSelectCategory') ?>",
                    data: {
                        id: id
                    },
                    type: 'post',
                    dataType: 'json',
                    cache: false,
                    error: function(e) {
                        swal('Error', e, 'error');
                    },
                    success: function(data) {
                        var i;
                        for (i = 0; i < data.length; i++) {
                            html += '<option value=' + data[i].subcategoryId + '>' + data[i].subcategory + '</option>';
                        }
                        html2 = "<option value=''>Select</option>";
                        $('#subcategory').html(html);
                        $('#case').html(html2);
                    }
                });
            }

            return false;
        });

        $('#subcategory').change(function(e) {
            var id = $(this).val();
            $.ajax({
                url: "<?= site_url('manager/getSelectSubcategory') ?>",
                data: {
                    id: id
                },
                type: 'post',
                dataType: 'json',
                cache: false,
                error: function(e) {
                    swal('Error', e, 'error');
                },
                success: function(data) {
                    var html = "<option value=''>Select</option>";
                    var i;
                    for (i = 0; i < data.length; i++) {
                        html += "<option value=" + data[i].id + ">" + data[i].name + "</option>";
                    }
                    $('#case').html(html);
                }
            });
            return false;
        });

        $('#karyawan').change(function(e) {
            var el = document.getElementById('karyawan');
            var label = $(this.options[this.selectedIndex]).closest('optgroup').prop('label');
            $("#group").val(label);
        });

    });

    $(".startdate").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false
    })

    $(".startdate").on("change.datetimepicker", function(e) {
        $(".enddate").val("")
        $(".enddate").datetimepicker('minDate', e.date);
    })

    $(".enddate").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false
    })

    function reject(id) {
        $("#btnReject, #btnAssign").attr("disabled", true);
        Swal.fire({
            title: 'Do you want to reject this ticket to ?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: `Reject`,
            denyButtonText: `Don't Reject`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                var ticket = '<?= $tiket; ?>';
                var keteranganManager = $('#keteranganManager').val();
                var status = $('#btnReject').val();
                var datanya =
                    "&id=" + id +
                    "&ticket=" + ticket +
                    "&keteranganManager=" + keteranganManager +
                    "&status=" + status;
                $.ajax({
                    type: "POST",
                    url: "<?= site_url('manager/reject') ?>",
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
                                title: 'Reject',
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
        $("#btnReject, #btnAssign").attr("disabled", false);
    }

    function assign(id) {
        $("#btnReject, #btnAssign").attr("disabled", true);
        var karyawan = $('#karyawan option:selected').text();
        Swal.fire({
            title: 'Do you want to assign this ticket ' +
                karyawan + ' ?',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: `Assign`,
            denyButtonText: `Don't Assign`,
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                var ticket = '<?= $tiket; ?>';
                var severity = $('#severity').val();
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();
                var category = $('#category').val();
                var subcategory = $('#subcategory').val();
                var cases = $('#case').val();
                var karyawan = $('#karyawan').val();
                var severity = $('#severity').val();
                var keteranganManager = $('#keteranganManager').val();
                var status = $('#btnAssign').val();
                var datanya =
                    "&id=" + id +
                    "&ticket=" + ticket +
                    "&severity=" + severity +
                    "&startDate=" + startDate +
                    "&endDate=" + endDate +
                    "&category=" + category +
                    "&subcategory=" + subcategory +
                    "&case=" + cases +
                    "&karyawan=" + karyawan +
                    "&keteranganManager=" + keteranganManager +
                    "&status=" + status;
                $.ajax({
                    type: "POST",
                    url: "<?= site_url('manager/assign') ?>",
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
        $("#btnReject, #btnAssign").attr("disabled", false);
    }
</script>