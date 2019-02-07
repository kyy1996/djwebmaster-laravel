<?php
/**
 * Created by PhpStorm.
 * User: alen
 * Date: 2019-02-07
 * Time: 22:10
 */

namespace App\Http\Controllers\Common;


use App\Http\Controllers\AppController;
use Illuminate\Http\Response;

class WelcomeController extends AppController
{
    public function getIndex(): Response
    {
        return $this->response();
    }
}
