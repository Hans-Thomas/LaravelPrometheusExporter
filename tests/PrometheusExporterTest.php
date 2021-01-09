<?php
namespace Tests;

use Illuminate\Foundation\Testing\TestResponse;
use phpDocumentor\Reflection\Types\Callable_;
use Triadev\PrometheusExporter\Contract\PrometheusExporterContract;

class PrometheusExporterTest extends TestCase
{
    /** @var PrometheusExporterContract */
    private $service;

    /**
     * Setup the test environment.
     */
    public function setUp() : void
    {
        parent::setUp();

        $this->service = app(PrometheusExporterContract::class);
    }

    private function getMetricResponseCallableForLaravel() : \Closure
    {
        return function () {
            return $this->get('/triadev/pe/metrics');
        };
    }


    private function getMetricValue(string $name, \Closure $metricCall) : ?int
    {
        $pattern = sprintf('/app_%s (?<metric>[0-9]+)/', $name);

        $content = $metricCall()->getContent();

        if (preg_match($pattern, $content, $matches)) {
            return $matches['metric'];
        }

        return null;
    }

    /**
     * @test
     */
    public function it_inc_a_counter()
    {
        $this->service->incCounter('phpunit_incCounter', '');

        $metricBeforeLaravel = $this->getMetricValue('phpunit_incCounter', $this->getMetricResponseCallableForLaravel());

        $this->service->incCounter('phpunit_incCounter', '');

        $metricAfterLaravel = $this->getMetricValue('phpunit_incCounter', $this->getMetricResponseCallableForLaravel());

        $this->assertGreaterThan($metricBeforeLaravel, $metricAfterLaravel);
    }

    /**
     * @test
     */
    public function it_inc_by_counter()
    {
        $this->service->incByCounter('phpunit_incByCounter', '', 10);

        $this->assertEquals(10, $this->getMetricValue('phpunit_incByCounter', $this->getMetricResponseCallableForLaravel()));
    }

    /**
     * @test
     */
    public function it_set_a_gauge()
    {
        $this->service->setGauge('phpunit_setGauge', '', 2);

        $this->assertEquals(2, $this->getMetricValue('phpunit_setGauge', $this->getMetricResponseCallableForLaravel()));
    }

    /**
     * @test
     */
    public function it_inc_gauge()
    {
        $this->service->incGauge('phpunit_incGauge', '');

        $this->assertEquals(1, $this->getMetricValue('phpunit_incGauge', $this->getMetricResponseCallableForLaravel()));

        $this->service->incGauge('phpunit_incGauge', '');

        $this->assertEquals(2, $this->getMetricValue('phpunit_incGauge', $this->getMetricResponseCallableForLaravel()));
    }

    /**
     * @test
     */
    public function it_inc_by_gauge()
    {
        $this->service->incByGauge('phpunit_incByGauge', '', 2);

        $this->assertEquals(2, $this->getMetricValue('phpunit_incByGauge', $this->getMetricResponseCallableForLaravel()));
    }

    /**
     * @test
     */
    public function it_set_a_histogram()
    {
        $this->service->setHistogram('phpunit_setHistogram', '', 1);
        $this->service->setHistogram('phpunit_setHistogram', '', 2);

        $this->assertEquals(3, $this->getMetricValue('phpunit_setHistogram_sum', $this->getMetricResponseCallableForLaravel()));
    }
}
