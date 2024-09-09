<!-- start footer -->
<footer class="footer">







	<style>
		.VIpgJd-ZVi9od-ORHb-OEVmcd {
			display: none !important;
			background-color: black !important;
			height: 0px !important;
			margin-top: -36px !important;


		}


		@media screen and (min-width: 768px) {
			@import url("https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap");

			body {
				font-family: "Inter", sans-serif;
				transition: background-color 0.5s ease, color 0.5s ease;
				background-color: white;
				color: #333;
				margin: 0;
				padding: 0;
			}

			.dark-mode,
			.dark-mode div,
			.dark-mode p,
			.dark-mode a,
			.dark-mode h1,
			.dark-mode h2,
			.dark-mode h3,
			.dark-mode h4,
			.dark-mode h5,
			.dark-mode h6,
			.dark-mode li,
			.dark-mode span {
				background-color: #333 !important;
				color: #f0f0f0 !important;
			}

			footer,
			.dark-mode footer {
				background-color: #1a1e31 !important;
				color: #f0f0f0 !important;
			}

			#restyle-for-portal-para {
				color: white;
			}

			.font_size_decrease {
				color: #ffffff;
				background-color: transparent;
			}

			.dark-mode .font_size_decrease {
				color: #ffffff !important;
				background-color: transparent !important;
			}

			.dark-mode #f-j-restyle a,
			#f-j-restyle a {
				background-color: initial !important;
				color: initial !important;
			}

			.dark-mode #f-j-restyle {
				background-color: transparent !important;
			}

			.dark-mode .bg_color_theme {
				color: #ffffff !important;
				background-color: transparent !important;
			}

			.dark-mode #restyle-for-portal-para {
				color: #ffffff !important;
				background-color: transparent !important;
			}

			.dark-mode #power-headling-restyle {
				background-color: transparent !important;
			}

			.dark-mode #powerful-job-respon,
			#content-size-accor-scr,
			#powerful-job-respon,
			.dark-mode .contenthire,
			.contenthire {
				background-color: initial !important;
				color: initial !important;
			}

			@media (max-width: 768px) {

				/* Disable dark mode below 768px */
				.dark-mode {
					background-color: initial !important;
					color: initial !important;
				}
			}

		}

		.hiremyname {
			color: red;
		}

		#langtrans {
			position: fixed;
			top: 20px;
			right: 28%;
			z-index: 999999999999;
		}

		@media (max-width: 768px) {
			#langtrans {
				position: fixed;
				top: 20px;
				right: 28%;

			}
		}
	</style>




	<!-- <div style="position: fixed; top: 20px; right: 28%; z-index: 111;" id="langtrans">
		<div id="google_translate_icon" style="cursor: pointer;">
			<i class="fa-solid fa-language" style="font-size: 30px; color: gray;"></i>
		</div>
		<div id="google_translate_element" style="display: none;"></div>
	</div> -->

	<script type="text/javascript">
		function googleTranslateElementInit() {
			new google.translate.TranslateElement({
				pageLanguage: 'en'
			}, 'google_translate_element');
		}


		document.getElementById('google_translate_icon').onclick = function () {
			this.style.display = 'none';
			document.getElementById('google_translate_element').style.display = 'block';
		};
	</script>

	<script type="text/javascript"
		src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>








	<!-- <div style="position: fixed; top: 24px; right: 25%; z-index: 999999999999;" id="ram_display_none">
		<button id="toggleButton" class="toggle-button"
			style="background-color: #00CEDC; border: none !important; color: white; border-radius: 5px;">
			<i id="icon" class="fas fa-sun"></i>
		</button>
	</div> -->

	<style>
		/* Hide the button when the screen width is 768px or less */
		@media only screen and (max-width: 768px) {
			#ram_display_none {
				display: none;
			}
		}
	</style>

	<script>
		function handleToggle() {
			if (window.innerWidth > 768) {
				document.body.classList.toggle('dark-mode');
				var icon = document.getElementById('icon');
				if (document.body.classList.contains('dark-mode')) {
					icon.classList.remove('fa-sun');
					icon.classList.add('fa-moon');
				} else {
					icon.classList.remove('fa-moon');
					icon.classList.add('fa-sun');
				}
			}
		}

		document.getElementById('toggleButton').addEventListener('click', handleToggle);

		// To disable dark mode if screen size is below 768px and reset the icon
		window.addEventListener('resize', function () {
			if (window.innerWidth <= 768) {
				document.body.classList.remove('dark-mode');
				document.getElementById('icon').classList.remove('fa-moon');
				document.getElementById('icon').classList.add('fa-sun');
			}
		});
	</script>














	<div class="container">
		<div class="row px-5-mx-3">
			<style>
				.v-align-zero {

					margin-top: -4.35rem;
				}

				.footer_bottom_strap {
					height: 15vh;
					width: 100%;

					display: flex;
				}

				.footer_bottom_strap img {
					margin: 15px auto 0;
				}

				.px-5-mx-3 {
					padding-left: 1.5rem !important;
					padding-right: 1.5rem !important;
				}

				.mb_10px {
					margin-bottom: 10px;
				}

				.fontnormal a {
					font-size: 12px;
				}

				@media(max-width:768px) {
					.px-5-mx-3 {
						padding-left: 0rem !important;
						padding-right: 0rem !important;
					}
				}
			</style>





			<div class="col-md-3 col-12">






















				<h3 class="h3Border mb-lg-4" data-toggle="collapse" data-target="#collapseabout"><?= $lang['about']; ?>
				</h3>
				<ul class=" show list-unstyled" id="collapseabout">
					<?php
					$get_footer_links = $db->select("footer_links", array("link_section" => "about", "language_id" => $siteLanguage));
					while ($row_footer_links = $get_footer_links->fetch()) {
						$link_id = $row_footer_links->link_id;
						$icon_class = $row_footer_links->icon_class;
						$link_title = $row_footer_links->link_title;
						$link_url = $row_footer_links->link_url;
						?>
						<li class="list-unstyled-item"><a href="<?= $site_url . $link_url; ?>"><i
									class="fa <?= $icon_class; ?>"></i> <?= $link_title; ?></a></li>
					<?php } ?>
				</ul>

				<h3 class="h3Border mb-lg-4 mt-4">Contact us</h3>
				<ul class="p-0">
					<li class="font-normal"><a href="mailto:info@hiremyprofile.com" class="text-secondary"><i
								class="fa fa-envelope"></i> info@hiremyprofile.com</a></li>
					<li><a href=""><i class="fa fa-home"></i></a> <a href=""><span class="fontnormal">126, FIRST FLOOR
								BANK ROAD, Ambala, Haryana , India 133001</span></a></li>
				</ul>
			</div>

			<div class="col-md-3 col-12">
				<h3 class="mb-lg-4" data-toggle="collapse" data-target="#collapsecategories"><?= $lang['categories']; ?>
				</h3>
				<ul class=" show list-unstyled" id="collapsecategories">
					<?php
					$get_footer_links = $db->query("select * from footer_links where link_section='categories' AND language_id='$siteLanguage'");
					while ($row_footer_links = $get_footer_links->fetch()) {
						$link_id = $row_footer_links->link_id;
						$link_title = $row_footer_links->link_title;
						$link_url = $row_footer_links->link_url;
						?>
						<li class="list-unstyled-item"><a href="<?= $site_url . $link_url; ?>"><?= $link_title; ?></a></li>
					<?php } ?>
				</ul>
			</div>



			<div class="col-md-3 col-12">


				<h3 class="h3Border mb-lg-4">Terms</h3>
				<!--<span class="font-weight-bold text-white"><i class="fa fa-envelope"></i></span>-->
				<p class="mb_10px"><span class="mr-2 font-weight-light text-secondary"><a
							href="<?= $site_url; ?>/privacy_policy" class="text-secondary">Privacy Policy</a></span></p>
				<p class="mb_10px"><span class="mr-2 font-weight-light text-secondary"><a
							href="<?= $site_url; ?>/terms_and_conditions" class="text-secondary">Terms &
							Conditions</a></span></p>
				<p class="mb_10px"><span class="mr-2 font-weight-light text-secondary"><a
							href="<?= $site_url; ?>/copyright_policy" class="text-secondary">Copyright Policy</a></span>
				</p>
				<p class="mb_10px"><span class="mr-2 font-weight-light text-secondary"><a href="#"
							class="text-secondary">Code of Conduct</a></span></p>
				<p class="mb_10px"><span class="mr-2 font-weight-light text-secondary"><a
							href="<?= $site_url; ?>/fees-and-charges" class="text-secondary">Fees & Charges</a></span>
				</p>
			</div>

			<div class="col-md-3 col-12 ">
				<!--- col-md-4 Starts --->
				<!-- <h3 class="h3Border mb-lg-4 mb-5"><a href="<?= $site_url; ?>" class="text-white"> HOME </a></h3>
				<br> -->
				<h3 class="h3Border mb-lg-4">Download </h3>
				<button class="btn btn-outline-dark text-white w-100 py-4 px-4 mb-4">
					<div class="d-flex align-items-center ">
						<i class="fa  fa-apple fa-3x text-green1"></i>
						<div class="d-flex flex-column pl-4 align-items-flex-start">
							<label class="mb-0 font-weight-bold">Google Play</label>
							<span class="text-muted">Coming soon</span>
						</div>
					</div>
				</button>
				<button class="btn btn-outline-dark text-white w-100 py-4 px-4 mb-4">
					<div class="d-flex align-items-center">
						<i class="fa fa-android fa-3x text-green1"></i>
						<div class="d-flex flex-column pl-4 align-items-flex-start">
							<label class="mb-0 font-weight-bold">App Store</label>
							<span class="text-muted">coming soon</span>
						</div>
					</div>
				</button>
				<p class="text-white mt-4">© 2024 Preferred Outsourcing Pvt Ltd. All Rights Reserved.</p>
			</div>
		</div>
	</div>
	<br>
