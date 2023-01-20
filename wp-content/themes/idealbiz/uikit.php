<?php // Template Name: Uikit

get_header(); 
?>
<style>
body{
    background: #e1e1e1;
}
</style>    


<?php

if ( have_posts() ) : 
    while ( have_posts() ) : the_post(); 
    $meta = get_post_meta( $post->ID, 'post_fields', true ); 
    endwhile; 
    the_content();
endif;
?>






<div style="width:100%; display: block; text-align: center;">
<div style="display: block;padding: 40px; border: 1px solid #d5d5d5; background:#fff; display:inline-block; max-width:1282px;  margin: 0 auto; width: 100%;">
    <div style="display: block;float: left; margin-bottom: 40px;">
        <div style=" float: left; word-spacing: 100vw;  width: 200px; height: 200px; margin:20px; margin: 20px; line-height: 25px; padding: 52px 0;" 
        class="blue-sky--background">
            $blue-sky
            #E4F2FF .blue-sky--background .blue-sky--color
        </div>
        <div style=" float: left; word-spacing: 100vw;  width: 200px; height: 200px; margin:20px; margin: 20px; line-height: 25px; padding: 62px 0;" 
        class="light-grey--background">
            $light-grey
            #F3F5FA .light-grey--background .light-grey--color
        </div>
        <div style=" float: left; word-spacing: 100vw;  width: 200px; height: 200px; margin:20px; margin: 20px; line-height: 25px; padding: 62px 0; color: #fff;" 
        class="base_color--background">
            $base_color
            #14307B .base_color--background .base_color--color
        </div>
        <div style=" float: left; word-spacing: 100vw;  width: 200px; height: 200px; margin:20px; margin: 20px; line-height: 25px; padding: 62px 0; color: #fff;" 
        class="blue--background">
            $blue
            #205AF6 .blue--background .blue--color
        </div>
        <div style=" float: left; word-spacing: 100vw;  width: 200px; height: 200px; margin:20px; margin: 20px; line-height: 25px; padding: 62px 0; color: #fff;" 
        class="blue-grey--background">
            $blue-grey
            #455880 .blue-grey--background .blue-grey--color
        </div>
        <div style=" float: left; word-spacing: 100vw;  width: 200px; height: 200px; margin:20px; margin: 20px; line-height: 25px; padding: 62px 0; color: #fff;" 
        class="violet--background">
            $violet
            #7A64E9 .violet--background .violet--color
        </div>
        <div style=" float: left; word-spacing: 100vw;  width: 200px; height: 200px; margin:20px; margin: 20px; line-height: 25px; padding: 62px 0; color: #000;" 
        class="violet-10--background">
            $violet-10
            #7A64E9-10% .violet-10--background .violet-10--color
        </div>
        <div style=" float: left; word-spacing: 100vw;  width: 200px; height: 200px; margin:20px; margin: 20px; line-height: 25px; padding: 62px 0; color: #000;" 
        class="grey--background">
            $grey
            #8BA7BD .grey--background .grey--color
        </div>
        <div style=" float: left; word-spacing: 100vw;  width: 200px; height: 200px; margin:20px; margin: 20px; line-height: 25px; padding: 62px 0; color: #fff;" 
        class="red--background">
            $red
            #FF5D74 .red--background .red--color
        </div>
        <div style=" float: left; word-spacing: 100vw;  width: 200px; height: 200px; margin:20px; margin: 20px; line-height: 25px; padding: 62px 0; color: #fff;" 
        class="light-blue--background">
            $light-blue
            #408EFC .light-blue--background .light-blue--color
        </div>
        <div style=" float: left; word-spacing: 100vw;  width: 200px; height: 200px; margin:20px; margin: 20px; line-height: 25px; padding: 62px 0; color: #fff;" 
        class="yellow--background">
            $yellow
            #E4B033 .yellow--background .yellow--color
        </div>
        <div style=" float: left; word-spacing: 100vw;  width: 200px; height: 200px; margin:20px; margin: 20px; line-height: 25px; padding: 62px 0; color: #fff;" 
        class="green--background">
            $green
            #1EB3C8 .green--background .green--color
        </div>
        <div style=" float: left; word-spacing: 100vw;  width: 200px; height: 200px; margin:20px; margin: 20px; line-height: 25px; padding: 62px 0; color: #fff;" 
        class="blue-alt--background">
            $blue-alt
            #4A73E2 .blue-alt--background .blue-alt--color
        </div>
        <div style=" float: left; word-spacing: 100vw;  width: 200px; height: 200px; margin:20px; margin: 20px; line-height: 25px; padding: 62px 0; color: #fff;" 
        class="black--background">
            $black
            #222222 .black--background .black--color
        </div>
        <div style=" float: left; word-spacing: 100vw;  width: 200px; height: 200px; margin:20px; margin: 20px; line-height: 25px; padding: 62px 0; color: #000;" 
        class="white--background">
            $white
            #ffffff .white--background .white--color
        </div>
        

    </div>

    <hr style="margin-top:3em;" class="clear" />
    <br/>
    
    <div style="text-align: left;">
        
        <h1>h1 &nbsp;&nbsp;&nbsp; Basis Grotesque Pro Bold (3em:48px)</h1>
        <br/>
        <h2>h2 &nbsp;&nbsp;&nbsp; Basis Grotesque Pro Bold (1.75em:28px)</h2>
        <br/>
        <h2 class="font-weight-light">h2.font-weight-light &nbsp;&nbsp;&nbsp; Basis Grotesque Pro Bold (1.75em:28px)</h2>
        <br/>
        <h3>h3 &nbsp;&nbsp;&nbsp; Basis Grotesque Pro Regular (1.25em:20px)</h3>
        <br/>
        <p class="font-btn">Basis Grotesque Pro Medium (1.125em:18px) (botões)
        <br/>
        <br/>
        <a class="btn-default" href="#">.btn-default</a> <a class="btn-blue" href="#">.btn-blue</a>

        <a class="btn-border h-38px l-h-12">.btn-border .h-38px .l-h-12</a>
            
        <p>
        <br/>
        <p class="font-weight-bold">Bold: .font-weight-bold</p>

        <div class="custom-control custom-checkbox idealbiz-checkbox m-b-15">
            <input type="checkbox" class="custom-control-input" id="customCheck1">
            <label class="custom-control-label" for="customCheck1">Hoje (0)</label>
        </div>
        <div class="custom-control custom-checkbox idealbiz-checkbox m-b-15">
            <input type="checkbox" class="custom-control-input" id="customCheck2">
            <label class="custom-control-label" for="customCheck2">Últimos 14 dias (1)</label>
        </div>
        <div class="custom-control custom-checkbox idealbiz-checkbox m-b-15">
            <input type="checkbox" class="custom-control-input" id="customCheck3">
            <label class="custom-control-label" for="customCheck3">Últimos 30 dias (2)</label>
        </div>
        <div class="custom-control custom-checkbox idealbiz-checkbox m-b-15">
            <input type="checkbox" class="custom-control-input" id="customCheck4" checked>
            <label class="custom-control-label" for="customCheck4">Desde sempre (178)</label>
        </div>
        
    </div>


    <hr style="margin-top:3em;" class="clear" />
    <br/>

    <div class="services" style="background:#fff; padding: 40px;">
        <div class="services-blocks d-flex flex-column align-items-center">
            <div class="d-flex flex-row block-row">                
                <div class="w-211px h-211px d-flex flex-column block justify-content-between m-t-20 m-x-8 p-x-20 stroke dropshadow">
                    <i class="text-center icon-aconselhamento-fiscal"></i>
                    <span>Aconselhamento Fiscal</span> 
                </div>
                        <div class="w-211px h-211px d-flex flex-column block justify-content-between m-t-20 m-x-8 p-x-20 stroke dropshadow">
                    <i class="text-center icon-negociacao"></i>
                    <span>Negociação</span>
                </div>
                <div class="w-211px h-211px d-flex flex-column block justify-content-between m-t-20 m-x-8 p-x-20 stroke dropshadow">
                    <i class="text-center icon-incentivos"></i>
                    <span>Incentivos ao Investimento</span>
                </div>
                <div class="w-211px h-211px d-flex flex-column block justify-content-between m-t-20 m-x-8 p-x-20 stroke dropshadow">
                    <i class="text-center icon-apresentacao"></i>
                    <span>Apresentações Negócios</span>
                </div>
            </div>
            <div class="d-flex flex-row block-row">                
                <div class="w-211px h-211px d-flex flex-column block justify-content-between m-t-20 m-x-8 p-x-20 stroke dropshadow">
                    <i class="text-center icon-contabilidade"></i>
                    <span>Contabilidade</span>
                </div>
                        <div class="w-211px h-211px d-flex flex-column block justify-content-between m-t-20 m-x-8 p-x-20 stroke dropshadow">
                    <i class="text-center icon-avaliacao"></i>
                    <span>Avaliação</span>
                </div>
                <div class="w-211px h-211px d-flex flex-column block justify-content-between m-t-20 m-x-8 p-x-20 stroke dropshadow">
                    <i class="text-center icon-auditoria"></i>
                    <span>Auditorias e Due Diligence</span>
                </div>
                <div class="w-211px h-211px d-flex flex-column block justify-content-between m-t-20 m-x-8 p-x-20 stroke dropshadow">
                    <i class="text-center icon-aconselhamento"></i>
                    <span>Aconselhamento Legal</span>
                </div>
            </div>    
        </div>
    </div>



</div>
</div>


<?php

get_footer(); 

