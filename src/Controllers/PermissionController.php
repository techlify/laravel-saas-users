<?php
namespace TechlifyInc\LaravelRbac\Controllers;

use App\Http\Controllers\Controller;
use TechlifyInc\LaravelRbac\Models\Permission;

class PermissionController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Permission::orderBy("label")->get();

        return array("items" => $items, "success" => true);
    }
}
