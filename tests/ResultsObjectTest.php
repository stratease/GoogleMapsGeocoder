<?php

class ResultsObjectTest extends PHPUnit_Framework_TestCase
{
    /**
     * simple request to fetch and normalize an addresses data.
     */
    public function testFetchNormalizedAddress()
    {
        // search by country zip
        $geo = new GoogleMapsGeocoder('32165 Sherman Rd, 92584 USA');
        $resultObj = $geo->getResults();

        $this->assertEquals('Menifee', $resultObj->getCity());
        $this->assertEquals('CA', $resultObj->getState());
        $this->assertEquals('32165 Sherman Road', $resultObj->getAddress());
        $this->assertEquals('92584', $resultObj->getZip());
        $this->assertEquals('US', $resultObj->getCountry());
    }

}
