<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    private $title = 'Error';
    private $module = 'error';

    public function index()
    {
        $this->_view->load('index');
    }
}