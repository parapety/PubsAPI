<?php

namespace AppBundle\Lib\Google;

use AppBundle\Lib\Helper;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Request;

abstract class AbstractApi
{
    /**
     * @var string Google Api Key (from env variable)
     */
    protected $apiKey;

    /**
     * @var string Google Api url
     */
    protected $apiUrl = '';

    /**
     * @var ClientInterface
     */
    protected $client;

    public function __construct(ClientInterface $client, $googleApiKey)
    {
        $this->apiKey = $googleApiKey;
        $this->apiUrl = $this->prepareUrl(['key' => $this->apiKey]);
        $this->client = $client;
    }

    /**
     * Returns data from google api
     *
     * @param array $params
     * @return array[
     * @var int $status_code http response code
     * @var string $status i.e. "OK" @link https://developers.google.com/places/web-service/search#PlaceSearchStatusCodes
     * @var array $data
     * @var array $html_attributions
     * @var string $error error message if status code other than 200
     * ]
     */
    protected function get(Request $request)
    {
        try {
            $response = $this->client->send($request);
            $responseBody = json_decode($response->getBody()->getContents(), true);

            if ($responseBody) {
                $result = [
                    'status_code' => $response->getStatusCode(),
                    'status' => $responseBody['status'],
                    'data' => isset($responseBody['results']) ? $responseBody['results'] : (isset($responseBody['result']) ? $responseBody['result'] : []),
                    'html_attributions' => isset($responseBody['html_attributions']) ? $responseBody['html_attributions'] : []
                ];
            } else {
                $result = ['status_code' => 500, 'status' => 'ERROR', 'error' => json_last_error()];
            }
        } catch (RequestException $e) {
            $result = ['status_code' => $e->getCode(), 'status' => 'ERROR', 'error' => $e->getMessage()];
        } catch (\InvalidArgumentException $e) {
            $result = ['status_code' => 500, 'status' => 'ERROR', 'error' => $e->getMessage()];
        }
        return $result;
    }

    /**
     * build request with given paramters
     * @param $params
     * @return Request
     */
    protected function buildRequest($params)
    {
        return new Request('GET', $this->prepareUrl($params));
    }

    /**
     * @see \AppBundle\Lib\Helper::prepareUrl
     *
     * @param $param
     * @return string
     */
    protected function prepareUrl($param)
    {
        return Helper::prepareUrl($this->apiUrl, $param);
    }
}

