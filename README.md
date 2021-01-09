# LaravelPrometheusExporter
A laravel and lumen service provider to export metrics for prometheus.

## Supported laravel versions
Laravel 7.x

## Main features
- Metrics with APC
- Metrics with Redis
- Metrics with InMemory
- Metrics with the push gateway
- Request per route middleware (total and duration metrics)

## Installation

### Composer
> composer require hans-thomas/laravel-prometheus-exporter

### Application

Once installed you can now publish your config file and set your correct configuration for using the package.
```php
php artisan vendor:publish --provider="Hasn\PrometheusExporter\Provider\PrometheusExporterServiceProvider" --tag="config"
```

This will create a file ```config/prometheus-exporter.php```.

## Configuration
| Key        | Env | Value           | Description  | Default |
|:-------------:|:-------------:|:-------------:|:-----:|:-----:|
| adapter | PROMETHEUS_ADAPTER | STRING | apc, redis, inmemory or push | apc |
| namespace | --- | STRING | default: app | app |
| namespace_http | --- | STRING | namespace for "RequestPerRoute-Middleware metrics" | http |
| redis.host | PROMETHEUS_REDIS_HOST, REDIS_HOST | STRING | redis host | 127.0.0.1
| redis.port | PROMETHEUS_REDIS_PORT, REDIS_PORT | INTEGER | redis port | 6379 |
| redis.password | PROMETHEUS_REDIS_PASSWORD, REDIS_PASSWORD | STRING | redis password | null |
| redis.timeout | --- | FLOAT | redis timeout | 0.1 |
| redis.read_timeout | --- | INTEGER | redis read timeout | 10 |
| push_gateway.address | PROMETHEUS_PUSH_GATEWAY_ADDRESS | STRING | push gateway address | localhost:9091 |
| buckets_per_route | --- | STRING | histogram buckets for "RequestPerRoute-Middleware" | --- |

### buckets_per_route
```
'buckets_per_route' => [
    ROUTE-NAME => [10,20,50,100,200],
    ...
]
```

## Usage

### Get metrics

#### Laravel
When you are using laravel you can use the default http endpoint:
>hans/pe/metrics

Of course you can also register your own route. Here is an example:
```
Route::get(
    ROUTE,
    [ \Hans\PrometheusExporter\Controller\LaravelController::class, 'metrics']
);
```

### Middleware

#### RequestPerRoute
A middleware to build metrics for "request_total" and "requests_latency_milliseconds" per route.

##### Alias
>lpe.requestPerRoute

##### Metrics
1. requests_total (inc)
2. requests_latency_milliseconds (histogram)

##### Example
```php
$router->get('/test/route', function () {
    return 'valid';
})->middleware('lpe.requestPerRoute');
```

>app_requests_latency_milliseconds_bucket{route="/test/route",method="GET",status_code="200",le="0.005"} 0
>...
>app_requests_latency_milliseconds_count{route="/test/route",method="GET",status_code="200"} 1
>app_requests_latency_milliseconds_sum{route="/test/route",method="GET",status_code="200"} 6
>app_requests_total{route="/test/route",method="GET",status_code="200"} 1

## Roadmap
- histogram buckets per route (RequestPerRoute)

## Reporting Issues
If you do find an issue, please feel free to report it with GitHub's bug tracker for this project.

Alternatively, fork the project and make a pull request. :)

## Testing
1. docker-compose up
2. docker exec fpm ./vendor/phpunit/phpunit/phpunit

## Contributing
Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Other

### License
The code for LaravelPrometheusExporter is distributed under the terms of the MIT license (see [LICENSE](LICENSE)).

