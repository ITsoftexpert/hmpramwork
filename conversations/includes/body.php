<style>
	.border5 {
		/* border: 1px solid gray; */
	}
	.box-shadow-border5{
		/* box-shadow: inset 0px 0px 20px gray; */
	}
</style>

<div class="specfic col-md-9 p-0 border5 box-shadow-border5 <?= ($lang_dir == "right" ? 'order-1 order-sm-2' : '') ?>">

	<center id="selectConversation" class="mt-5 pt-5 mt-sm-5">
		<img src="../images/chat.png" width="140" alt="" class="mt-5 pt-5">
		<h3 class="mt-3 empty-heading" style="font-weight:410;"><?= $lang['inbox']['select']['title']; ?></h3>
		<p class="lead"><?= $lang['inbox']['select']['desc']; ?></p>
	</center>

	<div id="msgHeader" class="card-header bg-transparent inboxHeader2 d-none"></div>
	<div id="showSingle" class="row"></div>

</div>