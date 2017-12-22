<?php

namespace Kenshoo;

use Exception;
use GuzzleHttp\Client;

/**
 * Query the API.
 *
 * @author jwhulette@gmail.com
 */
class Query
{
    /**
     * A configuration object.
     *
     * @var object
     */
    protected $configuration;

    /**
     * Query the API.
     *
     * @method __construct
     *
     * @param Configuration $configuration A kenshoo configuration object
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Download the returned data.
     *
     * @method downloadData
     *
     * @param string $url      The url to query
     * @param int    $reportId The report id
     *
     * @return object An httpguzzle object
     * 
     * @throws \Exception
     */
    public function downloadData($url, $reportId)
    {
        $client = new Client(
            ['base_uri' => $this->configuration->getUrl()]
        );

        $file = sys_get_temp_dir().'/'.$reportId.'_kenshoo.zip';

        $response = $client->request(
            'GET',
             $url,
             [
                 'debug' => $this->configuration->getDebug(),
                 'auth' => [
                 $this->configuration->getUsername(),
                 $this->configuration->getPassword(), ],
                 'http_errors' => false,
                 'sink' => $file,
             ]
             );

        $status = $this->checkHttpResponse($response->getStatusCode());

        if (false == $status) {
            return $file;
        } else {
            throw new Exception($status);
        }
    }

    /**
     * Query the api using get.
     *
     * @method getQuery
     *
     * @param string $url The url to query
     *
     * @return string A json response object
     *
     * @throws \Exception
     */
    public function getQuery($url)
    {
        $client = new Client(
            ['base_uri' => $this->configuration->getUrl()]
        );

        $response = $client->request('GET', $url, [
            'debug' => $this->configuration->getDebug(),
            'auth' => [
                $this->configuration->getUsername(),
                $this->configuration->getPassword(), ],
                'http_errors' => false,
            ]);

        $status = $this->checkHttpResponse($response->getStatusCode());

        if (false == $status) {
            return (string) $response->getBody();
        } else {
            throw new Exception($status);
        }
    }

    /**
     * Query the api using post.
     *
     * @method postQuery
     *
     * @param string $url    The url to query
     * @param string $params Params
     *
     * @return string A json response object
     * 
     * @throws \Exception
     */
    public function postQuery($url, $params)
    {
        $client = new Client(['base_uri' => $this->configuration->getUrl()]);
        $response = $client->request('POST', $url, [
            'debug' => $this->configuration->getDebug(),
            'auth' => [
                $this->configuration->getUsername(),
                $this->configuration->getPassword(),
            ],
            'headers' => [
                'content-type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'http_errors' => false,
            'json' => $params,
            ]
        );

        $status = $this->checkHttpResponse($response->getStatusCode());

        if (false == $status) {
            return $response->getHeader('Location')[0];
        } else {
            throw new Exception($status.' URL: '.$url.PHP_EOL.(string) $response->getBody());
        }
    }

    /**
     * Check the returned HTTP status code.
     *
     * @param int $code
     *
     * @return mixed
     */
    private function checkHttpResponse($code)
    {
        switch ($code) {
            case 500:
                return 'Internal Server Error';
            case 502:
                return 'Invalid Gateway: Check Kenshoo Server Id';
            case 401:
                return 'Unauthorized - Invlaid Username or Password';
            case 403:
                return 'Forbidden - User does not have access to the report';
            case 404:
                return 'Report not found';
            case 400:
                return 'Date range is not valid';
            case 409:
                return 'The report is already running';
            default:
                return false;
        }
    }
}
