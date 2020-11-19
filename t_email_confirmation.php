<?php 
/*Template Name: Email Confirmation */
get_header(); 
?>
<?php
$title_modal = "";
$message = "";
//if(!$user->isLoggedIn())
{
    $confirmation_code = filter_var($wp_query->query_vars['code'],FILTER_SANITIZE_STRING);
    $email = filter_var(urldecode($wp_query->query_vars['email']),FILTER_VALIDATE_EMAIL);
    $exists = email_exists( $email );
    // Check if email is valid and exist into database
    if($email && $exists)
    {    	
    	// get confirmation code from database
    	$exist_code = get_user_meta( $exists,'email_conf_code',true );    
        $user = new User($exists);	
    	// Check if is not empty and is equal to our confirmation code
        if ( !empty($exist_code) && ($exist_code == $confirmation_code))
        {
        	// Check if is validate already
        	$email_valid = get_user_meta( $exists,'email_valid',true );
        	if (intval($email_valid) === 1) {
            	$title_modal = "Thank You!";
            	$message = "<p>It seems that you already confirm this email. You can go directly to login page.</p><a class='btn' href=".get_site_url().">Login</a>";
        	}
        	else // Confirm Email
        	{
        		update_user_meta($exists, 'email_valid', 1);
            	update_user_meta($exists, 'email_conf_date', date("Y-m-d H:i:s"));	
                
	            $title_modal = "Thank you for confirming your Email Address!";
	            $message = "<p>We appreciate having you as a new member of the Responder Portal community and now you can start creating your profile too by going to your &quot;My Account&quot; link from the top right of any page.  Congratulations again and welcome!</p> <a class='btn' href=".get_site_url().">Login</a> ";
        	}
        }
        else // Confirmation code doesnt match
        {
            $title_modal = "Ops! Something weird happend";
            $message = "Sorry, We couldn't validate your confirmation code.";
        }
    }
    else // User doesnt exist into database.
    {
        $title_modal = "Ops! Something weird happend";
        $message = "<p>We couldn't validate your email. Maybe you need to be registered first. To do that do click here</p> <a class='btn' href=".get_permalink(34).">Register</a>";
    }
}
?>

<section class="module-main default-content">
	<div class="container">
    	<div class="default-inner-wrap">
        	<h2 class="title"><?php echo $title_modal; ?></h2>
    		<article><?php echo $message; ?></article>
        </div>
    </div>
</section>

<?php get_footer(); ?>