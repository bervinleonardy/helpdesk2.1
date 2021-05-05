<div class="container-fluid">

	<!-- Page Heading -->
	<div class="row">
		<div class="col-xl col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xl font-weight-bold text-success text-uppercase mb-1">
								<?=$title; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Content Row -->
	<div class="row">

		<!-- Earnings (Monthly) Card Example -->
		<div class="col-xl-6 col-md-6 mb-4">
			<div class="card border-left-primary shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
								Total Responden</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total['responden'];?></div>
						</div>
						<div class="col-auto">
							<i class="fas fa-users fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Earnings (Monthly) Card Example -->
		<div class="col-xl-6 col-md-6 mb-4">
			<div class="card border-left-success shadow h-100 py-2">
				<div class="card-body">
					<div class="row no-gutters align-items-center">
						<div class="col mr-2">
							<div class="text-xs font-weight-bold text-success text-uppercase mb-1">
								Apps</div>
							<div class="h5 mb-0 font-weight-bold text-gray-800"><?=  $totalApps['apps']; ?></div>
						</div>
						<div class="col-auto">
							<i class="far fa-check-circle fa-2x text-gray-300"></i>
						</div>
					</div>
				</div>
			</div>
		</div>

	</div>

	<!-- Content Row -->
	<div class="row">
		<div class="col-lg-5 mb-4">
			<!-- Illustrations -->
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-primary">Date range : <b><?= $start; ?></b> s/d <b><?= $end; ?></b></h6>
				</div>
				<div class="card-body">
					<div class="col-md-10">
						<div class="input-group">
							<input type="text" id="startDate" name="startDate" class="form-control startdate datetimepicker-input" data-toggle="datetimepicker" data-target=".startdate"/ value="">
							<div class="input-group-append">
								<span class="input-group-text">s/d</span>
							</div>
							<input type="text" id="endDate" name="endDate" class="form-control enddate datetimepicker-input" data-toggle="datetimepicker" data-target=".enddate"/>
						</div>
					</div>
				</div>
				<div class="card-footer">
					<button type="button" class="btn btn-primary float-right" style="margin-right: 5px;" onclick="return search();" title="Unduh"><i class="fas fa-search fa-sm text-white-50"></i> Search</button>
				</div>
			</div>
		</div>
		<div class="col-lg-7 mb-4">
			<!-- Illustrations -->
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-primary">Informations</h6>
				</div>
				<div class="card-body">
					<p>
						Dashboard Aplikasi ICT diselenggarakan setiap tahunnya.
						<br />
						Semua data yang ditampilkan berdasarkan tanggal yang telah diset sebelumnya.
					</p>
					<a target="_blank" rel="nofollow" href="<?= site_url('survey'); ?>">Go to Survey Apps &rarr;</a>
				</div>
			</div>
		</div>
	</div>

	<!-- Content Row -->
	<div class="row">

		<!-- Pie Chart -->
		<div class="col-xl-4 col-lg-5">
			<div class="card shadow mb-4">
				<!-- Card Header - Dropdown -->
				<div
					class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
					<h6 class="m-0 font-weight-bold text-primary">Penggunaan Apps</h6>
					<div class="dropdown no-arrow">
						<a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
							data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-1000"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
							aria-labelledby="dropdownMenuLink">
							<a class="dropdown-item" href="#">Download Excel</a>
						</div>
					</div>
				</div>
				<!-- Card Body -->
				<div class="card-body">
					<div class="chart-pie pt-4 pb-2">
						<canvas id="myPieChart">
						<?php
                            $appsName 	= "";
                            $color 		= "";
                            $hColor 	= "";
							$qty        = "";
							foreach($usePie as $itemPie) {
								$appsName      	= $itemPie->name;
								$color        	= $itemPie->bg_color;
								$hColor       	= $itemPie->hbg_color;
								$qty          	= $itemPie->qty;
								// $nama_apps		.= "$appsName" . ", ";
								$nama_apps[]	    .= "$appsName";
								$list_color[]   	.= $color;
								$list_hcolor[]  	.= $hColor;
								$list_qty[]     	.= $qty;
							} 
                            ?>
						</canvas>
					</div>
					<textarea name="namaApps" id="namaApps" cols="30" rows="10" style="display:true;"><?= json_encode($nama_apps); ?></textarea>
					<textarea name="listColor" id="listColor" cols="30" rows="10" style="display:true;"><?= json_encode($list_color); ?></textarea>
					<textarea name="listHColor" id="listHColor" cols="30" rows="10" style="display:true;"><?= json_encode($list_hcolor); ?></textarea>
					<textarea name="listQty" id="listQty" cols="30" rows="10" style="display:true;"><?= json_encode($list_qty); ?></textarea>
				</div>
			</div>
		</div>
	</div>

	<!-- Content Row -->
	<div class="row">
		<!-- Content Column -->
		<div class="col-lg-6 mb-4">

			<!-- Project Card Example -->
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m-0 font-weight-bold text-primary">Projects</h6>
				</div>
				<div class="card-body">
					<h4 class="small font-weight-bold">Server Migration <span
							class="float-right">20%</span></h4>
					<div class="progress mb-4">
						<div class="progress-bar bg-danger" role="progressbar" style="width: 20%"
							aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					<h4 class="small font-weight-bold">Sales Tracking <span
							class="float-right">40%</span></h4>
					<div class="progress mb-4">
						<div class="progress-bar bg-warning" role="progressbar" style="width: 40%"
							aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					<h4 class="small font-weight-bold">Customer Database <span
							class="float-right">60%</span></h4>
					<div class="progress mb-4">
						<div class="progress-bar" role="progressbar" style="width: 60%"
							aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					<h4 class="small font-weight-bold">Payout Details <span
							class="float-right">80%</span></h4>
					<div class="progress mb-4">
						<div class="progress-bar bg-info" role="progressbar" style="width: 80%"
							aria-valuenow="80" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
					<h4 class="small font-weight-bold">Account Setup <span
							class="float-right">Complete!</span></h4>
					<div class="progress">
						<div class="progress-bar bg-success" role="progressbar" style="width: 100%"
							aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
					</div>
				</div>
			</div>

		</div>
	</div>

</div>
