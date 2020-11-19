<?php $notification = $user->getMessageFormated();?>
<?php if ($notification['total']):?>
<div class="notification">
	<div class="text">
		<p><i class="icon-notification"></i>You Have New Notifications! <a href="#collapseNotification" class="user-login" data-toggle="collapse">View Notifications</a></p>
	</div>

	<span class="notif_close icon-close"></span>
</div>
<?php endif; ?>
