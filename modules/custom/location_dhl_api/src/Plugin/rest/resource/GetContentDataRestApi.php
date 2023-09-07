<?php
/**
 * API Call. 
 */
namespace Drupal\location_dhl_api\Plugin\rest\resource;

// use Drupal\Core\Entity\EntityInterface;
// use Drupal\Core\Url;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\Component\Serialization\Json;
// use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
// use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Drupal\node\Entity\Node;
// use Drupal\paragraphs\Entity\Paragraph;
use Drupal\rest\ModifiedResourceResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Provides REST API for Content Based on URL params.
 *
 * @RestResource(
 *   id = "get_content_rest_resource",
 *   label = @Translation("Content API"),
 *   uri_paths = {
 *     "canonical" = "/api/content"
 *   }
 * )
 */
class GetContentDataRestApi extends ResourceBase {

/**
 * Responds to entity GET requests.
 *
 * @return \Drupal\rest\ResourceResponse
 *   Returning rest resource.
 */
  public function get() {

    if (\Drupal::request()->query->has('url') ) {
      
      $url = \Drupal::request()->query->get('url');

      if (!empty($url)) {
        
        $query = \Drupal::entityQuery('node')
          ->condition('field_unique_url', $url);
        
        $nodes = $query->execute();
        
        $node_id = array_values($nodes);

        
        if (!empty($node_id)) {
        
          $data = Node::load($node_id[0]);
          return new ModifiedResourceResponse($data);

      		}
      	}
 	}

    $curl = curl_init();

    //calling API by passing static data
    $opt = "https://api-sandbox.dhl.com/location-finder/v1/find-by-address?countryCode=DE&addressLocality=Bonn&postalCode=53113&hideClosedLocations=true";
    curl_setopt_array($curl, [
        CURLOPT_URL => $opt,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "DHL-API-Key: demo-key",
            "cache-control: no-cache",
            "content-type: application/json"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        //echo $response;
    }
        return new ModifiedResourceResponse($response);
    }

    public function post(Request $request) {
	    $query = \Drupal::request()->query;
        $result = \Drupal::database()->select('address', 'n')
            ->fields('n', array('id', 'country_code', 'city', 'zip'))
            ->execute()->fetchAllAssoc('id');
            print_r($result);
            exit;
	    $response = [];
	    $params = Json::decode($request->getContent());
	    extract($params);
	    if($name!='' && $email!=''){
	      $response["ServerMsg"]=[ 
	          "your_name" => $name,
	          "your_email" => $email,
	          "Msg" => "SUCCESS",
	          "DisplayMsg" => "Rest message for post"
	      ];
	    }
	    else{
	      $response["ServerMsg"]=[ 
	          "Msg" => "Failure",
	          "DisplayMsg" => "Rest message for post",
	          "DisplayMsg1" => "Name & Email is required"
	      ];
	    }
	    return new ResourceResponse($response);
	}

}

?>