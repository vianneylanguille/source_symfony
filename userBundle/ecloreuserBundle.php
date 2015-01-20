<?php

namespace eclore\userBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ecloreuserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
