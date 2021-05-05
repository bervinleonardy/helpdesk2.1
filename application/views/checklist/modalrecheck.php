<!-- Modal -->
<div class="modal fade" id="modalrecheck" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modalrecheckLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalrecheckLabel">Form Re-Checklist Staff</h5>
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
                                <th scope="col">Comment Superior</th>
                                <th scope="col">Status</th>
                                <th scope="col">Remarks</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            <?php foreach ($items as $item) : ?>
                                <tr>
                                    <th scope="row"><?= $i; ?></th>
                                    <td><?= $item['category']; ?></td>
                                    <td><?= $item['description']; ?></td>
									<td>
										<?= $item['comment']; ?>
									</td>
									<td>
										<?= checkItemStatus($id, $item['item_id']); ?>
									</td>
									<td>
										<textarea class="form-control" name="remark<?= $item['item_id']; ?>" id="remark<?= $item['item_id']; ?>" data-remark="<?= $item['category_id']; ?>" cols="30" rows="1"><?= checkFormItemRemark($id, $item['site_id'], $item['category_id'], $item['item_id']); ?></textarea>
									</td>
									<td>
										<div class="form-check">
                                            <input class="form-check-input" id="checkItem<?= $item['id']; ?>" type="checkbox" <?= reCheckItem($id, $item['site_id'], $item['category_id'], $item['item_id']); ?> data-form="<?= $id; ?>" data-site="<?= $item['site_id']; ?>" data-category="<?= $item['category_id']; ?>" data-item="<?= $item['item_id']; ?>" data-value=1>
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
                <button type="button" id="btnSubmit" class="btn btn-success" onclick="submit('<?= $id; ?>')" value="14">Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
    $('.form-check-input').on('click', function() {

		const formId = $(this).data('form');
		const itemId = $(this).data('item');
		const siteId = $(this).data('site');
        const categoryId = $(this).data('category');
        const remark = $('#remark'+itemId).val();
		const value = $(this).data('value');

		$.ajax({
			url: "<?= base_url('FormCheck/change_radio'); ?>",
			type: 'post',
			data: {
				formId: formId,
				itemId: itemId,
				siteId: siteId,
				categoryId: categoryId,
				remark: remark,
				value: value
			}
		});
	});

	function submit(id) {
		let totalCheck = $('.form-check-input');
		let jmldata = $('.form-check-input:checked');
		if (jmldata.length === 0) {
			Swal.fire({
				icon: 'warning',
				title: 'Warning',
				text: 'Sorry, Form Check List cannot submitted, please check one item !'
			})
		} else {
			Swal.fire({
				title: 'Delete Data',
				text: `There's ${jmldata.length} from ${totalCheck.length} items checklist will be submit ?`,
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
						$('#modalrecheck').modal('hide');
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

</script>
