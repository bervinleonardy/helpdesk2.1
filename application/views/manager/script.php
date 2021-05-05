<!-- Bootstrap core JavaScript-->
<script src="<?= base_url('assets/');  ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?= base_url('assets/');  ?>vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Select 2 -->
<script src="<?= base_url('assets/annexadmin'); ?>/plugins/select2/select2.min.js" type="text/javascript"></script>

<!--Summernote js-->
<script src="<?= base_url('assets/annexadmin'); ?>/plugins/summernote/summernote-bs4.min.js"></script>

<!-- Include library Moment JS -->
<script src="<?= base_url('assets/datepicker'); ?>/libraries/moment/moment.min.js"></script>

<!-- Include library Datepicker Gijgo -->
<script src="<?= base_url('assets/datepicker'); ?>/libraries/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

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
      "fixedColumns": true,
      "order": [],

      "ajax": {
        "url": "<?= site_url('manager/getData') ?>",
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
      url: "<?= site_url('manager/formrespon'); ?>",
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

  function formtransfer(id) {
    $.ajax({
      type: 'post',
      url: "<?= site_url('manager/formtransfer'); ?>",
      data: {
        id: id
      },
      dataType: "json",
      success: function(response) {
        if (response.sukses) {
          $('.viewmodal').html(response.sukses).show();
          $('#modaltransfer').modal('show');
        }
      }
    });
  }

  function lihat(id) {
    $.ajax({
      type: 'post',
      url: "<?= site_url('manager/lihat'); ?>",
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

    $('#centangSemua').click(function(e) {
      if ($(this).is(":checked")) {
        $('.centangId').prop('checked', true);
      } else {
        $('.centangId').prop('checked', false);
      }
    });

    tampildata();

    $('.formclose').submit(function(e) {
      e.preventDefault();

      let jmldata = $('.centangId:checked');

      if (jmldata.length === 0) {
        Swal.fire({
          icon: 'warning',
          title: 'Warning',
          text: 'Sorry, item cannot be deleted, please check an item !'
        })
      } else {
        Swal.fire({
          title: 'Closed Ticket',
          text: `There's ${jmldata.length} data will be closed ?`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Closed',
          cancelButtonText: 'No'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              type: "post",
              url: $(this).attr('action'),
              data: $(this).serialize(),
              dataType: "json",
              success: function(response) {
                if (response.sukses) {
                  Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.sukses
                  })
                  tampildata();
                }
              },
              error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" +
                  thrownError);
              }
            });
          }
        })
      }
      return false;
    });

    $('#tomboltambah').click(function(e) {
      $.ajax({
        url: "<?= site_url('manager/formtambah'); ?>",
        dataType: "json",
        success: function(response) {
          if (response.sukses) {
            $('.viewmodal').html(response.sukses).show();
            $('#modaltambah').on('shown.bs.modal', function(e) {
              $('#nama').focus();
            })
            $('#modaltambah').modal('show');
          }
        }
      });
    });

  });
</script>