<?php

namespace MilesApart\PublicUserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class MilesApartPublicUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
