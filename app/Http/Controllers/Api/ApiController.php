<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use League\Fractal\Manager;
use Dingo\Api\Routing\Helpers;

/**
 * Class ApiController
 * Parent calss of all Api Controller. Common methods are defined here to use everywhere.
 *
 * @package Bloom\Cluster\Kernel\Http\Controllers\Api
 */
class ApiController extends Controller
{
    use Helpers;

    public function __construct()
    {
        if (isset($_GET['include']) && !empty($_GET['include'])) {
            $manager = new Manager();
            $manager->parseIncludes($_GET['include']);
        }
    }
}
