<!-- Modal -->
<div class="modal fade" id="modaledit" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modaleditLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modaleditLabel">Edit Case</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('administrator/updatedatacases', ['class' => 'formedit']); ?>
            <div class="pesan" style="display: none;"></div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="category" class="col-sm-2 col-form-label">Category</label>
                    <div class="col-sm-4">
                        <select name="category" id="category" class="select2 form-control mb-3 custom-select" style="width: 100%; height:36px;">
                            <?php
                            $department = '';
                            foreach ($category as $row) {
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
                </div>

                <div class="form-group row">
                    <label for="category" class="col-sm-2 col-form-label">Subcategory</label>
                    <div class="col-sm-6">
                        <select name="subcategory" id="subcategory" class="select2 form-control mb-3 custom-select" style="width: 100%; height:36px;">
                            <?php
                            $department = '';
                            foreach ($subcategory as $row) {
                                if ($department != $row->department) {
                                    if ($department != '') {
                                        echo '</optgroup>';
                                    }
                                    echo '<optgroup label="' . ucfirst($row->department) . '">';
                                }
                                echo '<option value="' . $row->id . '" ' . ($row->id == $subCategoryId ? 'selected' : '') . '>' . htmlspecialchars($row->name) . '</option>';
                                $department = $row->department;
                            }
                            if ($department != '') {
                                echo '</optgroup>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="nama" class="col-sm-2 col-form-label">Case</label>
                    <div class="col-sm-8">
                        <input type="hidden" name="id" id="id" class="form-control" readonly value="<?= $id; ?>">
                        <input type="text" name="nama" id="nama" class="form-control" value="<?= $nama; ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="is_active" class="col-sm-2 col-form-label">Activation</label>
                    <div class="col-sm-2">
                        <select name="is_active" id="is_active" class="form-control">
                            <option value="1" <?php if ($is_active == 1) echo 'selected'; ?>>Yes</option>
                            <option value="0" <?php if ($is_active == 0) echo 'selected'; ?>>No</option>
                        </select>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
            <?= form_close(); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2()

        $('#category').change(function(e) {
            var id = $(this).val();
            $.ajax({
                url: "<?php echo site_url('master/getSelectSubcategory') ?>",
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
                    var html = '';
                    var i;
                    for (i = 0; i < data.length; i++) {
                        html += '<option value=' + data[i].id + '>' + data[i].name + '</option>';
                    }
                    $('#subcategory').html(html);
                }
            });
            return false;
        });

        $('.formedit').submit(function(e) {
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
                            title: 'Berhasil',
                            text: response.sukses
                        });
                        tampildatacases();
                        $('#modaledit').modal('hide');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
            return false;
        });
    });
</script>
