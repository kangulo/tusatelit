<?php include( locate_template( "account/_common/header.php", false, false ) ); ?>
<div class="tusatelit-dashboard <?php echo $user->user_type; ?>">
	<div class="main-content">

		<div id="primary" class="content-area" data-sticky_parent>

		<?php
			if ( in_array( $user->user_type, array("satelite","operario","proveedor","comerciante") ) )
            { 
				include( locate_template( "account/$user->user_type/sidebar.php", false, false ) ); 
			}
		?>

		<?php //if ( in_array("approved", (array) $user->roles) ): ?>
			<section class="dashboard-tusatelit" data-sticky_column>
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="content">

								<?php //include( locate_template( "account/_common/notification-bar.php", false, false ) ); ?>
								<?php printf("<h1>%s</h1>", "Messages"); ?>

							</div>
						</div>
					</div>
				</div>
			</section>
		<?php //endif; ?>
		</div><!-- /primary -->
	</div><!-- /main-content -->
</div><!-- /tusatelit-dashboar -->
<?php include( locate_template( "account/_common/footer.php", false, false ) ); ?>
