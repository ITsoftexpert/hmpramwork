<div class="paypal-button-container" id="paypal-form">
    <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post">
        <input type="hidden" name="business" value="sb-ksqaz32461374@business.example.com">
        <input type="hidden" name="item_name" value="<?= $proposal_title; ?>">
        <input type="hidden" name="item_number" value="<?= $proposal_plan_id; ?>">
        <input type="hidden" name="amount" value="<?=  $sub_total; ?>"> 
        <input type="hidden" name="currency_code" value="USD"> 
        <input type="hidden" name="no_shipping" value="1">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="return" value="http://localhost/beta/success.php"> <!-- Return URL -->
        <input type="hidden" name="cancel_return" value="http://localhost/beta/decline.php"> <!-- Cancel URL -->
        <button class="btn btn-lg btn-success btn-block" type="submit">
            <?= $lang['button']['pay_with_paypal']; ?>
        </button>
    </form>
</div>