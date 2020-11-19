	<?php wp_footer(); ?>
	<script>
	    jQuery(document).ready(function($){
	        jQuery('#aux_profile_pic, #aux_banner_pic').hide();
	    });

		// Delete a user photo .
	    jQuery(document).on('click','.delete_photo', function (e) {
	        e.preventDefault();
	        swal.fire({
	            title: "Really want delete this photo?",
	            html: "Once deleted, you will not be able to recover this photo!",
	            type: "warning",
	            buttons: true,
	            dangerMode: true,
	        })
	        .then((willDelete) => {
	            if (willDelete) {
	                var photo_id = jQuery(this).data('photo_id');
	                var action = 'delete_photo'; 

	                jQuery.ajax({
	                    url: ajax.ajax_url,
	                    type: 'POST',
	                    dataType: 'html',
	                    cache: true,
	                    data: {
	                        action: action,
	                        photo_id: photo_id
	                    },
	                    beforeSend: function() {
	                        // Show full page LoadingOverlay
	                        jQuery.LoadingOverlay("show");
	                    },
	                    complete: function() {
	                        // Hide it after complete
	                        jQuery.LoadingOverlay("hide");
	                    },
	                    success: function(data) {  
	                        var resp = jQuery.parseJSON(data); 
	                        if (resp.success){
	                            swal.fire({
	                                title: resp.title,
	                                text: resp.message,
	                                type: "success",
	                                button: "OK",
	                            });
	                            jQuery('.listing-photos').find('#photo_'+ resp.photo_id).remove();
	                        }
	                        else
	                        {
	                            swal.fire({
	                                title: resp.title,
	                                text: resp.message,
	                                type: "warning",
	                                button: "OK",
	                            });
	                        }
	                    }
	                });
	                
	            } else {
	                swal.fire("Your photo is safe!");
	            }
	        });
	    });

	    // Get Uploaded user Photo
	    jQuery(document).on('click','.upload_photo', function (e) {
	        
	        jQuery.ajax({
	            url: ajax.ajax_url,
	            type: 'POST',
	            contentType: false,
	            processData: false,
	            cache: true,
	            data: new FormData(jQuery('#upload_photo_user')[0]),
	            beforeSend: function() {
	                jQuery('.upload_photo').prop("disabled",true);
	            },
	            complete: function() {
	                jQuery.colorbox.close();
	            },
	            success: function(data) { 
	                
	                var resp = jQuery.parseJSON(data); 
	                if (resp.success){
	                    jQuery('.listing-photos .photo-wrapper:last').before(
	                        `<div class="photo-wrapper popup-gallery" id="photo_` + resp.photo_id + `">
	                            <div class="inner overlay-wrapper radius-12 cover-bg" style="background-image: url('` + resp.thumb_url + `');">
	                                <div class="overlay-1 radius-12 align-center-v">
	                                    <div>
	                                        <button class="i-2"><i class="icon-bin delete_photo" data-photo_id="`+ resp.photo_id + `"></i></button>
	                                        <span>Delete</span>
	                                    </div>
	                                    <div>
	                                        <a href="` + resp.url + `" class="i-2 gallery"><i class="icon-zoom"></i></a>
	                                        <span>Zoom</span>
	                                    </div>
	                                </div>
	                            </div><!--photo-wrapper-->
	                        </div>`
	                    );
	                     jQuery('.popup-gallery').magnificPopup({
	                      delegate: 'a',
	                      type: 'image',
	                      tLoading: 'Loading image #%curr%...',
	                      mainClass: 'mfp-img-mobile',
	                      gallery: {
	                        enabled: true,
	                        navigateByImgClick: true,
	                        preload: [0,1] // Will preload 0 - before current, and 1 after the current image
	                      },
	                      image: {
	                        tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
	                        titleSrc: function(item) {
	                          return item.el.attr('title');
	                        },
	                        markup: '<div class="popup-arrow popup-prev mfp-prevent-close">'+
	                            '<i class="icon-sm-arrow-left mfp-prevent-close"></i>'+
	                        '</div>'+
	                            '<div class="mfp-figure mfp-custom-figure">'+
	                            '<div class="mfp-top-bar"><div class="mfp-title"></div></div>'+
	                            '<div class="mfp-img"></div>'+
	                            '<div class="mfp-bottom-bar">'+
	                              '<div class="mfp-close popup-arrow"></div>'+
	                            '</div>'+
	                          '</div>'+
	                          '<div class="popup-arrow popup-next mfp-prevent-close">'+
	                            '<i class="icon-sm-arrow-right mfp-prevent-close"></i>'+
	                        '</div>',
	                      }
	                    });
	                    swal.fire({
	                        title: resp.title,
	                        html: resp.message,
	                        type: "success",
	                        button: "OK",
	                    });
	                }
	                else
	                {
	                    swal.fire({
	                        title: resp.title,
	                        html: resp.message,
	                        type: "warning",
	                        button: "OK",
	                    });
	                }
	            }
	        });
	    });

	    jQuery(document).on('click','.saving_store_information_details', function (e) {        

	        var myform = document.getElementById("formStoreInfo");
	        var form = new FormData(myform);
	        form.append( 'action', 'saving_store_information_details' );
	        form.append( 'security', '<?php echo wp_create_nonce('ajax-saving-store-information-details'); ?>');

	        jQuery.ajax({
	            url: ajax.ajax_url,
	            type: 'POST',
	            contentType: false,
	            processData: false,
	            cache: false,
	            data: form,
	            beforeSend: function() {
	                // Show full page LoadingOverlay
	                jQuery.LoadingOverlay("show");
	            },
	            complete: function() {
	                // Hide it after complete
	                jQuery.LoadingOverlay("hide");
	            },
	            success: function(data) {                 
	                var resp = jQuery.parseJSON(data); 
	                if (resp.success){
	                    swal.fire({
	                        title: resp.title,
	                        html: resp.message,
	                        type: "success",
	                    });
	                    jQuery('.listing-photos').find('#photo_'+ resp.photo_id).remove();
	                }
	                else
	                {
	                    swal.fire({
	                        title: resp.title,
	                        html: resp.message,
	                        type: "warning",
	                        button: "OK",
	                    });
	                }
	            }

	        });
	    });

	    // Modal Windoup Open a forget popup.
	    jQuery(document).on('click','.photo-upload', function (e) {        

	        var action = 'get_modal_customer_add_photos';  
	        var user_id = jQuery(this).data('user_id');

	        jQuery.ajax({
	            url: ajax.ajax_url,
	            type: 'POST',
	            dataType: 'html',
	            cache: true,
	            cache: true,
	            data: {
	                action: action,
	                user_id: user_id
	            },
	            beforeSend: function() {
	                jQuery('#cboxLoadedContent').empty();
	                //jQuery('#cboxLoadingGraphic').show();
	            },
	            complete: function() {
	                //jQuery('#cboxLoadingGraphic').hide();
	            },
	            success: function(data) {  
	                jQuery.colorbox({html:data,close:'p',width:"50%"});
	            }
	        
	        });
	    });

	    // Upload profile pic
	    function upload_profile_pic(input) {

	        if (input.files && input.files[0]) {
	            var reader = new FileReader();

	            reader.onload = function (e) {

	                var image_base64 = e.target.result;
	                var action = 'upload_profile_pic';  
	                var ajax_nonce = '<?php echo wp_create_nonce('ajax-upload-profile-pic'); ?>';

	                jQuery.ajax({
	                    url: ajax.ajax_url,
	                    type: 'POST',
	                    data:{
	                        action: action,
	                        security: ajax_nonce,
	                        image_base64: image_base64
	                    },
	                    beforeSend: function() {
	                        // Show full page LoadingOverlay
	                        jQuery.LoadingOverlay("show");
	                    },
	                    complete: function() {
	                        // Hide it after complete
	                        jQuery.LoadingOverlay("hide");
	                    },
	                    success: function(data) { 
	                        
	                        var resp = jQuery.parseJSON(data); 
	                        if (resp.success){
	                            jQuery('#profile_pic').attr('src', resp.imageUrl);
	                            swal.fire({
			                        type: 'success',
			                        title: resp.title,
			                        html: resp.message	                        
			                    });
	                        }
	                        else
	                        {
	                            swal.fire({
	                                type: "warning",
	                                title: resp.title,
	                                html: resp.message,
	                            });
	                        }
	                    }
	                });
	            };

	            reader.readAsDataURL(input.files[0]);
	        }
	    }

	    // Upload banner pic
	    function upload_banner_pic(input) {

	        if (input.files && input.files[0]) {
	            var reader = new FileReader();

	            reader.onload = function (e) {

	                var image_base64 = e.target.result;
	                var action = 'upload_banner_pic';  
	                var ajax_nonce = '<?php echo wp_create_nonce('ajax-upload-banner-pic'); ?>';

	                jQuery.ajax({
	                    url: ajax.ajax_url,
	                    type: 'POST',
	                    data:{
	                        action: action,
	                        security: ajax_nonce,
	                        image_base64: image_base64
	                    },
	                    beforeSend: function() {
	                        // Show full page LoadingOverlay
	                        jQuery.LoadingOverlay("show");
	                    },
	                    complete: function() {
	                        // Hide it after complete
	                        jQuery.LoadingOverlay("hide");
	                    },
	                    success: function(data) { 
	                        
	                        var resp = jQuery.parseJSON(data); 
	                        if (resp.success){
	                            jQuery('#banner_pic').css('background-image', 'url(' + resp.imageUrl + ')');
	                            swal.fire({
			                        type: 'success',
			                        title: resp.title,
			                        html: resp.message	                        
			                    });
	                        }
	                        else
	                        {
	                            swal.fire({
	                                type: "warning",
	                                title: resp.title,
	                                html: resp.message,
	                            });
	                        }
	                    }
	                });
	            };

	            reader.readAsDataURL(input.files[0]);
	        }
	    }

	    // Upload special offer pic
	    function upload_special_offer_pic(input) {

	        if (input.files && input.files[0]) {
	            var reader = new FileReader();

	            reader.onload = function (e) {

	                var image_base64 = e.target.result;               
	                var action = 'upload_special_offer_pic';  
	                var ajax_nonce = jQuery('#update_special_offer_pic_nonce').val();

	                jQuery.ajax({
	                    url: ajax.ajax_url,
	                    type: 'POST',
	                    data:{
	                        action: action,
	                        ajax_nonce_update_special_offer_pic: ajax_nonce,
	                        image_base64: image_base64
	                    },
	                    beforeSend: function() {
	                        // Show full page LoadingOverlay
	                        jQuery.LoadingOverlay("show");
	                    },
	                    complete: function() {
	                        // Hide it after complete
	                        jQuery.LoadingOverlay("hide");
	                    },
	                    success: function(data) { 
	                        
	                        var resp = jQuery.parseJSON(data); 
	                        if (resp.success){
	                            jQuery('#special_offer_image').css('background-image', 'url(' + resp.imageUrl + ')');
	                            swal.fire({
			                        type: 'success',
			                        title: resp.title,
			                        html: resp.message	                        
			                    });
	                        }
	                        else
	                        {
	                            swal.fire({
	                                type: "warning",
	                                title: resp.title,
	                                html: resp.message,
	                            });
	                        }
	                    }
	                });
	            };

	            reader.readAsDataURL(input.files[0]);
	        }
	    }

	    // get the YouTube video ID from a URL?
	    function youtube_parser(url){
	        var regExp = /^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*/;
	        var match = url.match(regExp);
	        return (match&&match[7].length==11)? match[7] : false;
	    }

	    // Unfavorite a Listing.
	    jQuery(document).on('click','.unfavorite-listing', function (e) {
	        e.preventDefault();
	        swal.fire({
	            title: "Really want unfavorite this listing?",
	            html: "You will be able to favorite again in the public page of that listing!",
	            type: "warning",
	            buttons: true,
	            dangerMode: true,
	        })
	        .then((willDelete) => {
	            if (willDelete) {
	                var action     = 'remove_from_favorites';  
	                var listing_id = jQuery(this).data('favorite_id');
	                var ajax_nonce = jQuery('#unfavorite_listing_nonce').val();

	                jQuery.ajax({
	                    url: ajax.ajax_url,
	                    type: 'POST',
	                    dataType: 'html',
	                    cache: true,
	                    data: {
	                        action: action,
	                        listing_id: listing_id,
	                        security: ajax_nonce,
	                    },
	                    beforeSend: function() {
	                        // Show full page LoadingOverlay
	                        //jQuery.LoadingOverlay("show");
	                    },
	                    complete: function() {
	                        // Hide it after complete
	                        //jQuery.LoadingOverlay("hide");
	                    },
	                    success: function(data) {  
	                        var resp = jQuery.parseJSON(data); 
	                        if (resp.success){                            
	                            swal.fire({
			                        type: 'success',
			                        title: resp.title,
			                        html: resp.message	                        
			                    });
	                            jQuery('.listing-fav-main').find('#favorite_'+ resp.favorite_id).remove();
	                        }
	                        else
	                        {
	                            swal.fire({
	                                type: "warning",
	                                title: resp.title,
	                                html: resp.message,
	                            });
	                        }
	                    }
	                });
	                
	            } else {
	                swal.fire({
	                    title: "Deletion Aborted",
	                    html: "No changes were made",
	                    type: "success",
	                    button: "OK",
	                });
	            }
	        });
	    });
	    

	    // Delete a review.
	    jQuery(document).on('click','.delete-user-review', function (e) {
	        e.preventDefault();
	        swal.fire({
	            title: "Delete Review",
	            html: "Do you really want delete this review?",
	            type: "warning",
	            buttons: true,
	            dangerMode: true,
	        })
	        .then((willDelete) => {
	            if (willDelete) {
	                var action     = 'delete_user_review';  
	                var review_id = jQuery(this).data('review_id');
	                var ajax_nonce = jQuery('#delete_user_review_nonce').val();

	                jQuery.ajax({
	                    url: ajax.ajax_url,
	                    type: 'POST',
	                    dataType: 'html',
	                    cache: true,
	                    data: {
	                        action: action,
	                        review_id: review_id,
	                        security: ajax_nonce,
	                    },
	                    beforeSend: function() {
	                        // Show full page LoadingOverlay
	                        //jQuery.LoadingOverlay("show");
	                    },
	                    complete: function() {
	                        // Hide it after complete
	                        //jQuery.LoadingOverlay("hide");
	                    },
	                    success: function(data) {  
	                        var resp = jQuery.parseJSON(data); 
	                        if (resp.success){                            
	                            swal.fire({
	                                type: "success",
	                                title: resp.title,
	                                html: resp.message,
	                            });
	                            jQuery('.reviews-main').find('#review_'+ resp.review_id).remove();
	                        }
	                        else
	                        {
	                            swal.fire({
	                                type: "warning",
	                                title: resp.title,
	                                html: resp.message,
	                            });
	                        }
	                    }
	                });
	                
	            } else {
	            	swal.fire({
                        type: "success",
                        title: "Deletion Aborted",
                        html: "No changes were made",
                    });
	            }
	        });
	    });

	    // Add youtube Thumbnail.
	    var keyupTimeout;
	    jQuery('#listingdata_youtube_video_url').on('keyup', function() {
	        $this = jQuery(this); 
	        
	        // Very basic throttling to prevent mixer thrashing. Only search
	        // once 350ms has passed since the last keyup event

	        clearTimeout(keyupTimeout);

	        keyupTimeout = setTimeout(function() {
	            //filterByString(searchValue);
	            bg = youtube_parser($this.val());
	            if ( bg == false )
	            {
	                bg = '<?php print get_template_directory_uri();?>/images/bff_blurred_video_thumbnail.png';
	                jQuery(".bff-popup-video .cover-bg").css('background-image', 'url(' + bg + ')');
	                jQuery("#youtube_video_url_thumbnail").val('');
	                jQuery(".bff-popup-video").show();
	            }
	            else
	            {
	                jQuery(".bff-popup-video").attr("href", $this.val());
	                jQuery(".bff-popup-video .cover-bg").css('background-image', 'url(https://img.youtube.com/vi/' + bg + '/0.jpg)');
	                jQuery("#youtube_video_url_thumbnail").val('https://img.youtube.com/vi/' + bg + '/0.jpg');
	                jQuery(".bff-popup-video").show();
	            }
	             
	        }, 350);
	    });

	</script>

	</body>
</html>