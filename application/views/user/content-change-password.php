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
        <div class="col-lg-6">
            <?= $this->session->flashdata('message'); ?>
            <form action="<?= base_url('user/changepassword'); ?>" method="post">
                <div class="form-group">
                    <label for="currentPassword">Current Password</label>
                    <input type="password" class="form-control" id="currentPassword" name="currentPassword">
                    <?= form_error('currentPassword', '<small class="text-danger">', '</small>'); ?>
                </div>
                <div class="form-group">
                    <label for="newPassword1">New Password</label>
                    <input type="password" class="form-control" id="newPassword1" name="newPassword1">
                    <?= form_error('newPassword1', '<small class="text-danger">', '</small>'); ?>
                </div>
                <div class="form-group">
                    <label for="newPassword2">Confirm Password</label>
                    <input type="password" class="form-control" id="newPassword2" name="newPassword2">
                    <?= form_error('newPassword2', '<small class="text-danger">', '</small>'); ?>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- /.container-fluid -->