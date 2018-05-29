<?php

namespace App\Services;

use Symfony\Component\Cache\Exception\InvalidArgumentException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiRequest extends Request
{
    protected $dataContent = null;
    public function __construct()
    {
        $this->dataContent = \GuzzleHttp\json_decode($this->getContent());
        try {
            $this->dataContent = \GuzzleHttp\json_decode($this->getContent());
        } catch (\Exception $e) {
            throw new \Exception(
                'json data error',
                Response::HTTP_BAD_REQUEST,
                NULL
            );
        }

    }

    public function getBodyRawParam($property,$default = null)
    {
        if(
            $this->dataContent
            && property_exists($this->dataContent, $property)
            && !empty($this->dataContent->$property)) {

                return $this->dataContent->$property;
        } elseif (
            is_array($this->dataContent)
            && isset($this->dataContent[$property])
            && !empty($this->dataContent[$property])) {

            return $this->dataContent[$property];
        } else {
            return $default;
        }
    }

    public function getContente(){
        return $this->dataContent;
    }


}
