<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = $this->user->paginate(10);
        return response()->json($user, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        //
        $data = $request->all();
        try {

            $data['password'] = bcrypt($data['password']);

            $user = $this->user->create($data);
            return response()->json([
                'data' => [
                    'msg' => 'usuário cadastrado com sucesso'
                ]
            ]);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage(), $data);
            return response()->json($message->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        try {

            $user = $this->user->findOrFail($id);

            return response()->json(['data' => $user], 200);
        } catch (\Exception $e) {

            $message = new ApiMessages($e->getMessage());

            return response()->json($message->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {

        $data = $request->all();
        if ($request->has('password') && trim($request->get('password'))) {
            $data['password'] = bcrypt($data['password']);
        }

        try {
            $user = $this->user->findOrFail($id);

            $user->update($data);

            return response()->json([
                'data' => [
                    'msg' => 'usuário atualizado com sucesso'
                ]
            ]);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage(), $data);
            return response()->json($message->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            $user = $this->user->findOrFail($id);
            $user->delete();
            return response()->json(['message' => $user->name . ' usuario deleted!']);
        } catch (\Exception $e) {

            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage());
        }
    }
}
