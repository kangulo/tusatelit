
<div class="dashboard-sidebar">
	<ul class="nav nav-stacked" data-sticky_column>
		<li <?php if ( $post->ID == 13 ) echo "class='active'"; ?>>
			<a href="<?php echo get_permalink(13);?>">
				<i class="fas fa-home"></i>
				<span>Home</span>
			</a>
		</li>
		<li <?php if ( $post->ID == 90 ) echo "class='active'"; ?>>
			<a href="<?php echo get_permalink(90);?>">
				<i class="fas fa-store"></i>
				<span>Tienda</span>
			</a>
		</li>
		<li <?php if ( $post->ID == 94 ) echo "class='active'"; ?>>
			<a href="<?php echo get_permalink(94);?>">
				<i class="fas fa-dolly-flatbed"></i>
				<span>Productos</span>
			</a>
		</li>
		<li <?php if ( $post->ID == 96 ) echo "class='active'"; ?>>
			<a href="<?php echo get_permalink(96);?>">
				<i class="far fa-envelope-open"></i>
				<span>Mensajes</span>
			</a>
		</li>
		<li <?php if ( $post->ID == 98 ) echo "class='active'"; ?>>
			<a href="<?php echo get_permalink(98);?>">
				<i class="fas fa-heart"></i>
				<span>Favoritos</span>
			</a>
		</li>
		<li class="logout align-self-end">
			<a href="<?php echo wp_logout_url(); ?>">
				<i class="fas fa-sign-out-alt"></i>
				<span>Logout</span>
			</a>
		</li>
	</ul>
</div><!-- /dashboard-sidebar -->
