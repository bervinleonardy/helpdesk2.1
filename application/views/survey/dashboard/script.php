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
		$(".startdate").datetimepicker({
			format: "DD-MM-YYYY",
			useCurrent: false,
		})

		$(".startdate").on("change.datetimepicker", function(e) {
			$(".enddate").val("")
			$(".enddate").datetimepicker('minDate', e.date);
		})

		$(".enddate").datetimepicker({
			format: "DD-MM-YYYY",
			useCurrent: false
		})

		$(document).ready(function() {
			// Set new default font family and font color to mimic Bootstrap's default styling
			Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
			Chart.defaults.global.defaultFontColor = '#858796';

			// Pie Chart Example
			var ctx = document.getElementById("myPieChart");
			var namaApps = $('#namaApps').val();
			var listColor = $('#listColor').val();
			var listHColor = $('#listHColor').val();
			var listQty = $('#listQty').val().replace(new RegExp('"', 'g'),"");
			alert(listQty);
			var myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ["Helpdesk 2.1","Hospital Information System BOP","HCBS","Hospital Information System WTC2","Hospital Information System Rekind","Hospital Information System AMNT","Dashboard","Birthday Reminder","Costing","Hospital Information System CSTS","Risk Assessment Covid-19"],
                datasets: [{
                    data: [2, 2, 2, 2, 2, 2, 2, 2, 1, 2, 2],
                    backgroundColor: ['#3498db ', '#7f8c8d', '#7f8c8d', '#7f8c8d', '#7f8c8d', '#7f8c8d', '#7f8c8d', '#7f8c8d', '#7f8c8d', '#7f8c8d', '#7f8c8d',],
                    hoverBackgroundColor: ['#aed6f1', '#aeb6bf', '#aeb6bf', '#aeb6bf', '#aeb6bf', '#aeb6bf', '#aeb6bf', '#aeb6bf', '#aeb6bf', '#aeb6bf', '#aeb6bf', ],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 75,
            },
        });
      });


      function search() {
        var start = $('#startDate').val();
        var end = $('#endDate').val();
        location.href = ('<?= site_url('survey/dashboard/') ?>' + start + '/' + end);
			
      };
    </script>
