<!-- Modal -->
<div class="modal fade" id="modaledit" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modaleditLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modaleditLabel">Edit Submenuy</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('menu/updatedatasubmenu', ['class' => 'formedit']); ?>
            <div class="pesan" style="display: none;"></div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="title" class="col-sm-2 col-form-label">Submenu</label>
                    <div class="col-sm-6">
                        <input type="hidden" class="form-control" id="id" name="id" value="<?= $id; ?>">
                        <input type="text" class="form-control" id="title" name="title" value="<?= $title; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="urutan" class="col-sm-2 col-form-label">Urutan</label>
                    <div class="col-sm-2">
                        <input type="urutan" class="form-control" id="urutan" name="urutan" min="0" max="99" value="<?= $urutan; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="url" class="col-sm-2 col-form-label">Menu</label>
                    <div class="col-sm-5">
                        <select name="menu" id="menu" class="form-control">
                            <option value="">Select Menu</option>
                            <?php foreach ($menu as $m) : ?>
                                <option value="<?= $m['id']; ?>" <?php if ($m['id'] == $menu_id) echo 'selected' ?>><?= $m['menu']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="icon" class="col-sm-2 col-form-label">URL</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="url" name="url" value="<?= $url; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="icon" class="col-sm-2 col-form-label">Icon</label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="icon" name="icon" value="<?= $icon; ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="is_active" class="col-sm-2 col-form-label">Activation</label>
                    <div class="col-sm-3">
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
                        tampildata();
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