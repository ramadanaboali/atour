<?php

namespace App\Http\Middleware;

use App\Services\ActivityLogService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogUserActivity
{
    protected $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Only log for authenticated users
        if (Auth::check()) {
            $this->logActivity($request, $response);
        }

        return $response;
    }

    protected function logActivity(Request $request, $response)
    {
        $user = Auth::user();
        $method = $request->method();
        $url = $request->fullUrl();
        $route = $request->route();

        // Skip logging for certain routes to avoid noise
        $skipRoutes = [
            'admin.users.list',
            'admin.dashboard',
            'heartbeat',
            'health-check'
        ];

        if ($route && in_array($route->getName(), $skipRoutes)) {
            return;
        }

        // Determine action type based on HTTP method and route
        $actionType = $this->determineActionType($method, $route);
        
        if ($actionType) {
            $description = $this->generateDescription($method, $url, $route);
            
            $this->activityLogService->logCustomAction(
                $user->id,
                $actionType,
                $description
            );
        }
    }

    protected function determineActionType(string $method, $route): ?string
    {
        if (!$route) {
            return strtolower($method);
        }

        $routeName = $route->getName();
        
        if (str_contains($routeName, '.destroy')) {
            return 'delete';
        } elseif (str_contains($routeName, '.store')) {
            return 'create';
        } elseif (str_contains($routeName, '.update')) {
            return 'update';
        } elseif (str_contains($routeName, '.show') || str_contains($routeName, '.edit')) {
            return 'view';
        }

        return strtolower($method);
    }

    protected function generateDescription(string $method, string $url, $route): string
    {
        if ($route && $route->getName()) {
            $routeName = $route->getName();
            $parts = explode('.', $routeName);
            
            if (count($parts) >= 2) {
                $resource = ucfirst($parts[1]);
                $action = ucfirst($parts[2] ?? $method);
                return "Accessed {$resource} - {$action}";
            }
        }

        return "Accessed: {$method} {$url}";
    }
}
