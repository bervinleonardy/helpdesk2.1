<!-- Modal -->
<div class="modal fade" id="modaledit" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modaleditLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modaleditLabel">Edit Severity</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('superadmin/updatedataseverity', ['class' => 'formedit']); ?>
            <div class="pesan" style="display: none;"></div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="nama" class="col-sm-2 col-form-label">Severity</label>
                    <div class="col-sm-4">
                        <input type="hidden" name="id" id="id" class="form-control" readonly value="<?= $id; ?>">
                        <input type="text" name="nama" id="nama" class="form-control" value="<?= $nama; ?>">
                    </div>
                </div>

                <!-- Divider -->
                <hr class="sidebar-divider">

                <div class="form-group row">
                    <label for="nama" class="col-sm-2 col-form-label">Response Weekday Office</label>
                    <div class="col-sm-4">
                        <input type="number" name="response_weekday_o" id="response_weekday_o" class="form-control" placeholder="Minutes..." min="0" value="<?= $response_weekday_o; ?>">
                    </div>
                    <label for="nama" class="col-sm-2 col-form-label">Response Weekday After Office</label>
                    <div class="col-sm-4">
                        <input type="number" name="response_weekday_ao" id="response_weekday_ao" class="form-control" placeholder="Minutes..." min="0" value="<?= $response_weekday_ao; ?>">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nama" class="col-sm-2 col-form-label">Resolve Weekday Office</label>
                    <div class="col-sm-4">
                        <input type="number" name="resolve_weekday_o" id="resolve_weekday_o" class="form-control" placeholder="Hours..." min="0" value="<?= $resolve_weekday_o; ?>">
                    </div>
                    <label for="nama" class="col-sm-2 col-form-label">Resolve Weekday After Office</label>
                    <div class="col-sm-4">
                        <input type="number" name="resolve_weekday_ao" id="resolve_weekday_ao" class="form-control" placeholder="Hours..." min="0" value="<?= $resolve_weekday_ao; ?>">
                    </div>
                </div>

                <!-- Divider -->
                <hr class="sidebar-divider">

                <div class="form-group row">
                    <label for="nama" class="col-sm-2 col-form-label">Response Weekend Office</label>
                    <div class="col-sm-4">
                        <input type="number" name="response_weekend_o" id="response_weekend_o" class="form-control" placeholder="Minutes..." min="0" value="<?= $response_weekend_o; ?>">
                    </div>
                    <label for="nama" class="col-sm-2 col-form-label">Response Weekend After Office</label>
                    <div class="col-sm-4">
                        <input type="number" name="response_weekend_ao" id="response_weekend_ao" class="form-control" placeholder="Minutes..." min="0" value="<?= $response_weekend_ao; ?>">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="nama" class="col-sm-2 col-form-label">Resolve Weekend Office</label>
                    <div class="col-sm-4">
                        <input type="number" name="resolve_weekend_o" id="resolve_weekend_o" class="form-control" placeholder="Hours..." min="0" value="<?= $resolve_weekend_o; ?>">
                    </div>
                    <label for="nama" class="col-sm-2 col-form-label">Resolve Weekend After Office</label>
                    <div class="col-sm-4">
                        <input type="number" name="resolve_weekend_ao" id="resolve_weekend_ao" class="form-control" placeholder="Hours..." min="0" value="<?= $resolve_weekend_ao; ?>">
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
                        tampildataseverity();
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
