<!-- Modal -->
<div class="modal fade" id="modallihat" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="modallihatLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modallihatLabel">Lihat Form Request</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="pesan" style="display: none;"></div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label for="tanggal" class="col-sm-3 col-form-label">Tanggal</label>
                        <div class="col-sm-4">
                            <input class="form-control" type="text" id="tanggal" name="tanggal" readonly value="<?= date('d F y'); ?>">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="department" class="col-sm-12 col-form-label">No. Proyek/Departemen</label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" id="department" name="department">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <label for="nama" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-7">
                            <input class="form-control" type="text" id="nama" name="nama" value="<?= $nama; ?>" readonly>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <label for="lokasi" class="col-sm-5 col-form-label">Lokasi (Site/Bld/FI)</label>
                        <div class="col-sm-6">
                            <input class="form-control" type="text" id="lokasi" name="lokasi">
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-4">
                        <h6 class="text-muted fw-400">Date set :</h6>
                        <div class="input-group">
                            <input type="text" id="startDate" name="startDate" class="form-control startdate datetimepicker-input" value="" readonly />
                            <div class="input-group-append">
                                <span class="input-group-text">s/d</span>
                            </div>
                            <input type="text" id="endDate" name="endDate" class="form-control enddate datetimepicker-input" value="" readonly />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <h6 class="text-muted fw-400">From :</h6>
                        <input type="text" name="creator" id="creator" class="form-control" value="" readonly>
                    </div>
                    <div class="col-sm-4">
                        <h6 class="text-muted fw-400">To :</h6>
                        <input type="text" name="karyawan" id="karyawan" class="form-control" value="" readonly>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-6">
                        <h6 class="text-muted fw-400">Subject :</h6>
                        <input type="text" name="subject" id="subject" class="form-control" value="" readonly>
                    </div>
                </div>
                <!-- Divider -->
                <hr class="sidebar-divider d-none d-md-block">
                <div class="form-group row">
                    <div class="col-md-12">
                        <h6 class="text-muted fw-400">Description :</h6>
                        <textarea id="keterangan" name="keterangan" class="summernote"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <h6 class="text-muted fw-400">Description Employee :</h6>
                        <textarea id="keteranganEmployee" name="keteranganEmployee" class="summernote"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <h6 class="text-muted fw-400">Description Manager :</h6>
                        <textarea id="keteranganManager" name="keteranganManager" class="summernote"></textarea>
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