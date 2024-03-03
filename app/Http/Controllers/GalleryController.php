<?php

namespace App\Http\Controllers;

use App\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('galllery.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('galllery.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $photos = $request->file('file');

        if (!is_array($photos)) {
            $photos = [$photos];
        }

        for ($i = 0; $i < count($photos); $i++) {
            Gallery::create([
                'image' => $photos[$i]->store('public/gallery'),
                'company_id' => 1
            ]);
        }

        return response()->json([
            'message' => __('alert.success-create', ['attribute' => 'Images'])
        ]);
    }

    /**
     * Update the specified resource from storage.
     *
     * @param  \App\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function update(Gallery $gallery, Request $request)
    {
        File::delete(storage_path('app/'.$gallery->image));
        $gallery->image = $request->image->store('public/gallery');
        $gallery->save();
        return response()->json([
            'success'   => true,
            'message'   => __('alert.success-update', ['attribute' => 'Images']),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gallery $gallery)
    {
        File::delete(storage_path('app/'.$gallery->image));
        $gallery->delete();
        return response()->json([
            'success'   => true,
            'message'   => __('alert.success-delete', ['attribute' => 'Images']),
        ]);
    }
}
