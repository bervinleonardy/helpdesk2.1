  <!-- Bootstrap core JavaScript-->
  <script src="<?= base_url('assets/');  ?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="<?= base_url('assets/');  ?>vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="<?= base_url('assets/');  ?>js/sb-admin-2.min.js"></script>

  <!-- Login Javascript -->
  <script src="<?= base_url('assets') ?>/js/login.js"></script>

  <script>
      $(document).ready(function() {

			Swal.fire({
				title: 'Survey Aplikasi ICT',
				text : 'Isi Yuk !',
				width: 500,
				type: 'warning',
				showDenyButton: true,
				showCancelButton: false,
				confirmButtonText: `Yes`,
				denyButtonText: `No`,
				backdrop: `
					rgba(0,0,123,0.4)
					url("<?= base_url('assets/img/survey/bohlam2.gif'); ?>")
					center top
					no-repeat
				`,
				
				}).then((result) => {
					if (result.isConfirmed) {
						window.open('<?= site_url('survey'); ?>', "_blank");
					} 
			})

			$('.formlogin').submit(function(e) {
				let timerInterval
				$.ajax({
					type: "POST",
					url: $(this).attr('action'),
					data: $(this).serialize(),
					dataType: "json",
					success: function(response) {
						if (response.error) {
							Swal.fire({
								icon: 'error',
								title: 'Failed',
								text: response.error
							});
						}

						if (response.sukses) {
							Swal.fire({
								icon: 'success',
								title: 'Success',
								html: 'Loading <b></b> \n milliseconds.',
								timer: 1500,
								timerProgressBar: true,
								didOpen: () => {
									Swal.showLoading()
									timerInterval = setInterval(() => {
										const content = Swal.getContent()
										if (content) {
											const b = content.querySelector('b')
											if (b) {
												b.textContent = Swal.getTimerLeft()
											}
										}
									}, 100)
								},
								willClose: () => {
									clearInterval(timerInterval)
								}
							}).then((result) => {
								if (result.dismiss === Swal.DismissReason.timer) {
									location.href = ('<?= site_url('user'); ?>');
								}
							})
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
					}
				});
				return false;
			});

			$('.formsignin').submit(function(e) {
				let timerInterval
				$.ajax({
					type: "POST",
					url: $(this).attr('action'),
					data: $(this).serialize(),
					dataType: "json",
					success: function(response) {
						if (response.error) {
							Swal.fire({
								icon: 'error',
								title: 'Failed',
								text: response.error
							});
						}

						if (response.sukses) {
							Swal.fire({
								icon: 'success',
								title: 'Success',
								html: 'Loading <b></b> milliseconds.',
								timer: 1500,
								timerProgressBar: true,
								didOpen: () => {
									Swal.showLoading()
									timerInterval = setInterval(() => {
										const content = Swal.getContent()
										if (content) {
											const b = content.querySelector('b')
											if (b) {
												b.textContent = Swal.getTimerLeft()
											}
										}
									}, 100)
								},
								willClose: () => {
									clearInterval(timerInterval)
								}
							}).then((result) => {
								if (result.dismiss === Swal.DismissReason.timer) {
									location.href = ('<?= site_url('tiket'); ?>');
								}
							})
						}
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
					}
				});
				return false;
			});
      });
  </script>
