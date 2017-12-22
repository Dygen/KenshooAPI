<?php

namespace Kenshoo;

use PHPUnit\Framework\TestCase;

class KenshooTest extends TestCase
{
    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers \Kenshoo\Kenshoo::ReportsList
     */
    public function testReportsListSuccess()
    {
        $ks = new Kenshoo(getenv('USERNAME'), getenv('PASSWORD'), getenv('KS'));

        $response = $ks->reportsList()->getList();

        $this->assertTrue(is_string($response));
    }

    /**
     * @covers \Kenshoo\Kenshoo::ReportsList
     */
    public function testReportsListFailure()
    {
        $this->expectException(\Exception::class);

        $ks = new Kenshoo(getenv('USERNAME'), getenv('PASSWORD').'888', getenv('KS'));

        $response = $ks->reportsList()->getList();
    }

    /**
     * @covers \Kenshoo\Kenshoo::ReportInfo
     */
    public function testReportInfo()
    {
        $ks = new Kenshoo(getenv('USERNAME'), getenv('PASSWORD'), getenv('KS'));

        $response = $ks->reportInfo()->setReportId(getenv('REPORTID'))->getReport();

        $this->assertTrue(is_string($response));
    }

    /**
     * @covers \Kenshoo\Kenshoo::ReportRun
     *
     * @method type testReportRun().
     */
    public function testReportRun()
    {
        $ks = new Kenshoo(getenv('USERNAME'), getenv('PASSWORD'), getenv('KS'));

        $report = $ks->reportRun()
            ->setReportId(getenv('REPORTID'))
            ->setDateRange('2016-12-01', '2016-12-02')
            ->runReport();
        sleep(5);
        $status = $report->getReportRunStatus();
        $this->assertTrue(is_string($status));
    }

    /**
     * @covers \Kenshoo::reportDownload
     *
     * @method testReportDownload
     */
    public function testReportDownload()
    {
        $ks = new Kenshoo(getenv('USERNAME'), getenv('PASSWORD'), getenv('KS'), false);
        sleep(5);
        $report = $ks->reportRun()
            ->setReportId(getenv('REPORTID'))
            ->setDateRange('2016-12-01', '2016-12-02')
            ->runReport();

        $data = $report->getReport();

        $this->assertTrue(is_array($data));
    }
}
