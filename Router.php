<?php

class Router
{
    protected $actualPath;
    protected $actualMethod;
    protected $routes = [];
    protected $notFound;
    protected $groupPath = "";

    public function __construct($currentPath, $currentMethod)
    {

        $this->actualPath = $currentPath;
        $this->actualMethod = $currentMethod;

        $this->notFound = function () {
            $this->sendResponse(["message" => "Not Found!"], 404);
        };
    }

    public function get($path, $callback)
    {
        $path = $this->fixRoute($path);
        $this->routes[] = ['GET', $path, $callback];
    }

    public function post($path, $callback)
    {
        $path = $this->fixRoute($path);
        $this->routes[] = ['POST', $path, $callback];
    }

    public function put($path, $callback)
    {
        $path = $this->fixRoute($path);
        $this->routes[] = ['PUT', $path, $callback];
    }

    public function delete($path, $callback)
    {
        $path = $this->fixRoute($path);
        $this->routes[] = ['DELETE', $path, $callback];
    }

    public function patch($path, $callback)
    {
        $path = $this->fixRoute($path);
        $this->routes[] = ['PATCH', $path, $callback];
    }

    public function options($path, $callback)
    {
        $path = $this->fixRoute($path);
        $this->routes[] = ['OPTIONS', $path, $callback];
    }

    public function group($path, $callback)
    {
        $this->groupPath = $path;
        call_user_func($callback);
        $this->groupPath = "";
    }

    public function fixRoute($path)
    {
        if (!empty($this->groupPath)) {
            $path = $this->groupPath . $path;
            $path = rtrim($path, '/');
        }
        return $path;
    }

    public function run()
    {

        foreach ($this->routes as $route) {
            list($method, $path, $callback) = $route;
            $checkMethod = ($this->actualMethod === $method) ? 1 : 0;
            $checkPath = preg_match("~^{$path}$~ixs", $this->actualPath, $params);
            if ($checkMethod && $checkPath) {
                array_shift($params);
                return call_user_func_array($callback, $params);
            }
        }

        return call_user_func($this->notFound);
    }

    public function sendResponse($data, $code = 200)
    {
        ob_clean();
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: X-Requested-With');
        header('Content-type: application/json; charset=utf-8');
        http_response_code($code);

        if ($data) {
            echo json_encode($data);
        }

        exit();
    }

    public function __destruct()
    {
        $this->run();
    }
}
