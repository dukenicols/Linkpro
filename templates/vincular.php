<!-- //ND Julio-2015 -->
<div class="linkpro linkpro-<?php echo $i; ?> linkpro-<?php echo $layout; ?>" <?php linkpro_args_to_data( $args ); ?>>

    
    
    <div class="linkpro-head">
        
        <div class="linkpro-clear"></div>
    </div>
    <div class="linkpro-body">
    
        <?php //do_action('linkpro_pre_form_message'); ?>
        
        <form action="" method="post" data-action="<?php echo $template; ?>">
        
            <?php do_action('linkpro_super_get_redirect', $i); ?>
            
            <input type="hidden" name="force_redirect_uri-<?php echo $i; ?>" id="force_redirect_uri-<?php echo $i; ?>" value="<?php if (isset( $args["force_redirect_uri"] ) ) echo $args["force_redirect_uri"]; ?>" />
            <input type="hidden" name="redirect_uri-<?php echo $i; ?>" id="redirect_uri-<?php echo $i; ?>" value="<?php if (isset( $args["{$template}_redirect"] ) ) echo $args["{$template}_redirect"]; ?>" />
           <?php //print linkpro_get_option('facebook_app_id'); ?>
           
            
            <div style="height: 295px;" id="fb-root">
                <?php 

               // echo '<p style="text-align:center">Vincula tu cuenta de Mostaza Social con facebook, para continuar <br>';
                     echo '<a href="', $loginUrl, '" id="facebook-connect-btn" target="_top"><img  style="margin-top:25px; margin-bottom: 50px" src="' . $linkpro->img_url . 'facebook-connect.png"></a></p>';
                 
                  ?>
                
                
            </div> <!-- fb-root -->
            
            
        </form>
    
    </div>

</div>