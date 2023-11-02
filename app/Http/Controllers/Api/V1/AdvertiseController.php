<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdvertiseRequest;
use App\Http\Resources\V1\AdvertiseCollection;
use App\Http\Resources\V1\AdvertiseResource;
use App\Models\Advertise;
use App\Models\AdvertiseImage;

class AdvertiseController extends Controller
{
    public function index()
    {
        $user = auth('sanctum')->user();
        return new AdvertiseCollection(Advertise::where('user_id', $user->id)->paginate());
    }

    public function show(Advertise $advertise)
    {
        return new AdvertiseResource($advertise);
    }

    public function store(StoreAdvertiseRequest $request)
    {
        $model = Advertise::create($request->validated());

        $path = public_path('uploads');
        $file = $request->file('image');
        $name = uniqid() . '_' . trim($file->getClientOriginalName());
        $file->move($path, $name);
        $model->adsImages()->create([
            'name' => $name,
            'path' => $path . '/' . $name
        ]);

        return response()->json('advertise created ' . $model->id);
    }

    public function update(StoreAdvertiseRequest $request, Advertise $advertise, AdvertiseImage $adsImage)
    {
        $advertise->update($request->validated());

        if ($request->file('image')) {
            $path = public_path('uploads');
            $file = $request->file('image');
            $name = uniqid() . '_' . trim($file->getClientOriginalName());
            $file->move($path, $name);
            $adsImageModel = $adsImage->find($request->ads_img_id);
            $adsImageModel->update([
                'name' => $name,
                'path' => $path . '/' . $name
            ]);
        }
        return response()->json('advertise updated');
    }

    public function destroy(Advertise $advertise)
    {
        $advertise->delete();
        return response()->json('advertise deleted');
    }
}
