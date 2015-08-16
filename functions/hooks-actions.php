<?php

function linkpro_post_published_notification( $ID, $post ) {

 enviar_notificaciones_a_todos( ['title' => $post->post_title, 'url' => get_permalink($ID) ]);

}

add_action( 'publish_post', 'linkpro_post_published_notification', 10, 2 );

