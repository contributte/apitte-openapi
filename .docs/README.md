# Apitte\OpenApi

## Content

- [Installation - how to register a plugin](#installation)
- [Configuration - all options](#configuration)
- [Usage - controller showtime](#usage)

## Installation

This plugin require [Apitte/Core](https://github.com/apitte/core) library.

Register core.

```yaml
extensions:
    api: Apitte\Core\DI\ApiExtension
```

Add OpenApi plugin.

```yaml
api:
    plugins: 
        Apitte\OpenApi\DI\OpenApiPlugin:
```

## Configuration

You can configure SwaggerUi panel with few optional parameters.

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

### OpenApiService

Now you can use OpenApiService for creating OpenApiSchema.

### Controller

If you need json endpoint, create controller.

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
    public function index(ApiRequest $request, ApiResponse $response)
    {
        $schema = $this->openApiService->createSchema();
        return $response
            ->writeJsonBody($schema->toArray())
            ->withAddedHeader('Access-Control-Allow-Origin', '*');
    }

}
```

```yaml
services:
    - App\Controllers\OpenApiController
```

At the end, open your browser and locate to `localhost/<api-project>/openapi/schema`.
