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
        return $this->container->view->render($response, 'templates/guides/showGuides.twig', [
            'guides' => $guides
        ]);
    }

    public function getAllAvailableGuides(Request $request, Response $response)
    {
        $guides = Guide::where([
            ['isPublished', '=', 1],
            ['isApproved', '=', 1],
            ['isDeleted', '=', 0],
        ])->get();

        return $this->container->view->render($response, 'templates/guides/showGuides.twig', [
            'guides' => $guides
        ]);
    }

    public function getCreateGuide(Request $request, Response $response)
    {
        return $this->container->view->render($response, 'templates/guides/createGuide.twig');
    }

    public function postCreateGuide(Request $request, Response $response)
    {
        $params = $request->getParams();

        $guide = Guide::create([
            'title'         => $params['title'],
            'tags'          => $params['tags'],
            'content'       => $params['content'],
            'isPublished'   => (isset($params['isPublished'])) ? 1 : 0,
            'user_id'       => $_SESSION['USER_ID']
        ]);

        return $response->withRedirect($this->container->router->pathFor('guides.getAllAvailableGuides'));
    }

    public function getEditGuide(Request $request, Response $response, array $args)
    {
        $guide = Guide::where('id', '=', $args['id'])->first();

        if (!$guide) {
            $this->container->flash->addMessage('error', 'No guide exists with that id.');
            return $response->withRedirect($this->container->router->pathFor('guides.getAllAvailableGuides'));
        }

        if ($guide['user_id'] !== $_SESSION['USER_ID']) {
            $this->container->flash->addMessage('error', 'You do not have authorization to do that.');
            return $response->withRedirect($this->container->router->pathFor('guides.getAllAvailableGuides'));
        }
        
        $_SESSION['prevData'] = [
            'id'            => $guide->id,
            'title'         => $guide->title,
            'tags'          => $guide->tags,
            'content'       => $guide->content,
            'isPublished'   => $guide->isPublished
        ];
        
        return $this->container->view->render($response, 'templates/guides/editGuide.twig');
    }

    public function postEditGuide(Request $request, Response $response)
    {
        $params = $request->getParams();

        $guide = Guide::where('id', '=', $params['id'])->first();
        
        if (!$guide) {
            $_SESSION['prevData'] = $params;
            return $response->withRedirect($this->container->router->urlFor("guides.getEditGuide", [
                'id' => $params['id']
            ]));
        }

        $guide->title = $params['title'];
        $guide->tags = $params['tags'];
        $guide->content = $params['content'];
        $guide->isPublished = (isset($params['isPublished'])) ? 1 : 0;
        $guide->save();

        return $response->withRedirect($this->container->router->urlFor("guides.viewGuide", [
            'id' => $params['id']
        ]));
    }

    public function viewGuide(Request $request, Response $response, array $args)
    {
        $guide = Guide::where('id', '=', $args['id'])->first();

        if (!$guide) {
            $this->container->flash->addMessage('error', 'No guide exists with that id.');
            return $response->withRedirect($this->container->router->pathFor('guides.getAllAvailableGuides'));
        }

        return $this->container->view->render($response, 'templates/guides/viewGuide.twig', [
            'guide' => $guide
        ]);
    }
}