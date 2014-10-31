<?php
/**
 * Copyright 2014 Openstack.org
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * http://www.apache.org/licenses/LICENSE-2.0
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 **/
/**
 * Class GoogleGeoLocationService
 * https://developers.google.com/maps/documentation/geocoding/
 * Users of the free API:
 * 2,500 requests per 24 hour period.
 */
final class GoogleGeoCodingService implements IGeoCodingService {

	const ApiHost    = 'https://maps.googleapis.com';
	const ApiBaseUrl = '/maps/api/geocode/json';

	/**
	 * @var string
	 */
	private $api_key;
	/**
	 * @var string
	 */
	private $client_id;
	/**
	 * @var string
	 */
	private $private_key;
	/**
	 * @var IGeoCodingQueryRepository
	 */
	private $repository;

	/**
	 * @var ITransactionManager
	 */
	private $tx_manager;

	/**
	 * @var IUtilFactory
	 */
	private $factory;

	public function __construct(IGeoCodingQueryRepository $repository,
	                            IUtilFactory $factory,
								ITransactionManager $tx_manager,
								$api_key=null,
	                            $client_id=null,
	                            $private_key=null){

		$this->api_key     = $api_key;
		$this->client_id   = $client_id;
		$this->private_key = $private_key;
		$this->repository  = $repository;
		$this->tx_manager  = $tx_manager;
		$this->factory     = $factory;
	}

	// Encode a string to URL-safe base64
	private function encodeBase64UrlSafe($value) {
		return str_replace(array('+', '/'), array('-', '_'),base64_encode($value));
	}

	// Decode a string from URL-safe base64
	private function decodeBase64UrlSafe($value) {
		return base64_decode(str_replace(array('-', '_'), array('+', '/'),$value));
	}


	private function shouldSignUrl(){
		return !empty($this->client_id) && !empty($this->private_key);
	}

	private function shouldUseKey(){
		return !empty($this->api_key);
	}

	private function signUrl($url){
		$url .= "&client={$this->client_id}";
		// Decode the private key into its binary format
		$decodedKey = $this->decodeBase64UrlSafe($this->private_key);
		// Create a signature using the private key and the URL-encoded
		// string using HMAC SHA1. This signature will be binary.
		$signature = hash_hmac("sha1",$url, $decodedKey,  true);
		$encodedSignature = $this->encodeBase64UrlSafe($signature);
		return $url."&signature={$encodedSignature}";
	}

	/**
	 * return GPS coordinates array($lat,$lng)
	 * @param string $city
	 * @param string $country
	 * @param string|null $state
	 * @param string|null $address
	 * @param string|null $zip_code
	 * @throws EntityValidationException
	 * @return array
	 */
	private function doGeoQuery($city, $country ,$state = null, $address = null, $zip_code = null){

		$formatted_city = urlencode($city);
		$query = "?components=locality:{$formatted_city}|country:{$country}";
		if(!empty($state)){
			$formatted_state = urlencode($state);
			$query .= "|administrative_area:{$formatted_state}";
		}
		if(!empty($address)){
			$formatted_address = urlencode($address);
			$query .= "|address:{$formatted_address}";
		}
		if(!empty($zip_code)){
			$formatted_zip_code = urlencode($zip_code);
			$query .= "&postal_code={$formatted_zip_code}";
		}
		$query .= "&sensor=false";

		$url = self::ApiBaseUrl.$query;
		if($this->shouldUseKey()){
			$url .= "&key={$this->api_key}";
		}
		else if($this->shouldSignUrl()){
			$url = $this->signUrl($url);
		}

		$factory    = $this->factory;
		$repository = $this->repository;
		return $this->tx_manager->transaction(function() use($repository, $factory, $url, $query, $city, $country ,$state, $address , $zip_code){

			$res = $repository->getByGeoQuery($query);
			if($res) return array($res->getLat(),$res->getLng());
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, GoogleGeoCodingService::ApiHost . $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$response = curl_exec($ch);
			curl_close($ch);
			$response_a = json_decode($response);
			if(is_null($response_a)){
				if(!empty($address))
					throw new EntityValidationException(array( array('message' => sprintf('Address %s (%s) does not exist on City %s',$address,$zip_code,$city))));
				else
					throw new EntityValidationException(array( array('message' => sprintf('City %s does not exist on Country %s',$city,$country))));
			}
			if($response_a->status!='OK'){
				if(!empty($address))
					throw new EntityValidationException(array( array('message' => sprintf('Address %s (%s) does not exist on City %s - (STATUS: %s)',$address,$zip_code,$city,$response_a->status))));
				else
					throw new EntityValidationException(array( array('message' => sprintf('City %s does not exist on Country %s (STATUS: %s)',$city,$country,$response_a->status))));
			}
			$repository->add($factory->buildGeoCodingQuery($query,$response_a->results[0]->geometry->location->lat, $response_a->results[0]->geometry->location->lng));
			return array($response_a->results[0]->geometry->location->lat, $response_a->results[0]->geometry->location->lng);
		});
	}

	/**
	 * given a city name and an ISO 3166-1 country code
	 * return GPS coordinates array($lat,$lng)
	 * @param string $city
	 * @param string $country
	 * @param string|null $state
	 * @throws EntityValidationException
	 * @return array
	 */
	public function getCityCoordinates($city, $country ,$state = null)
	{
		return $this->doGeoQuery($city, $country ,$state);
	}

	/**
	 * given an address info
	 * return GPS coordinates array($lat,$lng)
	 * @param AddressInfo $address_info
	 * @throws EntityValidationException
	 * @return array
	 */
	public function getAddressCoordinates(AddressInfo $address_info)
	{
		list($address1,$address2) = $address_info->getAddress();
		$address = $address1.' '.$address2;
		$city    = $address_info->getCity();
		$state   = $address_info->getState();
		if(!empty($city)){
			$address.=", {$city}";
		}
		if(!empty($state)){
			$address.=", {$state}";
		}
		$zip_code = $address_info->getZipCode();
		$country  = $address_info->getCountry();
		return $this->doGeoQuery($city,$country,$state,$address,$zip_code);
	}
}