<?php

namespace Pantry;

use \Pantry\Exceptions\BasketNotFoundException;
use \Pantry\Exceptions\RequestException;

class Client {
    private string $pantryID;
    private bool $async;
    private \GuzzleHttp\Client $HttpClient;
    private ?object $data;
    const PANTRY_URL = 'https://getpantry.cloud/apiv1/pantry/';

    /**
     * Client constructor.
     * 
     * @param string $pantryID   The Pantry ID used to access your dashboard.
     * @param bool $async        Wheter the request should be asyncronous or not.
     */
    public function __construct(string $pantryID, bool $async = false) {
        $this->pantryID = $pantryID;
        $this->async = $async;
        $this->HttpClient = new \GuzzleHttp\Client();
        $this->data = null;
    }

    /**
     * Performs a request to the getpantry API
     * 
     * @param string $method        The HTTP method.
     * @param string $path          The path to the resource
     * @param array|null $body      The body of the request.
     * @param array|null $headers   Additional headers.
     * 
     * @return ?object              The bod of the response. 
     */
    protected function request(
        string $method = "GET",
        string $path = "",
        ?array $body = null,
        ?array $headers = ['Content-Type' => 'application/json']
    ): object {
        
        $request = new \GuzzleHttp\Psr7\Request(
            $method,
            self::PANTRY_URL . $this->pantryID . $path,
            $headers,
            isset($body) ? json_encode(
                array_filter($body, function($value)
                {
                    return $value !== null;
                })
            ) : null
        );

        $res = $this->async ? $this->HttpClient->sendAsync($request)->wait() : $this->HttpClient->send($request);

        return json_decode($res->getBody()->getContents());
    }

    /**
     * Gets information about the pantry.
     * 
     * @return object The pantry data.
     */
    public function getData(): object {
        if($this->data === null){
            $this->data = $this->request();
        }
        return $this->data;
    }

    /**
     * Returns the pantry data as json.
     * 
     * @return string The data.
     */
    public function __toString(): string {
        return json_encode($this->getData());
    }

    /**
     * Returns the pantry data as an object.
     * 
     * @return object The data.
     */
    public function __invoke(): object {
        return $this->getData();
    }

    /**
     * Returns the pantry data as an object.
     * 
     * @return object The data.
     */
    public function get(): object {
        return $this->__invoke();
    }

    /**
     * Returns the pantry data as an array.
     * 
     * @return array The data.
     */
    public function __debugInfo(): array {
        return (array)$this->getData();
    }

    /**
     * Updates the pantry with the given data.
     * 
     * @param array $data        The data to update.
     * 
     * @throws RequestException   On error.
     */
    public function update(array $data): void {
        try {

            $this->request("PUT", "", $data);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new RequestException("An error occurred while updating the pantry.", 0, $e);
        }
    }

    /**
     * Gets information about a basket.
     * 
     * @param string $name               The name of the basket.
     * 
     * @return Basket                    The basket object.
     * 
     * @throws BasketNotFoundException   If the basket doesn't exists.
     * @throws RequestException          On general error.
     */
    public function getBasket(string $name): Basket {
        try {

            $response = $this->request("GET", "/basket/$name");
            return new Basket($this->pantryID, $name, $response);

        } catch (\GuzzleHttp\Exception\ClientException $e) {

            if ($e->getResponse()->getStatusCode() === 400) {
                throw new BasketNotFoundException("Basket '$name' not found.", 400, $e);
            }

            throw new RequestException("An error occurred while fetching the basket.", 0, $e);
        }
    }

    /**
     * Creates a new basket.
     * 
     * @param string $name               The name of the basket.
     * @param array $contents            The basket contents.
     * 
     * @return Basket                    The basket object.
     * 
     * @throws RequestException          On general error.
     */
    public function createBasket(string $name, array $contents): Basket {
        try {

            $this->request("POST", "/basket/$name", $contents);
            return new Basket($this->pantryID, $name, (object)$contents);

        } catch (\GuzzleHttp\Exception\ClientException) {
            throw new RequestException("An error occurred while creating the basket.", 0, $e);
        }
    }
}