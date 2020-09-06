<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\RealStateRequest;
use App\RealState;

class RealStateController extends Controller
{
    //
    private $realState;

    public function __construct(RealState $realState)
    {
        $this->realState = $realState;
    }


    public function index()
    {
        $realState = $this->realState->paginate(10);
        return response()->json($realState, 200);
    }

    public function store(RealStateRequest $request)
    {
        $data = $request->all();
        try {

            $realState = $this->realState->create($data);
            return response()->json([
                'data' => [
                    'msg' => 'imóvel cadastrado com sucesso'
                ]
            ]);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage(), $data);
            return response()->json($message->getMessage());
        }
    }

    public function update(RealStateRequest $request, $id)
    {
        $data = $request->all();
        try {
            $realState = $this->realState->findOrFail($id);
            $realState->update($data);

            return response()->json([
                'data' => [
                    'msg' => 'imóvel atualizado com sucesso'
                ]
            ]);
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage(), $data);
            return response()->json($message->getMessage());
        }
    }

    public function show($id)
    {
        try {

            $realState = $this->realState->findOrFail($id)->get(['title']);

            return response()->json($realState, 200);
        } catch (\Exception $e) {

            $message = new ApiMessages($e->getMessage());

            return response()->json($message->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $realState = $this->realState->findOrFail($id);
            $realState->delete();

            return response()->json(['message' => 'Real State deleted!']);
        } catch (\Exception $e) {

            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage());
        }
    }
}
