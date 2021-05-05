<?= form_open('survey/simpan', ['class' => 'formsimpan']); ?>
<div class="row">
    <div class="col-lg-12">
            <div class="card m-b-30">
                <div class="card-body">
					<div class="col-sm-4 mx-auto">
						<div class="form-group">
							<label>NIK</label>
							<input type="text" id="nik" name="nik" class="form-control" required placeholder="Fill your ID employee"/>
						</div>
						<div class="form-group">
							<label>Name</label>
							<input type="text" id="name" name="name" class="form-control" required placeholder="Fill your name..."/>
						</div>
					</div>
                </div>
				<div class="card-footer">
				<p class="text-muted m-b-10 font-12" align="center">
					<b>
						We ask for your willingness to provide an assessment and input to ICT Medika Plaza.
						<br>
					</b>
						<i>
							Please fill in by clicking the radio option
							as well as a description according to your assessment
							in the column provided
						</i> 
				</p>
			</div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
<div class="row">
<?php 
	$i=0; 
	foreach ($apps as $row) : 
		if($i % 1  == 3) {
			echo "<div class='row'>";
		}
?>
		<div class="col-md-4">
			<div class="card m-b-30">
				<div class="card-body">
					<h4 class="card-title font-20 mt-0"><?= $row['name']; ?></h4>
					<h6 class="card-subtitle text-muted">Released on <?= date('F Y', strtotime($row['made_on'])); ?></h6>
				</div>
				<div id="carouselExampleIndicators<?= $i;?>" class="carousel slide" data-ride="carousel">
					<ol class="carousel-indicators">
						<li data-target="#carouselExampleIndicators<?= $i;?>" data-slide-to="0" class="active"></li>
						<li data-target="#carouselExampleIndicators<?= $i;?>" data-slide-to="1"></li>
						<li data-target="#carouselExampleIndicators<?= $i;?>" data-slide-to="2"></li>
					</ol>
					<div class="carousel-inner" role="listbox">
						<div class="carousel-item active">
							<img class="d-block img-fluid" src="<?= base_url('assets/img/apps/')  . $row['image1']; ?>" alt="<?= $row['image1'] ?> slide">
						</div>
						<div class="carousel-item">
							<img class="d-block img-fluid" src="<?= base_url('assets/img/apps/')  . $row['image2']; ?>" alt="<?= $row['image2'] ?> slide">
						</div>
						<div class="carousel-item">
							<img class="d-block img-fluid" src="<?= base_url('assets/img/apps/')  . $row['image3']; ?>" alt="<?= $row['image3'] ?> slide">
						</div>
					</div>
					<a class="carousel-control-prev" href="#carouselExampleIndicators<?= $i;?>" role="button" data-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
					<a class="carousel-control-next" href="#carouselExampleIndicators<?= $i;?>" role="button" data-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</div>
				<div class="card-body">
					<p class="card-text">
						Application <?= $row['name']; ?>
						<br>
						Created By <?= $row['made_by']; ?>
					</p>
					<div class="form-group row">
						<label class="col-md-5 my-1 control-label">What about this app ?</label>
						<div class="col-md-9">
							<div class="form-check-inline my-1">
								<div class="custom-control custom-radio">
									<input type="radio" id="gatau[<?= $i; ?>]" name="responds[<?= $i; ?>]" class="custom-control-input" value="Dunno" checked="true">
									<label class="custom-control-label" for="gatau[<?= $i; ?>]">I don't know</label>
								</div>
							</div>
							<div class="form-check-inline my-1">
								<div class="custom-control custom-radio">
									<input type="radio" id="gapake[<?= $i; ?>]" name="responds[<?= $i; ?>]" class="custom-control-input" value="Don't Use">
									<label class="custom-control-label" for="gapake[<?= $i; ?>]">Not applicable</label>
								</div>
							</div>
							<div class="form-check-inline my-1">
								<div class="custom-control custom-radio">
									<input type="radio" id="pake[<?= $i; ?>]" name="responds[<?= $i; ?>]" class="custom-control-input" value="Use" >
									<label class="custom-control-label" for="pake[<?= $i; ?>]">Applicable</label>
								</div>
							</div>
						</div>
					</div>
			
					<div id="Review<?= $i; ?>" class="ulasan<?= $i; ?>" style="display: none;">
						<hr />
						<div class="input-group mt-2">
							<div class="col-sm-6 col-lg-12">
								<div class="p-2 text-center">
									<h5 class="font-16 m-b-15">Rate me !</h5>
									<input type="hidden" id="star<?= $i; ?>" name="star[<?= $i; ?>]" class="rating check" data-filled="mdi mdi-star font-32 text-warning" data-empty="mdi mdi-star-outline font-32 text-muted"/>
									<input type="hidden" id="id<?= $i; ?>" name="id[<?= $i; ?>]" value="<?= $row['id']; ?>"/>
								</div>
							</div> 
						</div>
						<div class="input-group mt-2">
								<div class="input-group-prepend">
									<span class="input-group-text">Commentary</span>
								</div>
							<textarea id="commentary<?= $i; ?>" data-id="<?= $row['id']; ?>" name="commentary[<?= $i; ?>]" class="form-control" aria-label="With textarea"></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
<?php 
		if($i % 1 == 3) {
			echo "</div>";
		}
		$i++;
	endforeach; 
?>
</div>
<div class="row">
    <div class="col-lg-12">
    	<div class="card m-b-30">
        	<div class="card-body">
                <p class="text-muted mb-20 font-14" align="center">
					<b>
						Thank you for the time and input you provide, all the input you provide
						<br>
					</b>
					<i>we will accept as a means for us to improve quality.</i> 
				</p>
                <div class="button-items">
					<div class="col-sm-3 mx-auto">
						<button type="button" class="btn btn-warning btn-lg btn-block" onClick="window.location.href=window.location.href">Reset</button>
						<button type="submit" class="btn btn-success btn-lg btn-block" id="tombolSimpanBanyak">Submit</button>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= form_close(); ?>
