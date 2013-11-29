BtnNodesBundle
==============

sample cms structure tree for menus

=============

### Step 1: Add NodesBundle in your composer.json (private repo)

```js
{
    "require": {
        "bitnoise/nodes-bundle": "dev-master",
    },
    "repositories": [
        {
            "type": "vcs",
            "url":  "git@github.com:Bitnoise/BtnNodesBundle.git"
        }
    ],
}
```

### Step 2: Enable the bundle

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Knp\Bundle\MenuBundle\KnpMenuBundle(),
        new Btn\NodesBundle\BtnNodesBundle(),
    );
}
```

### Step 3: Import NodesBundle routing

``` yaml
# app/config/routing.yml
btn_nodes:
    resource: "@BtnNodesBundle/Controller/"
    type:     annotation
    prefix:   /
```

### Step 4: Update your database schema

``` bash
$ php app/console doctrine:schema:update --force
```

### Step 5: Set up avalible routes

``` yml
# app/conig/parameters.yml
parameters:
    # ...
    btn.nodes.availableRoutes:
        homepage: Homepage
        # ...
```

### Add gedmo orm mappings
