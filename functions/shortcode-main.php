<?php


	/* Registers and display the shortcode */
	add_shortcode('linkpro', 'linkpro' );


function linkpro( $args=array() ) {
	
	global $post, $wp, $userpro_admin, $linkpro;
               $argument = $args;

	if (is_home()){
		$permalink = home_url();
	} else {

		if (isset($post->ID)){
				$permalink = get_permalink($post->ID);
			} else {
				$permalink = '';
			}

	}

$default_args=array(
		  'vincular'         => 1,
			 'fb_autologin'     => 1,
			 'notificacion'     => 1,
);

$defaults = apply_filters('linkpro_shortcode_args', $default_args);

 $args = wp_parse_args( $args, $defaults );

	/* The arguments are passed via shortcode through admin panel*/

foreach ($default_args as $key => $val) {
		if(isset($args[$key])) {
				$$key = $args[$key];
		} else {
				$$key = $val;
		}

}

	if( $template ):
		STATIC $i = 0;
	
		ob_start();

		/* increment wall */
		$i = rand(1, 1000);


		$output = ob_get_contents();
		ob_end_clean();

			switch( $template ):
				case 'vincular':

				   $user_id = get_current_user_id();
                 
       die('entre');                  
                if(userpro_is_logged_in()){ // El usuario esta logueado
             
                    if(!userpro_has_facebook_id($user_id)){ //no tiene facebook_id
                    
                         if(!return_fb_user()) { // si el usuario no esta logueado a facebook abrir la plantilla 
                                                                                          
                             
                             $facebook = connect_fb_php();
                             $loginUrl = $facebook->getLoginUrl(array(  'redirect_uri' => site_url() . '/vincular/',
                                                                        'scope' => 'email',
                                                                        'display' => 'page'
                                                                      )); 
        
                            $template = 'vincular';$args['template'] = 'vincular';
                                  
                                
                                if (locate_template('userpro/' . $template . '.php') != '') {
                                
                                    include get_template_directory() . '/userpro/'. $template . '.php';
                                
                                } else {
                                
                                    include userpro_path . "templates/vincular.php";
                                }  
                                 
                         } else {
                             
                             vincular_facebook($user_id);
                             wp_redirect(site_url() . '/vincular/');
                             exit;
                             
                             
                         }
                    } else { // El usuario tiene facebook id
                        
                        //Verificar que el usuario tenga userpro_need_change_pass en wp_usermeta
                        //si no lo tiene crearlo y setearlo a 1
                        if(do_user_need_change_pass($user_id)){
                           // si es igual a 1 ... redireccionar a cambiar-pass
                           
                           wp_redirect( site_url() .'/cambiar-pass/' ); exit;
                        
                        } 
                        
                        wp_redirect( site_url() );
                        exit;
                        
                        
                    }
                } else { // El usuario no esta logueado
                       
                   //Redireccionamos al login screen
                   wp_redirect(site_url());
                   exit; 
               }
			break;
    case 'fb_autologin':
           
           if(empty($_POST['signed_request']) === false) {


           $data = parse_signed_request($_REQUEST["signed_request"], get_facebook_app_secret());

         		
            
            if (isset($data)) {
                
               $facebook = connect_fb_php();
             
             
               $user_fb_id = $facebook->getUser();
               
                   if($user_fb_id){
                       $user_data = get_users(array('meta_key' => 'userpro_facebook_id', 'meta_value' => $user_fb_id));
                       $user_login = $user_data[0]->user_login;
                       userpro_auto_login( $user_login, true);
                   }
            
             
            }

           } else {
           	return false;
           }
        break;	
			endswitch;		
		
		return $output;
		endif;
}