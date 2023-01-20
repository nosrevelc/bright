<?php
$tp = get_page_template_slug();
if(!$tp){
    global $template;
    $auxTp = explode('/',strrev($template));
    $tp = strrev($auxTp[0]);
}


if(pll_get_post(get_option('job_manager_jobs_page_id'))==get_the_ID() || 
pll_get_post(get_option('resume_manager_submit_resume_form_page_id'))==get_the_ID() ||
pll_get_post(get_option('resume_manager_candidate_dashboard_page_id'))==get_the_ID()
){
    $tp='page-jobs.php';
}

if(pll_get_post(get_option('resume_manager_resumes_page_id'))==get_the_ID() || 
pll_get_post(get_option('job_manager_submit_job_form_page_id'))==get_the_ID() ||
pll_get_post(get_option('job_manager_job_dashboard_page_id'))==get_the_ID()
){
    $tp='page-resumes.php';
}
//Exibe a caixa de pesquisa conforme a lista abaixo.
switch ($tp) {
    
    case 'page-result_listings.php':
    case 'page-listings.php':
    case 'submit-listing.php': 
    case 'single-listing.php':
        s_listing();
        break;
    case 'page-result_member.php':
    case 'page-experts.php':
    case 'submit-expert.php':
    case 'single-expert.php':
        s_expert();
        break;
    case 'page-franchises.php':
    case 'single-franchise.php':
        s_franchise();
        break;    
    case 'page-jobs.php':
        s_jobs();
        break;   
    case 'page-resumes.php':
        s_resumes();
        break;     
    case 'home.php':
        s_posts();  
        break;
    case 'homepage_v1.php':
        /* s_generic();  */ 
        break;
    case 'homepage_v2.php':
        /* s_generic();  */ 
        break;           
    default:
        s_generic();
        break;
}

/* Search for Generic and default search (home, contact, etc) */
function s_generic(){ ?>
    <div class="search-bar container medium-width p-t-30 p-b-34 collapse dont-collapse-sm m-0-auto toggle-search" id="site-search">
        <p class="d-none"><?php _e('Search ','idealbiz'); ?></p>
        <div class="row">
            <div class="col-md-2 d-flex align-items-center">
                <?php country_market_with_flag() ?>
            </div>
            <div class="col-md-8">
            <form id="search-form--header" class="search-form--header" action="<?php echo home_url( '/' ); ?>" method="get">
            <label class="text-global-search border-blue b-t-l-r b-b-l-r">
                <input class="g-search" type="text" name="s" id="search" autocomplete="off" minlength="3" placeholder="<?php echo _e('Search in all Idealbiz sites:').' '.home_url( '/' ); ?>"value="<?php the_search_query(); ?>" />
            </label>   
            <button class="btn btn-blue m-l-10 font-btn-plus blue--hover" type="submit" value="Submit"><?php _e('Search', 'idealbiz'); ?></button>
                
            
            </form>           
            </div>
            <div class="col-md-2 d-flex align-items-center justify-content-end hidden-mobile">
                <?php contacts_phone(); ?>
            </div>
            
        </div>
        
    </div>	
<?php }


