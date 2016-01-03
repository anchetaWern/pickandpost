<?php
namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use GuzzleHttp\Client;
use TwitterOAuth\Auth\SingleUserAuth;
use TwitterOAuth\Serializer\ArraySerializer;
use DB;
use CurlRequester;

class PublishPost extends Job implements SelfHandling, ShouldQueue
{
    use SerializesModels;

    protected $post_id;

    public function __construct($post_id)
    {
        $this->post_id = $post_id;
    }


    public function handle()
    {

        $client = new Client;

        $post = DB::table('posts')
            ->where('id', $this->post_id)
            ->first();

        $urls = \Purl\Url::extract($post->content);
    
        if(config('twitter.oauth_token')){        

            $credentials = array(
                'consumer_key' => config('twitter.id'),
                'consumer_secret' => config('twitter.secret'),
                'oauth_token' => config('twitter.oauth_token'),
                'oauth_token_secret' => config('twitter.oauth_secret'),
            );

            $auth = new SingleUserAuth($credentials, new ArraySerializer());

            $params = [
                'status' => $post->content
            ];

            $response = $auth->post('statuses/update', $params);

        }


        if(config('linkedin.oauth_token')){

            try{

                $post_data = array(
                    'comment' => $post->content,
                    'content' => array(
                        'description' => $post->content
                    ),
                    'visibility' => array('code' => 'anyone')
                );

                if(!empty($urls)){
                    $post_data['content']['submittedUrl'] = trim($urls[0]);
                }

                $request_body = $post_data;

                $linkedin_resource = '/v1/people/~/shares';
                $request_format = 'json';


                $linkedin_params = array(
                    'oauth2_access_token' => config('linkedin.oauth_token'),
                    'format'  => $request_format,
                );


                $linkedinurl_info = parse_url('https://api.linkedin.com' . $linkedin_resource);

                if(isset($linkedinurl_info['query'])){
                    $query = parse_str($linkedinurl_info['query']);
                    $linkedin_params = array_merge($linkedin_params, $query);
                }

                $request_url = 'https://api.linkedin.com' . $linkedinurl_info['path'] . '?' . http_build_query($linkedin_params);

                $request_body = json_encode($request_body);
                $linkedin_response = CurlRequester::requestCURL('POST', $request_url, $request_body, $request_format);


            }catch(Exception $e){


            }

        }


        if(config('facebook.oauth_token')){

            try{

                $post_data = array(
                    'access_token' => config('facebook.oauth_token'),
                    'message' => $post->content
                );
                
                if(!empty($urls)){
                    $post_data['link'] = trim($urls[0]);
                }
                

                $res = $client->post('https://graph.facebook.com/me/feed', array(
                    'query' => $post_data
                ));

                $response_body = $res->getBody();
                $response_body = json_decode($response_body, true);

            }catch(Exception $e){

            }

        }


    }
}