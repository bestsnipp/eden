<?php

namespace BestSnipp\Eden\Middleware;

use Closure;
use Illuminate\Http\Request;
use Livewire\LivewireManager;

class EdenRequestHandler
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        if ($this->isLivewireRequest()) {
            return $next($request);
        }

        if (! $this->isMethodSupported($request)) {
            return $next($request);
        }

        $this->createEdenRequest($request);

        return $next($request);
    }

    protected function isLivewireRequest(): bool
    {
        return class_exists(LivewireManager::class) && app(LivewireManager::class)->isLivewireRequest();
    }

    protected function isMethodSupported(Request $request): bool
    {
        return in_array($request->method(), ['GET']);
    }

    protected function createEdenRequest(Request $request)
    {
        $route = $request->route();
        $edenRequest = [];
        $edenRequest['uri'] = $route->uri;
        $edenRequest['methods'] = $route->methods();
        $edenRequest['parameters'] = $route->parameters;
        $edenRequest['originalParameters'] = $route->originalParameters();
        $edenRequest['query'] = $request->query->all();

        session()->put('_eden_request_route_current', json_encode($edenRequest));
        session()->put('_eden_request_route_previous', url()->previous());
    }
}