/* search for jobs */
function s_jobs(){ 
    $job_bm_archive_page_id = get_option('job_manager_jobs_page_id');
    $job_bm_archive_page_url = get_permalink($job_bm_archive_page_id);
    ?>
    <div class="search-bar container medium-width p-t-30 p-b-34 collapse dont-collapse-sm m-0-auto toggle-search " id="site-search">
    <p class="d-none"><?php _e('Search ','idealbiz'); ?></p>
    <div class="row">
        <div class="col-md-2 d-flex align-items-center">
            <?php country_market_with_flag(); echo $_GET['search_location']; ?>
        </div>
        <div class="col-md-8">
            <form role="search" method="get" id="search-form--header" class="job-search-bar search-form--header search-input" action="<?php echo get_permalink(pll_get_post(get_option('job_manager_jobs_page_id'))); //echo $job_bm_archive_page_url; ?>">
                <label class="text-global-search border-blue b-t-l-r b-b-l-r">
                    <input class="skey" name="search_keywords" type="search" autocomplete="off" minlength="3" placeholder="<?php _e('Searching for a Job? Keywords..', 'idealbiz'); ?>" value="<?php if(!empty($_GET['search_keywords'])) echo sanitize_text_field($_GET['search_keywords']) ?>" />
                </label>
                <div class="jobs_location--select border-blue m-l--1 b-t-r-r b-b-r-r d-w-50">
                    <select data-placeholder="<?php _e('Location'); ?>"  name="search_location" type="search">
                        <option></option>
                        <?php
                        $terms = get_terms(
                            array('taxonomy' => 'location', 'hide_empty' => false, 'parent' => 0) //change to true after
                        );
                        foreach ($terms as $term) {
                            $selected='';
                            if(isset($_REQUEST['search_location'])){
                                if($_REQUEST['search_location'] == $term->term_id){
                                    $selected= 'selected';
                                }
                            }
                            echo sprintf(
                                '<option class="lkey" value="%2$s" %3$s>%2$s</option>',
                                $term->term_id,
                                $term->name,
                                $selected
                            );
                        }
                        ?>
                    </select>
                </div>
                <button class="btn btn-blue m-l-10 font-btn-plus blue--hover" type="submit" value="Submit"><?php _e('Search', 'idealbiz'); ?></button>
            </form>
        </div>
        <div class="col-md-2 d-flex align-items-center hidden-mobile">
            <?php contacts_phone(); ?>
        </div>
    </div>
</div>	<?php
}
 

/* search for resumes */
function s_resumes(){ 
    $job_bm_archive_page_id = get_option('resume_manager_resumes_page_id');
    $resume_manager_resumes_page_id_url = get_permalink($job_bm_archive_page_id);
    ?>
    <div class="search-bar container medium-width p-t-30 p-b-34 collapse dont-collapse-sm m-0-auto toggle-search " id="site-search">
    <p class="d-none"><?php _e('Search ','idealbiz'); ?></p>
    <div class="row">
        <div class="col-md-2 d-flex align-items-center">
            <?php country_market_with_flag() ?>
        </div>
        <div class="col-md-8">
            <form role="search" method="get" id="search-form--header" class="job-search-bar search-form--header search-input" action="<?php echo get_permalink(pll_get_post(get_option('resume_manager_resumes_page_id'))); //echo $resume_manager_resumes_page_id_url; ?>">
                <label class="text-global-search border-blue b-t-l-r b-b-l-r">
                    <input name="search_keywords" type="search" autocomplete="off" minlength="3" placeholder="<?php _e('Searching for a Candidate? Keywords..', 'idealbiz'); ?>" value="<?php if(!empty($_GET['search_keywords'])) echo sanitize_text_field($_GET['search_keywords']) ?>" />
                </label>
                <div class="jobs_location--select border-blue m-l--1 b-t-r-r b-b-r-r d-w-50">
                    <select data-placeholder="<?php _e('Location'); ?>"  name="search_location" type="search">
                        <option></option>
                        <?php
                        $terms = get_terms(
                            array('taxonomy' => 'location', 'hide_empty' => false, 'parent' => 0) //change to true after
                        );
                        foreach ($terms as $term) {
                            $selected='';
                            if(isset($_REQUEST['search_location'])){
                                if($_REQUEST['search_location'] == $term->term_id){
                                    $selected= 'selected';
                                }
                            }
                            echo sprintf(
                                '<option value="%2$s" %3$s>%2$s</option>',
                                $term->term_id,
                                $term->name,
                                $selected
                            );
                        }
                        ?>
                    </select>
                </div>
                <button class="btn btn-blue m-l-10 font-btn-plus blue--hover" type="submit" value="Submit"><?php _e('Search', 'idealbiz'); ?></button>
            </form>
        </div>
        <div class="col-md-2 d-flex align-items-center hidden-mobile">
            <?php contacts_phone(); ?>
        </div>
    </div>
</div>	<?php
}
 


