
<p>
	<label for="event-date"> <?php echo _e('Date', 'event-manager'); ?> </label>
	<input type="date" class="date-picker" name="event_date" value="<?php echo esc_attr( get_post_meta( $post->ID, 'em_event_date', true ) ); ?>" id="event-date">
</p>
<p>
	<label for="event-url"> <?php echo _e('URL', 'event-manager'); ?> </label>
	<input type="text" class="event-url " name="event_url" value="<?php echo esc_attr( get_post_meta( $post->ID, 'em_event_url', true ) ); ?>" id="event-url">
</p>
<p>
	<label for="event-location"> <?php echo _e('Location', 'event-manager'); ?> </label>
	<input type="text" class="event-location" name="event_location" value="<?php echo esc_attr( get_post_meta( $post->ID, 'em_event_location', true ) ); ?>" id="event-location">
	<div class="map"></div>
</p>