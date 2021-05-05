<!-- Modal -->
<div class="modal fade" id="modalsuperior" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalsuperiorLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalsuperiorLabel">Form Checklist Superior</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
			<div class="modal-body">
                <div class="row">
                    <table class="table table-striped table-hover"  >
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Category</th>
                                <th scope="col">Description</th>
                                <th scope="col">Remarks</th>
                                <th scope="col">Status</th>
                                <th scope="col">Comment</th>
                                <th scope="col">Revision</th>
                                <th scope="col">Validate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($items as $item) : ?>
                                <tr>
                                    <th scope="row"><?= $i; ?></th>
                                    <td><?= $item['category']; ?></td>
                                    <td><?= $item['description']; ?></td>
									<td><?= $item['remark']; ?></td>
									<td>
										<?= checkItemStatus($id, $item['item_id']); ?>
									</td>
									<td>
										<textarea class="form-control" name="comment<?= $item['item_id']; ?>" id="comment<?= $item['item_id']; ?>" data-comment="<?= $item['category_id']; ?>" cols="30" rows="1"><?= checkFormItemComment($id, $item['site_id'], $item['category_id'], $item['item_id']); ?></textarea>
									</td>
									<td>
                                        <div class="form-check">
                                            <input class="form-rad-input-revision" id="radioItem<?= $item['item_id']; ?>" name="radio<?= $item['item_id']; ?>" type="radio" <?= revisionItem($id, $item['site_id'], $item['category_id'], $item['item_id']); ?> data-form="<?= $id; ?>" data-site="<?= $item['site_id']; ?>" data-category="<?= $item['category_id']; ?>" data-item="<?= $item['item_id']; ?>" data-value=2>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check">
                                            <input class="form-rad-input-validate" id="radioItem<?= $item['item_id']; ?>" name="radio<?= $item['item_id']; ?>" type="radio" <?= validateItem($id, $item['site_id'], $item['category_id'], $item['item_id']); ?> data-form="<?= $id; ?>" data-site="<?= $item['site_id']; ?>" data-category="<?= $item['category_id']; ?>" data-item="<?= $item['item_id']; ?>" data-value=1>
                                        </div>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btnRevision" class="btn btn-warning" onclick="revision('<?= $id; ?>')" value="17">Revision</button>
                <button type="button" id="btnSubmit" class="btn btn-primary" onclick="submit('<?= $id; ?>')" value="15">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('.form-rad-input-revision').on('click', function() {

		const formId = $(this).data('form');
		const itemId = $(this).data('item');
		const siteId = $(this).data('site');
        const categoryId = $(this).data('category');
        const comment = $('#comment'+itemId).val();
		const value = $(this).data('value');

		$.ajax({
			url: "<?= base_url('FormCheck/change_radio'); ?>",
			type: 'post',
			data: {
				formId: formId,
				itemId: itemId,
				siteId: siteId,
				categoryId: categoryId,
				comment: comment,
				value: value
			}
		});
	});

	$('.form-rad-input-validate').on('click', function() {

		const formId = $(this).data('form');
		const itemId = $(this).data('item');
		const siteId = $(this).data('site');
		const categoryId = $(this).data('category');
		const comment = $('#comment'+itemId).val();
		const value = $(this).data('value');

		$.ajax({
			url: "<?= base_url('FormCheck/change_radio'); ?>",
			type: 'post',
			data: {
				formId: formId,
				itemId: itemId,
				siteId: siteId,
				categoryId: categoryId,
				comment: comment,
				value: value
			}
		});
	});

	function revision(id) {
		var numberOfChecked = $('.form-rad-input-revision:radio:checked').length;

		if (numberOfChecked === 0) {
			Swal.fire({
				icon: 'warning',
				title: 'Warning',
				text: 'Sorry, cannot revision this form at least 1 checked radio !'
			})
		} else {
			Swal.fire({
				title: 'Confirmation',
				text: `There's ${numberOfChecked} checked items will be revision ?`,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#00cc00',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes',
				cancelButtonText: 'No'
			}).then((result) => {
			if (result.value) {
				var status = $('#btnRevision').val();
				$.ajax({
					type: "post",
					url: "<?= site_url('FormCheck/updateFormCheckList'); ?>",
					data: {
						id : id,
						status : status
					},
					dataType: "json",
					success: function(response) {
					if (response.sukses) {
						Swal.fire({
							icon: 'success',
							title: 'Berhasil',
							text: response.sukses
						})
						$('#modalsuperior').modal('hide');
						tampildataformcheck(); 
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
				}
				});
			}
			})
		}
		return false;
 	}

	function submit(id) {
		let totalRadio = $('.form-rad-input-validate');
		let jmldata = $('.form-rad-input-validate:checked');
		if (jmldata.length === totalRadio.length) {
			Swal.fire({
				title: 'Confirmation',
				text: `There's ${jmldata.length} from ${totalRadio.length} items checklist will be submit ?`,
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#00cc00',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes',
				cancelButtonText: 'No'
			}).then((result) => {
			if (result.value) {
				var status = $('#btnSubmit').val();
				$.ajax({
					type: "post",
					url: "<?= site_url('FormCheck/updateFormCheckList'); ?>",
					data: {
						id : id,
						status : status
					},
					dataType: "json",
					success: function(response) {
					if (response.sukses) {
						Swal.fire({
							icon: 'success',
							title: 'Success',
							text: response.sukses
						})
						$('#modalsuperior').modal('hide');
						tampildataformcheck(); 
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
				}
				});
			}
			})
		} else {
			Swal.fire({
				icon: 'warning',
				title: 'Warning',
				text: 'Sorry, if you want submit then must check all valdate items !'
			})
		}
		return false;
 	}
</script>
