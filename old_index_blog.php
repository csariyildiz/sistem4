<?php get_header(); ?>

<div class="wrapper section medium-padding">
<div style="height:100px;">
<h1 class="post-title">Bilişim Notları</h1>
</div>
	<?php
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$total_post_count = wp_count_posts();;
	$published_post_count = $total_post_count->publish;
	$total_pages = ceil( $published_post_count / $posts_per_page );
	
	if ( "1" < $paged ) : ?>
	
		<div class="page-title section-inner">
		
			<h5><?php printf( __('Page %s of %s', 'baskerville'), $paged, $wp_query->max_num_pages ); ?></h5>
			
		</div>
		
		<div class="clear"></div>
	
	<?php endif; ?>

	<div class="content section-inner">
<div style="max-width: 600px; margin-left: auto; margin-right: auto; margin-bottom: 40px;">
 <?php get_search_form(); ?>
	</div>	            
		
		<h2>Recent Posts</h2>
<ul>
<?php
	$recent_posts = wp_get_recent_posts();
	foreach( $recent_posts as $recent ){
		echo '<li><a href="' . get_permalink($recent["ID"]) . '">' .   $recent["post_title"].'</a> </li> ';
	}
	wp_reset_query();
?>
</ul>
		
		
		<?php if (have_posts()) : ?>
<div class="maintble">			
<table><th>Tarih</th><th>Gönderi Adı</th><th>Konu</th>
			<div class="posts">
				
		    	<?php while (have_posts()) : the_post(); ?>
		    	<tr><td><? the_time('d.m.y') ?></td><td><a href="<? the_permalink() ?>" target="_blank"><?php the_title() ?></a></td><td><? the_tags("") ?></td></tr>
		         		            
		        <?php endwhile; ?>
	        	                   
			<?php endif; ?>
</div>			
</table>
		</div> <!-- /posts -->
			
	</div> <!-- /content -->
	
	<?php if ( $wp_query->max_num_pages > 1 ) : ?>
		
		<div class="archive-nav section-inner">
					
			<?php echo get_next_posts_link( '&laquo; ' . __('Older posts', 'baskerville')); ?>
						
			<?php echo get_previous_posts_link( __('Newer posts', 'baskerville') . ' &raquo;'); ?>
			
			<div class="clear"></div>
			
		</div> <!-- /post-nav archive-nav -->
	
	<?php endif; ?>
			
	<div class="clear"></div>

</div> <!-- /wrapper -->
	              	        
<?php get_footer(); ?>
