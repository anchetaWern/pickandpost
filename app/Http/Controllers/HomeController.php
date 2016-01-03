<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Jobs\PublishPost;
use Carbon\Carbon;
use DB;
use Queue;

class HomeController extends BaseController
{
    
    public function index($category = 'hn'){

    	$news_sources = [
    		'hn' => 'Hacker News',
    		'curators' => 'Curators',
    		'webdev' => 'Web Development',
    		'programming' => 'Programming',
    		'db' => 'Database',
    		'tools' => 'Tools',
    		'mobile' => 'Mobile',
    		'gamedev' => 'Game Development',
    		'wordpress' => 'Wordpress',
    		'devops' => 'DevOps',
    		'design' => 'Design',
    		'product' => 'Product',
    		'machine-learning' => 'Machine Learning',
    		'data-science' => 'Data Science',
    		'github' => 'Github'
    	];

    	$client = new Client;
    	$items = $client->get("http://updatedapp.github.io/files/{$category}.json");
    	$items = json_decode($items->getBody(), true);

    	$page_data = [
    		'category' => $category,
    		'news_sources' => $news_sources,
    		'items' => $items
    	];

    	return view('index', $page_data);
    }


    public function publish(Request $request){
    	
    	
    	$title = $request->input('title');
    	$url = $request->input('url');

		$date = date('Y-m-d', strtotime('+' . mt_rand(1, 30) . ' days'));
		$times = config('times.publishing_times');
		$index = array_rand($times);
		$date_time = Carbon::parse($date . ' ' . $times[$index]);

    	$post_id = DB::table('posts')->insertGetId([
    		'content' => $title . ' ' . $url,
    		'date_time' => $date_time,
    		'is_published' => 0
    	]);
    	
		Queue::later($date_time, new PublishPost($post_id));

		return redirect()->back()->with('message', 'to be published at ' . $date_time);
		
    }

}
