<?php
namespace Tests\Provider;

use Prometheus\Storage\Adapter;
use Tests\TestCase;
use Hans\PrometheusExporter\Provider\PrometheusExporterServiceProvider;

class InMemoryAdapterTest extends TestCase
{
    /**
     * Get package providers.  At a minimum this is the package being tested, but also
     * would include packages upon which our package depends, e.g. Cartalyst/Sentry
     * In a normal app environment these would be added to the 'providers' array in
     * the config/app.php file.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        $app['config']->set('prometheus-exporter.adapter', 'inmemory');

        return [
            PrometheusExporterServiceProvider::class
        ];
    }

    /**
     * @test
     */
    public function it_checks_the_concrete_implementation_of_prometheus_adapter()
    {
        $this->assertEquals('InMemory', class_basename(app(Adapter::class)));
    }
}
