<?php
namespace App\Services;

use App\Entity\Constants\Constant;
use App\Entity\Partner;
use App\Models\Api\ApiResponse;
use App\Services\Rest\RestRequest;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Unirest\Request;

/**
 * Created by PhpStorm.
 * User: rnasolo@gmail.com
 * Date: 24/10/2017
 * Time: 10:39
 */
class NeobeTerApiService
{
    const SERVICE_NAME = 'api.neobe_ter.create_account';

    const API_CREATE_ACCOUNT = '/api/create-account';
    const API_GET_TOKEN = '/get-token';


    const CODE_SUCCESS = 200;
    const CODE_LOGIN_INCORECT = 151;
    const CODE_PWD_INCORECT = 153;
    const CODE_ERROR = 500;


    private $container;
    private $url;
    private $token;
    private $headers;


    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->url = $container->getParameter('api_url');
        $this->headers = [
            'Content-Type'  => 'application/json',
            'Accept'        => 'text/plain',
            'user-agent'    => 'neobe-ter'
        ];
    }

    public function authorization($username, $password)
    {
        $body = [
            'username' => $username,
            'password' => $password
            ];
        $request = new RestRequest();
        if ($this->container->getParameter('neobe_api_envtest')) {
            $request::verifyPeer(false);
            $request::verifyHost(false);
        }
        $response = $request::post($this->url.self::API_GET_TOKEN, $this->headers, json_encode($body));

        return $response->body;
    }


    /**
     * Call api to create neobe-ter account client
     *
     * @param array $data array of input data from the uploaded csv file
     * @return ApiResponse
     */
    public function createClient(Partner $partner = null, $simulation = false)
    {

        $return = new ApiResponse();
        if (!is_null($partner)) {
            $bodyData = [
                'society' => $partner->getName(),
                'address_1' => $partner->getAddress1()? $partner->getAddress1() : 'adr1' ,
                'address_2' => $partner->getAddress2()? $partner->getAddress2() : 'adr2' ,
                'zip_code' => $partner->getZipCode(),
                'city' => $partner->getCity(),
                'country' => $partner->getCountry(),
                'source' => $partner->getParent()->getName(),
                'category' => $partner->getCategory() ? $partner->getCategory() : Constant::DEFAULT_CATEGORY,
                'civility' => strtoupper($partner->getCivility()->getLabel()),
                'lastname' => $partner->getLastname(),
                'firstname' => $partner->getFirstname(),
                'email' => $partner->getMail(),
                'phone' => $partner->getPhone() ? $partner->getPhone() : '0',
                'mobile' => $partner->getMobile() ? $partner->getMobile() : '0',
                'nb_licence' => $partner->getNbLicense(),
                'volume_size' => $partner->getVolumeSize(),
                'id_parrain' => $partner->getParent()->getNeobeAccountId(),
                'simulation' => $simulation
            ];

            try {
                $request = new RestRequest();
                if ($this->container->getParameter('neobe_api_envtest')) {
                    $request::verifyPeer(false);
                    $request::verifyHost(false);
                }
                $pwdEncoder = new PasswordEncoder();
                $authData = $this->container->get(self::SERVICE_NAME)->authorization(
                    $partner->getParent()->getMail(),
                    $pwdEncoder->decode($partner->getParent()->getPassword())
                );
                if (isset($authData->code) && $authData->code == self::CODE_SUCCESS) {
                    $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
                    $this->headers['Authorization'] = 'Bearer ' . $authData->data->token;

                    $response = $request::post(
                        $this->url  . self::API_CREATE_ACCOUNT,
                        $this->headers,
                        json_encode($bodyData)
                    );

                    if ($response->body->code != self::CODE_SUCCESS) {
                        $return->setCode(self::CODE_ERROR)
                            ->setMessage($response->body->message);
                    }
                    $return->setData($response->body->data);
                }else{
                    $return->setCode(self::CODE_ERROR)
                        ->setMessage("API access denied.");
                    return $return;
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
