<?php if (!isset($_COOKIE['close_cookie'])) { ?>
    <section class="clearfix cookies_footer row animated slideInLeft">
        <div class="col-md-4">
            <img src="<?= $site_url; ?>/images/cookie.png" class="img-fluid" alt="">
        </div>
        <div class="col-md-8">
            <div class="float-right close btn btn-sm"><i class="fa fa-times"></i></div>
            <h4 class="mt-0 mt-lg-2">Our site uses cookies</h4>
            <p class="mb-1">We use cookies to ensure you get the best experience. By using our website you agree <br>to our <a href='<?= $site_url; ?>/terms_and_conditions'>Privacy Policy</a>.</p>
            <a href="#" class="btn btn-success btn-sm">Got It.</a>
        </div>
    </section>
<?php } ?>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Reject Offer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="reject_offer" method="post" id="reject_form">

                <div class="modal-body">
                    <input type="hidden" id="offer_id_reject" value="0" name="offer_id_reject">
                    <input type="hidden" id="message_group_id" value="<?php echo $message_group_id; ?>" name="message_group_id">
                    <div class="form-group">
                        <textarea name="reject_reasaon" id="reject_reson" cols="30" rows="5" placeholder="Would you like to ask your seller to change?" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="submitBtn" class="btn btn-primary">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
<section class="messagePopup animated slideInRight"></section>

<link rel="stylesheet" href="<?= $site_url; ?>/styles/msdropdown.css" />
<?php $disable_messages = 1;
require("footerJs.php"); ?>
<script>
    function putID(id, message_group_id) {
        if (id > 0) {
            $("#offer_id_reject").val(id);
            $("#message_group_id").val(message_group_id);
        } else {
            return false;
        }
    }
    $('#reject_form').submit(function(e) {
        e.preventDefault();
        $.ajax({
            method: 'POST',
            url: 'reject_offer',
            data: $("#reject_form").serialize(),
            cache: false,
            beforeSend: function() {
                $('#submitBtn').attr('disabled', true);
                $('#submitBtn').val('saving...');
            },
            success: function(response) {
                $('#submitBtn').removeAttr('disabled');
                $('#submitBtn').val('Reject');
                alert(response);
                $('#exampleModal').modal('toggle')
            }
        });
    });
</script>