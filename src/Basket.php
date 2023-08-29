<?php

namespace Pantry;

class Basket extends Client {
    private object $data;
    private string $name;

    /**
     * Basket constructor.
     * 
     * @param string $pantryID   The Pantry ID used to access your dashboard.
     * @param string $name       The basket name.
     * @param object $data       The contents of the basket.
     */
    public function __construct(string $pantryID, string $name, object $data) {
        parent::__construct($pantryID);
        $this->data = $data;
        $this->name = $name;
    }

    /**
     * Returns the basket data as json.
     * 
     * @return string The data.
     */
    public function __toString(): string {
        return json_encode($this->data);
    }

    /**
     * Returns the basket data as an object.
     * 
     * @return object The data.
     */
    public function __invoke(): object {
        return $this->data;
    }

    /**
     * Returns the basket data as an object.
     * 
     * @return object The data.
     */
    public function get(): object {
        return $this->__invoke();
    }

    /**
     * Returns the basket data as an array.
     * 
     * @return array The data.
     */
    public function __debugInfo(): array {
        return (array)$this->data;
    }

    /**
     * Updates the basket with the given data.
     * 
     * @param array $data        The data to update.
     * 
     * @throws RequestException   On error.
     */
    public function update(array $data): void {
        try {

            $this->request("PUT", "/basket/$this->name", $data);
            $this->data = (object)$data;

        } catch (\GuzzleHttp\Exception\ClientException) {
            throw new RequestException("An error occurred while updating the basket.", 0, $e);
        }
    }

    /**
     * Deletes the basket.
     * 
     * @throws RequestException   On error.
     */
    public function delete(): void {
        try {
            
            $this->request("DELETE", "/basket/$this->name");

        } catch (\GuzzleHttp\Exception\ClientException) {
            throw new RequestException("An error occurred while deleting the basket.", 0, $e);
        }
    }
}