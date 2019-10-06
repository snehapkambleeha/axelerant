<?php

namespace Drupal\site_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\Component\Serialization\Json;
use Symfony\Component\Serializer\SerializerInterFace;
use Symfony\Component\HttpFoundation\Response;

/**
 * Json Output of Nodes along woith Api key stored.
 */
class NodeJsonController extends ControllerBase {

	public function NodeJsonOutput($api_key,$nid) {
		//check if site api key value is saved
		if($api_key == \Drupal::config('system.site')->get('siteapikey') && !empty($nid)) {
			$serializer = \Drupal::service('serializer');
			$node = Node::load($nid);
			if(isset($node) && $node->bundle() == "page") {
				//serialise the node details into json
				$json_data = $serializer->serialize($node, 'json', ['plugin_id' => 'entity']);

				return new Response($json_data);
			}else {
				//Show error as Access Denied
				throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();	
			}
		}else {
			//Show error as Access Denied
			throw new \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException();	
		}
	}
}