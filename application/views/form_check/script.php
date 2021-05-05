<!-- Bootstrap core JavaScript-->
<script src="<?= base_url('assets/');  ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="<?= base_url('assets/');  ?>vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Include library Moment JS -->
<script src="<?= base_url('assets/datepicker'); ?>/libraries/moment/moment.min.js"></script>

<!-- Include library Datepicker Gijgo -->
<script src="<?= base_url('assets/datepicker'); ?>/libraries/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="<?= base_url('assets/');  ?>js/sb-admin-2.min.js"></script>

<script>
  function tampildataformcheck() {
    $('#centangSemua').prop('checked', false);

    table = $('#dataFormcheck').DataTable({
      responsive: true,
      "destroy": true,
      "processing": true,
      "serverSide": true,
      "order": [],

      "ajax": {
        "url": "<?= site_url('FormCheck/getDataFormCheck') ?>",
        "type": "POST"
      },

      "columnDefs": [{
        "targets": [0],
        "orderable": false,
        "width": 5
      }],
    });
  }

	function check(id) {
    $.ajax({
      type: 'post',
      url: "<?= site_url('FormCheck/formchecklist'); ?>",
      data: {
        id: id
      },
      dataType: "json",
      success: function(response) {
        if (response.sukses) {
          $('.viewmodal').html(response.sukses).show();
          $('#modalstaff').modal('show');
        }
      }
    });
  }

	function recheck(id) {
    $.ajax({
      type: 'post',
      url: "<?= site_url('FormCheck/formrechecklist'); ?>",
      data: {
        id: id
      },
      dataType: "json",
      success: function(response) {
        if (response.sukses) {
          $('.viewmodal').html(response.sukses).show();
          $('#modalrecheck').modal('show');
        }
      }
    });
  }

	function validate(id) {
    $.ajax({
      type: 'post',
      url: "<?= site_url('FormCheck/formcheckvalidate'); ?>",
      data: {
        id: id
      },
      dataType: "json",
      success: function(response) {
        if (response.sukses) {
          $('.viewmodal').html(response.sukses).show();
          $('#modalsuperior').modal('show');
        }
      }
    });
  }

	function done(id, no) {
    Swal.fire({
      title: 'Done',
      text: `Are you sure want to done this form : ${no} ?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes',
      cancelButtonText: 'No'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: 'post',
          url: "<?= site_url('FormCheck/doneformcheck'); ?>",
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
              tampildataformcheck();
            }
          }
        });
      }
    })
  }

  function pdf(url) {
		var a = document.createElement('a');
		a.target="_blank";
		a.href=url;
		a.click();
  }

  function hapus(id, no) {
    Swal.fire({
      title: 'Delete',
      text: `Are you sure delete Form Check : ${no} ?`,
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
          url: "<?= site_url('FormCheck/hapusformcheck'); ?>",
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
              tampildataformcheck();
            }
          }
        });
      }
    })
  }

  $(document).ready(function() {

		if ($("#dataFormcheck").data('role') == 2) {
			$("#tomboltambah").show();
			$("#tombolHapusBanyak").show();
		}

    $('#centangSemua').click(function(e) {
      if ($(this).is(":checked")) {
        $('.centangId').prop('checked', true);
      } else {
        $('.centangId').prop('checked', false);
      }
    });

    tampildataformcheck();

    $('.formhapus').submit(function(e) {
      e.preventDefault();

      let jmldata = $('.centangId:checked');
			var roleId  = $('#role').val();

			if(roleId != 2) {
				Swal.fire({
						icon: 'warning',
						title: 'Warning',
						text: 'Just Manager allowed to click this button !'
				})
			} else {
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
											title: 'Success',
											text: response.sukses
										})
										tampildataformcheck();
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
			}


      return false;
    });

    $('#tomboltambah').click(function(e) {
      $.ajax({
        url: "<?= site_url('FormCheck/formtambahformcheck'); ?>",
        dataType: "json",
        success: function(response) {
          if (response.sukses) {
            $('.viewmodal').html(response.sukses).show();
            $('#modaltambah').modal('show');
          }

					if (response.gagal) {
						Swal.fire({
              icon: 'warning',
              title: 'Warning',
              text: response.gagal
            });
          }
        }
      });
    });
  });
</script>
