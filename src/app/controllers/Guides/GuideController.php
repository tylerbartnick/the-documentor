<?php

namespace App\Controllers\Guides;

use App\Models\Guide;
use App\Controllers\BaseController;

use Slim\Container;
use Slim\Views\Twig;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class GuideController extends BaseController
{
    public function __construct(
        Container $container
    ) {
        parent::__construct($container);
    }

    public function getAllGuides(Request $request, Response $response)
    {
        $guides = Guide::all();
        return $this->container->view->render($response, 'templates/guides/getAllGuides.twig', [
            'guides' => $guides
        ]);
    }

    public function getCreateGuide(Request $request, Response $response)
    {
        return $this->container->view->render($response, 'templates/guides/createEditGuide.twig');
    }

    public function postCreateGuide(Request $request, Response $response)
    {
        $params = $request->getParams();

        if ($params['isPublished']) {
            $isPublished = 1;
        } else {
            $isPublished = 0;
        }

        $guide = Guide::create([
            'title' => $params['title'],
            'tags' => $params['tags'],
            'content' => $params['content'],
            'isPublished' => $isPublished,
            'user_id' => $_SESSION['USER_ID']
        ]);

        return $response->withRedirect($this->container->router->pathFor('guides.getAllGuides'));
    }

    public function getEditGuide(Request $request, Response $response, array $args)
    {
        $guide = Guide::where('id', '=', $args['id'])->first();

        if (!$guide) {
            $this->container->flash->addMessage('error', 'No guide exists with that id.');
            return $response->withRedirect($this->container->router->pathFor('guides.getAllGuides'));
        }
        $_SESSION['prevData'] = [
            'id' => $guide->id,
            'title' => $guide->title,
            'tags' => $guide->tags,
            'content' => $guide->content
        ];
        
        return $this->container->view->render($response, 'templates/guides/createEditGuide.twig');
    }

    public function postEditGuide(Request $request, Response $response)
    {
    }
}
