<?php
$protocolo = (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') ? 'http' : 'https';
$url = "$protocolo://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";


if (strpos($url , '?')){
        $cl_url = $url.'&certified_listing=1';
    }else{
        $cl_url = $url.'?certified_listing=1';  
    }
?>

<br/>

<div class="create">
</div>
<span class="m-b-15 search_text m-t-15" for="certified_listing"><a href="<?php echo $cl_url; ?>"><?php _e('Show certified only', 'idealbiz'); ?></a></span>



<label class="d-none custom-control custom-checkbox idealbiz-checkbox certification m-b-15">
    <input <?php if ($certified_listing) {
                /* echo 'checked' */;             
            } ?> type="checkbox" class="custom-control-input" name="certified_listing">
</label>

<style>

.create
{
background-image: url('https://idealbiz.eu/pt/wp-content/themes/idealbiz/assets/img/badge.png');
position: absolute;
left: .6rem;
top:29rem;
width: 3rem;
height: 3rem;
background-size: contain;
background-position: 50%;
background-repeat: no-repeat;
z-index: 10;
}
.search_text{
    padding-left: 30px;

}
</style>