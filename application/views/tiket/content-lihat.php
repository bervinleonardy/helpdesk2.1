<div class="row">
    <!-- Column -->
    <div class="col-md-6 col-lg-6 col-xl-4">
        <div class="card m-b-30">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="col-3 align-self-center">
                        <div class="round">
                            <i class="mdi mdi-ticket "></i>
                        </div>
                    </div>
                    <div class="col-6 text-center align-self-center">
                        <div class="m-l-10 ">
                            <h6 class="mt-0 round-inner"><?= $tiket; ?></h6>
                            <p class="mb-0 text-muted">Num. Ticket</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Column -->
    <!-- Column -->
    <div class="col-md-6 col-lg-6 col-xl-4">
        <div class="card m-b-30">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="col-3 align-self-center">
                        <div class="round ">
                            <i class="mdi mdi-calendar"></i>
                        </div>
                    </div>
                    <div class="col-6 align-self-center text-center">
                        <div class="m-l-10 ">
                            <h6 class="mt-0 round-inner"><?= date('d F Y', strtotime($created_date)); ?></h6>
                            <p class="mb-0 text-muted">Created Date</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Column -->
    <!-- End Column -->
    <div class="col-md-6 col-lg-6 col-xl-4">
        <div class="card m-b-30">
            <div class="card-body">
                <div class="d-flex flex-row">
                    <div class="col-3 align-self-center">
                        <div class="round">
                            <i class="mdi mdi-emoticon"></i>
                        </div>
                    </div>
                    <div class="col-6 align-self-center text-center">
                        <div class="m-l-10">
                            <h5 class="mt-0 round-inner"><?= $status; ?></h5>
                            <p class="mb-0 text-muted">Status</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Column -->
</div>
<!-- Collapse -->
<div class="row">
    <button type="button" class="btn btn-info mb-4 ml-3" onclick="return back();">
        <i class="mdi mdi-keyboard-backspace"> Back to List Ticket</i>
    </button>
    <div class="col-md-12 col-lg-12 col-xl-12">
        <div class="card m-b-30">
            <div class="card-body">

                <!-- Nav tabs -->
                <ul class="nav nav-pills nav-justified" role="tablist">
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link active" data-toggle="tab" href="#detail-1" role="tab">Detail</a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link" data-toggle="tab" href="#timeline-1" role="tab">Timeline</a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane active p-3" id="detail-1" role="tabpanel">
                        <div class="card">
                            <div class="card-body">
                                <blockquote class="card-bodyquote">
                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <h6 class="text-muted fw-400">Severity</h6>
                                            <input type="text" id="severity" name="severity" class="form-control mb-3" style="width: 100%; height:36px;" value="<?= $severity; ?>" readonly>
                                        </div>
                                        <div class="col-md-10">
                                            <h6 class="text-muted fw-400">Progress</h6>
                                            <div class="progress" style="height: 36px;">
                                                <div class="progress-bar progress-bar-striped bg-success progress-bar-animated" role="progressbar" aria-valuenow="<?= $progress; ?>" aria-valuemin="0" aria-valuemax="100" style="width: <?= $progress; ?>%; height:36px;"><?= $progress; ?>%</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-2">
                                            <h6 class="text-muted fw-400">Category</h6>
                                            <input type="text" id="category" name="category" class="form-control mb-3" style="width: 100%; height:36px;" value="<?= $category; ?>" readonly>
                                        </div>
                                        <div class="col-md-4">
                                            <h6 class="text-muted fw-400">Subcategory</h6>
                                            <input type="text" id="subcategory" name="subcategory" class="form-control mb-3" style="width: 100%; height:36px;" value="<?= $subcategory; ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="text-muted fw-400">Case</h6>
                                            <input type="text" id="case" name="case" class="form-control mb-3" style="width: 100%; height:36px;" value="<?= $case; ?>" readonly>
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
                                        <div class="col-md-8">
                                            <h6 class="text-muted fw-400">Subject </h6>
                                            <input class="form-control" type="text" id="subject" name="subject" value="<?= $subject; ?>" readonly>
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
                                </blockquote>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane p-3" id="timeline-1" role="tabpanel">
                        <p class="font-14 mb-0">
                        <div class="row">
                            <div class="col-md-12 col-xl-12">
                                <div class="card m-b-30">
                                    <div class="card-body">
                                        <div class="main-timeline mt-3">
                                            <?php
                                            $ci = get_instance();
                                            $timeline = $ci->timeline($tiket, $creator_id);
                                            foreach ($timeline->result_array() as $row) :
                                            ?>
                                                <div class="timeline">
                                                    <span class="timeline-icon"></span>
                                                    <span class="year"><?= date('d F Y H:i:s', strtotime($row['tanggal'])); ?></span>
                                                    <div class="timeline-content">
                                                        <h3 class="title"><?= $row['nama']; ?></h3>
                                                        <span class="post"><?= $row['status']; ?></span>
                                                        <p class="description">
                                                            <textarea id="description" name="description" class="summernote"><?= $row['description'] ?></textarea>
                                                        </p>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end row-->
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!--end row-->