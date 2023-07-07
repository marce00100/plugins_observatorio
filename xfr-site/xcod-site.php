<?php

/* --------------- F I L T E R S ---------------------- */

/**
 * Añade boton salir o ingresar al menu segun si el usuario esta logueado o no
 *  la doble barra __ traduce de acuerdo al idioma sistema configurado
 */
// add_filter('wp_nav_menu_items', 'micod_boton_menu_login_logout', 10, 2);
// function micod_boton_menu_login_logout($items, $args) {
// 	if ($args->theme_location == 'primary') {
// 		if (is_user_logged_in()) {
// 			$items .= '<li class="menu-item btn-menu btn-logout boton-entrar-salir">
// 						<a href="' . wp_logout_url(get_permalink()) . '">' . __("Log Out") . '</a> 
// 						</li>';
// 		} else {
// 			$items .= '<li class="menu-item btn-menu btn-login boton-entrar-salir">
// 						<a href="' . wp_login_url(get_permalink()) . '">' . __("Log In") . '</a>
// 						</li>';
// 		}
// 	}
// 	return $items;
// }

/** 
 * ACCESO_MENU
 * Restringe MENUS segun el ROL, del config
 */
add_filter('wp_nav_menu_objects', 'restrict_menu', 10, 2);
function restrict_menu($items, $args) {
	$ar = $args;
	if ($args->theme_location == 'primary') {
		$menu = [];
		$rol = 'publico';
		if (is_user_logged_in()) {
			$user = wp_get_current_user();
			$role = (array) $user->roles;
			$rol =  $role[0];
		} 

		$accesoMenus = 	[
			//'estadisticas'          	=> ['administrator', 'contenidos'],
			// 'gestionar contenidos'      => ['editor', 'administrator'],
			// 'creador de contenidos'     => ['editor', 'administrator'],
			// 'gest. normas'       	      => ['editor', 'administrator'],
			// 'gest. jurisprudencia'      => ['editor', 'administrator'],
			// 'gest. recomendaciones'     => ['editor', 'administrator'],
			// 'gest. j. relevantes'	      => ['editor', 'administrator'],
			// 'entrenamiento chatbot'	    => ['editor', 'administrator'],
		];
		foreach ($items as $item) {
			if (isset($accesoMenus[strtolower($item->title)])) {
				if (in_array(strtolower($rol),  $accesoMenus[strtolower($item->title)]))
					$menu[] = $item;
			} else
				$menu[] = $item;
		}
		
	}
	return $menu;
}

// function mostrar_menu_item_roles_especificos( $items, $menu, $args ) {
// 	// Define el rol de usuario para el cual deseas mostrar el elemento de menú
// 	$allowed_role = 'administrator';

// 	// Verifica si el usuario actual tiene el rol permitido
// 	if ( current_user_can( $allowed_role ) ) {
// 			return $items; // Muestra el elemento de menú sin cambios
// 	}

// 	// Si el usuario no tiene el rol permitido, oculta el elemento de menú
// 	foreach ( $items as $key => $item ) {
// 			if ( 'Noticias' === $item->title ) {
// 					unset( $items[$key] );
// 			}
// 	}

// 	return $items;
// }
// add_filter( 'wp_get_nav_menu_items', 'mostrar_menu_item_roles_especificos', 10, 3 );


/** ------------------------ ACTIONS --------------------------*/

/** Redireccion al iniciar sesion - directo a mis-cursos */
// add_action('wp_login', 'micod_custom_login_redirect');
// function micod_custom_login_redirect() {
// 	/* Pasamos a la función home_url() el slug de nuestra página de Área Privada */
// 	wp_redirect(home_url("mis-cursos"));
// 	exit();
// }


/** Redireccion al cerrara sesion - a mis-cursos  */
// add_action('wp_logout', 'micod_custom_logout_redirect');
// function micod_custom_logout_redirect() {
// 	wp_redirect(home_url("mis-cursos"));
// 	exit();
// }



/** Ocultar admin bar a todos los suscriptores */
// add_action('after_setup_theme', 'micod_ocultar_admin_bar');
// function micod_ocultar_admin_bar() {
// 	// if (current_user_can('oficial1') || current_user_can('oficial2')) {
// 		add_filter('show_admin_bar', '__return_false');
// 	// }
// }


/* Al activar el plugin se activa esta funcion */
// function xcod_plugin_activate($fileRuta) {

// 	register_activation_hook(
// 		$fileRuta,
// 		function () {
// 			global $wp_roles;
// 			if (!isset($wp_roles))
// 				$wp_roles = new WP_Roles();
	
				
// 				/** Crea los nuevos roles */
// 				$felcvRoles = FELCV_ROLES;
// 				foreach($felcvRoles as $rol => $rolAttr){
// 					if (empty($wp_roles->get_role($rol))) {
// 						$rolAttrObj = (object)$rolAttr;
// 						$clonRol = $wp_roles->get_role($rolAttrObj->clon);
// 						$wp_roles->add_role($rol, $rolAttrObj->nombre, $clonRol->capabilities);
// 					}
// 				}

// 			// $rolesWP = [/*'administrator',*/'editor', 'author', 'contributor', 'subscriber']; // No  eliminar al Administrador, si no , ya no se puede acceder
// 			/** Elimina los ROles WP  ---descomentar o cambiar nombre con prefijo No_USAR_ */
// 			// foreach($rolesWP as $rol){
// 			// 	$wp_roles->remove_role( $rol);
// 			// }
// 		}
// 	);
// }


