<?php

namespace App\Controller;

use App\Model\CustomerModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
{
    #[Route(path: '/customers', name: 'app_customers_index')]
    public function index(CustomerModel $customerModel): Response
    {
        $customers = $customerModel->findAll();

        return $this->render('customers/index.html.twig', [
            'customers' => $customers
        ]);
    }

    #[Route(path: '/customers/{id}', name: 'app_customers_show')]
    public function show(int $id, CustomerModel $customerModel): Response
    {
        $customer = $customerModel->find($id);

        // Générer une erreur 404 si le client n'existe pas
        if ($customer === false) {
            throw $this->createNotFoundException("Le client $id n'existe pas");
        }

        return $this->render('customers/show.html.twig', [
            'customer' => $customer
        ]);
    }
}