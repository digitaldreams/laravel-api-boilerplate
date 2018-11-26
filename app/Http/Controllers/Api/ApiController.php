<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use League\Fractal\Manager;

/**
 * Class ApiController
 * Parent calss of all Api Controller. Common methods are defined here to use everywhere.
 *
 * @package Bloom\Cluster\Kernel\Http\Controllers\Api
 */
class ApiController extends Controller
{
    public function __construct()
    {
        if (isset($_GET['include']) && !empty($_GET['include'])) {
            $manager = new Manager();
            $manager->parseIncludes($_GET['include']);
        }
    }
}
