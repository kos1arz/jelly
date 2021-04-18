<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Client;
use App\Entity\ClientType;

class ClientListController extends AbstractController
{
    /**
     * @Route("/", name="client_list")
     */
    public function index(): Response
    {
        try {
            $cilients = $this->getDoctrine()->getRepository(Client::class)->findAll();

            return $this->render('client_list/index.html.twig', [
                'controller_name' => 'ClientListController',
                'cilients' => $cilients
            ]);
        } catch (Exception $e) {
            exit('Error: '.$e);
        }
    }

    /**
     * @Route("/creat", name="client_list_create",  methods={"GET"})
     */
    public function creat(): Response {

        try {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

            $clientType = $this->getDoctrine()
            ->getRepository(ClientType::class)
            ->findAll();

        } catch (Exception $e) {
            exit('Error: '.$e);
        }

        return $this->render('client_list/form.html.twig', [
            'clientType' => $clientType
        ]);
    }

    /**
     * @Route("/creat", name="client_list_create_post",  methods={"POST"})
     */
    public function creatSave(Request $request): Response {

        try {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            $entityManager = $this->getDoctrine()->getManager();
            $clientType = $entityManager->getRepository(ClientType::class)->find($request->get('clientType'));

            $client = new Client();
            $client->setName($request->get('name'));
            $client->setSurname($request->get('surname'));
            $client->setPhone($request->get('phone'));
            $client->setEmail($request->get('email'));
            $client->setClientType($clientType);

            $entityManager->persist($client);
            $entityManager->flush();

        } catch (Exception $e) {
            exit('Error: '.$e);
        }
        return $this->redirectToRoute('client_list');
    }

    /**
     * @Route("/edit/{id}", name="client_list_edit_get",  methods={"GET"})
     */
    public function edit(int $id): Response {

        try {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

            $client = $this->getDoctrine()
            ->getRepository(Client::class)
            ->find($id);

            $clientType = $this->getDoctrine()
            ->getRepository(ClientType::class)
            ->findAll();

            if (!$client) {
                throw $this->createNotFoundException(
                    'No client found for id '.$id
                );
            }

            return $this->render('client_list/form.html.twig', [
                'client' => $client,
                'clientType' => $clientType
            ]);

        } catch (Exception $e) {
            exit('Error: '.$e);
        }
    }

    /**
     * @Route("/edit/{id}", name="client_list_edit_post",  methods={"POST"})
     */
    public function editSave(Request $request, int $id): Response
    {
        try {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

            $entityManager = $this->getDoctrine()->getManager();
            $client = $entityManager->getRepository(Client::class)->find($id);

            $clientType = $entityManager->getRepository(ClientType::class)->find($request->get('clientType'));

            if (!$client) {
                throw $this->createNotFoundException(
                    'No product found for id '.$id
                );
            }

            $client->setName($request->get('name'));
            $client->setSurname($request->get('surname'));
            $client->setPhone($request->get('phone'));
            $client->setEmail($request->get('email'));
            $client->setClientType($clientType);

            $entityManager->flush();

        } catch (Exception $e) {
            exit('Error: '.$e);
        }
        return $this->redirectToRoute('client_list');
    }

    /**
     * @Route("/delete/{id}", name="client_delete_show",  methods={"GET"})
     */
    public function delete(int $id): Response
    {
        try {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

            $entityManager = $this->getDoctrine()->getManager();
            $client = $entityManager->getRepository(Client::class)->find($id);

        } catch (Exception $e) {
            exit('Error: '.$e);
        }
        return $this->render('client_list/delete.html.twig', [
            'client' => $client
        ]);
    }

    /**
     * @Route("/delete/{id}", name="client_delete",  methods={"POST"})
     */
    public function deleteSave(int $id): Response
    {
        try {
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

            $entityManager = $this->getDoctrine()->getManager();
            $client = $entityManager->getRepository(Client::class)->find($id);

            $entityManager->remove($client);
            $entityManager->flush();
        } catch (Exception $e) {
            exit('Error: '.$e);
        }
        return $this->redirectToRoute('client_list');
    }
}
