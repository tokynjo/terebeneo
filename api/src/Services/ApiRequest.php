<?php
namespace App\Services;

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

    /**
     * get parameter
     * @param $property
     * @param null $default
     * @return mixed|null
     */
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

    /**
     * @return mixed|null|resource|string
     */
    public function getDataContent(){
        return $this->dataContent;
    }
}