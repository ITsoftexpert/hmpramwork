<link rel="stylesheet" href="styles/addnew.css">
<style>
	.buyer-offer-img {
		width: 18px;
	}

	.custom-dropdown {
		/* position: relative; */
		display: inline-block;
	}

	.dropdown-content-one {
		display: none;
		position: absolute;
		right: 17px;
		background-color: white;
		min-width: 160px;
		box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
		z-index: 999;
	}

	.dropdown-content-one a {
		color: black;
		padding: 12px 16px;
		text-decoration: none;
		display: block;
	}

	.dropdown-content-one a:hover {
		background-color: #f1f1f1;
	}

	.dropdown-button-one {
		background-color: #00cedc;
		border: none;
		color: #fff;
		border-radius: 5px;
	}

	/* byuer seller order summary page css new add */
	.order-card {
		border: 1px solid #e0e0e0;
		border-radius: 8px;
		padding: 16px;
		max-width: 100%;
		background-color: #fff;
		font-family: Arial, sans-serif;
	}

	.manage-req-heading-main {
		font-size: 13px;
	}

	@media (min-width: 1024px) {
		.order-card {
			display: none;
		}
	}

	.Order-Summary {
		margin: 0 0 10px;
		font-size: 1.25em;
		font-weight: bold;
		color: #a7a9ac;
	}

	.Order-Status-textmain {
		font-size: 1.25em;
		color: #a7a9ac;
	}

	.order-content {
		display: flex;
		gap: 10px;
		margin-bottom: 10px;
	}

	.order-image img {
		width: 100px;
		height: 85px;
		border-radius: 4px;
		object-fit: cover;
	}

	.order-text p {
		font-size: 13px;
		color: #333;
	}

	.order-text a {
		color: #007bff;
		text-decoration: none;
	}

	.order-info i {
		margin-right: 4px;
	}

	.order-status {
		display: flex;
		justify-content: space-between;
		align-items: center;
		border-top: 1px solid #e0e0e0;
		padding-top: 10px;
	}

	@media (max-width: 767px) {
		.buyer-nitin-edit-sec {
			display: none;
		}
	}

	.info-container {
		display: flex;
		font-size: 0.85em;
		color: #555;
		gap: 40px;
	}

	.info-item {
		position: relative;
		cursor: pointer;
	}

	.info-item .heading {
		position: absolute;
		bottom: 120%;
		left: 50%;
		transform: translateX(-50%);
		padding: 5px 10px;
		background-color: rgba(0, 0, 0, 0.8);
		color: white;
		font-size: 14px;
		border-radius: 5px;
		opacity: 0;
		pointer-events: none;
		transition: all 0.4s ease;
		white-space: nowrap;
	}

	.info-item:hover .heading {
		opacity: 1;
		bottom: 100%;
	}

	.buyer-active-orderdataby-nitin {
		display: flex;
		gap: 20px;
		flex-direction: column;
	}
