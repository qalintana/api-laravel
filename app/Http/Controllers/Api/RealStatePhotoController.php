<?php

namespace App\Http\Controllers\Api;

use App\Api\ApiMessages;
use App\Http\Controllers\Controller;
use App\RealStatePhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RealStatePhotoController extends Controller
{
    //
    private $realStatePhoto;

    public function __construct(RealStatePhoto $realStatePhoto)
    {
        $this->realStatePhoto = $realStatePhoto;
    }

    public function setThumb($photoId, $realStateId)
    {
        try {
            $photo = $this->realStatePhoto
                ->where('real_state_id', $realStateId)
                ->where('is_thumb', true);

            if ($photo->count())  $photo->first()->update(['is_thumb' => false]);

            $photo = $this->realStatePhoto->find($photoId);
            $photo->update(['is_thumb' => true]);

            return response()->json(
                ['data' => [
                    'msg' => 'Thumb atualizada com sucesso'
                ]]
            );
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }


    public function removePhoto($photoId)
    {
        try {
            $photo = $this->realStatePhoto->findOrFail($photoId);

            if ($photo->is_thumb) {
                $message = new ApiMessages('Não é possivel remover a foto de perfil');
                return response()->json($message->getMessage());
            }

            Storage::disk('public')->delete($photo->photo);
            $photo->delete();

            return response()->json(
                ['data' => [
                    'msg' => 'Foto removida com sucesso'
                ]]
            );
        } catch (\Exception $e) {
            $message = new ApiMessages($e->getMessage());
            return response()->json($message->getMessage(), 401);
        }
    }
}
