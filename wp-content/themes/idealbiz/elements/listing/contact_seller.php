<?php

/*

<form class="container col-md-5 contact-seller">
    <label class="form-row" for="inputEmail4">Name</label>
    <div class="form-row">
        <div class="form-group col p-0">
            <input type="text" class="form-control first-name" id="inputEmail4" placeholder="First">
        </div>
        <div class="form-group col p-0">
            <input type="text" class="form-control last-name" id="inputPassword4" placeholder="Last">
        </div>
    </div>
    <div class="form-group form-row">
        <input type="text" class="form-control" id="inputAddress" placeholder="Telephone">
    </div>
    <div class="form-group form-row">
        <input type="text" class="form-control" id="inputAddress2" placeholder="Company">
    </div>
    <div class="form-group form-row">
        <select class="form-control country-select" id="exampleFormControlSelect1">
        <?php foreach ($countries as $country) : ?>
                <option value="<?php echo $country->iso; ?>"><?php echo $country->country; ?></option>
        <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group form-row">
        <input type="text" class="form-control" id="inputAddress2" placeholder="City">
    </div>
    <div class="form-group form-row">
        <input type="text" class="form-control" id="inputAddress2" placeholder="Zip">
    </div>
    <div class="form-group form-row">
        <textarea class="form-control" rows="5" id="inputAddress2" placeholder="Message"></textarea>
    </div>
    <div class="form-group form-row">
        <input type="email" class="form-control" id="inputAddress2" placeholder="Email">
    </div>

    <button type="submit" class="btn btn-primary">Sign in</button>
</form>

*/

?>

<div class="popWrapper" id="contact_form_id">
    <div class="popWrapper_screen"></div>
    <div class="iziModal formPopUp">
        <div class="iziModal-wrap" style="height: auto;">
            <div class="iziModal-content" style="padding: 0px;">
                <div class="content generic-form p-b-20"> 
                    <button data-izimodal-close="" class="icon-close popUpForm" href="#contact_form_id"></button>
                    <div class="clear"></div>
                    <div class="acf-form">
                        <?php echo do_shortcode('[contact-form-7 id="2935" title="Contact Seller EN" html_id="contact_seller"]'); ?>
                    </div>
                </div>    
            </div>
        </div>    
    </div>
</div>
