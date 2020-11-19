<?php 
/*Template Name: My Account - Favorites*/
if ( !$user->isLoggedIn() ) {  wp_safe_redirect(site_url()); exit; }
get_header();
?>
<?php 
if ( file_exists( get_template_directory() . "/account/$user->user_type/favorites.php" ) ) 
{
    include( locate_template( "account/$user->user_type/favorites.php", false, false ) ); 
}
else
{
    wp_safe_redirect(site_url());   exit;
}
?>
<script>
	(function($){

		// Clear and remove notification from notification bar
		$(document).on('click','.clear-notification,.clear-all-notifications', function(e){
			e.preventDefault();
    
            var action     = 'markasread';                  
            var ajax_nonce = '<?php echo wp_create_nonce('markasread-nonce'); ?>';
            var notification_id = $(this).data('id');

            jQuery.ajax({
                url: ajax.ajax_url,
                type: 'POST',
                dataType: 'html',
                //cache: true,
                data: {
                    action: action,
                    notification_id: notification_id,
                    security: ajax_nonce,
                },
                beforeSend: function() {
                    // Show full page LoadingOverlay
                    //jQuery.LoadingOverlay("show");
                },
                complete: function() {
                    // Hide it after complete
                    //jQuery.LoadingOverlay("hide");
                    //$P$Biub/WRryrBN85vRrkH0ETmSwNj/bC1
                },
                success: function(data) {  
                    var resp = jQuery.parseJSON(data); 
                    if (resp.success)
                    {
                        swal.fire({
                            type: 'success',
                            title: resp.title
                        });
                        $('.notifications-tray').html(resp.message);
                        $('.notification-counter').html(resp.total);
                    }
                    else
                    {
                        swal.fire({
                            type: 'error',
                            title: resp.title,
                            html: resp.message,
                            timer: (resp.autoclose) ? 3000 : null,
                            onClose: function(){ 
                                if (resp.redirect_url != null ){ 
                                    window.location = resp.redirect_url;
                                }
                            }
                        });
                    }
                
                }
            });
		});

	}(jQuery))
</script>