<!-- Modal -->
<div class="modal fade" id="modaltambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modaltambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modaltambahLabel">Add Item Checklist</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('ict/simpandatachecklist', ['class' => 'formsimpan']); ?>
            <div class="pesan" style="display: none;"></div>
            <div class="modal-body">
				<div class="form-group row">
                    <label for="site" class="col-sm-2 col-form-label">Site</label>
                    <div class="col-sm-6">
                        <select id="site" name="site" class="select2 form-control mb-3 custom-select" style="width: 100%; height:36px;">
                            <option value="">Select</option>
                            <?php foreach ($site as $row) : ?>
                                <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="category" class="col-sm-2 col-form-label">Category</label>
                    <div class="col-sm-4">
                        <select id="category" name="category" class="select2 form-control mb-3 custom-select" style="width: 100%; height:36px;">
                            <option value="">Select</option>
                            <?php foreach ($category as $row_category) : ?>
                                <option value="<?php echo $row_category->id; ?>"><?php echo $row_category->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="nama" class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-12">
						<textarea name="name" id="name" class="form-control" cols="30" rows="3"></textarea>
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
                            title: 'Success',
                            text: response.sukses
                        });
                        tampildatachecklist();
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
