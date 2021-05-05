{header}

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper" class="">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column ">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid mt-5">

                    <!-- 404 Error Text -->
                    <div class="text-center">
                        <div class="error mx-auto" data-text="403">403</div>
                        <p class="lead text-gray-800 mb-5">Access Forbidden !</p>
                        <p class="text-gray-500 mb-0">It looks like you found a glitch in the matrix...</p>
                        <?php if ($this->session->userdata('session_id') == '1') : ?>
                            <a href="<?= base_url('tiket') ?>">&larr; Back to Ticket</a>
                        <?php else : ?>
                            <a href="<?= base_url('auth') ?>">&larr; Back to Login Page</a>
                        <?php endif; ?>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    {script}

</body>

</html>