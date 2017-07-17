<?php
namespace Managerio;

class Manual extends Connect
{
	public function __construct(){
		parent::__construct();
	}

	public function getList($page,$key="index")
	{
		$link = $this->keyLink($page,"/$key.json");
		if($key != "index"){
			$string =  explode("/",$link);
			$link = str_replace("{$string[5]}/", "", $link);
		}
		$list = $this->get($link);
		return json_decode($list['Body']);
	}

	public function getInfo()
	{
		$info = array(
			'username' => $this->username,
			'password' => $this->password,
			'home'     => $this->home,
			'base_uri' => $this->base_uri,
			'businessID' => $this->businessID,
			'bcode'    => $this->bcode
		);
		return $info;
	}


	public function addData($page,$array){
		$data['json'] = $array;
		$response = $this->post($this->keyLink($page),$data);
		$header   = $response->getHeaders();
		$location = $header['Location'];
		// json location/link of the new Items
		if($location){
			return $location[0];
		}
		return null;
	}

	public function deleteData($uri){
		$base = $this->keyLink("Home");
		$url  = "$base/$uri.json";
		$this->delete($url);
	}


	public function getKeyVal($string)
	{
		$exp = explode("/", $string);
		return rtrim($exp[3],".json");
	}

	public function justKey($string,$note="")
	{
		$exp = explode("/", $string);
		if($exp[3]){
			return rtrim($exp[3],".json");
		}
		echo "Note: $note";
		return null;
	}


	public function test(){
		echo "Im Here";
	}

}
