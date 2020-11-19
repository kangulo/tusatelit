<?php include( locate_template( "account/_common/header.php", false, false ) ); ?>
<div class="tusatelit-dashboard <?php echo $user->user_type; ?>">
	<div class="main-content">

		<div id="primary" class="content-area" data-sticky_parent>

		<?php
			if ( in_array( $user->user_type, array("satelite","operario","proveedor","comerciante") ) )
            { 
				include( locate_template( "account/$user->user_type/sidebar.php", false, false ) ); 
			}

			$store   			= new Store($user->store_id);
			$profile_pic_url    = $store->getProfilePicURL(); 
			$profile_banner_url = $store->getProfileBannerPicURL();
		?>

		<?php //if ( in_array("approved", (array) $user->roles) ): ?>
			<section class="dashboard-tusatelit" data-sticky_column>
				<div class="container-fluid">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="content">

								<?php //include( locate_template( "account/_common/notification-bar.php", false, false ) ); ?>
								<?php printf("<h1>%s</h1>", $store->title); ?>
								
								<div class="listing-main" id="basics">
									<form id="formStoreInfo">	
										<div class="listing-item">
											<h6 class="tab-title tab-title-gray"><i class="far fa-id-card"></i>&nbsp;Basic Information</h6>
											<div class="listing-basics">
												<!-- <div id="banner_pic" class="listing-cover cover-bg radius-top-12" style="background-image: url('https://boxingforfitness.com/wp-content/themes/boxing-for-fitness/images/default_banner.jpg');">					 -->
												<div id="banner_pic" class="listing-cover cover-bg radius-top-12" style="background-image: url('<?php echo esc_url( $profile_banner_url ); ?>');">					
													<button type="button" class="i-2 i-2-hover edit-cover btn-upload-photo"><i class="fas fa-pencil-alt"></i>&nbsp;</button>
													<input type="file" class="hidden" accept='image/*' name="" onchange="upload_banner_pic(this);">
													<div class="profile-image-wrapper btn-upload-photo">
														
														<img id="profile_pic" src="<?php echo esc_url( $profile_pic_url ); ?>" alt="">
														<!-- <img id="profile_pic" src="<?php echo get_template_directory_uri(); ?>/images/default-user-image.png" alt=""> -->
														<div class="edit-profile-photo">
															<button type="button"><i class="fas fa-camera"></i>&nbsp;</button>
														</div>
													</div>
													<input type="file" class="hidden" accept='image/*' name="" onchange="upload_profile_pic(this);">					
												</div><!--listing-cover-->

												<div class="listing-basics-body basics-body-2 radius-bottom-12">
													
													<div class="row row-eq-height block-1400">
														<div class="col-md-6 left-box">
															<div class="box-1">
																<div class="row">
																	<div class="col-md-6">
																		<div class="form-group">
																			<label>Nombre Tienda</label>
																			<input class="form-control" type="text" name="storedata[title]" id="storedata_title" autocomplete="given-name" value="<?php echo esc_attr( $store->title ); ?>" placeholder="Tienda XYZ">
																		</div>
																			
																		<div class="form-group">
																			<label>Website</label>
																			<input class="form-control" placeholder="Enter website" type="text" name="storedata[website]" id="storedata_website" value="<?php echo esc_attr( $store->website ); ?>">
																		</div>
																	</div>
																	<div class="col-md-6">
																		<div class="form-group">
																			<label>Email</label>
																			<input class="form-control" type="email" name="storedata[email]" id="storedata_email" value="<?php echo esc_attr( $store->email ); ?>" placeholder="email@store.com">
																		</div>
																		<div class="form-group">
																			<label>Phone Number</label>
																			<input class="form-control" type="text" name="storedata[phone]" id="storedata_phone" value="<?php echo esc_attr( $store->phone ); ?>">
																		</div>
																	</div>
																	<div class="col-md-12">
																		<div class="form-group">
																			<label>Street Address/City/State/Zip</label>
																			<input class="form-control" type="text" name="storedata[address]" id="userdata_location_autocomplete" value="<?php echo esc_attr( $store->google_map['address'] ); ?>">
											                                <input type="hidden" name="storedata[latitude]" id="latitude" value="<?= ($store->latitude != '') ? $store->latitude : '';?>"/>
											                                <input type="hidden" name="storedata[longitude]" id="longitude" value="<?=($store->longitude != '') ? $store->longitude : '';?>"/>
											                                <input type="hidden" name="storedata[city]" id="city" value="<?=($store->city != '') ? $store->city : '';?>"/>
											                                <input type="hidden" name="storedata[state]" id="state" value="<?=($store->state != '') ? $store->state : '';?>"/>
											                                <input type="hidden" name="storedata[state_short]" id="state_short" value="<?=($store->state_short != '') ? $store->state_short : '';?>"/>
											                                <input type="hidden" name="storedata[country]" id="country" value="<?=($store->country != '') ? $store->country : '';?>"/>
											                                <input type="hidden" name="storedata[country_short]" id="country_short" value="<?=($store->country_short != '') ? $store->country_short : '';?>"/>
											                                <input type="hidden" name="storedata[postcode]" id="postcode" value="<?=($store->postcode != '') ? $store->postcode : '';?>"/>
																		</div>
																		<div class="form-group">
																			<div class="align-center-v bff-toggle-btn-wrapper">
																				<span>Map Status:</span>
																				<div class="bff-toggle-btn">
																					<input id="a" name="storedata[store_display_status]" type="checkbox" <?php checked( "1", $store->store_display_status); ?> value="1">
																					<label for="a">
																						<div class="toggle__switch" data-checked="Show" data-unchecked="Hide"></div>
																					</label>
																				</div><!--bff-toggle-btn-->
																			</div>
																		</div>									
																	</div>
																</div>
															</div><!--box-1-->
															
														</div><!--left-->
														<div class="col-md-6 right-box">
															<div class="box-3">
																<div class="form-group">
																	<label>Description (750 Characters Max)</label>
																	<textarea class="form-control" name="storedata[description]" id="storedata_description" rows="8" placeholder="Somos una empresa..."><?php echo esc_attr( $store->description ); ?></textarea>
																</div>
															</div><!--box-3-->
														</div><!--right-->
													</div>					
												</div><!--listing-basics-body-->
											</div>
											<p class="text-right">

												<button type="button" class="button saving_store_information_details">Save changes</button>
											</p>
										</div><!--listing-item-->

										<div class="listing-item" id="specials">
											<h6 class="tab-title tab-title-gray"><i class="fas fa-tags"></i>&nbsp;Special Offers</h6>
											<div class="special-offers-input-body radius-12 box-shadow align-center-v">
												<div class="row">
												<div class="col-md-5">
													<div class="form-group">
														<label class="mb-5">Special Offer Description (350 Characters Max)</label>						
														<textarea class="form-control textarea-offer-desc" name="storedata[special_offer_text]" id="storedata_special_offer_text"><?php echo esc_attr( $store->special_offer_text ); ?></textarea>
													</div>
												</div>
												<div class="col-md-2">
													<span>Or</span>
												</div>
												<div class="col-md-5">
													<div class="top-row d-flex align-center-v">
														
														<div class="align-center-v">
															<h6>Show Photo or Text</h6>
															<div class="d-flex">
																<div class="radio-type-1">
																	<input type="radio" id="r1" name="storedata[special_offer_option]" value="text" <?php checked( "text", $store->special_offer_option); ?> autocomplete="off">
																	<label for="r1">Text</label>
																</div>
																<div class="radio-type-1">
																	<input type="radio" id="r2" name="storedata[special_offer_option]" value="image" <?php checked( "image", $store->special_offer_option); ?> autocomplete="off">
																	<label for="r2">Photo</label>
																</div>
															</div>
														</div>
														
														<div>
															<div class="align-center-v bff-toggle-btn-wrapper special-offers-status">
																<span>Offer Status:</span>
																<div class="bff-toggle-btn">
																	<input id="a11" name="storedata[special_offer_display_status]" type="checkbox" value="1" <?php checked( "1", $store->special_offer_display_status); ?>>
																	<label for="a11">
																		<div class="toggle__switch" data-checked="Show" data-unchecked="Hide"></div>
																	</label>
																</div><!--bff-toggle-btn-->
															</div>
														</div>
													</div><!--top-row-->
													<div class="bottom-row">
														<div class="box">
															<div id="special_offer_image" class="btn-upload-photo radius-12" style="background-size: cover; background-repeat: no-repeat; background-position: 50% 50%;background-image: url('<?php echo esc_url( $special_offer_image_url ); ?>');">
																<div class="align-center-v"><i class="fas fa-camera"></i>&nbsp;</div>
															</div>
															<input type="file" class="hidden" accept='image/*' name="special_offer_image"  onchange="upload_special_offer_pic(this);">
													</div>
												</div>
												</div>
											</div><!--special-offers-input-body-->
											<p class="text-right">

												<button type="button" class="button saving_store_information_details"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
											</p>
										</div><!--listing-item-->

										<div class="listing-item" id="social">
											<h6 class="tab-title tab-title-gray"><i class="fas fa-thumbs-up"></i>&nbsp;Social</h6>
											<div class="listing-social row">
												<div class="col-lg-3 col-sm-6">
													<div class="inner box-shadow radius-12">
														<span class="tw"><i class="icon-twitter"></i>&nbsp;Twitter Profile URL:</span>
														<input class="form-control" type="text" name="storedata[twitter]" id="storedata_twitter" value="<?php echo esc_attr( $store->twitter ); ?>" placeholder="Https://…">
													</div>
												</div>
												<div class="col-lg-3 col-sm-6">
													<div class="inner box-shadow radius-12">
														<span class="fb"><i class="icon-facebook"></i>&nbsp;Facebook Profile URL:</span>
														<input class="form-control" type="text" name="storedata[facebook]" id="storedata_facebook" value="<?php echo esc_attr( $store->facebook ); ?>" placeholder="Https://…">
													</div>
												</div>
												<div class="col-lg-3 col-sm-6">
													<div class="inner box-shadow radius-12">
														<span class="insta"><i class="icon-instagram"></i>&nbsp;Instagram Profile URL:</span>
														<input class="form-control" type="text" name="storedata[instagram]" id="storedata_instagram" value="<?php echo esc_attr( $store->instagram ); ?>" placeholder="Https://…">
													</div>
												</div>
												<div class="col-lg-3 col-sm-6">
													<div class="inner box-shadow radius-12">
														<span class="yt"><i class="icon-youtube"></i>&nbsp;Youtube Channel URL:</span>
														<input class="form-control" type="text" name="storedata[youtube]" id="storedata_youtube" value="<?php echo esc_attr( $store->youtube ); ?>" placeholder="Https://…">
													</div>
												</div>
												<div class="col-lg-3 col-sm-6">
													<div class="inner box-shadow radius-12">
														<span class="pin"><i class="icon-pinterest-p"></i>&nbsp;Pinterest Profile URL:</span>
														<input class="form-control" type="text" name="storedata[pintrest]" id="storedata_pintrest" value="<?php echo esc_attr( $store->pintrest ); ?>" placeholder="Https://…">
													</div>
												</div>
												<div class="col-lg-3 col-sm-6">
													<div class="inner box-shadow radius-12">
														<span class="linkedin"><i class="icon-linkedin"></i>&nbsp;LinkedIn Profile URL:</span>
														<input class="form-control" type="text" name="storedata[linkedin]" id="storedata_linkedin" value="<?php echo esc_attr( $store->linkedin ); ?>" placeholder="Https://…">
													</div>
												</div>
												<div class="col-lg-3 col-sm-6">
													<div class="inner box-shadow radius-12">
														<span class="g-plus"><i class="icon-gplus"></i>&nbsp;Google+ Profile URL:</span>
														<input class="form-control" type="text" name="storedata[google]" id="storedata_google" value="<?php echo esc_attr( $store->google ); ?>" placeholder="Https://…">
													</div>
												</div>
												<div class="col-lg-3 col-sm-6">
													<div class="inner box-shadow radius-12">
														<span class="rss"><i class="icon-rss"></i>&nbsp;Blog Feed URL:</span>
														<input class="form-control" type="text" name="storedata[blog]" id="storedata_blog" value="<?php echo esc_attr( $store->blog ); ?>" placeholder="Https://…">
													</div>
												</div>

											</div><!--listing-social-->
										</div><!--listing-item-->
										<p class="text-right">
											<button type="button" class="button saving_store_information_details"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
										</p>
										<input type="hidden" name="userdata[ID]" value="<?php print $user->ID; ?>">

										<div class="listing-item" id="videos">
											<h6 class="tab-title tab-title-gray"><i class="fas fa-film"></i>&nbsp;Featured Video</h6>
											<div class="listing-social row">
												<div class="col-lg-6 col-sm-6">
													<div class="inner box-shadow radius-12">
														<span class="yt"><i class="icon-youtube"></i>&nbsp;Youtube Video URL:</span>
														<input class="form-control" type="text" name="storedata[youtube_video_url]" id="storedata_youtube_video_url" value="<?php echo esc_attr( $store->youtube_video_url ); ?>" placeholder="Https://…">
													</div>
												</div>
												<div class="col-lg-6 col-sm-6">
													<a href="<?php echo esc_attr( $store->youtube_video_url ); ?>" class="bff-popup-video" title="<?php echo esc_attr( $store->youtube_video_url ); ?>" style="<?php echo ($store->youtube_video_url == "") ? "display: none;" : "display: block;"?>">
														<div class="dot-box"></div>	
														<div class="cover-bg" style="background-image: url(<?php echo $store->youtube_video_url_thumbnail; ?>);"><span></span></div>
													</a>
													
												</div>
											</div>
											<input type="hidden" name="storedata[youtube_video_url_thumbnail]" value="<?php echo $store->youtube_video_url_thumbnail; ?>" id="youtube_video_url_thumbnail">
										</div><!--listing-item-->
										<p class="text-right">
											<button type="button" class="button saving_store_information_details"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
										</p>
									</form>

									<div class="listing-item mb-5" id="photos">
										<h6 class="tab-title tab-title-gray"><i class="fas fa-camera"></i>&nbsp;Photos</h6>
										<div class="listing-photos row">
											<?php 
												//$photos = $store->getPhotos();
											?>
											<?php //if( $photos ):?>
												<?php //foreach( $photos as $photo ): ?>
												<div class="photo-wrapper popup-gallery" id="photo_<?php echo $photo['ID']; ?>">
													<div class="inner overlay-wrapper radius-12 cover-bg" style="background-image: url('<?php echo $photo['sizes']['thumbnail']; ?>');">
														<div class="overlay-1 radius-12 align-center-v">
															<div>
																<button class="i-2"><i class="icon-bin delete_photo" data-photo_id="<?php echo $photo['ID']; ?>"></i></button>
																<span>Delete</span>
															</div>
															<div>
																<a href="<?php echo $photo['url']; ?>" class="i-2 gallery"><i class="icon-zoom"></i></a>
																<span>Zoom</span>
															</div>
														</div>
													</div><!--photo-wrapper-->
												</div>
												<?php //endforeach; ?>
											<?php //endif;?>
											<div class="photo-wrapper">
												<button class="photo-upload box-shadow radius-12"><i class="icon-photo"></i></button>
											</div>
										</div>
									</div><!--listing-item-->

									<div class="listing-item mb-5" id="favorites">
										<h6 class="tab-title tab-title-gray"><i class="fas fa-star"></i>&nbsp;Favorites</h6>

										<div class="listing-fav-main row flex-col-main">
											<?php 
												//$favoriteListings = $store->getFavoriteListings();

												//if ( $favoriteListings ): 
								                //	foreach ( $favoriteListings as $favoriteListing ):
									            //    	$aux_user = new User($favoriteListing);
									            //    	$aux_listing = $aux_user->getListing();
											?>
													<div class="listing-fav col-lg-4 col-md-6 col-xs-12 fav-sm-boxes" id="favorite_<?php print $aux_listing->ID;?>">
														<div class="radius-12 overlay-wrapper inner-main box-shadow">
															<a href="<?php print get_permalink($aux_listing->ID);?>">
																<div class="align-center-v top-row">
																	<img class="small-user-profile-pic" src="<?php //echo $aux_listing->getProfilePicURL(); ?>" alt="">
																	<div>
																		<h6><?php //echo $aux_listing->full_name; ?></h6>
																		<span><?php //echo $aux_listing->city_short_state; ?></span>
																	</div>
																	<i class="icon-like"></i>
																</div>
															</a>
														</div><!--listing-fav-->
													</div>
												<?php// endforeach; ?>
											<?php //else: ?>
								            	<h6 class="tab-title tab-title-red">Oops, looks like nobody has Favorited you yet!</h6>
								            <?php //endif; ?>
										</div>
									</div><!--listing-item-->

									<div class="listing-item mb-5" id="reviews">
										<h6 class="tab-title tab-title-gray"><i class="fas fa-comment"></i>&nbsp;Reviews</h6>

										<div class="reviews-main flex-col-main row">
											<?php 
												//$reviews = $store->getReviews();
								                // Review Loop
								                //if ( $reviews ) :
								                    //foreach ( $reviews as $review ) :     
								                    //    $user_review = new User($review->user_id);  
								                    //    $listing_review = $user_review->getListing(); 
								                    //    $rating = get_comment_meta($review->comment_ID,'comment_rating', true);
											?>
														<div class="col-lg-4 col-md-6" id="review_<?php //print $review->comment_ID;?>">
															<div class="box overlay-wrapper radius-12">
																<div class="align-center-v top-row">
																	<img src="<?php //echo $user_review->getProfilePicURL(); ?>" alt="">
																	<div>
																		<h6><?php //echo $user_review->full_name; ?></h6>
																		<span><?php //echo $user_review->user_login; ?></span>
																		<div><?php //echo _rating_render($rating); ?></div>
																	</div>
																</div>

																<div class="content-box">
																	<?php //echo $review->comment_content; ?>
																	<div class="date"><em><?= date( "F j, Y \a\\t g:i a",strtotime($review->comment_date)); ?></em></div>
																</div>
																<div class="overlay-1 align-center-v">
																	<div>
																		<a href="<?php //print get_permalink($listing_review->ID);?>" class="i-2"><i class="icon-link"></i></a>
																		<span>View Listing</span>
																	</div>
																</div>
															</div><!--review-->
														</div>
												<?php //endforeach; ?>
											<?php //else: ?>
								            	<h6 class="tab-title tab-title-red">Oops, looks like nobody has reviewed you yet!</h6>
								            <?php //endif; ?>
										</div><!--reviews-main-->
									</div><!--listing-item-->
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