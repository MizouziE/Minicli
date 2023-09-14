<?php

namespace Minicli\Util;

class CurlClient
{
	protected $lastResponse;

	public function get($endpoint, array $headers = [])
	{
		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_URL => $endpoint,
			CURLINFO_HEADER_OUT => true
		]);

		return $this->getQueryResponse($curl);
	}

	public function post($endpoint, array $params, $headers = [])
	{
		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_HTTPHEADER => $headers,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => json_encode($params),
			CURLOPT_URL => $endpoint,
			CURLOPT_TIMEOUT => 120,
		]);

		return $this->getQueryResponse($curl);
	}

	public function delete($endpoint, $headers = [])
	{
		$curl = curl_init();

		curl_setopt_array($curl, [
			CURLOPT_HEADER => $headers,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CUSTOMREQUEST => "DELETE",
			CURLOPT_URL => $endpoint
		]);

		return $this->getQueryResponse($curl);
	}

	protected function getQueryResponse($curl)
	{
		$response = curl_exec($curl);
		$responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		curl_close($curl);

		return [
			'code' => $responseCode,
			'body' => $response
		];
	}
}
