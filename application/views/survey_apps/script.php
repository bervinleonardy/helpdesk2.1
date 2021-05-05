<!-- Bootstrap core JavaScript-->
<script src="<?= base_url('assets/');  ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?= base_url('assets/');  ?>vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Select 2 -->
<script src="<?= base_url('assets/annexadmin'); ?>/plugins/select2/select2.min.js" type="text/javascript"></script>

<!-- Custom scripts for all pages-->
<script src="<?= base_url('assets/');  ?>js/sb-admin-2.min.js"></script>

<script>
  function tampildata() {
    $('#centangSemua').prop('checked', false);

    table = $('#data').DataTable({
      responsive: true,
      "destroy": true,
      "processing": true,
      "serverSide": true,
      "order": [],

      "ajax": {
        "url": "<?= site_url('survey/getData') ?>",
        "type": "POST"
      },

      "columnDefs": [{
        "targets": [0],
        "orderable": false,
        "width": 5
      }],
    });
  }

  function hapus(id, nik, created_date, name) {
    Swal.fire({
      title: 'Delete',
      text: `Are you sure delete responden : ${name} ?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, Delete',
      cancelButtonText: 'No'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: 'post',
          url: "<?= site_url('survey/hapus'); ?>",
          data: {
            id: id,
						nik: nik,
						created_date: created_date,
          },
          dataType: "json",
          success: function(response) {
            if (response.sukses) {
              Swal.fire({
                icon: 'success',
                title: 'Confirmation',
                text: response.sukses,
              });
              tampildata();
            }
          }
        });
      }
    })
  }

  $(document).ready(function() {

    $('#centangSemua').click(function(e) {
      if ($(this).is(":checked")) {
        $('.centangId').prop('checked', true);
      } else {
        $('.centangId').prop('checked', false);
      }
    });

    tampildata();
  });
</script>
