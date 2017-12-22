<?php

namespace Kenshoo\Report;

use Kenshoo\Query;
use Kenshoo\Configuration;

/**
 * Get a list of reports available.
 *
 *  @author jwhulette@gmail.com
 */
class ReportList
{
    /**
     * A configuration object.
     *
     * @var Kenshoo\Configuration
     */
    protected $configuration;

    /**
     *  The number of reports to return, default 100.
     *
     *  @var int
     */
    protected $pageSize = 100;

    /**
     * The report status to filter for.
     *
     * @var string
     */
    protected $status = '';

    /**
     * @method __construct
     *
     * @param Kenshoo\Configuration $configuration A kenshoo configuration object
     */
    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Returns a list of all defined reports, containing:.
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
     * @method getReportList
     *
     * @return string A json repsonse
     */
    public function getList()
    {
        $queryData = [
            'ks' => $this->configuration->getKenshooId(),
            'pageSize' => $this->getPageSize(),
        ];
        if ('' != $this->getStatus()) {
            array_push($queryData, ['status' => $this->getStatus()]);
        }

        $params = 'reports?'.http_build_query($queryData);

        $query = new Query($this->configuration);

        return $query->getQuery($params);
    }

    /**
     * Set the page size to return.
     *
     * @method setPageSize
     *
     * @param int $pageSize The page size
     *
     * @return $this
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;

        return $this;
    }

    /**
     * Get the page size to send.
     *
     * @method getPageSize
     *
     * @return int The page size
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * Set the report status to filter by.
     *
     * @method setStatus
     *
     * @param string $status The status of PENDING, COMPLETED, COMPLETED_WITH_ERRORS, RUNNING, FAILED, ABORT or FAILED_DATA_NOT_AVAILABLE
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the report status.
     *
     * @method getStatus
     *
     * @return string The report status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Get a listing of the report status types.
     *
     * @return array
     */
    public function getStatusList()
    {
        return [
            'PENDING',
            'COMPLETED',
            'COMPLETED_WITH_ERRORS',
            'RUNNING',
            'FAILED',
            'ABORT',
            'FAILED_DATA_NOT_AVAILABLE',
        ];
    }
}
