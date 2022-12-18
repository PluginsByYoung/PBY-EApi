<?php

namespace PBY\EApi\Api\Controller;

class Index extends AbstractController
{
    public function actionGet()
    {
        $am = $this->app->addOnManager();
        $data = $am->getById('PBY/EApi')->getJsonVersion();

        return $this->apiResult($data);
    }
}