<?php
namespace App\Services;

use App\Entity\Constants\Constant;
use App\Entity\Partner;
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

    const API_CREATE_NEOBE_ACCOUNT = '/app_dev.php/create';
    const API_GETINOF_NEOBE_ACCOUNT = '/app_dev.php/activation';

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
        $this->username = $container->getParameter('neobe_api_username');
        $this->pwd = $container->getParameter('neobe_api_pwd');
        $this->url = $container->getParameter('neobe_api_url');
        $this->headers = [
            'Content-Type'  => 'application/json',
            'Accept'        => 'text/plain',
            'user-agent'    => 'neobe-ter'
        ];
    }

    public function authorization()
    {
        $body = [
            'username' => $this->username,
            'password' => $this->pwd
            ];
        $request = new RestRequest();
        if ($this->container->getParameter('neobe_api_envtest')) {
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
    public function createNeobeAccount(Partner $partner = null, $nbLicencesToCreate, $sizeGo)
    {
        $return = new ApiResponse();
        if (!is_null($partner)) {
            $bodyData = [
                'societe' => $partner->getName(),
                'adresse_1' => $partner->getAddress1()? $partner->getAddress1() : 'adr1' ,
                'adresse_2' => $partner->getAddress2()? $partner->getAddress2() : 'adr2' ,
                'code_postal' => $partner->getZipCode(),
                'ville' => $partner->getCity(),
                'pays' => $partner->getCountry(),
                'source' => $partner->getParent()->getName(),
                'id_categorie' => $partner->getCategory() ? $partner->getCategory() : Constant::DEFAULT_CATEGORY,
                'civilite' => strtoupper($partner->getCivility()->getLabel()),
                'nom' => $partner->getLastname(),
                'prenom' => $partner->getFirstname(),
                'email' => $partner->getMail(),
                'telephone' => $partner->getPhone() ? $partner->getPhone() : '0',
                'mobile' => $partner->getMobile() ? $partner->getMobile() : '0',
                'nombre_de_licences' => $partner->getNbLicense(),
                'volume_global_de_sauvegarde_Go' => $partner->getVolumeSize(),
                'nb_licences_a_creer' => $nbLicencesToCreate,
                'volume_par_licence_Go' => $sizeGo,
                'id_parrain' => $partner->getParent()->getNeobeAccountId()
            ];

            try {
                $request = new RestRequest();
                if ($this->container->getParameter('neobe_api_envtest')) {
                    $request::verifyPeer(false);
                    $request::verifyHost(false);
                }
                //die('dfsdfsdfsdfsdfsdfsdf');
                $authData = $this->container->get(self::SERVICE_NAME)->authorization();
                dump("api auth result");
                dump($authData);
                if ($authData->code == self::CODE_SUCCESS) {
                    $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
                    $this->headers['Authorization'] = 'Bearer ' . $authData->id_token;
                    $body = Request\Body::Form($bodyData);
                    //var_dump($body); die;
                    $response = $request::post(
                        $this->url  . self::API_CREATE_NEOBE_ACCOUNT,
                        $this->headers,
                        $body
                    );
                    if (!$response->code == self::CODE_SUCCESS) {
                        $return->setCode(self::CODE_ERROR)
                            ->setMessage($response->body->error->message);
                    }

                   dump("api result");
                   dump($response);
                    $return->setData($response->body->result);
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


    /**
     * Call api to create Neobe account
     *
     * @param array $data array of input data from the uploaded csv file
     * @return ApiResponse
     */
    public function getInfosAccount($id)
    {
        $return = new ApiResponse();
        $bodyDatxa["id_client"] = $id;
            try {
                $request = new RestRequest();
                if ($this->container->getParameter('neobe_api_envtest')) {
                    $request::verifyPeer(false);
                    $request::verifyHost(false);
                }
                $authData = $this->authorization();
                if ($authData->code == self::CODE_SUCCESS) {
                    $this->headers['Content-Type'] = 'application/x-www-form-urlencoded';
                    $this->headers['Authorization'] = 'Bearer ' . $authData->id_token;
                    $body = Request\Body::Form($bodyDatxa);
                    $response = $request::post(
                        $this->url  . self::API_GETINOF_NEOBE_ACCOUNT,
                        $this->headers,
                        $body
                    );
                    if ($response->code == self::CODE_SUCCESS) {
                        $return->setCode(self::CODE_SUCCESS)
                        ->setData($response->body->result);
                    }
                }
            } catch (\Exception $e) {
                $return->setCode(self::CODE_ERROR)
                    ->setMessage($e->getMessage());
                return $return;
            }
        return $return;
    }
}
