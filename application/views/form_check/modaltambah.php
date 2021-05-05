<!-- Modal -->
<div class="modal fade" id="modaltambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modaltambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modaltambahLabel">Add Form Check</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open('FormCheck/simpandataformcheck', ['class' => 'formsimpan']); ?>
            <div class="pesan" style="display: none;"></div>
            <div class="modal-body">
				<div class="form-group row">
                    <label for="site" class="col-sm-1 col-form-label">Site</label>
                    <div class="col-sm-5">
                        <select id="site" name="site" class="select2 form-control mb-3 custom-select" style="width: 100%; height:36px;">
                            <option value="">Select</option>
                            <?php foreach ($site as $row) : ?>
                                <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Superior #1</label>
                    <div class="col-sm-4 input-group">
                        <select id="superior1" name="superior1" class="select2 form-control mb-3 custom-select" style="width: 100%; height:36px;">
                            <option value=''>Choose...</option>
                            <?php foreach ($superior as $row) : ?>
                                <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <label class="col-sm-2 col-form-label">Superior #2</label>
                    <div class="col-sm-4 input-group">
                        <select id="superior2" name="superior2" class="select2 form-control mb-3 custom-select" style="width: 100%; height:36px;">
                            <option value=''>Choose...</option>
                            <?php foreach ($superior as $row) : ?>
                                <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Staff #1</label>
                    <div class="col-sm-4 input-group">
                        <select id="staff1" name="staff1" class="select2 form-control mb-3 custom-select" style="width: 100%; height:36px;">
                            <option value=''>Choose...</option>
                            <?php foreach ($staff as $row) : ?>
                                <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <label class="col-sm-2 col-form-label">Staff #2</label>
                    <div class="col-sm-4 input-group">
                        <select id="staff2" name="staff2" class="select2 form-control mb-3 custom-select" style="width: 100%; height:36px;">
                            <option value=''>Choose...</option>
                            <?php foreach ($staff as $row) : ?>
                                <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
				<div class="form-group row">
                    <div class="col-md-8">
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
                            title: 'Success',
                            text: response.sukses
                        });
                        tampildataformcheck();
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
</script>
