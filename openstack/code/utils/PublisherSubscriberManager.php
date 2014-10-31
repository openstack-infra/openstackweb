<?php
/**
 * Copyright 2014 Openstack Foundation
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
 * Class PublisherSubscriberManager
 */
final class PublisherSubscriberManager {
	/**
	 * @var PublisherSubscriberManager
	 */
	private static $instance;

	private $events = array(); // all subscriptions

	private function __construct(){}

	private function __clone(){}

	/**
	 * @return PublisherSubscriberManager
	 */
	public static function getInstance(){
		if(!is_object(self::$instance)){
			self::$instance = new PublisherSubscriberManager();
		}
		return self::$instance;
	}

	/**
	 * @param string   $event_name
	 * @param $callback
	 */
	public function subscribe($event_name, $callback){

		// Make sure the subscription isn't null
		if ( empty( $this->events[ $event_name ] ) )
			$this->events[ $event_name ] = array();
		// push the $callback onto the subscription stack
		array_push( $this->events[ $event_name ], $callback );
	}

	/**
	 * @param string$event_name
	 * @param array $params
	 * @return bool
	 */
	public function publish( $event_name, array $params = array())
	{
		// Check to see if the subscribe isn't null
		if ( empty($this->events[$event_name] ) )
			return false;

		// Loop through all the events and call them
		foreach ( $this->events[$event_name] as $event )
		{
			if ( is_callable( $event ) )
				call_user_func_array( $event, $params );
		}
	}

	/**
	 * @param string $event_name
	 */
	public function unsubscribe($event_name){
		if ( !empty( $this->events[$event_name] ) )
			unset($this->events[$event_name] );
	}

} 