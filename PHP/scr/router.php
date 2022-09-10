<?php



function route($route, $path_to_include)
{



  $ROOT = $_SERVER['DOCUMENT_ROOT'];
  $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
  $request_url = strtok($request_url, '?');
  $route_parts = explode('/', $route);
  $request_url_parts = explode('/', $request_url);

  array_shift($route_parts);
  array_shift($request_url_parts);

  if (count($route_parts) != count($request_url_parts)) {
    return;
  }

  $parameters = [];
  for ($__i__ = 0; $__i__ < count($route_parts); $__i__++) {
    $route_part = $route_parts[$__i__];
    if (preg_match("/^[$]/", $route_part)) {
      $route_part = ltrim($route_part, '$');
      array_push($parameters, $request_url_parts[$__i__]);
      $route_part = $request_url_parts[$__i__];
    } else if ($route_parts[$__i__] != $request_url_parts[$__i__]) {
      return;
    }
  }

  if (is_callable($path_to_include)) {
    call_user_func($path_to_include);
    exit();
  }
  include_once("$ROOT/$path_to_include");
  exit();
}
