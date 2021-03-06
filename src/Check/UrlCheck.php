<?php
namespace BretRZaun\StatusPage\Check;

use BretRZaun\StatusPage\Result;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class UrlCheck extends AbstractCheck
{

    protected $url;

    /**
     * @var Client
     */
    protected $client;

    /**
     * UrlCheck constructor.
     *
     * @param string $label Label
     * @param string $url URL to be tested
     */
    public function __construct(string $label, string $url)
    {
        parent::__construct($label);
        $this->url = $url;
        $this->setHttpClient(new Client());
    }

    /**
     * set http client
     *
     * @param Client $client
     */
    public function setHttpClient(Client $client): void
    {
        $this->client = $client;
    }

    /**
     * Check URL
     *
     * @return Result
     * @throws GuzzleException
     */
    public function checkStatus(): Result
    {
        $result = new Result($this->label);
        try {
            $res = $this->client->request('GET', $this->url);
            if ($res->getStatusCode() !== 200) {
                $result->setError('HTTP status code is '.$res->getStatusCode());
            }
        } catch (Exception $e) {
            $result->setError('URL failed: '.$this->url);
        }
        return $result;
    }
}
