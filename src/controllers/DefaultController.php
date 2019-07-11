<?php

namespace anatoliy700\robots\controllers;

use anatoliy700\robots\actions\RobotsActions;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actions()
    {
        return [
            'index' => RobotsActions::class,
        ];
    }

}
