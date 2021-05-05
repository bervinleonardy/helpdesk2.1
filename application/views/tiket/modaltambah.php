<!-- Modal -->
<div class="modal fade" id="modaltambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modaltambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modaltambahLabel">Add Tiket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open_multipart('tiket/simpandata', ['class' => 'formsimpan']); ?>
            <div class="pesan" style="display: none;"></div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-md-2">
                        <h6 class="text-muted fw-400">Severity</h6>
                        <select id="severity" name="severity" class="select2 form-control mb-3 custom-select" style="width: 100%; height:36px;">
                            <option value="">No Selected</option>
                            <?php foreach ($severity as $row) : ?>
                                <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-md-2">
                        <h6 class="text-muted fw-400">Category</h6>
                        <select id="category" name="category" class="select2 form-control mb-3 custom-select" style="width: 100%; height:36px;">
                            <option value="">Select</option>
                            <?php
                            $department = '';
                            foreach ($category as $row) {
                                if ($department != $row->department) {
                                    if ($department != '') {
                                        echo '</optgroup>';
                                    }
                                    echo '<optgroup label="' . ucfirst($row->department) . '">';
                                }
                                echo '<option value="' . $row->id . '" data-group="' . $row->departmentId . '">' . htmlspecialchars($row->name) . '</option>';
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
                            <option value="">Select</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted fw-400">Case</h6>
                        <select id="case" name="case" class="select2 form-control mb-3 custom-select" style="width: 100%; height:36px;">
                            <option value="">Select</option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="subject" class="col-sm-1 col-form-label">Subject :</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" id="subject" name="subject" placeholder="Subject...">
                    </div>
                </div>

                <!-- Divider -->
                <hr class="sidebar-divider d-none d-md-block">

                <div class="form-group row">
                    <div class="col-md-12">
                        <h6 class="text-muted fw-400">Keterangan :</h6>
                        <textarea id="keterangan" name="keterangan" class="summernote"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" id="btnSubmit" name="btnSubmit" class="btn btn-primary">Save</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2()

        $('#keterangan').summernote({
            height: ($(window).height() - 550),
            callbacks: {
                onImageUpload: function(image) {
                    uploadImage(image[0]);
                },
                onMediaDelete: function(target) {
                    deleteImage(target[0].src);
                }
            }
        });

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
                    url: "<?= site_url('tiket/getSelectCategory') ?>",
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
                url: "<?= site_url('tiket/getSelectSubcategory') ?>",
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

        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        $('.formsimpan').submit(function(e) {
            $("#btnSubmit").attr("disabled", true);
            var group = $('option:selected', '#category').data("group");
            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: $(this).serialize() + '&group=' + group,
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        $('.pesan').html(response.error).show();
                    }

                    if (response.sukses) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.sukses
                        });
                        tampildatatiket();
                        $('#modaltambah').modal('hide');
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
</script>