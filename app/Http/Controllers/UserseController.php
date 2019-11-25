<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;

class UserseController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function get(Request $request)
    {
        return response()->json(['status'=>'1'],200);
    }

    public function store(Request $request)
    {

    }

    public function storeConsumer(Request $request)
    {

    }

    public function storeSeller(Request $request)
    {

    }

    public function destroy($id)
    {

    }
}
