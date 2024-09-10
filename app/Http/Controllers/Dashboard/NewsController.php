<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\News;
use App\Models\NewsSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('view_news');

        if ($request->ajax())
        {
            $data = getModelData( model: new News() );

            return response()->json($data);
        }

        return view('dashboard.news.index');
    }

    public function create()
    {
        $this->authorize('create_news');

        return view('dashboard.news.create');
    }


    public function edit(News $news)
    {
        $this->authorize('update_news');

        return view('dashboard.news.edit',compact('news'));
    }


    public function show(News $news)
    {
        $this->authorize('show_news');

        return view('dashboard.news.show',compact('news'));
    }

    public function store(Request $request)
    {
        $this->authorize('create_news');

        $data = $request->validate([
            'title'         => ['required','string','max:255'],
            'tags'          => ['required','string','max:255'],
            'description'   => ['required','string'],
            'main_image'    => ['required','mimes:jpeg,jpg,png,gif,svg,webp','max:2048'],
            'cover_image'   => ['required_with:highlighted','mimes:jpeg,jpg,png,gif,svg,webp','max:2048'],
        ]);

        if ($request->file('main_image'))
            $data['main_image'] = uploadImage( $request->file('main_image') , "News");

        if ($request->file('cover_image'))
            $data['cover_image'] = uploadImage( $request->file('cover_image') , "News");

        $data['highlighted'] = $request['highlighted'] == "on";

        $news = News::create($data);

        NewsSubscriber::get()->map( function ($user) use ($news) {

            try {

                Mail::send('mails.newsletter', compact('news') ,function($message) use ($news , $user){
                    $message->to([$user->email])
                        ->subject($news->title);
                });

            } catch (\Throwable $th) {
                dd($th->getMessage()) ;
            }

        });

    }

    public function update(Request $request , News $news)
    {
        $this->authorize('update_news');

        $data = $request->validate([
            'title'         => ['required','string','max:255'],
            'tags'          => ['required','string','max:255'],
            'description'   => ['required','string'],
            'main_image'    => ['nullable','mimes:jpeg,jpg,png,gif,svg,webp','max:2048'],
            'cover_image'   => ['nullable','mimes:jpeg,jpg,png,gif,svg,webp','max:2048'],
        ]);

        if ($request->file('main_image'))
        {
            deleteImage( $news['main_image'] , "News");
            $data['main_image'] = uploadImage( $request->file('main_image') , "News");
        }

        if ($request->file('cover_image'))
        {
            deleteImage( $news['cover_imag'] , "News");
            $data['cover_image'] = uploadImage( $request->file('cover_image') , "News");
        }

        $data['highlighted'] = $request['highlighted'] == "on";

        $news->update($data);
    }


    public function destroy(Request $request, News $news)
    {
        $this->authorize('delete_news');

        if($request->ajax())
        {
            $news->delete();
        }
    }
}
