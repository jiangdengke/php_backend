<?php

namespace App\Http\Controllers;

use App\Models\User;
use Jiannei\Response\Laravel\Response;

abstract class Controller
{
        public function getUsers(){

            return Response::success(User::all());
        }

}
