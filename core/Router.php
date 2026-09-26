<?php

/**
 * Class Router
 *
 * Manual regex-based router. Matches "{param}" placeholders in routes
 *against the request URI at dispatch time.
 * @package core
 */
class Router
{
    /**
     * @var array<int, array{method:string, path:string, handler:array}>
     * Holds every registered route definition.
     */
    private array $routes = [];

    /**
     * Registers a new route.
     *
     * @param string $method  HTTP verb: GET, POST, etc.
     * @param string $path    Route pattern, e.g. "/photo/{id}"
     * @param array  $handler [ControllerClassName, methodName]
     * @return void
     */
    public function add(string $method, string $path, array $handler): void
    {
        $this->routes[] = [
            'method'  => strtoupper($method),
            'path'    => $path,
            'handler' => $handler,
        ];
    }

    /**
     * Matches the current request method + URI against the registered
     * routes, converting "{param}" placeholders into a capturing regex
     * group, then dispatches to the matched controller/action with
     * any captured parameters passed as arguments.
     *
     * @param string $method HTTP method of the incoming request
     * @param string $uri    Raw request URI (may include query string)
     * @return void
     */
    public function dispatch(string $method, string $uri): void
    {
        // Strip query string and trailing slash for clean matching.
        $path = parse_url($uri, PHP_URL_PATH);
        $path = rtrim($path, '/');
        if ($path === '') {
            $path = '/';
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($method)) {
                continue;
            }

            // Convert "{name}" tokens into a regex capture group that
            // matches any run of non-slash characters, per the manual
            // routing requirement of the specification.
            $pattern = preg_replace('/\{([a-zA-Z_]+)\}/', '([^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches); // drop the full match, keep captured params

                [$controllerName, $action] = $route['handler'];
                $controllerFile = __DIR__ . "/../controllers/{$controllerName}.php";

                if (!file_exists($controllerFile)) {
                    $this->abort(500, "Controller not found: {$controllerName}");
                }

                require_once $controllerFile;

                if (!class_exists($controllerName)) {
                    $this->abort(500, "Controller class not found: {$controllerName}");
                }

                $controller = new $controllerName();

                if (!method_exists($controller, $action)) {
                    $this->abort(500, "Action not found: {$controllerName}::{$action}");
                }

                call_user_func_array([$controller, $action], $matches);
                return;
            }
        }

        $this->abort(404, 'Page Not Found');
    }

    /**
     * Sends an HTTP error status and a minimal message, then halts.
     *
     * @param int    $code
     * @param string $message
     * @return void
     */
    private function abort(int $code, string $message): void
    {
        http_response_code($code);
        echo "<h1>{$code}</h1><p>{$message}</p>";
        exit;
    }
}
