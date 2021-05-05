<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <title><?= $title; ?></title>
    <meta content="" name="description" />
    <meta content="Oyoy" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <link rel="shortcut icon" href="<?= base_url('assets/img'); ?>/icons/mp.ico">

    <!-- Annex Admin Templates -->
    <link href="<?= base_url('assets/annexadmin'); ?>/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/annexadmin'); ?>/css/icons.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/annexadmin'); ?>/css/style.css" rel="stylesheet" type="text/css">

    <!-- Annex Admin (Admin) Templates -->
    <link href="<?= base_url('assets/annexadmin/crypto'); ?>/css/style.css" rel="stylesheet" type="text/css">

    <!-- Sweet Alert 2 -->
    <link href="<?= base_url('assets/sweetalert');  ?>/sweetalert2.min.css" rel="stylesheet" type="text/css">
    <script src="<?= base_url('assets/sweetalert');  ?>/sweetalert2.all.min.js"></script>

    <!-- Bootstrap rating css
    <link href="assets/plugins/bootstrap-rating/bootstrap-rating.css" rel="stylesheet" type="text/css"> -->

    <link href="<?= base_url('assets/annexadmin'); ?>/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/annexadmin'); ?>/css/icons.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url('assets/annexadmin'); ?>/css/style.css" rel="stylesheet" type="text/css">

    <script src="<?= base_url('assets/annexadmin'); ?>/js/jquery.min.js"></script>

    <style>
        /* selection 2 css  */
        .select2-selection__rendered {
            line-height: 33px !important;
        }

        .select2-container .select2-selection--single {
            height: 38px !important;
        }

        .select2-selection__arrow {
            height: 37px !important;
        }

        .navbar-custom {
            margin: 0px -6px -6px -7px;
        }

        /* End Selection 2 CSS */

		/* Marquee CSS  */

		.marquee {
			width: 100%;
			text-align: center;
			margin: 0 auto;
			overflow: hidden;
			box-sizing: border-box;
		}

		.marquee span {
			display: inline-block;
			width: max-content;
			padding-left: 100%;
			/* show the marquee just outside the paragraph */
			will-change: transform;
			animation: marquee 40s linear infinite;
		}

		.marquee span:hover {
			animation-play-state: paused
		}


		@keyframes marquee {
			0% { transform: translate(0, 0); }
			100% { transform: translate(-100%, 0); }
		}


		/* Respect user preferences about animations */

		@media (prefers-reduced-motion: reduce) {
			.marquee span {
				animation-iteration-count: 1;
				animation-duration: 0.01; 
				/* instead of animation: none, so an animationend event is 
				* still available, if previously attached.
				*/
				width: auto;
				padding-left: 0;
			}
		}

		/* End Marquee CSS  */
    </style>
</head>
