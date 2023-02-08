
<?php 

$key = 'AIzaSyCETxZ_RlpLL1i-PMC2VGRWEIQD42tiN6w';

?>

<?php
$includeIds = array();
if (WEBSITE_SYSTEM == '1') {
    $experts_with_fees = getExpertsWithActiveFees();
    if (empty($experts_with_fees)) {
        $includeIds = array(-1);
    } else {
        $includeIds = $experts_with_fees;
    }
}

                                            if (!$_GET['search']) {
                                                if (!$_GET['location']) {
                                                    if (!$_GET['service_cat']) {


                                                        $args = array(
                                                            'posts_per_page' => -1,
                                                            'post_type' => 'expert',
                                                            'post_status' => 'publish',
                                                            'post__in' => $includeIds

                                                        );

                                                        $experts = new WP_Query($args);
                                                    
                                                    }
                                                }
                                            } 
$i=0;
$check_andress = 0;




foreach($experts->posts as $cl_expert){

            $member_category = get_field('member_category',$cl_expert->ID);
            $expert_address = get_field('expert_address',$cl_expert->ID);
            $expert_postal_code = get_field('expert_postal_code',$cl_expert->ID);
            $expert_city = get_field('expert_city',$cl_expert->ID);
            $cl_perfil = get_post_permalink($cl_expert->ID);
            $foto = get_field('foto',$cl_expert->ID)['sizes']['medium'];
        $i++;

        

        if($expert_address){
            $check_andress++;

                        $term_obj_list = get_the_terms($cl_expert->ID, 'service_cat' );
                        $location_objs = get_the_terms($cl_expert->ID, 'location' );
                        $location_string = $expert_city;
                        $terms_string = join(', ', wp_list_pluck($term_obj_list, 'name'));
                        $lis_cat=str_replace(', ','<br/>',$terms_string ); 
                        $address = $expert_address;
                        
            $url = "https://maps.google.com/maps/api/geocode/json?address=".urlencode($address).'&key='.$key;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    
            $responseJson = curl_exec($ch);
            curl_close($ch);



            $response = json_decode($responseJson);

                if ($response->status == 'OK') {
                    $latitude = $response->results[0]->geometry->location->lat;
                    $longitude = $response->results[0]->geometry->location->lng;

                    $cl_Json_array[]= array(
                        "cl_latitude" => $latitude,
                        "cl_longitude" => $longitude,
                        "cl_nome"=> get_the_title($cl_expert->ID),
                        "cl_perfil"=>$cl_perfil,
                        "cl_zoom_map"=> $zoom_map,
                        "cl_latitude_center"=> $latitude_center,
                        "cl_longitude_center"=> $longitude_center,
                        "cl_categoria"=>$lis_cat,
                        "cl_foto" => $foto,
                        "cl_link" => '<a class="link_nome" href="'
                            .$cl_perfil.'" target="">'
                            .$cartao.'<div class="image"><img style="border-radius:50%" src="'
                            .$foto.'" alt="Girl in a jacket" width="100" height="100"></div><br/>'
                            .get_the_title($cl_expert->ID)
                            .'</a><br/><br/><div class="lista_cat"><a href="'
                            .$cl_perfil.'" target="">'
                            .$lis_cat.'</a></div>'
                            .'<br/><div><i class="icon-local"></i>
                            <span class="text-uppercase">'.$location_string .'</span></div>'
                            ,
                        
                            

                    );
                    
                
                    ?>
                    <!-- <a href="https://www.google.com/maps/place/<?php echo $address;?>/@<?php echo $latitude.','.$longitude;?>,15z" target="_blank"><?php echo 'Ver no Mapa'; ?></a> -->
                    <?php
                        } else {
                        
                            /* echo $response->status; */
                        } 
                    }else{
                       
                        /*  cl_alerta('Sem morada Correta'); */
                    }

        
}

$cl_Json_array = json_encode($cl_Json_array);






/* var_dump($cl_Json_array); */

?>


<script type="text/javascript">
     var key = "https://maps.googleapis.com/maps/api/js?key="+<?php echo $key ;?>+"""
    <?php foreach($locations as $location){ ?>
        var location = new google.maps.LatLng(<?php echo $location['lat']; ?>, <?php echo $location['lng']; ?>);
        var marker = new google.maps.Marker({
            position: location,
            map: map
        });
    <?php } ?>
</script>


    <title>How to add multiple markers on google maps javascript</title>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $key;?>" ></script>
    <script type="text/javascript">
        
        let cl_dados_map = JSON.stringify(<?php echo $cl_Json_array; ?>);

        let cl_mapa_json = JSON.parse(cl_dados_map); 

        console.log(cl_mapa_json);



        locations = cl_mapa_json.map((p,i)=>
        [cl_mapa_json[i].cl_link,cl_mapa_json[i].cl_latitude,cl_mapa_json[i].cl_longitude,i+1],
        );


        console.log(locations);
        function InitMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: cl_mapa_json[0].cl_zoom_map,
                center: new google.maps.LatLng(cl_mapa_json[0].cl_latitude_center, cl_mapa_json[0].cl_longitude_center),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            });


            var infowindow = new google.maps.InfoWindow();
            var marker, i;
            for (i = 0; i < locations.length; i++) {
                var iconBase = 'https://idealbiz.io/pt/wp-content/uploads/sites/86/2021/10/';
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                    map: map,
                    icon: iconBase + 'pin-idealbiz-blue.png'
                });
                google.maps.event.addListener(marker, 'click', (function (marker, i) {
                    return function () {
                        infowindow.setContent(locations[i][0]);
                        infowindow.open(map, marker);
                    }
                })(marker, i));

            
            }
        }
    </script>

<body onload="InitMap();">
<?php 
    if ($check_andress > 0){?>
        <div class="container dropshadow"id="map" style="height: 700px; width: 100%;">
        </div>
        <?php
    }else{
        echo '<h2 class="text-center p-t-15 p-b-15">';   
        _e('_str Unable to display the map as there are no addresses to display for this search','idelabiz');
        echo '</h2>'; 
    }
?>
</body>