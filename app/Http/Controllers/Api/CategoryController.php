<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Category;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{

    private $category;

    public function __construct(Category $category)
    {
        $this->category = $category;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->category->paginate(10);
        return response()->json($categories, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        //
        $data = $request->all();
        try {

            $this->category->create($data);
            return response()->json([
                'data' => [
                    'msg' => 'categoria cadastrada com sucesso'
                ]
            ]);
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

        try {

            $category = $this->category->findOrFail($id);

            return response()->json(['data' => $category], 200);
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
    public function update(CategoryRequest $request, $id)
    {
        $data = $request->all();

        try {
            $category = $this->category->findOrFail($id);

            $category->update($data);

            return response()->json([
                'data' => [
                    'msg' => 'categoria atualizada com sucesso'
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
            $category = $this->category->findOrFail($id);
            $category->delete();
            return response()->json(['message' => $category->name . ' usuario deleted!']);
        } catch (\Exception $e) {

            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage());
        }
    }

    public function realStates($id)
    {
        try {
            $data = $this->category->with('realStates')->findOrFail($id);
            return response()->json($data, 200);
        } catch (\Exception $e) {
            $messages = new ApiMessages($e->getMessage());
            return response()->json($messages->getMessage(), 404);
        }
    }
}
