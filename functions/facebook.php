<?php

function get_facebook_app_id(){
    return linkpro_get_option('facebook_app_id');
   
}

function get_facebook_app_secret(){
    return linkpro_get_option('facebook_app_secret');
    
}

function connect_fb_php(){
      $facebook = new Facebook(array(
        'appId' => get_facebook_app_id(),
        'secret' => get_facebook_app_secret(),
    ));
    
    return $facebook;
}

function return_fb_user($return = 0){
    $facebook = connect_fb_php();
    try{
        $user = $facebook->getUser();
        
        if($user){
            if($return){
                return $user;
            } else {
                return true;    
            }
            
        }
    } catch (Exception $e){
        mail('nicolas.duque@dodable.com', 'new exepction', $e->getMessage());
    }
    return false;
}


function vincular_facebook($user_id) {
    global $linkpro;
    
     $facebook = connect_fb_php();
     
    //get user from facebook object
    try{
       $user = $facebook->getUser();
    } catch (Exception $e) {
         mail('nicolas.duque@dodable.com', 'new exepction', $e->getMessage());
    }
   
    
    
    if($user): //check for existing user id
    
        $user_graph = $facebook->api('/me');
        $profile_pic = $facebook->api('/me/picture?redirect=false&height=100');
        
        $user_graph['profile_pic'] = $profile_pic['data']['url'];
        
        
       
            //Actualizamos la data con la data que recogemos de facebook
               if( linkpro_update_profile_via_facebook($user_id, $user_graph) ) {
                   return true;
               }
                
               return false;
        
    else: //user doesn't exist
            
       return false;
    endif; 
}



function logout_fb(){
    
    $facebook = connect_fb_php();
    
    setcookie('fbs_'.$facebook->getAppId(),'', time()-100, '/', 'mostazasocial.com');
    return $facebook->destroySession()?  true :  false;
  
}



function get_usuarios_vinculados_facebook(){
    global $wpdb;
   
   
    $resultados = $wpdb->get_results("SELECT meta_value as facebook_id FROM wp_usermeta WHERE meta_key = 'linkpro_facebook_id'", 'OBJECT');
    return $resultados;
}

function enviar_notificacion($user_fb_id, $title, $url){
   $facebook = connect_fb_php();
   $access_token = get_facebook_app_id() .'|'. get_facebook_app_secret();
   
   if($user_fb_id):
          try{  
            $response = $facebook->api($user_fb_id.'/notifications/', 
                           'post', 
                           array(
                           'href' => $url,
                           'template' => $title,
                           'access_token' => $access_token,
                           ));
                           
        return true;  
         } catch (Exception $e) {
           return false;
         } 
   else: 
        return false;
   endif;      
}

function enviar_notificaciones_a_todos($data = array()){
    $usuariosArray = get_usuarios_vinculados_facebook();
    
    foreach ($usuariosArray as $usuario){
        enviar_notificacion($usuario->facebook_id, $data['title'], $data['url']);
    }
    return true;
}


function parse_signed_request($signed_request, $secret) {
    list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

    // decode the data
    $sig = base64_url_decode($encoded_sig);
    $data = json_decode(base64_url_decode($payload), true);

    if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
        error_log('Unknown algorithm. Expected HMAC-SHA256');
        return null;
    }

    // check sig
    $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
    if ($sig !== $expected_sig) {
        error_log('Bad Signed JSON signature!');
        return null;
    }

    return $data;
}

function base64_url_decode($input) {
    return base64_decode(strtr($input, '-_', '+/'));
}


    