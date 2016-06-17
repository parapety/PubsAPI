<?php

namespace AppBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createFormBuilder(null, ['method' => 'get', 'attr' => ['novalidate' => 'novalidate'], 'csrf_protection' => false,])
            ->add('search', TextType::class, [
                'attr' => [
                    'placeholder' => 'Type address...'
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])->getForm();

        $form->handleRequest($request);
        $searchResults = null;
        if ($form->isSubmitted() && $form->isValid()) {
            $searchResults = $this->makeRequest('/search?address=' . $form->get('search')->getData());
        }

        $pubs = $this->makeRequest('/pubs');

        return $this->render('default/index.html.twig', [
            'form' => $form->createView(),
            'pubs' => $pubs,
            'searchResults' => $searchResults,
            'searchQuery' => $form->get('search')->getData()
        ]);
    }

    /**
     * @Route("/pub/{pubId}", name="place")
     */
    public function placeAction(Request $request, $pubId)
    {
        $pub = $this->makeRequest('/pub/' . $pubId . '/detail');
        $viewData = ['pub' => $pub];
        if ($request->query->get('form')) {
            $viewData['searchQuery'] = $request->query->get('form')['search'];
        }
        return $this->render('default/place.html.twig', $viewData);
    }

    /**
     * @Route("/pubs/location", name="location")
     */
    public function locationAction(Request $request)
    {
        $pubs = $this->makeRequest('/pubs?location=' . $request->query->get('location'));
        $viewData = ['pubs' => $pubs, 'location' => $request->query->get('location')];

        return $this->render('default/location.html.twig', $viewData);
    }

    private function makeRequest($action, $options = [])
    {
        $response = $this->get('guzzle_http_client')->request('GET', 'http://localhost/api' . $action, $options);
        if ($response->getStatusCode() != 200) {
            return ['status' => 'ERROR'];
        }
        $result = json_decode($response->getBody());
        if (!$result) {
            return ['status' => 'ERROR'];
        }
        return $result;
    }
}
