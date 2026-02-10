<?php

declare(strict_types=1);

/**
 * @file 
 * @package 
 * @author Vinícius Gonçalves Cordeiro <vinicordeirogo@gmail.com>
 * @link https://github.com/vinicius-g-cordeiro/
 * @version 0.0.1
 * @copyright 2026 Vinicius Gonçalves Cordeiro
 */

namespace Routes;

use App\Core\Controller; // Default Controller
use App\Core\Request;
use Closure;
use App\Types\Methods;
use App\Core\Connection;
use App\Exceptions\RouteNotFoundException;

class Router {
    public static ?Router $instance = null;
    function __construct(private ?array $routes = [], private ?array $groupMiddlewares = [], private ?array $middlewares = []
    , private ?array $cache = [], private ?string $groupPrefix = null, private ?Request $request = null
    , public ?string $title = null, public ?array $permissions = []) {
        
    }

    public static function instance() {
        if(!isset(self::$instance) || self::$instance === null) {
            self::$instance = new Router();
        }
        return self::$instance;
    }

    function add(?Methods $method, ?string $path = '', $callback = null, ?array $middlewares = []) {
        $prefixedPath = $this->groupPrefix . $path;

        $this->routes[] = [
            'method' => $method,
            'path' => $prefixedPath,
            'callback' => $callback,
            'middlewares' => array_merge($this->groupMiddlewares ?? [], $middlewares ?? []),
            'params' => object(),
            'title' => $this->title,
            'request' => Request::instance(),
            'locale' => 'pt-br',
        ];

        return $this;
    }

    function all() : array {
        return $this->routes;
    }

    function match(array $methods, ?string $path, $callback = null, ?array $middlewares = []) {
        foreach($methods as $method) {
            $this->add($method, $path, $callback, $middlewares);
        }
        return $this;
    }

    function get(?string $path = '', $callback = null, ?array $middlewares = []) {
        return $this->add(Methods::GET, $path, $callback, $middlewares);
    }

    function post(?string $path = '', $callback = null, ?array $middlewares = []) {
        return $this->add(Methods::POST, $path, $callback, $middlewares);
    }

    function put(?string $path = '', $callback = null, ?array $middlewares = []) {
        return $this->add(Methods::PUT, $path, $callback, $middlewares);
    }

    function delete(?string $path = '', $callback = null, ?array $middlewares = []) {
        return $this->add(Methods::DELETE, $path, $callback, $middlewares);
    }

    function patch(?string $path = '', $callback = null, ?array $middlewares = []) {
        return $this->add(Methods::PATCH, $path, $callback, $middlewares);
    }

    function head(?string $path = '', $callback = null, ?array $middlewares = []) {
        return $this->add(Methods::HEAD, $path, $callback, $middlewares);
    }

    function options(?string $path = '', $callback = null, ?array $middlewares = []) {
        return $this->add(Methods::OPTIONS, $path, $callback, $middlewares);
    }

    function any(?string $path = '', $callback = null, ?array $middlewares = []) {
        $methods = [Methods::GET, Methods::POST, Methods::PUT, Methods::DELETE, Methods::PATCH, Methods::HEAD, Methods::OPTIONS];
        return $this->match($methods, $path, $callback, $middlewares);
    }

    function hasAny(?string $path = '') {
        $methods = [Methods::GET, Methods::POST, Methods::PUT, Methods::DELETE, Methods::PATCH, Methods::HEAD, Methods::OPTIONS];
        foreach($methods as $method) {
            $route = $this->find($method, $path);

            if($route['callback'] == null) { continue; }

            if(is_array($route['callback'])) {
                if($route['callback'][1] != 'notFound'){
                    return true;
                }
            }
        }
        return false;
    }

    function find(?Methods $method, ?string $path = '') {
        
		$cacheKey = $method->value . ' ' . $path;
		if (isset($this->cache[$cacheKey])) {
			return $this->cache[$cacheKey];
		}
		
		foreach ($this->routes as $route) {
			if ($route['method'] !== $method) continue;
			$pattern = preg_replace_callback('#\{(\w+)(?::([^}]+))?\}#', function ($matches) {
				$param = $matches[1];
				$regex = isset($matches[2]) ? $matches[2] : '[^/]+';
				return "(?P<$param>$regex)";
			}, $route['path']);

			$pattern = "#^" . $pattern . "$#";
			if (preg_match($pattern, $path, $matches)) {
				$params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);	
				return $this->cache[$cacheKey] = [
					'method' => $route['method'],
					'path' => $route['path'],
					'callback' => $route['callback'],
					'params' => $params,
					'request' => Request::instance(),
					'server' => $_SERVER,
					'files' => $_FILES,
					'title' => $route['title'] ?? '',
					'permissions' => $route['permissions'] ?? null,
					'locale' => $route['locale'] ?? 'en-us',
					'admin' => $route['admin'] ?? false,
					'middlewares' => array_merge($this->globalMiddlewares ?? [], $route['middlewares'] ?? []),
				];
			}
		}

