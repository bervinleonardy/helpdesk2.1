<!-- Modal -->
<div class="modal fade" id="modaltambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modaltambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modaltambahLabel">Add Severity</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('superadmin/simpandataseverity', ['class' => 'formsimpan']); ?>
            <div class="pesan" style="display: none;"></div>
            <div class="modal-body">
                <div class="form-group row">
                    <label for="nama" class="col-sm-2 col-form-label">Severity</label>
                    <div class="col-sm-4">
                        <input type="text" name="nama" id="nama" class="form-control" placeholder="Severity...">
                    </div>
                </div>

                <!-- Divider -->
                <hr class="sidebar-divider">

                <div class="form-group row">
                    <label for="nama" class="col-sm-2 col-form-label">Response Weekday Office</label>
                    <div class="col-sm-4">
                        <input type="number" name="response_weekday_o" id="response_weekday_o" class="form-control" placeholder="Minutes..." min="0">
                    </div>
                    <label for="nama" class="col-sm-2 col-form-label">Response Weekday After Office</label>
                    <div class="col-sm-4">
                        <input type="number" name="response_weekday_ao" id="response_weekday_ao" class="form-control" placeholder="Minutes..." min="0">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nama" class="col-sm-2 col-form-label">Resolve Weekday Office</label>
                    <div class="col-sm-4">
                        <input type="number" name="resolve_weekday_o" id="resolve_weekday_o" class="form-control" placeholder="Hours..." min="0">
                    </div>
                    <label for="nama" class="col-sm-2 col-form-label">Resolve Weekday After Office</label>
                    <div class="col-sm-4">
                        <input type="number" name="resolve_weekday_ao" id="resolve_weekday_ao" class="form-control" placeholder="Hours..." min="0">
                    </div>
                </div>

                <!-- Divider -->
                <hr class="sidebar-divider">

                <div class="form-group row">
                    <label for="nama" class="col-sm-2 col-form-label">Response Weekend Office</label>
                    <div class="col-sm-4">
                        <input type="number" name="response_weekend_o" id="response_weekend_o" class="form-control" placeholder="Minutes..." min="0">
                    </div>
                    <label for="nama" class="col-sm-2 col-form-label">Response Weekend After Office</label>
                    <div class="col-sm-4">
                        <input type="number" name="response_weekend_ao" id="response_weekend_ao" class="form-control" placeholder="Minutes..." min="0">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="nama" class="col-sm-2 col-form-label">Resolve Weekend Office</label>
                    <div class="col-sm-4">
                        <input type="number" name="resolve_weekend_o" id="resolve_weekend_o" class="form-control" placeholder="Hours..." min="0">
                    </div>
                    <label for="nama" class="col-sm-2 col-form-label">Resolve Weekend After Office</label>
                    <div class="col-sm-4">
                        <input type="number" name="resolve_weekend_ao" id="resolve_weekend_ao" class="form-control" placeholder="Hours..." min="0">
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
        $('.formsimpan').submit(function(e) {
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
                        $('#modaltambah').modal('hide');
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
