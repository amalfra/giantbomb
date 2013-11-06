﻿<?php
/**
* GiantBomb PHP wrapper is a simple class written in PHP to
* make interactions with GiantBomb api easier.
*
* @package    GiantBomb api PHP wrapper
* @version    0.1dev
* @author     Amal Francis
* @license    MIT License
**/
class GiantBomb {

	/**
	* The api key
	*
	* @access private 
	* @type   string
	**/
	private $api_key = "";
	
	/**
	* The api response type : json/xml
	*
	* @access private 
	* @type   string
	**/
	private $resp_type = "";
	
	/**
	* A variable to hold the formatted result of  
	* last api request
	*
	* @access public
	* @type   array
	**/
	public $result = array();
	
	/**
	* Constructor
	**/
	function __construct($key = "", $resp = "")
	{
		// No api key? There is no need to continue
		if($key == "") 
		{
			throw new GiantBombException("You need to provide an API key. Get your API Key at http://api.giantbomb.com");
		}
		// Set the api key
		$this->api_key = $key;
		
		// Now set the api response type, Default to json
		if($resp != "" && in_array($resp, array("json", "xml"))) 
		{
			$this->resp_type = $resp;
		}
		else
		{
			$this->resp_type = "json";
		}
	}
	
	/**
	* Get a URL
	*
	* @access private 
	* @return array
	*
	* @param  url string
	**/
	private function get_url($url)
	{
		// Get cURL resource
		$curl = curl_init();
		// Set some options - we are passing in a useragent too here
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url,
			CURLOPT_USERAGENT => 'BombAgent'
		));
		// Send the request & save response to $resp
		$resp["data"] = curl_exec($curl);
		$resp["httpCode"] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		// Close request to clear up some resources
		curl_close($curl);
		return $resp;
	}
	
	/**
	* Get information about a game
	*
	* @access public 
	* @return array
	*
	* @param  game_id string
	**/
	public function game($game_id = "")
	{
		// No game_id? 
		if($game_id == "") 
		{
			throw new GiantBombException("You need to provide a game id to get information about a particular game");
		} 
		$resp = $this->get_url("http://www.giantbomb.com/api/game/" . $game_id . "/?api_key=" . $this->api_key . "&format=" . $this->resp_type);
		if(!$resp || !$resp["data"])
		{
			throw new GiantBombException("Couldn't get information about the game");
		}
		// No game with given game id found
		if($resp["httpCode"] == 404)
		{
			throw new GiantBombException("Couldn't find game with game id '" .$game_id. "'");
		}
		$formatted_resp = $this->format_result($resp["data"]);
		
		// Does the result returned by api have any errors?
		if($formatted_resp->error != "" && strtoupper($formatted_resp->error) != "OK")
		{
			throw new GiantBombException("Following error encountered : " . $formatted_resp["error"]);
		}
		return $formatted_resp;
	}
	
	private function format_result($res)
	{
		if($this->resp_type == "json")
		{
			$this->result = json_decode($res);
		}
		else
		{
			$this->result = simplexml_load_string($res);
		}
		return $this->result;
	}
}

/**
* Define a custom exception class for api wrapper
**/
class GiantBombException extends Exception {

    // Redefine the exception so message isn't optional
    public function __construct($message, $code = 0, Exception $previous = null) 
	{
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }

    // custom string representation of object
    public function __toString() 
	{
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}