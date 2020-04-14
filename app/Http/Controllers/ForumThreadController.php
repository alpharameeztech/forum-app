<?php

namespace App\Http\Controllers;

use App\Services\CustomSlug;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\ForumThread;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\ForumChannel;
use App\Services\SiteAppearance;
use App\Repositories\FilterRepository;
use App\Inspections\Spam;
use App\Services\TrendingThreads;
use Illuminate\Support\Facades\Cache;
use Zttp\Zttp;
use App\Repositories\ChannelRepository;

class ForumThreadController extends Controller
{
    protected $channelRepository;

    public function __construct(ChannelRepository $channelRepository)
    {
        $this->channelRepository = $channelRepository;
        view()->share('channels', $this->channelRepository->all());
        view()->share('siteCssClass', SiteAppearance::filter('css_class'));
        view()->share('siteBanner', SiteAppearance::filter('banner'));
        view()->share('siteCssCode', SiteAppearance::filter('css_code'));
        view()->share('siteJsCode', SiteAppearance::filter('js_code'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(ForumChannel $channel,  TrendingThreads $trendingThreads) //or $channelSlug = null
    {

        if($channel->exists){ // if the channel is valid model on forumChannel
            $threads_builder_query = $channel->threads()->latest()->where('is_ban',0);
        }
        else {
            $threads_builder_query = ForumThread::where('is_ban',0)->latest();
        }

        $threads = FilterRepository::apply($threads_builder_query);

        return view('forum.threads.index',[
            'threads' => $threads,
            'trending_threads' => $trendingThreads->get()
        ]);
        return $threads;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param TrendingThreads $trendingThreads
     * @return Response
     */
    public function create(TrendingThreads $trendingThreads)
    {

        return view('forum.threads.create', [
            'trending_threads' => $trendingThreads->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function store(Request $request)
    {


        $this->validateRequest();

        $thread = ForumThread::create([
            'shop_id' => Cache::get('shop_id'),
            'user_id' => Auth::id(),
            'forum_channel_id' => request('channel'),
            'is_ban' => 0, // allow by default
            'title' => request('title'),
            'slug' => CustomSlug::create(request('title')), //str_slug(request('title')),
            'body' => request('body')
        ]);

        //increment user experience
        $thread->creator->updateExperience(100);

        return redirect('forum'. $thread->path())
                ->with('flash-message','Your thread has been published!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($channelId, ForumThread $thread, TrendingThreads $trendingThreads)
    {

        if(auth()->user()){

            Auth::user()->read($thread); // user has read this thread

       }

        $thread = ForumThread::find($thread->id);

        // incremet the trending threads value
        $trendingThreads->push($thread);

        $thread->visits()->record(); // where visit is a function on forumThread model

        return view('forum.threads.show',[
            'thread' => $thread,
            'replies' => $thread->replies()->paginate(10),
            'trending_threads' => $trendingThreads->get()
        ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $channel
     * @param ForumThread $thread
     * @return int
     * @throws AuthorizationException
     */
    public function update($channel, ForumThread $thread)
    {

        //use policies to verify whether the user can do this action or not
        $this->authorize('update', $thread);

         $thread->update(request()->validate([

            'body' => 'required'
         ]));

         if( request()->expectsJson()){

            return 1;
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $channel
     * @param ForumThread $thread
     * @return Response
     * @throws AuthorizationException
     */
    public function destroy($channel, ForumThread $thread)
    {
        //use policies to verify whether the user can do this action or not
        $this->authorize('delete', $thread);

        //first delete all the replies of a thread
        $thread->replies()->delete();
        //then delete a thread
        $thread->delete();

        return redirect('forum/threads');
    }

    protected function validateRequest(){

        $this->validate(request(), [
            'channel' => 'required|exists:forum_channels,id',  // a valid forum_channel_id is required of  the forum_channels table
            'title' => 'required',
            // 'body' => 'required'
        ]);

        resolve(Spam::class)->detect(request('body'));// with resolve you dont have to inject into class constructor

        resolve(Spam::class)->detect(request('title'));
    }
}
