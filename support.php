<?php
session_start();
require_once("includes/db.php");
require_once("functions/mailer.php");
require_once("social-config.php");
if (isset($_GET['view_tickets'])) {
	include_once('ticket_support/view_tickets.php');
} elseif (isset($_GET['new_ticket'])) {
	include_once('ticket_support/new_ticket.php');
} elseif (isset($_GET['view_conversation'])) {
	include_once('ticket_support/view_conversation.php');
}