		return $this->cache[$cacheKey] = [
			'method' => $method,
			'path' => $path,
			'callback' => [
				'\\App\\Core\\Controllers\\Controller',
				'notFound',
			],
			'params' => [],
			'request' => Request::instance(),
			'server' => $_SERVER,
			'files' => $_FILES,
			'title' => 'Route: ' . $path . ' not found on method: ' . $method->value . '.',
			'locale' => 'en-us',
			'permissions' => '*',
			'admin' => '',
			'error' => [
				'code' => 404,
				'message' => 'Route: ' . $path . ' not found on method: ' . $method->value . '.',
				'title' => 'Route: ' . $path . ' not found on method: ' . $method->value . '.',
				'description' => 'The route you are trying to access does not exist.',
				'solution' => 'The route you are trying to access does not exist. If the problem persists, contact the developer.',
				'type' => 'error',
				'path' => $path
			]
		];
    }

    protected function runMiddlewares(?array $middlewares = [], ?Request $request = null, $finalCallback = null) : mixed {
        $next = $finalCallback;

        while($middleware = array_pop($middlewares)) {
            $middlewareClass = $middleware;
            $middlewareParams = [];

            if(is_array($middleware)) {
                $middlewareClass = key($middleware);
                $middlewareParams = current($middleware);
            }

            $next = function ($request) use ($middlewareClass, $middlewareParams, $next) {
                $middleware = new $middlewareClass($request, $next, $middlewareParams ?? []);
                return $middleware->handle($request, $next, ...$middlewareParams);
            };
        }

        return $next($request);
    }

	function resolve($method, $path) : Router {
		$route = $this->find($method, $path);
		if (isset($route['error'])) {
			error_log("[Router Error] {$route['error']['message']} ({$route['error']['code']}) at {$route['error']['path']}");
			throw new RouteNotFoundException('Route: ' . $path . ' not found on method: ' . $method->value . '.', 404);
		}
		
		$params = (object)$route['params'] ?? [];
		$params->title = $route['title'] !== '' && $route['title'] !== null ? $route['title'] : '';
		$params->permissions = isset($route['permissions']) ? $route['permissions'] : null;
		$params->locale = $route['locale'] !== '' && $route['locale'] !== null ? $route['locale'] : 'pt-br';
		$params->admin = $route['admin'] !== '' && $route['admin'] !== null ? $route['admin'] : false;
		if(isset($params->id) && $params->id !== null && $params->id !== ''){ // if id is not null or empty
			$params->id = (int)$params->id; // convert to int by default
		}

		$route['request']->setParams($params);
		
		$finalCallback = function ($req) use ($route) {
			if (is_callable($route['callback'])) {
				return call_user_func($route['callback'], $req);
			}
			
			$databaseConnection = Connection::instance();
			$connectionDebugInfo = [
				'Object_ID' => spl_object_id($databaseConnection),
				'Server_Info' => $databaseConnection->ServerInfo(),
				'Is_Connected' => $databaseConnection->IsConnected(),
			];
			if(getenv('APP_DEBUG') === 'true' && getenv('MYSQL_DEBUG_DATABASE_CONNECTION') === 'true'){
				print_r($connectionDebugInfo);
			}
			
			$controller = new $route['callback'][0]($databaseConnection, (object)$route['request']);
			$methodName = $route['callback'][1];
			
			if (!method_exists($controller, $methodName)) {
				throw new RouteNotFoundException("Method {$methodName} not found in controller {$route['callback'][0]}", 404);
			}

			return $controller->$methodName($req);
		};

		$this->runMiddlewares($route['middlewares'] ?? [], $route['request'], $finalCallback);

		return $this;
	}

	function run($method, $path) : Router {
		$this->resolve($method, $path);
		return $this;
	}


	function middleware($middleware) : Router {
		$this->groupMiddlewares[] = $middleware;
		return $this;
	}

	function group(array $optionsOrMiddlewares, Closure $callback) : Router {
		$previousMiddlewares = $this->groupMiddlewares;
        $previousPrefix = $this->groupPrefix;

        if (array_keys($optionsOrMiddlewares) === range(0, count($optionsOrMiddlewares) - 1)) {
            // Simple way: [Middleware::class]
            $options = ['middleware' => $optionsOrMiddlewares];
        } else {
            // Full way: ['middleware' => [...], 'prefix' => '/admin']
            $options = $optionsOrMiddlewares;
        }

        $this->groupMiddlewares = array_merge($this->groupMiddlewares, $options['middleware'] ?? []);
        $this->groupPrefix .= $options['prefix'] ?? '';

        $callback($this);

        $this->groupMiddlewares = $previousMiddlewares;
        $this->groupPrefix = $previousPrefix;

        return $this;
	}

	function title(?string $title = '') : Router {
		$this->routes[count($this->routes) - 1]['title'] = $title;
		return $this;
	}

	function permissions(?array $permissions = []) : Router {
		$this->routes[count($this->routes) - 1]['permissions'] = $permissions;
		return $this;
	}

	function locale(?string $locale = 'pt-br') : Router {
		$this->routes[count($this->routes) - 1]['locale'] = $locale;
		return $this;
	}

	function admin(?bool $admin = false) : Router {
		$this->routes[count($this->routes) - 1]['admin'] = $admin;
		return $this;
	}

}