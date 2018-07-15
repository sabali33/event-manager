<?php 
get_header();
?>
<?php
			if(isset($_GET['code'])){
				$client = em_google_calendar_client();
				$client->authenticate($_GET['code']);
				$client->setAccessToken($client->getAccessToken());
				$service = new Google_Service_Calendar($client);
				$calendarId = 'primary';
				$optParams = array(
				  'maxResults' => 10,
				  'orderBy' => 'startTime',
				  'singleEvents' => true,
				  'timeMin' => date('c'),
				);
				$results = $service->events->listEvents($calendarId, $optParams);
				$date = get_post_meta(get_the_id(), 'em_event_date', true);
				$event_date = new DateTime($date, new DateTimeZone('America/Los_Angeles'));
				
				$end_date =  $event_date->modify('+1 day');//new DateTime('07/25/2018');
				


				$calendarDateTime = new \Google_Service_Calendar_EventDateTime();
				$calendarDateTime->setDateTime($event_date->format(\DateTime::RFC3339));
				$calendarDateTimeEnd = new \Google_Service_Calendar_EventDateTime();
				$calendarDateTimeEnd->setDateTime($end_date->format(\DateTime::RFC3339));
				//2018-07-15T18:48:22+00:00
				$event_data  = [
					'summary' => get_the_title(),
					'location' => get_post_meta(get_the_id(), 'em_event_location', true),
					'start' => array(
					    'dateTime' => $event_date->format('Y-m-d\TH:i:sP'),//$calendarDateTime->DateTime,//date_format( $date, 'Y-m-d H:i:s'),
					    'timeZone' => 'America/Los_Angeles',

					),
					'description' => get_the_content(),
					'end' => array(
						'dateTime' => $end_date->format('Y-m-d\TH:i:sP'),//$calendarDateTimeEnd->DateTime,
    					'timeZone' => 'America/Los_Angeles',
					),
					'reminders' => array(
					    'useDefault' => FALSE,
					    'overrides' => array(
					      array('method' => 'email', 'minutes' => 24 * 60),
					      array('method' => 'popup', 'minutes' => 10),
					    ),
				    ),
				];
				$event = new Google_Service_Calendar_Event($event_data);
				$results = $service->events->insert($calendarId, $event );
				var_dump($results);
			}
		?>
<section class="event-container">
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
						<div class="add-event"><a href="<?php echo em_google_calendar_client()->createAuthUrl(); ?>"><?php echo _e( 'Add to Calendar', 'event' ); ?></a></div>
					</div>
				</li>
			<?php

				}
				
			endif;
			?>
</section>
<?php
get_footer();