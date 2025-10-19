<?php

namespace Core;

/**
 * Router - Gerencia rotas e despacha requisições para Controllers
 */
class Router
{
    private $routes = [];
    private $params = [];

    /**
     * Adiciona uma rota GET
     *
     * @param string $path Caminho da URL
     * @param string $controller Controller@method
     */
    public function get($path, $controller)
    {
        $this->addRoute('GET', $path, $controller);
    }

    /**
     * Adiciona uma rota POST
     *
     * @param string $path Caminho da URL
     * @param string $controller Controller@method
     */
    public function post($path, $controller)
    {
        $this->addRoute('POST', $path, $controller);
    }

    /**
     * Adiciona uma rota ao array
     *
     * @param string $method Método HTTP
     * @param string $path Caminho da URL
     * @param string $controller Controller@method
     */
    private function addRoute($method, $path, $controller)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'controller' => $controller
        ];
    }

    /**
     * Despacha a requisição para o controller apropriado
     */
    public function dispatch()
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove a base do projeto da URI
        $requestUri = str_replace('/encomendas_chef_gestor', '', $requestUri);
        
        // Remove /public/ da URI se existir
        $requestUri = str_replace('/public', '', $requestUri);
        
        // Remove /app.php se existir (acesso direto)
        $requestUri = str_replace('/app.php', '', $requestUri);
        
        // Garante que sempre comece com /
        $requestUri = $requestUri === '' ? '/' : $requestUri;
        if ($requestUri[0] !== '/') {
            $requestUri = '/' . $requestUri;
        }

        foreach ($this->routes as $route) {
            // Converte {param} em regex
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if ($route['method'] === $requestMethod && preg_match($pattern, $requestUri, $matches)) {
                // Extrai parâmetros
                $this->params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                return $this->executeController($route['controller']);
            }
        }

        // Rota não encontrada - 404
        $this->notFound();
    }

    /**
     * Executa o controller
     *
     * @param string $controllerString Controller@method
     */
    private function executeController($controllerString)
    {
        list($controllerName, $method) = explode('@', $controllerString);

        $controllerClass = "Controllers\\{$controllerName}";

        if (!class_exists($controllerClass)) {
            die("Controller {$controllerClass} não encontrado");
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $method)) {
            die("Método {$method} não encontrado no controller {$controllerClass}");
        }

        // Passa os parâmetros para o método
        call_user_func_array([$controller, $method], $this->params);
    }

    /**
     * Página 404
     */
    private function notFound()
    {
        http_response_code(404);
        echo "<h1>404 - Página não encontrada</h1>";
        exit;
    }
}
