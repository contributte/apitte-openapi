<?php

namespace Apitte\OpenApi\SchemaType;

use Apitte\Core\Schema\EndpointParameter;
use Apitte\OpenApi\Schema\Schema;

interface ISchemaType
{

	/**
	 * @param EndpointParameter $endpointParameter
	 *
	 * @return Schema
	 */
	public function createSchema(EndpointParameter $endpointParameter);

}
