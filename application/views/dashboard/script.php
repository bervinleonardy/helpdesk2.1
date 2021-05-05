    <!-- Bootstrap core JavaScript-->
    <script src="<?= base_url('assets/');  ?>vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets/');  ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('assets/');  ?>vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('assets/');  ?>js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="<?= base_url('assets/');  ?>vendor/chart.js/Chart.min.js"></script>

    <!-- Include library Moment JS -->
    <script src="<?= base_url('assets/datepicker'); ?>/libraries/moment/moment.min.js"></script>

    <!-- Include library Datepicker Gijgo -->
    <script src="<?= base_url('assets/datepicker'); ?>/libraries/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

    <script>
      $(document).ready(function() {


      });

      $(".startdate").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false
      })

      $(".startdate").on("change.datetimepicker", function(e) {
        $(".enddate").val("")
        $(".enddate").datetimepicker('minDate', e.date);
      })

      $(".enddate").datetimepicker({
        format: "DD-MM-YYYY",
        useCurrent: false
      })

      function report() {
        var start = $('#startDate').val();
        var end = $('#endDate').val();
        location.href = ('<?php echo site_url('dashboard/excel/') ?>' + start + '/' + end);
      };
    </script>