</style>
<div class="table-responsive box-table  box-shadow-req-act">
	<table class="table table-bordered buyer-nitin-edit-sec" id="requestActive">
		<thead>
			<tr>
				<th class="font-size-3"><?= $lang['th']['title']; ?></th>
				<th class="font-size-3"><?= $lang['th']['description']; ?></th>
				<th class="font-size-3"><?= $lang['th']['date']; ?></th>
				<th class="font-size-3"><?= $lang['th']['offers']; ?></th>
				<th class="font-size-3"><?= $lang['th']['budget']; ?></th>
				<th class="font-size-3"><?= $lang['th']['actions']; ?></th>
			</tr>
		</thead>
		<tbody>
			<tr class="table-info">
				<td colspan="6">
					data fetching...
				</td>
			</tr>
		</tbody>
	</table>

	<div class="buyer-active-orderdataby-nitin">
		<div class="order-card">
			<!-- <h3 class="Order-Summary">Order Summary</h3> -->
			<div class="order-content">
				<!-- <div class="order-image">
					<img src="https://images.unsplash.com/photo-1688888745596-da40843a8d45?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mjd8fHByb2ZpbGUlMjBwaG90b3xlbnwwfHwwfHx8MA%3D%3D" alt="Order Image">
				</div> -->
				<div class="order-text">
					<h3 class="manage-req-heading-main">I want to make my website in wordpress from expert testing</h3>
					<div class="order-info">
						<div class="info-container">
							<div class="info-item">
								<i class="fas fa-calendar"></i> July 24, 2024
								<span class="heading">date</span>
							</div>
							<div class="info-item">
								<img class="buyer-offer-img" src="images/buyer-offer-img.png" alt=""> 0
								<span class="heading">offer</span>
							</div>
							<div class="info-item">
								<i class="fa-solid fa-sack-dollar"></i> 22.00
								<span class="heading">Budget</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="order-status">
				<span class="Order-Status-textmain">Actions</span>
				<div class="custom-dropdown">
					<button class="dropdown-button-one" id="dropdownBtn">
						<i class="fa-solid fa-caret-down"></i>
					</button>
					<div class="dropdown-content-one" id="dropdownMenu">
						<a href="#">View Offers</a>
						<a href="#">Pause</a>
						<a href="#">Edit</a>
						<a href="#">Delete</a>
					</div>
				</div>
			</div>
		</div>
		<div class="order-card">
			<!-- <h3 class="Order-Summary">Order Summary</h3> -->
			<div class="order-content">
				<!-- <div class="order-image">
					<img src="https://images.unsplash.com/photo-1688888745596-da40843a8d45?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mjd8fHByb2ZpbGUlMjBwaG90b3xlbnwwfHwwfHx8MA%3D%3D" alt="Order Image">
				</div> -->
				<div class="order-text">
					<h3 class="manage-req-heading-main">i want to make my website in wordpress from expert testing</h3>
					<div class="order-info">
						<div class="info-container">
							<div class="info-item">
								<i class="fas fa-calendar"></i> July 24, 2024
								<span class="heading">date</span>
							</div>
							<div class="info-item">
								<img class="buyer-offer-img" src="images/buyer-offer-img.png" alt=""> 0
								<span class="heading">offer</span>
							</div>
							<div class="info-item">
								<i class="fa-solid fa-sack-dollar"></i> 22.00
								<span class="heading">Budget</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="order-status">
				<span class="Order-Status-textmain">Actions</span>
				<div class="custom-dropdown">
					<button class="dropdown-button-one" id="dropdownBtn">
						<i class="fa-solid fa-caret-down"></i>
					</button>
					<div class="dropdown-content-one" id="dropdownMenu">
						<a href="#">View Offers</a>
						<a href="#">Pause</a>
						<a href="#">Edit</a>
						<a href="#">Delete</a>
					</div>
				</div>
			</div>
		</div>
		<div class="order-card">
			<!-- <h3 class="Order-Summary">Order Summary</h3> -->
			<div class="order-content">
				<!-- <div class="order-image">
					<img src="https://images.unsplash.com/photo-1688888745596-da40843a8d45?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mjd8fHByb2ZpbGUlMjBwaG90b3xlbnwwfHwwfHx8MA%3D%3D" alt="Order Image">
				</div> -->
				<div class="order-text">
					<h3 class="manage-req-heading-main">i want to make my website in wordpress from expert testing</h3>
					<div class="order-info">
						<div class="info-container">
							<div class="info-item">
								<i class="fas fa-calendar"></i> July 24, 2024
								<span class="heading">date</span>
							</div>
							<div class="info-item">
								<img class="buyer-offer-img" src="images/buyer-offer-img.png" alt=""> 0
								<span class="heading">offer</span>
							</div>
							<div class="info-item">
								<i class="fa-solid fa-sack-dollar"></i> 22.00
								<span class="heading">Budget</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="order-status">
				<span class="Order-Status-textmain">Actions</span>
				<div class="custom-dropdown">
					<button class="dropdown-button-one" id="dropdownBtn">
						<i class="fa-solid fa-caret-down"></i>
					</button>
					<div class="dropdown-content-one" id="dropdownMenu">
						<a href="#">View Offers</a>
						<a href="#">Pause</a>
						<a href="#">Edit</a>
						<a href="#">Delete</a>
					</div>
				</div>
			</div>
		</div>
		<div class="order-card">
			<!-- <h3 class="Order-Summary">Order Summary</h3> -->
			<div class="order-content">
				<!-- <div class="order-image">
					<img src="https://images.unsplash.com/photo-1688888745596-da40843a8d45?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mjd8fHByb2ZpbGUlMjBwaG90b3xlbnwwfHwwfHx8MA%3D%3D" alt="Order Image">
				</div> -->
				<div class="order-text">
					<h3 class="manage-req-heading-main">i want to make my website in wordpress from expert testing</h3>
					<div class="order-info">
						<div class="info-container">
							<div class="info-item">
								<i class="fas fa-calendar"></i> July 24, 2024
								<span class="heading">date</span>
							</div>
							<div class="info-item">
								<img class="buyer-offer-img" src="images/buyer-offer-img.png" alt=""> 0
								<span class="heading">offer</span>
							</div>
							<div class="info-item">
								<i class="fa-solid fa-sack-dollar"></i> 22.00
								<span class="heading">Budget</span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="order-status">
				<span class="Order-Status-textmain">Actions</span>
				<div class="custom-dropdown">
					<button class="dropdown-button-one" id="dropdownBtn">
						<i class="fa-solid fa-caret-down"></i>
					</button>
					<div class="dropdown-content-one" id="dropdownMenu">
						<a href="#">View Offers</a>
						<a href="#">Pause</a>
						<a href="#">Edit</a>
						<a href="#">Delete</a>
					</div>
				</div>
			</div>
		</div>

	</div>
	<nav id="pagination-request-active" aria-label="Active request navigation"></nav>
