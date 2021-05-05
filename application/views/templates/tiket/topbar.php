<div class="topbar-main">
    <div class="container-fluid">

        <!-- Logo container-->
        <div class="logo">
            <!-- Text Logo -->
            <!--<a href="index.html" class="logo">-->
            <!--Annex-->
            <!--</a>-->
            <!-- Image Logo -->
            <a href="<?= site_url('tiket'); ?>" class="logo">
                <img src="<?= base_url('assets/img'); ?>/logo-mp-sm1.png" alt="" height="53" class="logo-small">
                <img src="<?= base_url('assets/img'); ?>/logo-mp4.png" alt="" height="53" width="220" class="logo-large">
            </a>

        </div>
        <!-- End Logo container-->

        <div class="menu-extras topbar-custom">

            <ul class="list-inline float-right mb-0">
                <!-- User-->
                <li class="list-inline-item dropdown notification-list">
                    <a class="nav-link dropdown-toggle arrow-none waves-effect nav-user" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <?php
                        if ($user['image'] != '') {
                            $fotoProfile = $user['image'];
                        } else {
                            $fotoProfile = 'default.jpg';
                        }
                        ?>
                        <img src="<?= base_url('assets/img/profile/') . $fotoProfile;  ?>" alt="user" class="rounded-circle">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                        <!-- item-->
                        <div class="dropdown-item noti-title">
                            <h6 style="text-align: center;"><?= $username; ?></h6>
                        </div>
                        <img class="rounded-circle" alt="Saya" width="128" height="128" style="vertical-align:middle;margin:0px 20px" src="<?= base_url('assets/img/profile/thumbnail/') . $fotoProfile;  ?>" data-holder-rendered="true">
                        <button type="button" id="btnLogout" class="dropdown-item" href="#"><i class="mdi mdi-logout m-r-5 text-muted"></i> Logout</button>
                    </div>
                </li>
            </ul>
        </div>
        <!-- end menu-extras -->

        <div class="clearfix"></div>

    </div> <!-- end container -->
</div>