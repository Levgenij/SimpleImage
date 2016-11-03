<?php

namespace LightAdmin\Image\Facades;

use Illuminate\Support\Facades\Facade;

class SimpleImage extends Facade
{
	protected static function getFacadeAccessor()
	{
		return 'SimpleImage';
	}

}