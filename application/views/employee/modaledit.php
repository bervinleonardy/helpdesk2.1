<!-- Modal -->
<div class="modal fade" id="modaledit" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modaleditLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modaleditLabel">Edit Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('administrator/updatedataemployee', ['class' => 'formedit']); ?>
            <div class="pesan" style="display: none;"></div>
            <div class="modal-body">

                <div class="form-group row">
                    <label for="nik" class="col-sm-2 col-form-label">NIK</label>
                    <div class="col-sm-4">
                        <input type="hidden" name="id" id="id" class="form-control" readonly value="<?= $id; ?>">
                        <input type="text" name="nik" id="nik" class="form-control" value="<?= $nik; ?>" readonly>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                    <div class="col-sm-5">
                        <input type="text" name="email" id="email" class="form-control" value="<?= $email; ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="nama" class="col-sm-2 col-form-label">Employee</label>
                    <div class="col-sm-4">
                        <input type="text" name="nama" id="nama" class="form-control" value="<?= $nama; ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="mobile" class="col-sm-2 col-form-label">Mobile</label>
                    <div class="col-sm-4">
                        <input type="text" name="mobile" id="mobile" class="form-control" value="<?= $mobile; ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="position" class="col-sm-2 col-form-label">Position</label>
                    <div class="col-sm-4">
                        <input type="text" name="position" id="position" class="form-control" value="<?= $position; ?>">
                    </div>
                </div>

				<div class="form-group row">
                    <label for="level" class="col-sm-2 col-form-label">Level</label>
                    <div class="col-sm-4">
                        <select name="level" id="level" class="form-control">
							<option value="" <?php if ($level_id == 0) echo 'selected'; ?>>Select</option>
                            <?php foreach ($level as $row) : ?>
                                <option value="<?= $row->id; ?>" <?php if ($row->id == $level_id) echo 'selected'; ?>><?= $row->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="department" class="col-sm-2 col-form-label">Department</label>
                    <div class="col-sm-4">
                        <select name="department" id="department" class="select2 form-control custom-select" style="width: 100%; height:36px;">
                            <?php foreach ($department as $row) : ?>
                                <option value="<?= $row->id; ?>" <?php if ($row->id == $department_id) echo 'selected'; ?>><?= $row->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="role" class="col-sm-2 col-form-label">Role</label>
                    <div class="col-sm-4">
                        <select name="role" id="role" class="form-control">
                            <?php foreach ($role as $row) : ?>
                                <option value="<?= $row->id; ?>" <?php if ($row->id == $role_id) echo 'selected'; ?>><?= $row->role; ?></option>
                            <?php endforeach; ?>
                        </select>
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
                        tampildataemployee();
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
