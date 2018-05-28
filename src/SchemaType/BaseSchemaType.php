<?php

namespace Apitte\OpenApi\SchemaType;

use Apitte\Core\Schema\EndpointParameter;
use Apitte\OpenApi\Schema\Schema;

final class BaseSchemaType implements ISchemaType
{

	/**
	 * @param EndpointParameter $endpointParameter
	 *
	 * @return Schema
	 */
	public function createSchema(EndpointParameter $endpointParameter)
	{
		switch ($endpointParameter->getType()) {
			case EndpointParameter::TYPE_SCALAR:
				return new Schema(
					[
						'type' => 'scalar',
					]
				);

			case EndpointParameter::TYPE_STRING:
				return new Schema(
					[
						'type' => 'string',
					]
				);

			case EndpointParameter::TYPE_INTEGER:
				return new Schema(
					[
						'type'   => 'integer',
						'format' => 'int32',
					]
				);

			case EndpointParameter::TYPE_FLOAT:
				return new Schema(
					[
						'type'   => 'float',
						'format' => 'float64',
					]
				);

			case EndpointParameter::TYPE_BOOLEAN:
				return new Schema(
					[
						'type' => 'boolean',
					]
				);

			case EndpointParameter::TYPE_DATETIME:
				return new Schema(
					[
						'type'   => 'string',
						'format' => 'date-time',
					]
				);

			case EndpointParameter::TYPE_OBJECT:
				return new Schema(
					[
						'type' => 'object',
					]
				);

			default:
				throw new UnknownSchemaType('Unknown endpoint parameter type ' . $endpointParameter->getType());
		}
	}

}
