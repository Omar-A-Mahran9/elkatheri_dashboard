<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view_tags');

        if ($request->ajax())
        {
            $data = getModelData( model: new Tag() );

            return response()->json($data);
        }

        return view('dashboard.tags.index');
    }

    public function create()
    {
        $this->authorize('create_tags');

        return view('dashboard.tags.create');
    }


    public function edit(Tag $tag)
    {
        $this->authorize('update_tags');

        return view('dashboard.tags.edit',compact('tag'));
    }

    public function store(Request $request)
    {

        $this->authorize('create_tags');

        $data = $request->validate([
            'name_ar'    => 'required | string | max:255 | unique:tags',
            'name_en'    => 'required | string | max:255 | unique:tags',
            'bg_color'   => 'required | string | max:255',
        ]);


        Tag::create($data);

    }

    public function show($id)
    {
        abort(404);
    }

    public function update(Request $request , Tag $tag)
    {
        $this->authorize('update_tags');

        $data = $request->validate([
            'name_ar'    => 'required | string | max:255 | unique:tags,name_ar,' . $tag->id,
            'name_en'    => 'required | string | max:255 | unique:tags,name_en,' . $tag->id,
            'bg_color'   => 'required | string | max:255',
        ]);


        $tag->update($data);
    }


    public function destroy(Request $request, Tag $tag)
    {
        $this->authorize('delete_tags');

        if($request->ajax())
        {
            $tag->delete();
        }
    }

}
