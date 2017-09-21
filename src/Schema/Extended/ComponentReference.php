<?php

namespace Apitte\OpenApi\Schema\Extended;

use Apitte\OpenApi\Schema\Reference;

class ComponentReference extends Reference
{

	const TYPE_SCHEMA = 'schemas';
	const TYPE_RESPONSE = 'responses';
	const TYPE_PARAMETER = 'parameters';
	const TYPE_EXAMPLE = 'examples';
	const TYPE_REQUEST_BODY = 'requestBodies';
	const TYPE_HEADER = 'headers';
	const TYPE_SECURITY_SCHEMA = 'securitySchemes';
	const TYPE_LINK = 'links';
	const TYPE_CALLBACK = 'callbacks';

	/**
	 * @param string $type
	 * @param string $name
	 */
	public function __construct($type, $name)
	{
		parent::__construct('#/components/' . $type . '/' . $name);
	}

}
