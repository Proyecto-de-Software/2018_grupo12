<?php

namespace App\Http\Middleware;

use Closure;

use App\Models\RepositorioPermiso;

class Authorize
{
    protected $repoPermiso;

    public function __construct(RepositorioPermiso $repoPermiso) {
        $this->repoPermiso = $repoPermiso;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permiso)
    {
        if ($this->repoPermiso->id_usuario_tiene_permiso(session("id"), $permiso)) {
            return $next($request);
        }
        return redirect()->route('home');
    }
}
