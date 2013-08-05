<?php

namespace Btn\NodesBundle\Service;

use Symfony\Component\HttpFoundation\Request;

interface NodeContentProviderInterface
{
    public function getForm();

    public function resolveRouteName(Request $request);
}