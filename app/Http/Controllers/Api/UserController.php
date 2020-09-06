<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $user = $this->user->with('profile')->paginate(10);
        return response()->json($user, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = $request->segment(3);
        $data = $request->all();

        $validator = Validator::make($data, [
            'email' => ['required', 'string', 'email', "unique:users,email,{$id},id", 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required'],
            'mobile_phone' => ['required'],
        ]);

        try {

            if ($validator->fails()) {
                $message = new ApiMessages($validator->errors());
                return response()->json($message->getMessage(), 401);
            }

            $data['password'] = bcrypt($data['password']);

            $user = $this->user->create($data);

            $user->profile()->create([
                'phone' => $data['phone'],
                'mobile_phone' => $data['mobile_phone'],
            ]);

            return response()->json([
                'data' => [
                    'msg' => 'usuário cadastrado com sucesso'
                ]
            ], 201);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage(), $data);
            return response()->json($message->getMessage(), 401);
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

            $user = $this->user->with('profile')->findOrFail($id);
            $user->profile->social_networks = unserialize($user->profile->social_networks);

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
    public function update(Request $request, $id)
    {

        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => ['string', 'email', 'max:255'],
            'name' => ['string', 'max:255']
        ]);

        if ($validator->fails()) {
            $message = new ApiMessages($validator->errors());
            return response()->json($message->getMessage(), 401);
        }

        if ($request->has('password') && trim($request->get('password'))) {
            $data['password'] = bcrypt($data['password']);
        }

        try {
            $user = $this->user->findOrFail($id);


            $profile = $data['profile'];
            $profile['social_networks'] = serialize($profile['social_networks']);

            $user->update($data);

            $user->profile()->update($profile);

            return response()->json([
                'data' => [
                    'msg' => 'usuário atualizado com sucesso'
                ]
            ]);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage(), $data);
            return response()->json($message->getMessage(), 404);
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
