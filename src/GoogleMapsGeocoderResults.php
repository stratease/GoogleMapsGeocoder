<?php

class GoogleMapsGeocoderResults
{
    protected $city;
    protected $state;

    /**
     * GoogleMapsGeocoderResults constructor.
     * @param array $httpResponse The decoded json response array
     */
    public function __construct(array $httpResponse)
    {
        $this->parseResponse($httpResponse);
    }

    /**
     * @param array $httpResponse
     * @return boolean If valid request, true
     */
    protected function parseResponse(array $httpResponse)
    {
        if(isset($httpResponse['status'])
        && $httpResponse['status'] === GoogleMapsGeocoder::STATUS_SUCCESS
        && isset($httpResponse['results'][0]['address_components'])) {
            $this->setCity($this->parseForCity($httpResponse))
                ->setState($this->parseForState($httpResponse));

            return true;
        }

        return false;
    }

    protected function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    protected function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getState()
    {
        return $this->state;
    }

    public function parseForCity(array $httpResponse)
    {
        foreach($httpResponse['results'][0]['address_components'] as $component) {
            if(in_array(GoogleMapsGeocoder::TYPE_LOCALITY, $component['types'])) {

                return $component['long_name'];
            }
        }

        return null;
    }

    public function parseForState(array $httpResponse)
    {
        foreach($httpResponse['results'][0]['address_components'] as $component) {
            if(in_array(GoogleMapsGeocoder::TYPE_ADMIN_AREA_1, $component['types'])) {

                return $component['short_name']; // 2 char iso code?
            }
        }

        return null;
    }
}
