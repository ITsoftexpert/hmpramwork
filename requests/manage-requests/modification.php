
<style>
	@media (max-width:768px){
		.font-size-3 {
            font-size: 13px !important;
            padding: 10px !important;
            text-align: center;
         }
		
	}
</style>

<div class="table-responsive box-table box-shadow-req-act">
    <table class="table table-bordered" id="requestModification">
        <thead>
            <tr>
                <th class="font-size-3"><?= $lang['th']['modification_proposal_title']; ?></th>
                <th class="font-size-3"><?= $lang['th']['modification_message']; ?></th>
                <th class="font-size-3"><?= $lang['th']['actions']; ?></th>
            </tr>
        </thead>
        <tbody>
            <tr class="table-info">
                <td colspan="3">
                    data fetching...
                </td>
            </tr>
        </tbody>
    </table>
    <nav id="pagination-request-modification" aria-label="modification request navigation"></nav>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var modificationRequest = function(userId, status, limit, page = 1) {
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
                $('body #requestModification tbody').html(data.data);
                $('body #pagination-request-modification').html(data.pagination);
                $('body #wait').removeClass("loader");
            });
        }
        modificationRequest(<?= $login_seller_id ?>, 'modification', <?= isset($homePerPage) ? $homePerPage : 10 ?>);

        //executes code below when user click on pagination links
        $("body #pagination-request-modification").on("click", ".pagination a", function(e) {
            e.preventDefault();
            var page = $(this).attr("data-page"); //get page number from link
            $('body #wait').addClass("loader");
            modificationRequest(<?= $login_seller_id ?>, 'modification', <?= isset($homePerPage) ? $homePerPage : 10 ?>, page);
        })
    });
</script>