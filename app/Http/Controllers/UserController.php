<?php
namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Retrieve all users.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $page    = $request->input('page', 1);
        $perPage = $request->input('perPage', 30);
        $sortBy  = $request->input('sortBy', 'nombre');
        $order   = $request->input('order', 'desc');
        $filterColumn = $request->input('column', 'desc');
        $filterValue  = $request->input('value', 'desc');

        $users =  User::orderBy($sortBy, $order)->paginate($perPage, ['*'], 'page', $page);

        if($request->has('column') && $request->has('value')) {
            $users =  User::where($filterColumn, 'like', '%'. $filterValue . '%')->orderBy($sortBy, $order)->paginate($perPage, ['*'], 'page', $page);
        }

        return $users;
    }

    /**
     * Retrieve the user for the given ID.
     *
     * @param  int  $id
     * @return Response
     */
    public function getUser($id)
    {
        return User::findOrFail($id);
    }
}