<?php
require_once("./router.php");
require_once("./BaseGen.php");
base_generation();
create_table();
//разделение запроса от параметров
$server_request_uri = explode('?', $_SERVER['REQUEST_URI']);
// масив содержазий значение url->path_file ulr|путь до файла
// Если параметров не должно быть, то массив должен быть пустой []
$url_path_file = array(
    '/|controllers/index.php' => [
        'GET' => [],
        'POST' => ['fname', 'lastname', 'age'],
        'PUT' => ['new_name', 'new_lastname', 'new_age', 'old_name', 'old_lastname', 'old_age'],
        'DELETE' => ['fname', 'lastname']
    ]


);

function path_check($request_uri, $paths)
{
    $existing_paths = [];
    foreach (array_keys($paths) as $url_dir) {
        $url = explode("|", $url_dir);
        array_push($existing_paths, $url);
        if ($request_uri == $url[0]) {
            return $url_dir;
        }
    }
    echo "Данные пути не обнаружены\n Доступные пути:\n";
    echo json_encode($existing_paths);
    return false;
}

function method_check($request_method, $url_dir, $paths)
{
    $available_methods = [];
    foreach (array_keys($paths[$url_dir]) as $method) {
        array_push($available_methods, $method);
        if ($request_method == $method) {
            return $method;
        }
    }
    echo "Метод не доступен\n Доступные методы:\n";
    echo json_encode($available_methods);
    return false;
}

function parameter_check($server_request, $paths, $url_dir, $methods)
{
    $url_path = explode("|", $url_dir);
    $body_parmas = json_decode(file_get_contents('php://input'), true);

    if (file_get_contents('php://input') == "" && count($paths[$url_dir][$methods]) == 0) {
        return   route($url_path[0], $url_path[1]);
    } elseif (file_get_contents('php://input') == "" && count($paths[$url_dir][$methods]) != 0) {
        echo "не переданы параметры \n Нужные параметры:";
        echo json_encode($paths[$url_dir][$methods]);
        return false;
    } elseif (file_get_contents('php://input') != "" && count($paths[$url_dir][$methods]) == 0) {
        echo "На данный запрос не существует параметров \n";
        return false;
    } elseif (count((array)json_decode(file_get_contents('php://input'), true)) < count($paths[$url_dir][$methods])) {
        echo "Ошибка в параметрах\n Допустимые : ";
        echo json_encode($paths[$url_dir][$methods]);
        return false;
    } elseif (count((array)$body_parmas) == count($paths[$url_dir][$methods])) {
        $check = true;
        // Массив для записи сбойных параметров
        $parameters_error = [];
        foreach (array_keys($body_parmas) as $parameter) {
            if (array_search($parameter, $paths[$url_dir][$methods]) == "") {
                $check = false;
                array_push($parameters_error, $parameter);
            }
        }
        if ($check != false) {
            return route($url_path[0], $url_path[1]);
        } else {
            echo "Неизвестные параметры\n";
            echo json_encode($parameters_error) . "\n";
            echo "Доступные параметры:\n" . json_encode($paths[$url_dir][$methods]);
            return false;
        }
    } else {
        echo "Ошибка в параметрах, доступные параметры :\n";
        echo json_encode($paths[$url_dir][$methods]);
        return false;
    }
}

if (path_check($server_request_uri[0], $url_path_file) != false) {
    $path = path_check($server_request_uri[0], $url_path_file);
    if (method_check($_SERVER["REQUEST_METHOD"], $path, $url_path_file) != false) {
        $methods = method_check($_SERVER["REQUEST_METHOD"], $path, $url_path_file);
        parameter_check($server_request_uri, $url_path_file, $path, $methods);
    }
}
