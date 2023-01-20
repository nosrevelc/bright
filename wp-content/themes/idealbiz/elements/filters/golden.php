<?php
$protocolo = (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') ? 'http' : 'https';
$url = "$protocolo://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";


if (strpos($url , '?')){
        $cl_url = $url.'&golden=1';
    }else{
        $cl_url = $url.'?golden=1';  
    }
?>

<br/>

<div class="create2">
</div>
<span class="m-b-15 search_text m-t-15" for="golden"><a href="<?php echo $cl_url; ?>"><?php _e('Show Golden Visa only', 'idealbiz'); ?></a></span>



<label class="d-none custom-control custom-checkbox idealbiz-checkbox certification m-b-15">
    <input <?php if ($certified_golden) {
                /* echo 'checked' */;             
            } ?> type="checkbox" class="custom-control-input" name="golden">
</label>

<style>

.create2
{
background-image: url('https://idealbiz.io/pt/wp-content/uploads/sites/86/2021/03/golden.png');
position: absolute;
left: .9rem;
top:34.9rem;
width: 2.5rem;
height: 2.5rem;
background-size: contain;
background-position: 50%;
background-repeat: no-repeat;
z-index: 10;
}
.search_text{
    padding-left: 30px;

}
</style>