/* Search for experts */
function s_expert(){ ?>
    <div class="search-bar container medium-width p-t-30 p-b-34 collapse dont-collapse-sm m-0-auto toggle-search" id="site-search">
        <p class="d-none"><?php _e('Search ','idealbiz'); ?></p>
        <div class="row">
            <div class="col-lg-2 col-md-1 d-flex align-items-center">
                <?php country_market_with_flag() ?>
            </div>
            <div class="col-lg-8 col-md-9">
                <form role="search" method="get" id="search-form--header" class="search-form--header" action="<?php echo getLinkByTemplate('page-result_member.php'); ?>">
                    <label class="text-global-search border-blue b-t-l-r b-b-l-r">
                        <input name="search" type="text" autocomplete="off" minlength="3" placeholder="<?php _e('Searching for an expert?', 'idealbiz'); ?>" value="<?php if(isset($_REQUEST['search'])){ echo $_REQUEST['search']; } ?>" />
                    </label>
                    <div class="listing_cat--select border-blue m-l--1 b-t-r-r b-b-r-r d-w-100">
                        <select class="generic-search-field" data-placeholder="<?php _e('Area of ​​Expertise','idealbiz'); ?>" name="service_cat">
                            <option></option>
                            <?php
                            $terms = get_terms(
                                array('taxonomy' => 'service_cat', 'hide_empty' => false, 'parent' => 0) //change to true after
                            );
                            foreach ($terms as $term) {
                                $selected='';
                                if(isset($_REQUEST['service_cat'])){
                                    if($_REQUEST['service_cat'] == $term->term_id){
                                        $selected= 'selected';
                                    }
                                }
                                echo sprintf(
                                    '<option value="%1$d" %3$s>%2$s</option>',
                                    $term->term_id,
                                    $term->name,
                                    $_REQUEST['service_cat'] == $term->term_id ? 'selected' : ''
                                );
                            }
                            ?>
                        </select>
                    </div>
                    <div class="expert-location--select border-blue m-l--1 b-t-r-r b-b-r-r d-w-100">
                        <select data-placeholder="<?php _e('Location'); ?>" name="location">
                            <option></option>
                            <?php
                            $terms = get_terms(
                                array('taxonomy' => 'location', 'hide_empty' => false, 'parent' => 0) //change to true after
                            );
                            foreach ($terms as $term) {
                                $selected = '';
                                if(isset($_REQUEST['location'])){
                                    if($_REQUEST['location'] == $term->term_id){
                                        $selected= 'selected';
                                    }
                                }
                                echo sprintf(
                                    '<option value="%1$d" %3$s>%2$s</option>',
                                    $term->term_id,
                                    $term->name,
                                    $selected
                                );
                            }
                            ?>
                        </select>
                    </div>
                    <button class="btn btn-blue m-l-10 font-btn-plus blue--hover" type="submit" value="Submit"><?php _e('Search', 'idealbiz'); ?></button>
                </form>
            </div>
            <div class="col-md-2 d-flex align-items-center hidden-mobile">
                <?php contacts_phone(); ?>
            </div>
        </div>
    </div>	
<?php }



