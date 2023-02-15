<?php

namespace App\Controller;

use App\DTO\ClientEditPayload;
use App\Service\ClientService;
use App\Type\ClientType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ClientController extends AbstractController
{
    public function list(
        Request $request,
        ClientService $clientService,
    ): Response {
        $page = $request->get('page', 1);

        if (!filter_var($page, FILTER_VALIDATE_INT)) {
            throw new BadRequestHttpException('Invalid page number');
        }

        return $this->render('client/list.html.twig', [
            'pageData' => $clientService->getClientsPage($page),
        ]);
    }

    public function create(
        Request $request,
        ClientService $clientService,
    ): Response {
        $clientEditPayload = new ClientEditPayload();
        $form = $this->createForm(ClientType::class, $clientEditPayload);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clientService->create($clientEditPayload);
        }

        return $this->render('client/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
