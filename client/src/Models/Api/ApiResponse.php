<?php

namespace App\Models\Api;


/**
 * ApiResponse
 */
class ApiResponse
{
    public $code;
    public $message;
    public $data;

    /**
     * @param int $code
     * @param string $message
     */
    public function __construct($code = 200, $message = 'success')
    {
        $this->code = $code;
        $this->message = $message;
    }

    /**
     * @param int $code
     * @return ApiResponse
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param mixed $message
     * @return ApiResponse
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return ApiResponse
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }
}
