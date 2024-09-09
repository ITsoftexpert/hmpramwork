<a href="index?view_proposals" class="<?=isset($_GET['view_proposals']) ? 'make-black font-weight-bold ' : ''?>mr-2">
    All (<?= $count_all_proposals; ?>)
</a>

<span class="mr-2">|</span>

<a href="index?view_proposals_active" class="<?=isset($_GET['view_proposals_active']) ? 'make-black font-weight-bold ' : ''?>mr-2">

    Active (<?= $count_active_proposals; ?>)

</a>

<span class="mr-2">|</span>

<a href="index?view_proposals_featured" class="<?=isset($_GET['view_proposals_featured']) ? 'make-black font-weight-bold ' : ''?>mr-2">

    Featured (<?= $count_featured_proposals; ?>)

</a>

<span class="mr-2">|</span>

<a href="index?view_proposals_pending" class="<?=isset($_GET['view_proposals_pending']) ? 'make-black font-weight-bold ' : ''?>mr-2">

    Pending Approval (<?= $count_pending_proposals; ?>)

</a>

<span class="mr-2">|</span>

<a href="index?view_proposals_paused" class="<?=isset($_GET['view_proposals_paused']) ? 'make-black font-weight-bold ' : ''?>mr-2">

    Paused (<?= $count_pause_proposals; ?>)

</a>

<span class="mr-2">|</span>

<a href="index?view_proposals_deleted" class="<?=isset($_GET['view_proposals_deleted']) ? 'make-black font-weight-bold ' : ''?>mr-2">

    Delete Requests (<?= $count_delete_proposals; ?>)

</a>

<span class="mr-2">|</span>

<a href="index?view_proposals_trash" class="<?=isset($_GET['view_proposals_trash']) ? 'make-black font-weight-bold ' : ''?>mr-2">

    Trash (<?= $count_trash_proposals; ?>)

</a>