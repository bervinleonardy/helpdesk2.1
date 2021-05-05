<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-xl col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xl font-weight-bold text-success text-uppercase mb-1">
                                <?= $title; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Category Table</h6>
                </div>
                <div class="card-body">

                    <?= form_open('administrator/deletemultiplecategory', ['class' => 'formhapus']); ?>
                    <button type="button" class="btn btn-primary mb-3" id="tomboltambah">
                        <i class="fa fa-plus-circle"> Add Category</i>
                    </button>

                    <button type="submit" class="btn btn-danger mb-3" id="tombolHapusBanyak">
                        <i class="fas fa-trash-alt"> Multiple Delete</i>
                    </button>

                    <div class="table-responsive">
                        <table class="table table-bordered display nowrap" id="dataCategory" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" name="centangSemua" id="centangSemua">
                                    </th>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Created Date</th>
                                    <th>Created By</th>
                                    <th>Active</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                    <?= form_close(); ?>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- /.container-fluid -->
