<?php

class ResultsObjectTest extends PHPUnit_Framework_TestCase
{
    /**
     * simple request to fetch and normalize an addresses data.
     */
    public function testFetchNormalizedAddress()
    {
        // search by country zip
        $geo = new GoogleMapsGeocoder('USA 92584');
        $resultObj = $geo->getResults();

        $this->assertEquals('Menifee', $resultObj->getCity());
        $this->assertEquals('CA', $resultObj->getState());
    }

}
