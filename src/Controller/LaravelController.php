<?php
namespace Hans\PrometheusExporter\Controller;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Prometheus\RenderTextFormat;
use Hans\PrometheusExporter\PrometheusExporter;

class LaravelController extends Controller
{
    /**
     * @var PrometheusExporter
     */
    protected $prometheusExporter;

    /**
     * PrometheusExporterController constructor.
     *
     * @param PrometheusExporter $prometheusExporter
     */
    public function __construct(PrometheusExporter $prometheusExporter)
    {
        $this->prometheusExporter = $prometheusExporter;
    }

    /**
     * metrics
     *
     * Expose metrics for prometheus
     *
     * @return Response
     */
    public function metrics() : Response
    {
        $renderer = new RenderTextFormat();

        return Response::create(
            $renderer->render($this->prometheusExporter->getMetricFamilySamples())
        )->header('Content-Type', RenderTextFormat::MIME_TYPE);
    }
}
