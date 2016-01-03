<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
use GuzzleHttp\Client;
use TwitterOAuth\Auth\SingleUserAuth;
use TwitterOAuth\Serializer\ArraySerializer;


$app->get('/', 'HomeController@index');
$app->get('/publish', 'HomeController@publish');
$app->get('/{category}', 'HomeController@index');
use Carbon\Carbon;
$app->get('/d/e/f', function(){

	$date = date('Y-m-d', strtotime('+' . mt_rand(1, 30) . ' days'));
	$times = config('times.publishing_times');
	$index = array_rand($times);
	$date_time = Carbon::parse($date . ' ' . $times[$index]);
});

$app->get('fo/fa/fi', function(){

	$post = DB::table('posts')
            ->where('id', 2)
            ->first();

    $urls = \Purl\Url::extract($post->content);
    if(!empty($urls)){
    	return $urls[0];
    }
});

$app->get('/a/b/c', function(){
	
	$client = new Client;
	$content = 'The App-ocalypse: can web standards make mobile apps obsolete? http://arstechnica.com/information-technology/2015/12/the-app-ocalypse-can-web-standards-make-mobile-apps-obsolete/';
	$post_url = 'http://arstechnica.com/information-technology/2015/12/the-app-ocalypse-can-web-standards-make-mobile-apps-obsolete/';
	$content = 'testing ahead';
	/*


	Twitter::setOAuthToken(config('twitter.oauth_token'));
	Twitter::setOAuthTokenSecret(config('twitter.oauth_secret'));
	$twitter_response = Twitter::statusesUpdate($content);
	return $twitter_response;
	*/

	/*
	$nonce = base64_encode(str_random(32));
	$data = urlencode('POST&https://api.twitter.com/1/statuses/update.json&include_entities=true&oauth_consumer_key=' . config('twitter.id') . '&oauth_nonce=' . $nonce . '&oauth_signature_method=HMAC-SHA1&oauth_timestamp=' . time() . '&oauth_token=' . config('twitter.oauth_token') . '&oauth_version=1.0&status=testing ahead');

	$signing_key = config('twitter.secret') . '&' . config('twitter.oauth_secret');

	$signature = base64_encode(hash_hmac('sha1', $data, $signing_key, true));

	$client->post('https://api.twitter.com/1.1/statuses/update.json',
		[

'headers' => [
			'oauth_consumer_key' => config('twitter.id'),
			'oauth_nonce' => $nonce,
			'oauth_signature' => $signature,
			'oauth_signature_method' => 'HMAC-SHA1',
			'oauth_timestamp' => time(),
			'oauth_token' => config('twitter.oauth_token'),
			'oauth_version' => '1.0'
		],
		'form' => [
			'status' => $content
		]
		]
		);
	return $client->getBody();
	*/
	/*
	$twitter = new Twitter(config('twitter.id'), config('twitter.secret'));
	$twitter->setOAuthToken(config('twitter.oauth_token'));
	$twitter->setOAuthTokenSecret(config('twitter.oauth_secret'));

	$response = $twitter->statusesUpdate('Running the tests.. 私のさえずりを設定する '. time());
	return $response;
	*/

	$credentials = array(
    'consumer_key' => config('twitter.id'),
    'consumer_secret' => config('twitter.secret'),
    'oauth_token' => config('twitter.oauth_token'),
    'oauth_token_secret' => config('twitter.oauth_secret'),
);

	$auth = new SingleUserAuth($credentials, new ArraySerializer());

	$params = [
		'status' => 'Running the tests.. 私のさえずりを設定する'
	];

	$response = $auth->post('statuses/update', $params);
	return $response;
});

