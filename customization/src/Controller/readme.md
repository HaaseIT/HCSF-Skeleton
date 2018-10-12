Place custom controllers into this directory. Make sure to add routes to them.

An example for a regular controller:

```php
<?php
// customization/src/Controller/Test.php

namespace HaaseIT\HCSF\Controller\Custom;

use HaaseIT\HCSF\Controller\Base;

class Test extends Base
{
    public function preparePage()
    {
        $this->P = new \HaaseIT\HCSF\CorePage($this->serviceManager);
        $this->P->cb_pagetype = 'content';

        $this->P->oPayload->cl_html = 'Cool story bro...';
    }
}
```

A controller for the admin area:

```php
<?php
// customization/src/Controller/Admin/Fubar.php

namespace HaaseIT\HCSF\Controller\Custom\Admin;


use HaaseIT\HCSF\Controller\Admin\Base;

class Fubar extends Base
{
    public function preparePage()
    {
        $this->P = new \HaaseIT\HCSF\CorePage($this->serviceManager, [], 'admin/base.twig');
        $this->P->cb_pagetype = 'content';
        $this->P->cb_subnav = 'admin';

        $this->P->oPayload->cl_html = 'Cool story bro... Fo sho!';
    }
}
```
