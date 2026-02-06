<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Token;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Execute an action on the controller.
     * Override to fix PHP 8.0+ named argument compatibility issue
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($method, $parameters)
    {
        // Fix for PHP 8.0+ named argument compatibility
        // Convert associative array to indexed array to avoid named argument issues
        $args = array_values($parameters);
        return call_user_func_array([$this, $method], $args);
    }

    protected function  verifyToken($token)
    {

        if ($token !=null)
        {
            $dbToken = Token::find($token);

            return ($dbToken==null)? false : true;
        }
       
        return false;
        
    }
    protected function getAllToken()
    {
        return Token::All();
    }
    protected function  getTokenInfo($token)
    {
        $dbToken = Token::find($token);

        return $dbToken;
        
    }
}
