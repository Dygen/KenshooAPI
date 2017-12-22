<?php

namespace Kenshoo\Report;

use Exception;
use Kenshoo\Query;
use Kenshoo\Configuration;

/**
 *  Run a specified report.
 *
 *  @author jwhulette@gmail.com
 */
class RunReport
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
     * Report start date YYYY-MM-DD format.
     *
     * @var string
     */
    protected $dateFrom;

    /**
     * Report end date YYYY-MM-DD format.
     *
     * @var string
     */
    protected $dateTo;

    /**
     * A Kenshoo run token.
     *
     * @var string
     */
    protected $runToken = null;

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
     * Runs specified report and returns runToken (representing it),
     * runToken can be used for sampling report progress or data fetching.
     *
     * @return $this
     */
    public function runReport()
    {
        $queryData = [
            'ks' => $this->configuration->getKenshooId(),
        ];

        $url = 'reports/'.$this->getReportId().'/runs?'.http_build_query($queryData);

        echo 'Running report'.PHP_EOL;

        $query = new Query($this->configuration);

        $result = $query->postQuery($url, $this->getDateRange());

        /*
          Extract the token from the returned url
         */
        $path           = parse_url($result, PHP_URL_PATH);
        $pathFragments  = explode('/', $path);
        $this->runToken = end($pathFragments);

        return $this;
    }

    /**
     * Get the status of a report.
     *
     * @return string
     */
    public function getReportRunStatus()
    {
        if (null == $this->runToken) {
            throw new Exception('A run token is required to get the status of a report');
        }

        $queryData = [
            'ks' => $this->configuration->getKenshooId(),
        ];

        $url = 'reports/runs/'.$this->runToken.'/status?'.http_build_query($queryData);

        $query = new Query($this->configuration);

        return $query->getQuery($url);
    }

    /**
     * Return the report data.
     *
     * @method getReport
     *
     * @return array The report csv as an array
     */
    public function getReport()
    {
        $report = json_decode($this->getReportRunStatus());

        if ('COMPLETED' == $report->status) {
            echo 'Report status: '.$report->status.PHP_EOL;

            return $this->getReportData();
        } elseif ('RUNNING' == $report->status) {
            echo 'Report status: '.$report->status.PHP_EOL;
            sleep(5);

            return $this->getReport();
        } else {
            throw new Exception('Report run failed: '.$report->status);
        }
    }

    /**
     * Returns the report data according to the report's configuration.
     *
     * @method getReportData
     *
     * @return string
     */
    private function getReportData()
    {
        if (null == $this->runToken) {
            throw new Exception('A run token is required to get the report data');
        }

        $queryData = [
            'ks' => $this->configuration->getKenshooId(),
        ];

        echo 'Getting report data'.PHP_EOL;

        $query = new Query($this->configuration);

        $url = 'reports/runs/'.$this->runToken.'/data?'.http_build_query($queryData);

        $file = $query->downloadData($url, $this->getReportId());

        return $this->extractData($file);
    }

    /**
     * Extract the data from the CSV Zip file.
     *
     * @method extractData
     *
     * @param string $file The file to extract
     *
     * @return array The CSV file contents as an array
     */
    private function extractData($file)
    {
        $zip = new \ZipArchive();
        if (true === $zip->open($file)) {
            $name          = $zip->getNameIndex(0);
            $info          = new \SplFileInfo($file);
            $extractFolder = sys_get_temp_dir().uniqid();
            $fileName      = $extractFolder.'/'.$name;
            $zip->extractTo($extractFolder);
            $zip->close();
            $data = array_map('str_getcsv', file($fileName));

            // Delete Files
            unlink($file);
            unlink($fileName);
            rmdir($extractFolder);

            return $data;
        } else {
            throw new Exception('Error extracting ZIP file', 1);
        }
    }

    /**
     * Get the report range.
     *
     * @return string
     */
    public function getDateRange()
    {
        return [
            'dateRange' => [
                'from' => $this->dateFrom,
                'to'   => $this->dateTo,
            ],
        ];
    }

    /**
     * Set the reporting range.
     *
     * @param string $from YYYY-MM-DD format
     * @param string $to   YYYY-MM-DD format
     *
     * @return $this
     */
    public function setDateRange($from, $to)
    {
        $this->dateFrom = $from;
        $this->dateTo   = $to;

        return $this;
    }

    /**
     * The the report id.
     *
     * @return int
     */
    public function getReportId()
    {
        return $this->reportId;
    }

    /**
     * Set the report id.
     *
     * @param int $reportId
     *
     * @return $this
     */
    public function setReportId($reportId)
    {
        $this->reportId = $reportId;

        return $this;
    }
}
