# Apitte\OpenApi

## Content

- [Installation - how to register a plugin](#installation)
- [Configuration - all options](#configuration)
- [Usage - controller showtime](#usage)

## Installation

This plugin requires [Apitte/Core](https://github.com/apitte/core) library.

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

## Configuration

You can configure Swagger UI with a few optional parameters.

```yaml
api:
    plugins:
        Apitte\OpenApi\DI\OpenApiPlugin:
            swagger:
                url: null # default url
                expansion: list # list|full|none
                filter: true # true|false|string
                title: My API v2
```

## Usage

Let say you would like to display application's OpenAPI in some swagger gui. At first you
have to make a controller, secondly inject the `OpenApiService` and create a schema.

At least send the response with generated schema.

```yaml
services:
    - App\Controllers\OpenApiController
```

```php
namespace App\Controllers;

use Apitte\Core\Annotation\Controller\Controller;
use Apitte\Core\Annotation\Controller\Method;
use Apitte\Core\Annotation\Controller\Path;
use Apitte\Core\Annotation\Controller\RootPath;
use Apitte\Core\Http\ApiRequest;
use Apitte\Core\Http\ApiResponse;
use Apitte\Core\UI\Controller\IController;
use Apitte\OpenApi\OpenApiService;

/**
 * @Controller
 * @RootPath("/openapi")
 */
final class OpenApiController implements IController
{

    /** @var OpenApiService @inject */
    public $openApiService;

    /**
     * @Path("/schema")
     * @Method("GET")
     */
    public function index(ApiRequest $request, ApiResponse $response): ApiResponse
    {
        $schema = $this->openApiService->createSchema();

        return $response
            ->writeJsonBody($schema->toArray())
            ->withAddedHeader('Access-Control-Allow-Origin', '*');
    }

}
```

At the end, open your browser and locate to `localhost/<api-project>/openapi/schema`.
