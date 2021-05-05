    <!-- jQuery  -->
    <script src="<?= base_url('assets/annexadmin'); ?>/js/jquery.min.js"></script>
    <script src="<?= base_url('assets/annexadmin'); ?>/js/popper.min.js"></script>
    <script src="<?= base_url('assets/annexadmin'); ?>/js/bootstrap.min.js"></script>
    <script src="<?= base_url('assets/annexadmin'); ?>/js/modernizr.min.js"></script>
    <script src="<?= base_url('assets/annexadmin'); ?>/js/waves.js"></script>
    <script src="<?= base_url('assets/annexadmin'); ?>/js/jquery.slimscroll.js"></script>
    <script src="<?= base_url('assets/annexadmin'); ?>/js/jquery.nicescroll.js"></script>
    <script src="<?= base_url('assets/annexadmin'); ?>/js/jquery.scrollTo.min.js"></script>

	<!-- Bootstrap rating js -->
	<script src="<?= base_url('assets/annexadmin'); ?>/plugins/bootstrap-rating/bootstrap-rating.min.js"></script>

    <!-- App js -->
    <script src="<?= base_url('assets/annexadmin'); ?>/js/app.js"></script>

    <script type="text/javascript">

		$(document).ready(function() {
			$("input[type=radio][id^='pake']").click(function() {
				var apayah = $(this).attr('id');
				var i = apayah.replace('pake', '').replace('[', '').replace(']', '');
				$("#commentary" + i).val('');
				$("#star" + i).val('');
				$("#Review" + i).show();
			});

			$("input[type=radio][id^='gatau']").click(function() {
				var apayah = $(this).attr('id');
				var i = apayah.replace('gatau', '').replace('[', '').replace(']', '');
				$("#commentary" + i).val('');
				$("#star" + i).val('');
				$("#Review" + i).hide();
			});

			$("input[type=radio][id^='gapake']").click(function() {
				var apayah = $(this).attr('id');
				var i = apayah.replace('gapake', '').replace('[', '').replace(']', '');
				$("#commentary" + i).val('');
				$("#star" + i).val('');
				$("#Review" + i).hide();
			});

			$('.formsimpan').submit(function(e) {
			e.preventDefault();

			let jmldata = $(".custom-control-input:checked");

			if (jmldata.length === 0) {
				Swal.fire({
				icon: 'warning',
				title: 'Warning',
				text: 'Sorry, survey cannot be submit, please fill survey !'
				})
			} else {
				Swal.fire({
				title: 'Submit Survey',
				text: 'Survey nya sudah diisi dengan sepenuh hati dan kejujuran ?',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Yes',
				cancelButtonText: 'No'
				}).then((result) => {
				if (result.value) {
					$.ajax({
					type: "post",
					url: $(this).attr('action'),
					data: $(this).serialize(),
					dataType: "json",
					success: function(response) {
						Swal.fire({
                              icon: 'success',
                              title: 'Terimakasih',
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
								window.location = window.close();
                              }
                          })
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
		});

		$('input.check').on('change', function () {			
			rate = $(this).val();
			if (rate == 1) {
				title = 'Yahh, hiks';
				text = 'akan kami tingkatkan kembali kualitasnya, terimakasih';
			} else if (rate == 2) {
				title = 'Hhhmm';
				text = 'kami akan terus memperbaikinya, terimakasih';
			} else if (rate == 3) {
				title = 'Sip';
				text = 'Terimakasih'
			} else if (rate == 4) {
				title = 'Oke';
				text = 'Terimakasih !';
			} else {
				title = 'Terimakasih yah';
				text = 'Kami akan selalu setia terhadap anda !';
			}

			Swal.fire({
				title: title,	
				text: text,
				imageUrl: '<?= base_url('assets/img/survey/rate/'); ?>star(' + rate + ').png',
				imageWidth: 400,
				imageHeight: 400,
				imageAlt:  rate + ' Star Image',
			})
		});

		$('.rating').each(function () {
			$('<span class="badge badge-warning"></span>')
				.text($(this).val() || ' ')
				.insertAfter(this);
		});

		$('.rating').on('change', function () {
			$(this).next('.badge').text($(this).val());
		});
    </script>