</div>





<script type="text/javascript">
	$(document).ready(function() {
		var activeRequest = function(userId, status, limit, page = 1) {
			return $.ajax({
				url: "<?= $site_url ?>/ajax/request_data.php",
				dataType: "json",
				data: {
					user_id: userId,
					status: status,
					limit: limit,
					page: page,
				}
			}).done(function(data) {
				$('body #requestActive tbody').html(data.data);
				$('body #pagination-request-active').html(data.pagination);
				$('body #wait').removeClass("loader");
			});
		}
		activeRequest(<?= $login_seller_id ?>, 'active', <?= isset($homePerPage) ? $homePerPage : 10 ?>);

		//executes code below when user click on pagination links
		$("body #pagination-request-active").on("click", ".pagination a", function(e) {
			e.preventDefault();
			var page = $(this).attr("data-page"); //get page number from link
			$('body #wait').addClass("loader");
			activeRequest(<?= $login_seller_id ?>, 'active', <?= isset($homePerPage) ? $homePerPage : 10 ?>, page);
		})
	});
</script>

<script>
	document.querySelectorAll(".dropdown-button-one").forEach(button => {
		button.addEventListener("click", function(event) {
			const dropdownMenu = this.nextElementSibling;
			dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
			event.stopPropagation();
		});
	});

	window.addEventListener("click", function() {
		document.querySelectorAll(".dropdown-content-one").forEach(menu => {
			menu.style.display = "none";
		});
	});
</script>
<!-- <script>
	document.querySelectorAll(".dropdown-button-one").forEach(button => {
		button.addEventListener("click", function(event) {
			const dropdownMenu = this.nextElementSibling;
			dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
			event.stopPropagation(); // Prevent event from bubbling up
		});
	});

	window.addEventListener("click", function() {
		document.querySelectorAll(".dropdown-content-one").forEach(menu => {
			menu.style.display = "none"; // Hide all dropdowns
		});
	});
</script> -->