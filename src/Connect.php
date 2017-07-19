<?php
namespace Managerio;

use GuzzleHttp\Client as GuzzleClient;

class Connect implements ConnectInterface
{
	public  $username = "administrator";
	public  $password = "";
	public  $home     = "http://localhost:8080/api";
	public  $base_uri = "http://localhost:8080/api/";
	public  $businessID = "3d43eb26-81dc-4a2c-90cc-df2cc433d9601";
	public  $bcode = "PH001";
	public  $uri   = null;

	public function __construct($auth = "default")
	{
		if (is_array($auth)) {
			$host = $auth['host'];
			$this->username   =  $auth['username'];
			$this->password   =  $auth['password'];
			$this->home       =  $host."/api";
			$this->base_uri   =  $host."/api/";
			$this->businessID =  $auth['key'];
			$this->bcode      =  $auth['bcode'];
		}
	}

	// Initialize base uri and auth
	public function client()
	{
		$client =  new GuzzleClient(['base_uri' => $this->base_uri, 'auth' => [$this->username,$this->password]]);
		return $client;
	}

	// set $uri = uri you want to retrieve , $requestResult = Result you want to retrieve
	public function get($uri= null, $requestResult= null)
	{
		$uri = $uri === null ? $this->home : $uri ;
		$client   = $this->client();
		$response = $client->get($uri);
		$result   = $this->requestResult($response,$requestResult);
		return $result;
	}

	//Post
	public function method($uri,$method,$data)
	{
		$client = $this->client();
		$response = $client->request($method, $uri, $data);
		return $response;
	}


	public function post($uri,$data)
	{
		return $this->method($uri,'POST',$data);
	}

	public function put($uri,$data)
	{
		return $this->method($uri,'PUT',$data);
	}

	public function delete($uri)
	{
		return $this->method($uri,'DELETE',null);
	}


	public function requestResult($response,$requestResult = null)
	{
		$result = "No Result";
		switch ($requestResult) {
			case "StatusCode":
				$result =  $response->getStatusCode();
				break;
			case "Reason":
				$result = $response->getReasonPhrase();
				break;

			case "HeaderExist":
				if ($response->hasHeader('Content-Length')) { $result =  "It exists"; }
				break;

			case "HeaderResponse":
				if ($response->hasHeader('Content-Length')) {
					$string = "";
					foreach ($response->getHeaders() as $name => $values) {
	    				$string .=  $name . ': ' . implode(', ', $values) . "\r\n";
					}
					$result = $string;
				} else {
					$result = "No Header Response";
				}
				break;

			case "Body":
				$result = $response->getBody();
				break;
			case "BodyString":
				$body = $response->getBody();
				$result = (string) $body;
				break;
			default:
				$header =  $response->hasHeader('Content-Length') ?  $response->getHeaders() : "No Header";
				$result = array("StatusCode" => $response->getStatusCode(),"Reason" => $response->getReasonPhrase(),"Body" => $response->getBody(), "Header" => $header );
		}
		return $result;
	}

	// Key Links
	public function keyLink($name,$uri="")
	{
		$links = array(
			"Home"       => "",
			"Customers"  =>  "/ec37c11e-2b67-49c6-8a58-6eccb7dd75ee", // Customer Folder
			"Invoices"   => "/ad12b60b-23bf-4421-94df-8be79cef533e",  // Invoice Folder
			"Inventory"  => "/0dbdbf8a-d80c-48e6-b453-bb7862445b7c",  // Inventory
			"Coa"        => "/26b9e4a5-ce10-4f30-94c7-23a1ca4428f9",  // Profit and Loss Accounts
			"CoaGroup"   => "/5770616c-0e01-46ca-a172-f7042275da6c",  // Profit and Loss
			"Coa2"        => "/6ef13e42-ad89-4d42-9480-546e0c04a411",  // Balance Sheet Accounts
			"NonInventory" => "/7affe9ee-731f-4936-8acf-15cae7bcacee",
			"Supplier"     => "/6d2dc48d-2053-4e45-8330-285ebd431242",
			"CashAccount2" => "/1408c33b-6284-4f50-9e31-48cbea21f3cf", // Cash Account(Bank etc.)
			"PurchaseInvoice" => "/58b9eb90-f6b8-4abc-8ea1-12fd77b8336e",
			"Receipts" => "/8a995f93-a7a7-4297-a3b6-35a339a5ae0d",
			"Employee" => "/dadb7f95-a5dd-45c0-945d-6ad4ee28776e",
			"Capital"  => "/b9c4cd62-7569-44f0-bc62-9df3007a6a5c",
			"GoodReceipt" => "/866217a4-f841-47de-a4e6-87152405c88d"
		);
		return $this->base_uri.$this->businessID.$links[$name].$uri;
	}

}
