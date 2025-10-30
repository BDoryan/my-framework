<?php

namespace MyFramework\Router;

class Router
{
    private array $routes = [];
    private array $named_routes = [];

    public function add(string $http_method, string $route_path, $handler, ?string $route_name = null): self
    {
        $http_method = strtoupper($http_method);
        $route_regex = $this->pathToRegex($route_path);

        $route = [
            'method' => $http_method,
            'path' => $route_path,
            'regex' => $route_regex,
            'handler' => $handler,
        ];

        $this->routes[] = $route;

        if ($route_name !== null) {
            $this->named_routes[$route_name] = $route;
        }

        return $this;
    }

    public function get(string $route_path, $handler, ?string $route_name = null): self
    {
        return $this->add('GET', $route_path, $handler, $route_name);
    }

    public function post(string $route_path, $handler, ?string $route_name = null): self
    {
        return $this->add('POST', $route_path, $handler, $route_name);
    }

    public function put(string $route_path, $handler, ?string $route_name = null): self
    {
        return $this->add('PUT', $route_path, $handler, $route_name);
    }

    public function delete(string $route_path, $handler, ?string $route_name = null): self
    {
        return $this->add('DELETE', $route_path, $handler, $route_name);
    }

    public function dispatch(?string $request_method = null, ?string $request_uri = null)
    {
        if(empty($request_method))
            $request_method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if(empty($request_uri))
            $request_uri = ($_SERVER['REQUEST_URI'] ?: null) ?? '/';

        // Default to global server variables when explicit values are not provided.
        $request_method = $request_method ?? $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $request_uri = $request_uri ?? ($_SERVER['REQUEST_URI'] ?? '/');

        $request_path = parse_url($request_uri, PHP_URL_PATH) ?: '/';

        $route_match = $this->match($request_method, $request_path);
        if ($route_match === null) {
            http_response_code(404);
            throw new \RuntimeException('Route not found for ' . $request_method . ' ' . $request_path);
        }

        $handler = $route_match['route']['handler'];
        $route_params = $route_match['params'];

        if (is_callable($handler)) {
            return call_user_func_array($handler, $route_params);
        }

        if (is_string($handler) && strpos($handler, '@') !== false) {
            // Support the "Controller@method" notation without hiding runtime errors.
            [$controller_class, $method] = explode('@', $handler, 2);
            if (!class_exists($controller_class)) {
                throw new \RuntimeException("Controller class {$controller_class} not found.");
            }
            $controller = new $controller_class();
            if (!method_exists($controller, $method)) {
                throw new \RuntimeException("Method {$method} not found on controller {$controller_class}.");
            }
            return call_user_func_array([$controller, $method], $route_params);
        } else if(is_array($handler)) {
            // Support the [ControllerClass::class, 'method'] notation
            [$controller_class, $method] = $handler;
            if (!class_exists($controller_class)) {
                throw new \RuntimeException("Controller class {$controller_class} not found.");
            }
            $controller = new $controller_class();
            if (!method_exists($controller, $method)) {
                throw new \RuntimeException("Method {$method} not found on controller {$controller_class}.");
            }
            return call_user_func_array([$controller, $method], $route_params);
        }

        throw new \RuntimeException('Invalid route handler : ' . print_r($handler, true));
    }

    public function generate(string $route_name, array $params = []): string
    {
        if (!isset($this->named_routes[$route_name])) {
            throw new \RuntimeException("No route named {$route_name}.");
        }
        $route_path = $this->named_routes[$route_name]['path'];

        $url = preg_replace_callback('/\{([a-zA-Z0-9_]+)\}/', function ($placeholder_match) use ($params) {
            $param_key = $placeholder_match[1];
            if (!array_key_exists($param_key, $params)) {
                throw new \RuntimeException("Missing parameter '{$param_key}' for URL generation.");
            }
            return rawurlencode((string)$params[$param_key]);
        }, $route_path);

        return $url;
    }

    private function match(string $http_method, string $request_path): ?array
    {
        $http_method = strtoupper($http_method);

        foreach ($this->routes as $route) {
            if ($route['method'] !== $http_method) {
                continue;
            }
            if (preg_match($route['regex'], $request_path, $matches)) {
                // Only keep named capture groups so handler signatures stay predictable.
                $params = [];
                foreach ($matches as $match_key => $match_value) {
                    if (is_string($match_key)) {
                        $params[$match_key] = $match_value;
                    }
                }
                return ['route' => $route, 'params' => $params];
            }
        }

        return null;
    }

    public static function getRequestPath(): string
    {
        $request_uri = $_SERVER['REQUEST_URI'] ?? '/';
        $request_uri = explode('?', $request_uri, 2)[0];
        $request_uri = $request_uri ?: '/';
        return parse_url($request_uri, PHP_URL_PATH) ?: '/';
    }

    private function pathToRegex(string $path): string
    {
        // Convert "/user/{id}" into a regex with named capture groups.
        $route_regex = preg_replace_callback('/\{([a-zA-Z0-9_]+)\}/', function ($placeholder_match) {
            return '(?P<' . $placeholder_match[1] . '>[^\/]+)';
        }, $path);

        // Ensure the regex matches the entire route string.
        return '#^' . $route_regex . '$#';
    }
}