</footer>

<!-- end footer -->
<section class="post_footer d-none">
	<?= $db->select("general_settings")->fetch()->site_copyright; ?>
</section>

<?php if (!isset($_COOKIE['close_cookie'])) { ?>
	<section class="clearfix cookies_footer row animated slideInLeft">
		<div class="col-md-4">
			<img src="<?= $site_url; ?>/images/cookie.png" class="img-fluid" alt="">
		</div>
		<div class="col-md-8">
			<div class="float-right close btn btn-sm"><i class="fa fa-times"></i></div>
			<h4 class="mt-0 mt-lg-2 <?= ($lang_dir == "right" ? 'text-right' : '') ?>"><?= $lang["cookie_box"]['title']; ?>
			</h4>
			<p class="mb-1 "><?= str_replace('{link}', "$site_url/terms_and_conditions", $lang["cookie_box"]['desc']); ?>
			</p>
			<a href="#" class="btn btn-success btn-sm"><?= $lang["cookie_box"]['button']; ?></a>
		</div>
	</section>
<?php } ?>
<section class="messagePopup animated slideInRight"></section>

<div id="report-modal-uni" class="modal fade" role="dialog">
	<!-- report-modal modal fade Starts -->
	<div class="modal-dialog">
		<!-- modal-dialog Starts -->
		<div class="modal-content">
			<!-- modal-content Starts -->
			<div class="modal-header p-2 pl-3 pr-3">
				<!-- modal-header Starts -->
				Report this item
				<button class="close" data-dismiss="modal"><span>&times;</span></button>
			</div><!-- modal-header Ends -->
			<div class="modal-body">
				<!-- modal-body p-0 Starts -->
				<h6>Let us know why you would like to report this item.</h6>
				<form action="#" method="post" id="reportUni">
					<div class="form-group mt-3">
						<!--- form-group Starts --->
						<select class="form-control float-right" name="reason" required="">
							<option value="">Select</option>
							<option>Non Original Content</option>
							<option>Inappropriate Proposal</option>
							<option>Trademark Violation</option>
							<option>Copyrights Violation</option>
							<option>Other</option>
						</select>
					</div>
					<!--- form-group Ends --->
					<br>
					<br>
					<div class="form-group mt-1 mb-3">
						<!--- form-group Starts --->
						<label> Additional Information </label>
						<textarea name="additional_information" rows="3" class="form-control" required=""></textarea>
					</div>
					<!--- form-group Ends --->
					<button type="submit" name="submit_report" class="float-right btn btn-sm btn-success">
						Submit Report
					</button>
					<input type="hidden" name="reporter_id" value="<?= $login_seller_id ?>" required />
					<input type="hidden" name="content_id" value="" required />
					<input type="hidden" name="content_type" value="" required />
					<input type="hidden" name="url" value="" required />
				</form>
			</div><!-- modal-body p-0 Ends -->
		</div><!-- modal-content Ends -->
	</div><!-- modal-dialog Ends -->
</div><!-- report-modal modal fade Ends -->

<link rel="stylesheet" href="<?= $site_url; ?>/styles/msdropdown.css" />
<?php

if ($videoPlugin == 1) {
	require("$dir/plugins/videoPlugin/footer/videoCall.php");
}
require("footerJs.php");

?>