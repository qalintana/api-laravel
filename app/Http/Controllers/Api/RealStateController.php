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

        $images = $request->file('images');

        try {
            $realState = $this->realState->create($data);

            if (isset($data['categories']) && count($data['categories'])) {
                $realState->categories()->attach($data['categories']);
            }


            if ($images) {
                $imagesUploaded = [];
                foreach ($images as $image) {
                    $path = $image->store("images", 'public');
                    $imagesUploaded[] = ['photo' => $path, 'is_thumb' => false];
                }

                $realState->photos()->createMany($imagesUploaded);
            }

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
        $images = $request->file('images');

        try {
            $realState = $this->realState->findOrFail($id);
            $realState->update($data);

            if (isset($data['categories']) && count($data['categories'])) {
                $realState->categories()->sync($data['categories']);
            }


            if ($images) {
                $imagesUploaded = [];
                foreach ($images as $image) {
                    $path = $image->store("images", 'public');
                    $imagesUploaded[] = ['photo' => $path, 'is_thumb' => false];
                }

                $realState->photos()->createMany($imagesUploaded);
            }

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
            $realState = $this->realState->with('photos')->findOrFail($id);

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
