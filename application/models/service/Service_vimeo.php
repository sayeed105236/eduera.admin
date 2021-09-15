<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Service_vimeo extends CI_Model {

	private $client = null;

    function __construct()
    {
        parent::__construct();
        require getcwd() . '/vendor/autoload.php';
        $this->client = new Vimeo\Vimeo("ff81b9d6043e1dc75e1a66110a7299df53571c53", "ejap0rj5bsSCpfviEn7VC8DU8GNeNrP5iIh5wDuYk4kEhxDEJVzHkp07yw+hkuvFUUFuKmzxn60UY8+asjSpFtUZKUt/TSTv1p851NJ2Bfc94NR0C05yvDyGlYFN55ON", "721204db726b580bbcd810b456c1c69c");
    }


    public function get_video_duration($video_id){
    	$response = $this->client->request('/me/videos/'. $video_id . '?fields=duration', array(), 'GET');
    	if (isset($response['body']['error'])){
    		return $response['body'];
    	} else {
    		return $response['body']['duration'];
    	}
        
    }


    public function get_youtube_video_id($embed_url = '') {
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $embed_url, $match);
        $video_id = $match[1];
        return $video_id;
    }
// AIzaSyD6u9vOLMdyItByvxp7OGHRQd6GycIk7mY
    public function get_youtube_video_duration($video_url){
        $video_id = $this->get_youtube_video_id($video_url);
        $youtube_key = get_settings('youtube_api_key');
        $api_url = 'https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails&id='.$video_id.'&key='.$youtube_key;
        $json = file_get_contents($api_url);
        $obj = json_decode($json);
        $video = new DateInterval($obj->items[0]->contentDetails->duration);

        return time_to_second_conversion($video);
    }


}