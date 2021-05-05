{header}

<body>

    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner"></div>
        </div>
    </div>

    <!-- Navigation Bar-->
    <header id="topnav">
        {topbar}

        <!-- end topbar-main -->

        <!-- MENU Start -->
        {menu}

        <!-- end navbar-custom -->
    </header>
    <!-- End Navigation Bar-->


    <div class="wrapper">
        <div class="container-fluid">

            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="page-title-box">
                        <h4 class="page-title"><?= $title; ?></h4>
                    </div>
                </div>
            </div>

            {content}

            <!--end row-->

        </div> <!-- end container -->
    </div>
    <!-- end wrapper -->

    <!-- Footer -->
    {footer}
    <!-- End Footer -->

    {script}

</body>

</html>