<?php

class Route {
  private static function controllerFunction($stringControllerFunction) {
    $split = explode('@', $stringControllerFunction);
    $controller = $split[0] . 'Controller';
    $controller = new $controller();
    $function = $split[1];
    return array($controller, $function);
  }

  public static function get($namespace, $route, $stringCallbackFunction) {
    $arrayCallback = self::controllerFunction($stringCallbackFunction);
    add_action('rest_api_init', function () use ($namespace, $route, $arrayCallback) {
      register_rest_route($namespace, $route, array(
        'methods'  => 'GET',
        'callback' => $arrayCallback
      ));
    });
  }

  public static function post($namespace, $route, $stringCallbackFunction) {
    $arrayCallback = self::controllerFunction($stringCallbackFunction);
    add_action('rest_api_init', function () use ($namespace, $route, $arrayCallback) {
      register_rest_route($namespace, $route, array(
        'methods'  => 'POST',
        'callback' => $arrayCallback
      ));
    });
  }
}
