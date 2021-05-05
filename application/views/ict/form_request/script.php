<!-- Bootstrap core JavaScript-->
<script src="<?= base_url('assets/');  ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?= base_url('assets/');  ?>vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?= base_url('assets/');  ?>js/sb-admin-2.min.js"></script>

<script>
  function tampildatadepartment() {
    $('#centangSemua').prop('checked', false);

    table = $('#data').DataTable({
      responsive: true,
      "destroy": true,
      "processing": true,
      "serverSide": true,
      "order": [],

      "ajax": {
        "url": "<?= site_url('ict/getDataFormRequest') ?>",
        "type": "POST"
      },

      "columnDefs": [{
        "targets": [0],
        "orderable": false,
        "width": 5
      }],
    });
  }

  function edit(id) {
    $.ajax({
      type: 'post',
      url: "<?= site_url('master/formeditdepartment'); ?>",
      data: {
        id: id
      },
      dataType: "json",
      success: function(response) {
        if (response.sukses) {
          $('.viewmodal').html(response.sukses).show();
          $('#modaledit').on('shown.bs.modal', function(e) {
            $('#nama').focus();
          })
          $('#modaledit').modal('show');
        }
      }
    });
  }

  function hapus(id, name) {
    Swal.fire({
      title: 'Delete',
      text: `Are you sure delete department : ${name} ?`,
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
          url: "<?= site_url('master/hapusdepartment'); ?>",
          data: {
            id: id
          },
          dataType: "json",
          success: function(response) {
            if (response.sukses) {
              Swal.fire({
                icon: 'success',
                title: 'Success',
                text: response.sukses,
              });
              tampildatadepartment();
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

    tampildatadepartment();

    $('.formhapus').submit(function(e) {
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
          title: 'Delete Data',
          text: `There's ${jmldata.length} data department will be delete ?`,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, Delete',
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
                  tampildatadepartment();
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
        url: "<?= site_url('master/formtambahdepartment'); ?>",
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