/* Search for experts */
function s_franchise(){ ?>
    <div class="search-bar container medium-width p-t-30 p-b-34 collapse dont-collapse-sm m-0-auto toggle-search" id="site-search">
        <p class="d-none"><?php _e('Search ','idealbiz'); ?></p>
        <div class="row">
            <div class="col-md-2 d-flex align-items-center">
                <?php country_market_with_flag() ?>
            </div>
            <div class="col-md-8">
                <form role="search" method="get" id="search-form--header" class="search-form--header" action="<?php echo getLinkByTemplate('page-franchise.php'); ?>">
                    <label class="text-global-search border-blue b-t-l-r b-b-l-r">
                        <input name="search" type="text" autocomplete="off" minlength="3" placeholder="<?php _e('Searching for a Franchise?', 'idealbiz'); ?>" value="<?php if(isset($_REQUEST['search'])) echo $_REQUEST['search']; ?>" />
                    </label>
                    <?php
                      $terms = get_terms(
                        array('taxonomy' => 'franchise_cat', 'hide_empty' => false, 'parent' => 0) //change to true after
                    );
                    if($terms){
                    ?>
                    <div class="listing_cat--select border-blue m-l--1 b-t-r-r b-b-r-r d-w-50">
                        <select class="generic-search-field" data-placeholder="<?php _e('Category','idealbiz'); ?>" name="franchise_cat">
                            <option></option>
                            <?php
                            $selected='';
                            if(isset($_REQUEST['franchise_cat'])){
                                if($_REQUEST['franchise_cat'] == $term->term_id){
                                    $selected= 'selected';
                                }
                            }
                            foreach ($terms as $term) {
                                echo sprintf(
                                    '<option value="%1$d" %3$s>%2$s</option>',
                                    $term->term_id,
                                    $term->name,
                                    $selected
                                );
                            }
                            ?>
                        </select>
                    </div>
                    <?php } ?>
                    <div class="expert-location--select border-blue m-l--1 b-t-r-r b-b-r-r d-w-50">
                        <select data-placeholder="<?php _e('Location'); ?>" name="location">
                            <option></option>
                            <?php
                            $terms = get_terms(
                                array('taxonomy' => 'location', 'hide_empty' => false, 'parent' => 0) //change to true after
                            );
                            foreach ($terms as $term) {
                                $selected='';
                                if(isset($_REQUEST['location'])){
                                    if($_REQUEST['location'] == $term->term_id){
                                        $selected= 'selected';
                                    }
                                }
                                echo sprintf(
                                    '<option value="%1$d" %3$s>%2$s</option>',
                                    $term->term_id,
                                    $term->name,
                                    $selected
                                );
                            }
                            ?>
                        </select>
                    </div>
                    <button class="btn btn-blue m-l-10 font-btn-plus blue--hover" type="submit" value="Submit"><?php _e('Search', 'idealbiz'); ?></button>
                </form>
            </div>
            <div class="col-md-2 d-flex align-items-center hidden-mobile">
                <?php contacts_phone(); ?>
            </div>
        </div>
    </div>	
<?php }



/* Search for posts */
function s_posts(){ ?>
    <div class="search-bar container medium-width p-t-30 p-b-34 collapse dont-collapse-sm m-0-auto toggle-search" id="site-search">
        <p class="d-none"><?php _e('Search ','idealbiz'); ?></p>
        <div class="row">
            <div class="col-md-2 d-flex align-items-center">
                <?php country_market_with_flag() ?>
            </div>
            <div class="col-md-8">
                <form role="search" method="get" id="search-form--header" class="search-form--header" action="<?php echo get_permalink( get_option( 'page_for_posts' ) ); ?>">
                    <label class="text-global-search border-blue b-t-l-r b-b-l-r">
                        <input name="search" type="text" autocomplete="off" minlength="3" placeholder="<?php _e('Searching for Advices?', 'idealbiz'); ?>" value="<?php if(isset($_REQUEST['search'])) echo $_REQUEST['search']; ?>" />
                    </label>
                    <?php
                      $terms = get_terms(
                        array('taxonomy' => 'category', 'hide_empty' => false, 'parent' => 0) //change to true after
                    );
                    if($terms){
                    ?>
                    <div class="listing_cat--select border-blue m-l--1 b-t-r-r b-b-r-r d-w-50">
                        <select class="generic-search-field" data-placeholder="<?php _e('Category','idealbiz'); ?>" name="category">
                            <option></option>
                            <?php
                          
                            foreach ($terms as $term) {
                                $selected='';
                                if(isset($_REQUEST['category'])){
                                    if($_REQUEST['category'] == $term->term_id){
                                        $selected= 'selected';
                                    }
                                }
                                echo sprintf(
                                    '<option value="%1$d" %3$s>%2$s</option>',
                                    $term->term_id,
                                    $term->name,
                                    $selected
                                );
                            }
                            ?>
                        </select>
                    </div>
                    <?php } ?>
                    <button class="btn btn-blue m-l-10 font-btn-plus blue--hover" type="submit" value="Submit"><?php _e('Search', 'idealbiz'); ?></button>
                </form>
            </div>
            <div class="col-md-2 d-flex align-items-center hidden-mobile">
                <?php contacts_phone(); ?>
            </div>
        </div>
    </div>	
<?php }



