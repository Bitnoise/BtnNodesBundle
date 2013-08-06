<?php

namespace Btn\NodesBundle\Service;

interface NodeContentProviderInterface
{
    public function getForm();

    public function resolveRouteName($dataForm = array());

    public function resolveControlRouteName($dataForm = array());

}