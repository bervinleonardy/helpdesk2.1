<!-- Bootstrap core JavaScript-->
<script src="<?= base_url('assets/');  ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?= base_url('assets/');  ?>vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Select 2 -->
<script src="<?= base_url('assets/annexadmin'); ?>/plugins/select2/select2.min.js" type="text/javascript"></script>

<!--Summernote js-->
<script src="<?= base_url('assets/annexadmin'); ?>/plugins/summernote/summernote-bs4.min.js"></script>

<!--Plugin JavaScript file-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js"></script>

<!-- Include library Moment JS -->
<script src="<?= base_url('assets/datepicker'); ?>/libraries/moment/moment.min.js"></script>

<!-- Include library Datepicker Gijgo -->
<script src="<?= base_url('assets/datepicker'); ?>/libraries/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?= base_url('assets/');  ?>js/sb-admin-2.min.js"></script>

<script>
  function tampildata() {
    table = $('#data').DataTable({
      responsive: true,
      "destroy": true,
      "processing": true,
      "serverSide": true,
      "fixedColumns": true,
      "order": [],

      "ajax": {
        "url": "<?= site_url('karyawan/getData') ?>",
        "type": "POST"
      },

      "columnDefs": [{
        "targets": [0],
        "orderable": false,
        "width": 5
      }],
    });
  }

  function formrespon(id) {
    $.ajax({
      type: 'post',
      url: "<?= site_url('karyawan/formrespon'); ?>",
      data: {
        id: id
      },
      dataType: "json",
      success: function(response) {
        if (response.sukses) {
          $('.viewmodal').html(response.sukses).show();
          $('#modalrespon').modal('show');
        }
      }
    });
  }

  function formdevelop(id) {
    $.ajax({
      type: 'post',
      url: "<?= site_url('karyawan/formdevelop'); ?>",
      data: {
        id: id
      },
      dataType: "json",
      success: function(response) {
        if (response.sukses) {
          $('.viewmodal').html(response.sukses).show();
          $('#modaldevelop').modal('show');
        }
      }
    });
  }

  function lihat(id) {
    $.ajax({
      type: 'post',
      url: "<?= site_url('karyawan/lihat'); ?>",
      data: {
        id: id
      },
      dataType: "json",
      success: function(response) {
        if (response.sukses) {
          $('.viewmodal').html(response.sukses).show();
          $('#modallihat').modal('show');
        }
      }
    });
  }

  $(document).ready(function() {

    tampildata();
  });
</script>