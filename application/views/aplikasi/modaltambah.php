<!-- Modal -->
<div class="modal fade" id="modaltambah" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modaltambahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modaltambahLabel">Add Apps</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?= form_open_multipart('apps/simpandataapps', ['class' => 'formsimpan']); ?>
            <div class="pesan" style="display: none;"></div>
            <div class="modal-body">

                <div class="form-group row">
                    <label for="nama" class="col-sm-3 col-form-label">Application</label>
                    <div class="col-sm-4">
                        <input type="text" name="nama" id="nama" class="form-control">
                    </div>
                </div>
				
				<div class="form-group row">
                    <label for="made_by" class="col-sm-3 col-form-label">Made By</label>
                    <div class="col-sm-4">
                        <input type="text" name="madeBy" id="madeBy" class="form-control">
                    </div>
                </div>

				<div class="form-group row">
					<label for="made_on" class="col-sm-3 col-form-label">Made On</label>
                    <div class="col-sm-3">
                   		<input type="text" id="madeOn" name="madeOn" class="form-control startdate" data-toggle="datetimepicker" data-target=".startdate" />
					</div>
			  	</div>

				<div class="form-group row">
                    <label for="made_by" class="col-sm-3 col-form-label">Bg color (<i>Dashboard</i>)</label>
                    <div class="col-sm-4">
                        <input type="text" name="bgColor" id="bgColor" class="form-control">
                    </div>
                </div>

				<div class="form-group row">
                    <label for="made_by" class="col-sm-3 col-form-label">H Bg color (<i>Dashboard</i>)</label>
                    <div class="col-sm-4">
                        <input type="text" name="hBgColor" id="hBgColor" class="form-control">
                    </div>
                </div>

				<hr />
				
				<div class="form-group row">
                	<label for="nama" class="col-sm-2 col-form-label">Image #1</label>
					<div class="col-sm-10">
						<div class="row">
							<div class="col-sm-6">
								<div class="custom-file">
									<input type="file" class="custom-file-input" id="image1" name="image1">
									<label class="custom-file-label" for="image1">Choose file</label>
								</div>
							</div>
						</div>
					</div>
           		</div>

				<div class="form-group row">
                	<label for="nama" class="col-sm-2 col-form-label">Image #2</label>
					<div class="col-sm-10">
						<div class="row">
							<div class="col-sm-6">
								<div class="custom-file">
									<input type="file" class="custom-file-input" id="image2" name="image2">
									<label class="custom-file-label" for="image2">Choose file</label>
								</div>
							</div>
						</div>
					</div>
           		</div>

				<div class="form-group row">
                	<label for="nama" class="col-sm-2 col-form-label">Image #3</label>
					<div class="col-sm-10">
						<div class="row">
							<div class="col-sm-6">
								<div class="custom-file">
									<input type="file" class="custom-file-input" id="image3" name="image3">
									<label class="custom-file-label" for="image3">Choose file</label>
								</div>
							</div>
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

	$("#madeOn").datetimepicker({
		format: "DD-MM-YYYY",
		useCurrent: false
	})

	$('.custom-file-input').on('change', function() {
      let fileName = $(this).val().split('\\').pop();
      $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    $(document).ready(function() {
        $('.formsimpan').submit(function(e) {
			var data;
			data = new FormData();

    		data.append('nama', $('#nama').val());
    		data.append('madeBy', $('#madeBy').val());
    		data.append('madeOn', $('#madeOn').val());
    		data.append('bgColor', $('#bgColor').val());
    		data.append('hBgColor', $('#hBgColor').val());
    		data.append('image1', $('#image1')[0].files[0]);
    		data.append('image2', $('#image2')[0].files[0]);
    		data.append('image3', $('#image3')[0].files[0]);

            $.ajax({
                type: "POST",
                url: $(this).attr('action'),
                data: data,
				processData: false,
  				contentType: false,
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
                        tampildataapps();
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
