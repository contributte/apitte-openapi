# Apitte - OpenApi plugin

- offers several ways to define [OpenAPI Specification](https://github.com/OAI/OpenAPI-Specification/blob/master/versions/3.0.2.md) (including generated)
- adds php objects / classes for better manipulation with OpenApi schema
- adds SwaggerUi panel to Tracy

## Content 

- [Installation - how to register plugin](#installation)
- [Usage](#usage)
  - [SchemaBuilder - how to create schema](#schemabuilder)
  - [Definitions - how to define schema](#definitions)
    - [Config](#config)
    - [External Files](#external-files)
    - [OpenApi Annotations](#openapi-annotations-experimental)
    - [Core Annotations](#core-annotations)
    - [Entity Annotations](#entity-annotations)
    - [Custom - how to write custom definition](#custom-definition)
  - [SwaggerUI Tracy panel - how to configure panel](#tracy-swaggerui-panel)
  - [OpenApi Controller Endpoint - how to provide schema in api](#openapi-controller-endpoint)

## Installation

This plugin requires [apitte/core](https://github.com/apitte/core) library.

At first you have to register the main extension.

```yaml
extensions:
    api: Apitte\Core\DI\ApiExtension
```

Secondly, add the `OpenApiPlugin` plugin.

```yaml
api:
    plugins:
        Apitte\OpenApi\DI\OpenApiPlugin:
```

## Usage

### SchemaBuilder

You can find new service in DIC, called SchemaBuilder.

It contains default set of definitions.

When you run `build()`, these definitions are **merged together** and result is `OpenApi` object.

```php
$openApi = $schemaBuilder->build(); // OpenApi object
```

You have objecs, so you can use.

```php
$version = $openApi->getInfo()->getVersion(); // string
$data = $openApi->toArray(); // array
```

### Definitions

There are many ways how to define open api schema. 

You can write **raw** OpenApi.

- [Config](#config)
- [External Files](#external-files)
- [OpenApi Annotations](#openapi-annotations-experimental)

Or you can let the plugin do for you in **dynamic** way.

- [Core Annotations](#core-annotations)
- [Entity Annotations](#entity-annotations)

Also you can write your **own** definition.

- [Custom](#custom-definition)

-----------------
#### Config

You can easily define whole schema (or part) directly in extension config.

```yaml
Apitte\OpenApi\DI\OpenApiPlugin:
    definition:
        openapi: "3.0.2"
        info:
            title: My awesome OpenApi specification
            version: "1.0.0"
        ...
```

#### External Files

Define whole OpenApi schema (or part) in external files.

```yaml
Apitte\OpenApi\DI\OpenApiPlugin:
    files:
        - %appDir%/openApi/petstore.neon
        - %appDir%/openApi/petstoreExtended.yaml
        - %appDir%/openApi/petstoreAdmin.json
```

Supported types are `neon`, `yaml` and `json`.

#### OpenApi-Annotations **(Experimental)**

This definition  comes from core, but use only `OpenApi` annotation. 

```php
/**
 * @Controller
 * @ControllerPath("/")
 * @OpenApi("
 *    openapi: '4.0.3'
 *    info:
 *       title: Defined by controller annotation
 *       version: '1.0.0'"
 *)
 */
final class HomeController extends BaseV1Controller
{

	/**
	 * @Path("/")
	 * @Method("GET")
	 * @OpenApi("
	 *    summary: Defined specific endpoint
	 *    operationId: listPets
	 *    tags:
	 *       - pets
	 *    parameters:
	 *       -
	 *           name: limit
	 *           in: query
	 *           description: 'How many items to return at one time (max 100)'
	 *           required: false
	 *           schema:
	 *               type: integer
	 *               format: int32
	 *    responses:
	 *       '200':
	 *           description: A paged array of pets
	 *           headers:
	 *               x-next:
	 *                   description: A link to the next page of responses
	 *                   schema:
	 *                       type: string
	 *           content:
	 *               application/json:
	 *                   schema:
	 *                       $ref: '#/components/schemas/Pets'
	 *       default:
	 *           description: unexpected error
	 *           content:
	 *               application/json:
	 *                   schema:
	 *                       $ref: '#/components/schemas/Error'
	 * ")
	 */
	public function index(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		return $response->withEntity(ArrayEntity::from(['data' => ['Welcome']]));
	}

}
```

#### Core-Annotations

This definition is based on Doctrine Annotation, which are parts of core.

```php

/**
 * @Controller
 * @ControllerPath("/users")
 * @Tag(value="User")
 */
final class UserDetailController extends BaseV1Controller
{

	/**
	 * @Path("/create")
	 * @Method("POST")
	 * @Request(required="true", description="Sample request")
	 * @Responses({
	 * 		@Response(code="200", description="Success"),
	 * 		@Response(code="404", description="Not found")
	 * })
	 */
	public function detail(ApiRequest $request): array
	{
		return [];
	}

}
```

#### Entity-Annotations

Same as CoreDefinition but it use `entity` in `RequestBody` & `Response` annotation.

```php
/**
 * @Controller
 * @ControllerPath("/users")
 */
final class UserDetailController extends BaseV1Controller
{

	/**
	 * @Path("/create")
	 * @Method("POST")
	 * @Request(required="true", description="Sample request", entity="App\Controllers\Entity\User")
	 * @Responses({
	 * 		@Response(code="200", description="Success", entity="App\Controllers\Entity\User"),
	 * 		@Response(code="404", description="Not found")
	 * })
	 */
	public function detail(ApiRequest $request): array
	{
		return [];
	}

}
```

Entity is loaded by reflection, it loads all public properties using `EntityAdapter`.

You can redefine entity adapter by interface.


#### Custom Definition

If you need, you can add your definition using `IDefinition` interface. 

### Tracy SwaggerUI Panel

You can configure Swagger UI with a few optional parameters.

```yaml
Apitte\OpenApi\DI\OpenApiPlugin:
    swaggerUi:
        url: null # default url
        expansion: list # list|full|none
        filter: true # true|false|string
        title: My API v2
```

### OpenApi Controller Endpoint 

You will probably need to provide your open api schema outside from your app.

Create controller for this case.

```php
/**
 * @Controller
 * @ControllerPath("/open-api")
 */
final class OpenApiController
{

	/** @var ISchemaBuilder */
	private $schemaBuilder;

	public function __construct(ISchemaBuilder $schemaBuilder)
	{
		$this->schemaBuilder = $schemaBuilder;
	}

	/**
	 * @Path("/")
	 * @Method("GET")
	 */
	public function index(ApiRequest $request, ApiResponse $response): ApiResponse
	{
		$openApi = $this->schemaBuilder->build();
		return $response->writeJsonBody($openApi->toArray());
	}

}
```
