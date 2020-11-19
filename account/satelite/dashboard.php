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
						<div class="col-md-12 col-sm-12 col-xs-12" t>
							<div class="content">

								<?php //include( locate_template( "account/_common/notification-bar.php", false, false ) ); ?>
								<?php printf("<h1>%s</h1>", "Dashboard"); ?>

								<div class="container mt-n10">
			                        <div class="row">
			                            <div class="col-xxl-4 col-xl-12 mb-4">
			                                <div class="card h-100">
			                                    <div class="card-body h-100 d-flex flex-column justify-content-center py-5 py-xl-4">
			                                        <div class="row align-items-center">
			                                            <div class="col-xl-8 col-xxl-12">
			                                                <div class="px-4 mb-4 mb-xl-0 mb-xxl-4">
			                                                    <h1 class="text-primary">Welcome Back!</h1>
			                                                    <p class="lead"><?php echo $user->first_name . " " .$user->last_name; ?></p>
			                                                    <p class="text-gray-700 mb-0">It's time to get started! View new opportunities now, or continue on your previous work.</p>
			                                                </div>
			                                            </div>
			                                            <div class="col-xl-4 col-xxl-12 text-center"><img class="img-fluid" src="assets/img/freepik/at-work-pana.svg" style="max-width: 26rem;"></div>
			                                        </div>
			                                    </div>
			                                </div>
			                            </div>
			                        </div>
			                        <!-- Example Colored Cards for Dashboard Demo-->
			                        <div class="row">
			                            <div class="col-xxl-3 col-lg-6">
			                                <div class="card bg-primary text-white mb-4">
			                                    <div class="card-body">
			                                        <div class="d-flex justify-content-between align-items-center">
			                                            <div class="mr-3">
			                                                <div class="text-white-75 small">Productos</div>
			                                                <div class="text-lg font-weight-bold">40</div>
			                                            </div>
			                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar feather-xl text-white-50"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
			                                        </div>
			                                    </div>
			                                    <div class="card-footer d-flex align-items-center justify-content-between">
			                                        <a class="small text-white stretched-link" href="#">View More</a>
			                                        <div class="small text-white"><svg class="svg-inline--fa fa-angle-right fa-w-8" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="angle-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512" data-fa-i2svg=""><path fill="currentColor" d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z"></path></svg><!-- <i class="fas fa-angle-right"></i> --></div>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-xxl-3 col-lg-6">
			                                <div class="card bg-warning text-white mb-4">
			                                    <div class="card-body">
			                                        <div class="d-flex justify-content-between align-items-center">
			                                            <div class="mr-3">
			                                                <div class="text-white-75 small">Solicitudes</div>
			                                                <div class="text-lg font-weight-bold">15</div>
			                                            </div>
			                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign feather-xl text-white-50"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
			                                        </div>
			                                    </div>
			                                    <div class="card-footer d-flex align-items-center justify-content-between">
			                                        <a class="small text-white stretched-link" href="#">View More</a>
			                                        <div class="small text-white"><svg class="svg-inline--fa fa-angle-right fa-w-8" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="angle-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512" data-fa-i2svg=""><path fill="currentColor" d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z"></path></svg><!-- <i class="fas fa-angle-right"></i> --></div>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-xxl-3 col-lg-6">
			                                <div class="card bg-success text-white mb-4">
			                                    <div class="card-body">
			                                        <div class="d-flex justify-content-between align-items-center">
			                                            <div class="mr-3">
			                                                <div class="text-white-75 small">Favoritos</div>
			                                                <div class="text-lg font-weight-bold">24</div>
			                                            </div>
			                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-square feather-xl text-white-50"><polyline points="9 11 12 14 22 4"></polyline><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
			                                        </div>
			                                    </div>
			                                    <div class="card-footer d-flex align-items-center justify-content-between">
			                                        <a class="small text-white stretched-link" href="#">View More</a>
			                                        <div class="small text-white"><svg class="svg-inline--fa fa-angle-right fa-w-8" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="angle-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512" data-fa-i2svg=""><path fill="currentColor" d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z"></path></svg><!-- <i class="fas fa-angle-right"></i> --></div>
			                                    </div>
			                                </div>
			                            </div>
			                            <div class="col-xxl-3 col-lg-6">
			                                <div class="card bg-danger text-white mb-4">
			                                    <div class="card-body">
			                                        <div class="d-flex justify-content-between align-items-center">
			                                            <div class="mr-3">
			                                                <div class="text-white-75 small">Mensajes</div>
			                                                <div class="text-lg font-weight-bold">17</div>
			                                            </div>
			                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle feather-xl text-white-50"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
			                                        </div>
			                                    </div>
			                                    <div class="card-footer d-flex align-items-center justify-content-between">
			                                        <a class="small text-white stretched-link" href="#">View More</a>
			                                        <div class="small text-white"><svg class="svg-inline--fa fa-angle-right fa-w-8" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="angle-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 512" data-fa-i2svg=""><path fill="currentColor" d="M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34z"></path></svg><!-- <i class="fas fa-angle-right"></i> --></div>
			                                    </div>
			                                </div>
			                            </div>
			                        </div>
			                        
			                    </div>

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
