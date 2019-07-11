<?php

namespace anatoliy700\robots\actions;

use anatoliy700\robots\IRobots;
use Yii;
use yii\base\Action;
use yii\web\Response;

class RobotsActions extends Action
{
    /**
     * @var IRobots
     */
    protected $robots;

    /**
     * RobotsActions constructor.
     * @param $id
     * @param $controller
     * @param IRobots $robots
     * @param array $config
     */
    public function __construct($id, $controller, IRobots $robots, $config = [])
    {
        $this->robots = $robots;
        parent::__construct($id, $controller, $config);
    }

    /**
     * @return Response
     */
    public function run()
    {
        $response = Yii::$app->response;
        $headers = $response->getHeaders();
        $headers->set('Content-Type', 'text/plain; charset=UTF-8');
        $response->format = Response::FORMAT_RAW;

        if ($content = $this->robots->generateContent()) {
            $response->content = $content;
        }

        return $response;
    }
}
