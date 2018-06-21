<?php
namespace App\Services;

use App\Models\Api\ApiResponse;
use App\Services\Rest\RestRequest;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Unirest\Request;

/**
 * Created by PhpStorm.
 * User: rnasolo@gmail.com
 * Date: 24/10/2017
 * Time: 10:39
 */
class NeobeApiService
{
    const SERVICE_NAME = 'api.neobe.create_account';

    const API_CREATE_SAFEDATA_ACCOUNT = '/app_dev.php/create';

    const CODE_SUCCESS = 200;
    const CODE_LOGIN_INCORECT = 151;
    const CODE_PWD_INCORECT = 153;
    const CODE_ERROR = 500;


    private $container;
    private $username;
    private $pwd;
    private $url;
    private $headers;


    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->username = $container->getParameter('prospect_api_dc_username');
        $this->pwd = $container->getParameter('prospect_api_dc_pwd');
        $this->url = $container->getParameter('prospect_api_dc_url');
        $this->headers = [
            'Content-Type'  => 'application/json',
            'Accept'        => 'text/plain',
            'user-agent'    => 'dropsale'
        ];
    }

    public function authorization()
    {
        $body = [
            'username' => $this->username,
            'password' => $this->pwd
            ];
        $request = new RestRequest();
        $request::verifyPeer(false);
        $request::verifyHost(false);
        if ($this->container->getParameter('prospect_api_dc_envtest')) {
            $request::verifyPeer(false);
            $request::verifyHost(false);
        }
        $response = $request::post($this->url.'/login', $this->headers, json_encode($body));
        return $response->body;
    }


    /**
     * Call api to create Neobe account
     *
     * @param array $data array of input data from the uploaded csv file
     * @return ApiResponse
     */
    public function createNeobeAccount($data = [])
    {
        $return = new ApiResponse();
        if (sizeof($data) >0) {
            $bodyData = ['userLists'=>[]];
            foreach ($data as $key => $value) {
                $bodyData['userLists'][]= implode(';', $value);
            }
            try {
                $request = new RestRequest();
                if ($this->container->getParameter('prospect_api_dc_envtest')) {
                    $request::verifyPeer(false);
                    $request::verifyHost(false);
                }
                $authData = $this->container->get(ProspectApiService::SERVICE_NAME)->authorization();
                if ($authData->code == self::CODE_SUCCESS) {
                    $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
                    $this->headers['Authorization'] = 'Bearer ' . $authData->id_token;
                    $body = Request\Body::Form($bodyData);
                    $response = $request::post(
                        $this->url  . self::API_CREATE_SAFEDATA_ACCOUNT,
                        $this->headers,
                        $body
                    );
                    if (!$response->code == self::CODE_SUCCESS) {
                        $return->setCode(self::CODE_ERROR)
                            ->setMessage($response->body->error->message);
                    }
                }
            } catch (\Exception $e) {
                $return->setCode(self::CODE_ERROR)
                    ->setMessage($e->getMessage());
                return $return;
            }
        } else {
            $return->setCode(self::CODE_SUCCESS);
            return $return;
        }
        return $return;
    }
}
