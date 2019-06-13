<?php
namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    /**
     * Retrieve all users.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if (Gate::denies('update-post', $request->route('hotel_id'))) {
          //  abort(403);
        }

        $page    = $request->input('page', 1);
        $perPage = $request->input('perPage', 10);
        $sortBy  = $request->input('sortBy', 'nombre');
        $order   = $request->input('order', 'asc');

        $hotelId = $request->route('hotel_id');

        $model = new User();

        $model =  $model->with('hotels')->whereHas('hotels', function($q) use ($hotelId){
                        $q->where('commerce.id_commerce',$hotelId);
                  });

        if($request->has('guest_name')) {
            $model = $model->where('nombre', 'like', '%'. $request->input('guest_name') . '%');    
        }

        if($request->has('email')) {
            $model = $model->where('correo', 'like', '%'. $request->input('email') . '%');    
        }

        if($request->has('phone')) {
            $model = $model->where('telefono', 'like', '%'. $request->input('phone') . '%');    
        } 

        if($request->has('status')) {
            $model = $model->where('estado', '=', $request->input('status'));    
        }  

        $model = $model->orderBy($sortBy, $order);

        if(!$request->has('all')) {
            $users = $model->paginate($perPage, ['*'], 'page', $page);
        } else {
            $users = $model->get();
        }

        $recordStatus = config('variable.recordStatus');

        $users->transform(function($user) use ($recordStatus) {
            return [
                'id' => $user->id_usuario,
                'id_number' => $user->identificacion,
                'id_type' => $user->identificacion,
                'name' => $user->nombre,
                'last_name' => $user->apellido,
                'country' => $user->pais_origen ,
                'email' => $user->correo,
                'phone' => $user->telefono,
                'status' => $recordStatus[$user->estado],
            ];
        })->toArray();

        return response()->json($users);
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