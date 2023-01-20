<?php
// Template Name: Contacts
get_header();
?>

<section class="contacts">
    <div class="container text-center m-t-30">
        <h1 class="m-h3 m-b-15"><?php _e('Where can we help you?', 'idealbiz'); ?></h1>
        <?php _e('Contact us by e-mail or phone. We are always available to help you.', 'idealbiz'); ?>
    </div>
    <div class="container text-center m-t-30 m-b-30">
        <?php if ($map = get_field('map', 'options')) : ?>
            <div class="acf-map" data-zoom="15">
                <div class="marker" data-lat="<?php echo esc_attr($map['lat']); ?>" data-lng="<?php echo esc_attr($map['lng']); ?>"></div>
            </div>
        <?php endif; ?>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-8 m-b-10">
                <?php the_content(); ?>
            </div>
            <div class="col-12 col-sm-4 text-center">

                <div class="other-contacts-wrapper text-left">
                    <h3 class="text-uppercase">
                        <?php _e('Other contacts', 'idealbiz'); ?>
                    </h3>
                    <?php if ($phone = get_field('phone', 'options')) : ?>
                        <div>
                            <div class="other-contacts-title">
                                <?php _e('Phone', 'idealbiz'); ?>
                            </div>
                            <div>
                                <a href="tel:<?php echo $phone['call_code'] . $phone['number']; ?>"><?php echo $phone['call_code'] . " " . $phone['number']; ?></a>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($email = get_field('email', 'options')) : ?>
                        <div>
                            <div class="other-contacts-title">
                                <?php _e('Email', 'idealbiz'); ?>
                            </div>
                            <div>
                                <a href="mailto:<?php echo $email ?>">
                                    <?php echo $email ?>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($address = get_field('address', 'options')) : ?>
                        <div>
                            <div class="other-contacts-title">
                                <?php _e('Address', 'idealbiz'); ?>
                            </div>
                            <div>
                                <a href="https://www.google.com/maps?q=<?php echo $address ?>">
                                    <?php echo $address ?>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>


</section>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8DxAtR_T04UmhQwb7GK0IjpqDBlaYiNQ"></script>
<script type="text/javascript">
    jQuery(document).ready(($) => {
        $('input[name="options_email"]').val('<?php echo get_field('email', 'options'); ?>');
    });
    
    (function($) {

        /**
         * initMap
         *
         * Renders a Google Map onto the selected jQuery element
         *
         * @date    22/10/19
         * @since   5.8.6
         *
         * @param   jQuery $el The jQuery element.
         * @return  object The map instance.
         */
        function initMap($el) {

            // Find marker elements within map.
            var $markers = $el.find('.marker');

            // Create gerenic map.
            var mapArgs = {
                zoom: $el.data('zoom') || 16,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            var map = new google.maps.Map($el[0], mapArgs);

            // Add markers.
            map.markers = [];
            $markers.each(function() {
                initMarker($(this), map);
            });

            // Center map based on markers.
            centerMap(map);

            // Return map instance.
            return map;
        }

        /**
         * initMarker
         *
         * Creates a marker for the given jQuery element and map.
         *
         * @date    22/10/19
         * @since   5.8.6
         *
         * @param   jQuery $el The jQuery element.
         * @param   object The map instance.
         * @return  object The marker instance.
         */
        function initMarker($marker, map) {

            // Get position from marker.
            var lat = $marker.data('lat');
            var lng = $marker.data('lng');
            var latLng = {
                lat: parseFloat(lat),
                lng: parseFloat(lng)
            };

            // Create marker instance.
            var marker = new google.maps.Marker({
                position: latLng,
                map: map
            });

            // Append to reference for later use.
            map.markers.push(marker);

            // If marker contains HTML, add it to an infoWindow.
            if ($marker.html()) {

                // Create info window.
                var infowindow = new google.maps.InfoWindow({
                    content: $marker.html()
                });

                // Show info window when marker is clicked.
                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open(map, marker);
                });
            }
        }

        /**
         * centerMap
         *
         * Centers the map showing all markers in view.
         *
         * @date    22/10/19
         * @since   5.8.6
         *
         * @param   object The map instance.
         * @return  void
         */
        function centerMap(map) {

            // Create map boundaries from all map markers.
            var bounds = new google.maps.LatLngBounds();
            map.markers.forEach(function(marker) {
                bounds.extend({
                    lat: marker.position.lat(),
                    lng: marker.position.lng()
                });
            });

            // Case: Single marker.
            if (map.markers.length == 1) {
                map.setCenter(bounds.getCenter());

                // Case: Multiple markers.
            } else {
                map.fitBounds(bounds);
            }
        }

        // Render maps on page load.
        $(document).ready(function() {
            $('.acf-map').each(function() {
                var map = initMap($(this));
            });
        });
    })(jQuery);
</script>

<?php get_footer(); ?>