<?php

namespace KenshooAPI\Report;

use KenshooAPI\Query;
use KenshooAPI\Configuration;

/**
 * Get information on a specified report.
 *
 *  @author wes.hulette@dygen.com
 */
class GetReport
{
    /**
     * A configuration object.
     *
     * @var Kenshoo\Configuration
     */
    protected $configuration;

    /**
     * ID of report to fetch.
     *
     * @var int
     */
    protected $reportId;

    /**
     * Get information on a specified report.
     *
     * @method __construct
     *
     * @param Kenshoo\Configuration $configuration A kenshoo configuration object
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Get information on the selected report.
     *
     * reportId (long, optional): The ID of the report,
     * reportName (string, optional): The name of the report,
     * template (string, optional),
     * status (string, optional),
     * lastRun (string, optional),
     * format (string, optional),
     * owner (string, optional),
     * profile (string, optional)
     *
     * @return string A json response
     */
    public function getReport()
    {
        $queryData = [
            'ks' => $this->configuration->getKenshooId(),
        ];

        $params = 'reports/'.$this->getReportId().'?'.http_build_query($queryData);

        $query = new Query($this->configuration);

        return $query->getQuery($params);
    }

    /**
     * Set the report id.
     *
     * @param int $reportId The report id
     *
     * @return object
     */
    public function setReportId($reportId)
    {
        $this->reportId = $reportId;

        return $this;
    }

    /**
     * Get the selected report id.
     *
     * @return int
     */
    public function getReportId()
    {
        return $this->reportId;
    }
}
