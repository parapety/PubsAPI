<?php

namespace AppBundle\Controller;

use AppBundle\Lib\Helper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Api controller.
 *
 * @Route("/api")
 *
 */
class ApiController extends Controller
{
    /**
     * @Route("/pubs")
     */
    public function barsAction(Request $request)
    {
        try {
            $latlng = $request->query->get('location');
            $res = $this->get('wr_api_request_handler')->getBars(Helper::locationToArray($latlng));
            return new JsonResponse(['data' => $res['data'], 'html_attributions' => $res['html_attributions'], 'status' => 'OK', 'count' => count($res)]);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage(), 'status' => 'ERROR',]);
        }
    }

    /**
     * @Route("/pub/{placeId}/detail")
     */
    public function detailsAction(Request $request, $placeId)
    {
        try {
            $res = $this->get('wr_api_request_handler')->getDetails($placeId);
            return new JsonResponse(['data' => $res['data'], 'status' => 'OK']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage(), 'status' => 'ERROR',]);
        }
    }

    /**
     * @Route("/search")
     */
    public function searchAction(Request $request)
    {
        try {
            $address = $request->query->get('address');
            $matchedLocations = $this->get('wr_api_request_handler')->getLocation($address);
            $result = $first = [];
            if (!empty($matchedLocations['data'])) {
                $first = array_shift($matchedLocations['data']);
                $result = $this->get('wr_api_request_handler')->getBars(Helper::locationToArray($first['latlng']));
            }
            return new JsonResponse([
                'data' => !empty($result['data']) ? $result['data'] : [],
                'html_attributions' => !empty($result['html_attributions']) ? $result['html_attributions'] : [],
                'matched_location' => $first,
                'other_matched_location' => $matchedLocations['data'],
                'status' => 'OK']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage(), 'status' => 'ERROR',]);
        }
    }
}
