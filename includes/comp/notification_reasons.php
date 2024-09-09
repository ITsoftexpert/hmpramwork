<?php

if ($reason == "referral_approved") {
	return "Has approved your user referral. you have got the commission.";
}
if ($reason == "proposal_referral_approved") {
	return "Has approved your proposal referral. you have got the commission.";
}

if ($reason == "modification") {
	return "Has sent modification to your proposal.";
}

if ($reason == "declined") {
	return "Has Declined your proposal. Please submit a valid proposal.";
}

if ($reason == "approved") {
	return "Has approved your proposal. Thanks for posting.";
}

if ($reason == "unapproved_request") {
	return "Has unapproved your request. Please submit a valid request.";
}

if ($reason == "approved_request") {
	return "Has approved your request. Thanks for posting.";
}

if ($reason == "modification_request") {
	return "Has sent modification to your job request.";
}

if ($reason == "offer") {
	return "Has just sent you an offer on your request click here to view.";
}

if ($reason == "order") {
	return "Has just sent you an order.";
}

if ($reason == "order_tip") {
	return "Has has given you an tip.";
}

if ($reason == "order_message") {
	return "Updated the order.";
}

if ($reason == "order_revision") {
	return "Requested for a revision."; 
}

if ($reason == "order_completed") {
	return "Completed your order.";
}

if ($reason == "order_delivered") {
	return "Delivered your order.";
}

if ($reason == "cancellation_request") {
	return "Wants to cancel the order.";
}

if ($reason == "decline_cancellation_request") {
	return "Declined your cancellation request.";
}

if ($reason == "accept_cancellation_request") {
	return "Accepted cancellation request.";
}

if ($reason == "cancelled_by_customer_support") {
	return "Order has been cancelled by admin.";
}

if ($reason == "buyer_order_review") {
	return "Please review and rate your buyer.";
}
if ($reason == "seller_order_review") {
	return "Please review and rate your seller.";
}

if ($reason == "order_cancelled") {
	return "Your order has been cancelled.";
}

if ($reason == "withdrawal_declined") {
	return "your withdrawal request has been declined. click here to view reason.";
}

if ($reason == "withdrawal_approved") {
	return "your withdrawal request has been completed. click here to view.";
}
if ($reason == "extendTimeRequest") {
	return "Wants to extend the order delivery.";
}

if ($reason == "extendTimeDeclined") {
	return "Has Declined your extention.";
}

if ($reason == "extendTimeAccepted") {
	return "Has accepted your extension. Time was increased successfully.";
}

if ($reason == "buyerextendTimeAccepted") {
	return "Time increased successfully.";
}

if ($reason == "ticket_reply") {
	return "just responded to your ticket.";
}

if ($reason == "following") {
	return "is following you";
}
/*
if($reason == "unfollow"){
    return "Unfollow you";
}*/

if ($reason == "feedback_respond") {
	return "has given feedback response.";
}

$reports = ['offer_report_action_taken', 'users_report_action_taken', 'order_report_action_taken', 'proposal_report_action_taken', 'job_report_action_taken', 'message_report_action_taken'];

if (in_array($reason, $reports)) {
	return "has taken action against your report";
}

$pReports = ['profile_modification', 'professional_modification', 'account_modification'];
if (in_array($reason, $pReports)) {
	return "has given modification requests on profile update.";
}

$pAReports = ['profile_approved', 'professional_approved', 'account_approved'];
if (in_array($reason, $pAReports)) {
	return "has approved profile update."; 
}

if ($reason == "dispute_raised") {
	return "has raised a dispute against order #{$order_id}.";
}
