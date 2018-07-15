<?php 
	get_header();
?>
<section class="event-list-container">
	<div class="event-list">
		
		<ul>
			<?php 
			if(have_posts()):
				while (have_posts()) {
				    the_post();
			?>
				<li class="event-item">

					<div class="thumb">
						<?php the_post_thumbnail(); ?>
						<a href="<?php echo esc_attr(get_permalink()); ?>"><h2><?php the_title() ?></h2></a>
					</div>
					<div class="excerpt">
						<?php the_excerpt(); ?>
					</div>
					<div class="event-meta">
						<div class="event-date"><?php echo get_post_meta( get_the_ID(), 'em_event_date', true ); ?></div>
						<div class="event-location"><?php echo get_post_meta( get_the_ID(), 'em_event_location', true ); ?></div>
						<div class="event-website"><a href="<?php echo esc_url(get_post_meta( get_the_ID(), 'em_event_url', true )); ?>"><?php echo _e( 'Visit Website', 'event-manager' ); ?></a></div>
						<!-- <div class="add-event"><a href="<?php echo em_google_calendar_client()->createAuthUrl(); ?>"><?php echo _e( 'Add to Calendar', 'event' ); ?></a></div> -->
					</div>
				</li>
			<?php

				}
			endif;
			?>

		</ul>
	</div>
</section>
<?php
if(class_exists('Google_Client')){
		echo '<pre>';
		var_dump(Google_Service_Calendar::CALENDAR);
	}
	get_footer();