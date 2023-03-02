<?php
/* Member search results
 * Presents members in card form
 */

$array = array (1, 2, 3, 4, 5);
?>

<div class="expert-preview m-t-20">
    <?php foreach ($array as $a): ?>
        <div data-escalao="" data-fee="" data-ppc-fixo="" data-f="" data-competencyfactor="" data-expert="" data-locations="" class="p-20 m-b-20 service_cat_[TODO] location_[TODO] expert-card position-relative flex-column black--color white--background dropshadow font-weight-medium">
            <div class="d-flex flex-row center-content">
                <div class="w-100px h-100px b-r d-block o-hidden no-decoration">
                    <img class="w-100 h-100 object-cover" src="[TODO]"/>
                    <div class="calc-100-120 h-100 d-flex justify-content-between flex-column p-y-10 p-x-17">
                        <div>
                            <h3 class="font-weight-semi-bold base_color--color">[Member]</h3>
                        </div>
                        <span class="small">[Role]</span>
                        <div class="cl_icon location p-t-10 font-weight-bold">
                            <span class="cl_icon-local dashicons dashicons-yes-alt"/>[Payment]</div>
                        <span class="small location p-t-10 font-weight-bold">
                            <i class="icon-local"/>
                            <span class="text-uppercase">[Location]</span>
                        </span>
                    </div>
                    <a href="#" data-izimodal-open="#[TODO]" class="info-balloon info-modal">i</a>
                </div>
            </div>
        </div>
    <?php endforeach ?>

    <div class="not-found" style="display:none;">
        <p class="not-found" style="display: none;">[Not found]</p>
    </div>
    <span id="result_D" class="cl_aviso">[Not found Message]</span>
</div>