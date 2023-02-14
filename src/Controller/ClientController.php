<?php

namespace App\Controller;

use App\DTO\ClientUpdateRequest;
use App\Service\ClientService;
use App\Type\ClientType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientController extends AbstractController
{
    public function create(
        Request $request,
        ClientService $clientService,
    ): Response {
        $clientUpdateRequest = new ClientUpdateRequest();
        $form = $this->createForm(ClientType::class, $clientUpdateRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientService->create($clientUpdateRequest);
        }

        return $this->render('client/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
