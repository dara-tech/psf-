<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;
use Ramsey\Uuid\Uuid;
use Cookie;


class ReportingController extends Controller
{
    public function index($token=null,$locale=null)
    {
        return "OK";
    }
}