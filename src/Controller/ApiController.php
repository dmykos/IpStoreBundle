<?php


namespace Dmykos\IpStoreBundle\Controller;



use Dmykos\IpStoreBundle\Entity\IpModel;
use Dmykos\IpStoreBundle\StoreDriverInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ApiController
{
    /**
     * @var StoreDriverInterface
     */
    private $storeDriver;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * ApiController constructor.
     *
     * @param StoreDriverInterface $storeDriver
     */
    public function __construct(StoreDriverInterface $storeDriver, ValidatorInterface $validator) {

        $this->storeDriver = $storeDriver;
        $this->validator = $validator;
    }

    /**
     * @param string $ip
     *
     * @return JsonResponse
     */
    public function add($ip){
        $ipModel = new IpModel();
        $ipModel->setIp($ip);

        $errors =  $this->validator->validate($ipModel);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new JsonResponse(['error'=>$errorsString], 422);
        }

        $count = $this->storeDriver->add($ipModel);


        return new JsonResponse(["count" => $count]);
    }

    /**
     * @param string $ip
     *
     * @return JsonResponse
     */
    public function query($ip){
        $ipModel = new IpModel();
        $ipModel->setIp($ip);

        $errors =  $this->validator->validate($ipModel);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new JsonResponse($errorsString);
        }

        $count = $this->storeDriver->query($ipModel);

        return new JsonResponse(["count" => $count]);
    }

}