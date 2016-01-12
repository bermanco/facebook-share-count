<?php

namespace bermanco\FacebookCount;

class FacebookCount {

	const FACEBOOK_API_URL = 'https://graph.facebook.com/fql';

	protected $include_shares = true;
	protected $include_likes = true;
	protected $include_comments = true;

	/////////////
	// Setters //
	/////////////

	public function set_include_shares($bool){
		$this->include_shares = $bool;
	}

	public function set_include_likes($bool){
		$this->include_likes = $bool;
	}

	public function set_include_comments($bool){
		$this->include_comments = $bool;
	}

	/////////////
	// Getters //
	/////////////

	public function get_counts(array $urls){

		$request_url = $this->build_request($urls);

		$data = $this->make_request($request_url);

		$results = $this->process_response($data);

		return $results;

	}

	public function get_single_url_count($url){

		$results = $this->get_counts(array($url));

		if (isset($results[$url])){
			return $results[$url];
		}

	}

	///////////////
	// Protected //
	///////////////

	protected function build_request($urls){

		$args = array(
			'q' => $this->create_fql_query($urls)
		);

		$query_string = http_build_query($args);

		$request_url = self::FACEBOOK_API_URL . '?' . $query_string;

		return $request_url;

	}

	/**
	 * Create FQL query statement
	 * @param  array $urls  Array of URLs to use for the query
	 * @return string       FQL query statement
	 */
	protected function create_fql_query($urls){

		$imploded_urls = "'" . implode("', '", $urls) . "'";

		$fql = "SELECT url, like_count, total_count, share_count, click_count, comment_count FROM link_stat WHERE url IN ($imploded_urls)";

		return $fql;

	}

	protected function make_request($request_url){

		$response = file_get_contents($request_url);

		if ($response){
			$array = json_decode($response, true); // return an array
			return $array;
		}

	}

	protected function process_response(array $response = null){

		if ($response){

			$data = $response['data'];

			$processed_response = array();

			foreach ($data as $item){

				$url = $item['url'];
				$count = $this->calculate_count($item);

				$processed_response[$url] = $count;

			}

			return $processed_response;

		}

	}

	protected function calculate_count(array $data){

		$count = 0;

		if ($this->include_shares && isset($data['share_count'])){
			$count += $data['share_count'];
		}

		if ($this->include_likes && isset($data['like_count'])){
			$count += $data['like_count'];
		}

		if ($this->include_comments && isset($data['comment_count'])){
			$count += $data['comment_count'];
		}

		return $count;

	}

}


