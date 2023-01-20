<?php
/**
 * View: Default Template for Events
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/events/v2/default-template.php
 *
 * See more documentation about our views templating system.
 *
 * @link http://evnt.is/1aiy
 *
 * @version 5.0.0
 */

use Tribe\Events\Views\V2\Template_Bootstrap;

get_header();
echo tribe( Template_Bootstrap::class )->get_view_html();

get_footer();
?>
<style>
        .bg_content_event{
            background-image: url('https://idealbiz.io/pt/wp-content/uploads/sites/86/2022/01/Calendario-Eventos.jpg');
            background-repeat: no-repeat;
            background-position: center;
            /* background-size: cover; */
        }
        .tribe-common h1{
            font-family: var(--font-default), sans-serif;
            font-weight: 500;
            padding-top: 50px;
            color:#fff;
        }
        .tribe-common h3{
        margin-top: 0px;
        text-align: center;
        color: #fff !important;
        padding-bottom: 0px;
        line-height: 1.5em;
        font-size: 21px;
        font-weight: 400;
        font-family: var(--font-default), sans-serif;
        }
        .tribe-common--breakpoint-medium.tribe-events .tribe-events-l-container{
            padding-top: 10px !important;
        }
        .content_event{
            min-height: 300px;
            text-align: center;
        }
</style>
