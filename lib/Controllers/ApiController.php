<?php

namespace App\Controllers;

class ApiController extends ControllerBase
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $exists = $this->auth->existsUser("max");
    $this->app->response()->json(["has_max" =>  $exists]);
  }
}