/* Search for Listings related pages (listing, search) */
function s_listing(){ ?>
    <div class="search-bar container medium-width p-t-30 p-b-34 collapse dont-collapse-sm m-0-auto toggle-search" id="site-search">
        <p class="d-none"><?php _e('Search Listings ','idealbiz'); ?></p>
        <div class="row">
            <div class="col-md-2 d-flex align-items-center">
                <?php country_market_with_flag() ?>
            </div>
            <div class="col-md-8">
                <form role="search" method="get" id="search-form--header" class="search-form--header" action="<?php echo getLinkByTemplate('page-result_listings.php'); ?>">
                    <div class="listing_cat--select border-blue b-t-l-r b-b-l-r">
                        <select data-placeholder="<?php _e('Category','idealbiz'); ?>" name="listing_cat">
                            <option></option>
                            <?php
                            $terms = get_terms(
                                array('taxonomy' => 'listing_cat', 'hide_empty' => false, 'parent' => 0) //change to true after
                            );
                            foreach ($terms as $term) { 
                                $selected='';
                                if(isset($_REQUEST['listing_cat'])){
                                    if($_REQUEST['listing_cat'] == $term->term_id){
                                        $selected= 'selected';
                                    }
                                }
                                echo sprintf(
                                    '<option value="%1$d" %3$s>%2$s</option>',
                                    $term->term_id,
                                    $term->name,
                                    $selected
                                );
                            }
                            ?>
                        </select>
                    </div>
                    <label class="text-global-search border-blue m-l--1">
                        <input name="search" type="text" autocomplete="off" minlength="3" placeholder="<?php _e('What listing you looking for?', 'idealbiz'); ?>" value="<?php if(isset($_REQUEST['search'])) echo $_REQUEST['search']; ?>" />
                    </label>
                    <div class="location--select border-blue m-l--1 b-t-r-r b-b-r-r">
                        <select data-placeholder="<?php _e('Location'); ?>" name="location">
                            <option></option>
                            <?php
                            $terms = get_terms(
                                array('taxonomy' => 'location', 'hide_empty' => false, 'parent' => 0) //change to true after
                            );
                            foreach ($terms as $term) {
                                $selected='';
                                if(isset($_REQUEST['location'])){
                                    if($_REQUEST['location'] == $term->term_id){
                                        $selected= 'selected';
                                    }
                                }
                                echo sprintf(
                                    '<option value="%1$d" %3$s>%2$s</option>',
                                    $term->term_id,
                                    $term->name,
                                    $selected
                                );
                            }
                            ?>
                        </select>
                    </div>
                    <button class="btn btn-blue m-l-10 font-btn-plus blue--hover" type="submit" value="Submit"><?php _e('Search', 'idealbiz'); ?></button>
                </form>
            </div>
            <div class="col-md-2 d-flex align-items-center hidden-mobile">
               <?php contacts_phone(); ?>
            </div>
        </div>
    </div>	
<?php }

function country_market_with_flag() {
    $country_iso = get_option('country_market');
    $country_name = getCountry($country_iso)['country'];
    $country_flag= DEFAULT_WP_CONTENT.'/plugins/polylang/flags/'.strtolower($country_iso).'.png';

    ?>
        <div class="m-3 country-market">
            <img src="<?php echo $country_flag; ?>" alt="<?php echo $country_name; ?>">
            <span class="m-l-5"><?php echo $country_name; ?></span>
        </div>
    <?php
}

function contacts_phone() {
    $phone = get_field('phone', 'option');

    if ($phone) { ?>
        <div class="contacts w-100 d-flex flex-wrap justify-content-between font-weight-semi-bold">
            <a class="blue_hover--color" href="tel:<?php echo $phone['call_code'].$phone['number']; ?>"><?php echo $phone['number']; ?></a>
            <a class="greyer--color" href="<?php echo get_permalink( pll_get_post(get_page_by_path( 'contacts' )->ID )); ?>"><?php _e('Contacts','idealbiz'); ?></a>
        </div>
    <?php }
}