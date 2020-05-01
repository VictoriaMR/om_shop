<?php

namespace App\Http\Middleware;

use App\Service\Member\MemberService;

/**
 * 验证token.
 */
class VerifyToken
{
    /**
     * The URIs that should be excluded from token verification.
     *
     * @var array
     */
    protected $except = [
        'Admin/Index/loginIn',
    ];

    protected $exceptNotToken = [

    ];

    protected $exceptNotAgreement = [

    ];

    /**
     * Handle an incoming request.
     * @return mixed
     */
    public function handle($request, Closure $next = null)
    {
        $param = array_pop($request);
        $route = implode('\\', $request);

        if ($this->inExceptArray($route)) {
            return true;
        }

        switch ($request['Class']) {
            case 'Admin':
                // dd($_SESSION);
                if (empty($_SESSION['admin_user'])) {
                    \Mvc::jump('admin/index/login', ['title'=>'管理员登陆']);
                }
                break;
            
            default:
                # code...
                break;
        }

        return true;
    }

    /**
     * Determine if the request has a URI that should pass through token verification.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function inExceptArray($route)
    {
        return in_array(str_replace('\\', '/', $route), $this->except);
    }

    protected function inExceptByNotTokenArray($request)
    {
        foreach ($this->exceptNotToken as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }

    protected function inExceptByNotAgreementArray($request)
    {
        foreach ($this->exceptNotAgreement as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate the token.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function tokenMatch($request)
    {
        $token = $this->getTokenFromRequest($request);
        if ($this->inExceptByNotTokenArray($request) && (empty($token) || $token == 'null')) return true;

        list($externalMemberId) = $this->memberService->checkToken($token);
        if (empty($externalMemberId)) {
            return false;
        }

        return $externalMemberId;
    }

    /**
     * Get the token from the request.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return string
     */
    protected function getTokenFromRequest($request)
    {
        $token = $request->input('token') ?: $request->header('X-AUTH-ACCESS-TOKEN');

        if (empty($token)) {
            $token = $request->input('access_token');
        }

        return $token;
    }
}
