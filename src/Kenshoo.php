<?php

namespace Kenshoo;

use Kenshoo\Report\RunReport;
use Kenshoo\Report\GetReport;
use Kenshoo\Report\ReportList;

/**
 *  An interface for the Kenshoo API.
 *
 *  @author wes.hulette@dygen.com
 */
class Kenshoo
{
    /**
     * A configuration object.
     *
     * @var object
     */
    protected $configuration;

    /**
     * @method __construct
     *
     * @param string $username  The Kenshoo user name
     * @param string $password  The Kenshoo password
     * @param string $kenshooId The Kenshoo Server Id
     * @param bool   $debug     The debug flag
     */
    public function __construct($username, $password, $kenshooId, $debug=false)
    {
        $this->configuration = new Configuration();
        $this->configuration->setUsername($username)
            ->setPassword($password)
            ->setKenshooId($kenshooId)
            ->setDebug($debug);
    }

    /**
     * Get a listing of available reports.
     *
     * @return ReportList
     */
    public function reportsList()
    {
        return new ReportList($this->configuration);
    }

    /**
     * Get information on a specified report.
     *
     * @return RunReport
     */
    public function reportInfo()
    {
        return new GetReport($this->configuration);
    }

    /**
     * Run a specified report.
     *
     * @return RunReport
     */
    public function reportRun()
    {
        return new RunReport($this->configuration);
    }
}
