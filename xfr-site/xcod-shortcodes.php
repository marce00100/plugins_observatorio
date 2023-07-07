<?php

/**
 *ahort code para acceso a paginas segun el rol
 */
// add_action('init', 'verifica_acceso');
// function verifica_acceso() {
// 	add_shortcode('seg_acceso', function($attr, $content){
// 		// $roles 		= $attr['roles'] ?? '';
// 		// $rolesEnter = explode(',', $roles);

// 		global $post;
//    		 $post_slug=$post->post_name;

// 		// 	$slug = get_queried_object()->post_name;

// 		$accesoPages = FELCV_ACCESO_PAGES;
// 		$rolesEnter =  isset($accesoPages[$post_slug]) ?  $accesoPages[$post_slug] : [];

// 		if (is_user_logged_in() && count($rolesEnter) > 0) {
// 			$user      = wp_get_current_user();
// 			$rolesUser = (array) $user->roles;
// 			$rolUser   =  $rolesUser[0];
// 			$access = false;
// 			foreach($rolesEnter as $rolEnter){
// 				$rolEnter = strtolower(trim($rolEnter));
// 				if($rolEnter == $rolUser){
// 					$access = true;
// 				}
// 			}
// 			if(!$access){
// 				echo "Error. No se tiene acceso, puede que esta página no exista, o no esté publicda. " . $post_slug;
// 				exit();
// 			}
// 		}
// 		else{
// 			echo "Error. No se tiene acceso, puede que esta página no exista, o no esté publicda. " . $post_slug;
// 			exit();
// 		}

// 	});
// }





