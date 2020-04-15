<?php

namespace App\Http\Controllers;

use App\Advert;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\Resources\Advert as AdvertResource;
use Illuminate\Validation\ValidationException;

class AdvertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        if ($request->filled('sort')) {
            $field = $request->get('sort');

            switch ($field) {
                // будем отбираться два значения параметра sort - price, created_at
                case 'price':
                    return AdvertResource::collection(Advert::orderBy('price', 'ASC')->paginate(10));
                case'-price':
                    return AdvertResource::collection(Advert::orderBy('price', 'DESC')->paginate(10));
                case 'created_at':
                    return AdvertResource::collection(Advert::orderBy('created_at', 'ASC')->paginate(10));
                case '-created_at':
                    return AdvertResource::collection(Advert::orderBy('created_at', 'DESC')->paginate(10));
            }
        }

        return AdvertResource::collection(Advert::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {

        try {
            $this->validate($request, [
                'title' => 'required|max:200',
                'description' => 'required|max:1000',
                'price' => 'required|numeric',
                'photo' => ['required',
                    function ($attribute, $value, $fail) {
                        if (count(explode(',', $value)) > 3) {
                            $fail($attribute . ' is invalid.');
                        }
                    }
                ]
            ]);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 400);
        }

        $advert = Advert::create($request->all());
        return response()->json($advert->id, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Advert $advert
     *
     * @return AdvertResource
     */
    public function show(Advert $advert)
    {
        return new AdvertResource($advert);
    }

}
