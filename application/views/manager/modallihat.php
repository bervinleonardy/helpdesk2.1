<!-- Modal -->
<div class="modal fade" id="modallihat" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modallihatLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modallihatLabel">Lihat Ticket</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="pesan" style="display: none;"></div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-1 col-form-label">Ticket :</label>
                    <label class="col-sm-2 col-form-label"><?= $tiket; ?></label>
                    <label class="col-sm-2 col-form-label">Created Date :</label>
                    <label class="col-sm-2 col-form-label"><?= date('d F Y', strtotime($created_date)); ?></label>
                    <label class="col-sm-1 col-form-label">Severity :</label>
                    <label class="col-sm-2 col-form-label"><?= $severity; ?></label>
                </div>
                <div class="form-group row">
                    <div class="col-md-2">
                        <h6 class="text-muted fw-400">Category :</h6>
                        <input type="text" name="category" id="category" class="form-control" value="<?= $category; ?>" readonly>
                    </div>
                    <div class="col-md-4">
                        <h6 class="text-muted fw-400">Subcategory :</h6>
                        <input type="text" name="subcategory" id="subcategory" class="form-control" value="<?= $subcategory; ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted fw-400">Case :</h6>
                        <input type="text" name="case" id="case" class="form-control" value="<?= $case; ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <h6 class="text-muted fw-400">Date set :</h6>
                        <div class="input-group">
                            <input type="text" id="startDate" name="startDate" class="form-control startdate datetimepicker-input" value="<?= date('d F Y', strtotime($startDate)); ?>" readonly />
                            <div class="input-group-append">
                                <span class="input-group-text">s/d</span>
                            </div>
                            <input type="text" id="endDate" name="endDate" class="form-control enddate datetimepicker-input" value="<?= date('d F Y', strtotime($endDate)); ?>" readonly />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <h6 class="text-muted fw-400">From :</h6>
                        <input type="text" name="creator" id="creator" class="form-control" value="<?= $creator; ?>" readonly>
                    </div>
                    <div class="col-sm-4">
                        <h6 class="text-muted fw-400">To :</h6>
                        <input type="text" name="karyawan" id="karyawan" class="form-control" value="<?= $karyawan; ?>" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <h6 class="text-muted fw-400">Subject :</h6>
                        <input type="text" name="subject" id="subject" class="form-control" value="<?= $subject; ?>" readonly>
                    </div>
                </div>
                <!-- Divider -->
                <hr class="sidebar-divider d-none d-md-block">
                <div class="form-group row">
                    <div class="col-md-12">
                        <h6 class="text-muted fw-400">Description :</h6>
                        <textarea id="keterangan" name="keterangan" class="summernote"><?= $keterangan; ?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <h6 class="text-muted fw-400">Description Employee :</h6>
                        <textarea id="keteranganEmployee" name="keteranganEmployee" class="summernote"><?= $keteranganEmployee; ?></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <h6 class="text-muted fw-400">Description Manager :</h6>
                        <textarea id="keteranganManager" name="keteranganManager" class="summernote"><?= $keteranganManager; ?></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            toolbar: [
                ['view', ['fullscreen']],
            ],
            height: ($(window).height() - 600)

        }).next().
        find(".note-editable").
        attr("contenteditable", false).
        css("background-color", "#FFF");

    });
</script>