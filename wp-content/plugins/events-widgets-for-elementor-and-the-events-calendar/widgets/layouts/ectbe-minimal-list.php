<?php
$ev_day     = tribe_get_start_date($event_id, false, 'd' );
$ev_month   = tribe_get_start_date($event_id, false, 'M' );
$event_year = tribe_get_start_date( $event_id, true, 'F Y' );

$events_html.='<div id="event-'.esc_attr($event_id).'" class="ectbe-list-posts '.esc_attr($style).' '.esc_attr($event_type).'">';
	$events_html.='<div class="ectbe-event-date-tag"><div class="ectbe-event-datetimes">
		        <span class="ev-mo">'.esc_html( $ev_month ).'</span>
		        <span class="ev-day">'.esc_html($ev_day).'</span>
		        </div></div>';
	$events_html.='<div class="ectbe-event-details">
            <div class="ectbe-event-datetime"><i class="ectbe-icon-clock"></i>
            <span class="ectbe-minimal-list-time">'.esc_html($ev_time).'</span></div>';
    		$events_html.='<div class="ectbe-events-title">'.wp_kses_post($event_title).'</div>'; 
			$events_html.='<div class="ectbe-'.esc_attr( $style ).'-more"><a href="'.esc_url( tribe_get_event_link($event_id) ).'" class="ectbe-events-read-more" rel="bookmark">'.esc_html__( 'Find out more', 'the-events-calendar' ).' &raquo;</a></div>';
	$events_html.='</div>';
$events_html .= '</div>';
