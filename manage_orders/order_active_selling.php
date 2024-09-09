<link rel="stylesheet" href="styles/addnew.css"> 

<div class="table-responsive box-table mt-3">
	<table class="table table-bordered" id="orderSellerActive">
		<thead>
			<tr>
				<th class="font-size-3"><?= $lang['th']['order_summary']; ?></th>
				<th class="font-size-3"><?= $lang['th']['order_date']; ?></th>
				<th class="font-size-3"><?= $lang['th']['due_on']; ?></th>
				<th class="font-size-3"><?= $lang['th']['total']; ?></th>
				<th class="font-size-3"><?= $lang['th']['status2']; ?></th>
			</tr>
		</thead>
		<tbody>
			<tr class="table-info">
				<td colspan="5">
					data fetching...
				</td>
			</tr>
		</tbody>
	</table>
	<nav id="pagination-seller-order-yes" aria-label="Active order navigation">
	</nav>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		var activeSellerOrder = function(userId, status, limit, page = 1) {
			return $.ajax({
				url: "<?= $site_url ?>/ajax/order_seller_data.php",
				dataType: "json",
				data: {
					user_id: userId,
					status: status,
					limit: limit,
					page: page,
				}
			}).done(function(data) {
				$('body #orderSellerActive tbody').html(data.data);
				$('body #pagination-seller-order-yes').html(data.pagination);
				$('body #wait').removeClass("loader");
			});
		}
		activeSellerOrder(<?= $login_seller_id ?>, 'yes', <?= isset($homePerPage) ? $homePerPage : 10 ?>);

		//executes code below when user click on pagination links
		$("body #pagination-seller-order-yes").on("click", ".pagination a", function(e) {
			e.preventDefault();
			var page = $(this).attr("data-page"); //get page number from link
			$('body #wait').addClass("loader");
			activeSellerOrder(<?= $login_seller_id ?>, 'yes', <?= isset($homePerPage) ? $homePerPage : 10 ?>, page);
		})
	});
</script>