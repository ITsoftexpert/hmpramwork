<?php if ($current_balance >= $sub_total) { ?>
	<form action="shopping_balance" method="post" id="shopping-balance-form">
		<button class="btn btn-lg btn-success btn-block" type="submit" name="checkout_submit_order" onclick="return confirm('Are you sure you want to pay for this with your shopping balance?')">
			<?= $lang['button']['pay_with_shopping']; ?>
		</button>
	</form>
<?php } ?>


<?php if ($enable_tazapay == "yes") { ?>
	<form action="checkout_tazapay" method="post" id="tazpay-form">
		<button class="btn btn-lg btn-success btn-block" type="submit" name="checkout_submit_order">
			<?= $lang['button']['pay_with_tazapay']; ?>
		</button>
	</form>
<?php } ?>

<?php if ($enable_paypal == "yes") { ?>

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







<?php } ?>

<?php if ($enable_stripe == "yes") { ?>
	<form action="checkout_charge" method="post" id="credit-card-form"><!--- credit-card-form Starts --->
		<input name='stripe' type='submit' class="btn btn-lg btn-success btn-block" value='<?= $lang['button']['pay_with_stripe']; ?>' />
	</form>
<?php } ?>


<?php if ($enable_2checkout == "yes") { ?>
	<form action='plugins/paymentGateway/2checkout_charge' id="2checkout-form" method='post'>
		<input name='2Checkout' type='submit' class="btn btn-lg btn-success btn-block" value='<?= $lang['button']['pay_with_2checkout']; ?>' />
	</form>
<?php } ?>


<?php if ($enable_mercadopago == "1") { ?>
	<form action="mercadopago_charge" method="post" id="mercadopago-form">
		<input type="submit" name="mercadopago" class="btn btn-lg btn-success btn-block" value="<?= $lang['button']['pay_with_mercadopago']; ?>">
	</form>
<?php } ?>

<?php if ($enable_coinpayments == "yes") { ?>

	<form action="crypto_charge" method="post" id="coinpayments-form">
		<button type="submit" name="coinpayments" class="btn btn-lg btn-success btn-block"><?= $lang['button']['pay_with_coinpayments']; ?></button>
	</form>

<?php } ?>

<?php if ($enable_paystack == "yes") { ?>
	<form action="paystack_charge" method="post" id="paystack-form"><!--- paypal-form Starts --->
		<button type="submit" name="paystack" class="btn btn-lg btn-success btn-block"><?= $lang['button']['pay_with_paystack']; ?></button>
	</form>
<?php } ?>

<?php

if ($enable_dusupay == "yes") {
	$form_action = "dusupay_charge";
	include("includes/comp/dusupay_method.php");
}

?>