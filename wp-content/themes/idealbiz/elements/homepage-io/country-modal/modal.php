<div id="country_select_modal" class="iziModal">
    <!--
    <a href="" class="close-izi-modal d-flex justify-content-end m-t-5" data-izimodal-close="" data-izimodal-transitionout="bounceOutDown">
        <i class="icon-close"></i>
    </a>
    !-->

    <?php
    if (get_custom_logo()) {
        the_custom_logo();
    } else {
        the_custom_logo(1);
    }
    ?>

    <h2 class="text-center m-b-30"><?php echo wp_strip_all_tags( get_the_content() ); ?></h2>
    
    <?php
    Component_Countries_Modal::country_select();
    Component_Countries_Modal::language_select();
    ?>

    <a class="country-link btn-blue font-weight-bold w-100 text-center m-b-15" href="#"><?php _e('Continue', 'idealbiz'); ?></a>
    <script>
        function getCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
        }
        var x = getCookie('autohref');
        if (x) {
            jQuery('body').css('display','none');
            window.location.href = x;
        }
    </script>
</div>