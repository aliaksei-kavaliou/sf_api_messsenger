<?php declare(strict_types = 1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TransactionController
 * @package App\Controller
 * @Route("/api/v1", name="api_v1_")
 */
class TransactionController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/info")
     */
    public function getInfo()
    {
        return $this->handleView($this->view(["foo" => "bar"]));
    }
}
