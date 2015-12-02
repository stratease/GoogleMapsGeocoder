<?php

class GoogleMapsGeocoderResults
{
    protected $city;
    protected $state;
    protected $address;
    protected $zip;
    protected $country;

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
                ->setZip($this->parseForZip($httpResponse))
                ->setAddress($this->parseForAddress($httpResponse))
                ->setCountry($this->parseForCountry($httpResponse))
                ->setState($this->parseForState($httpResponse));

            return true;
        }

        return false;
    }

    /**
     * @param $city
     * @return $this
     */
    protected function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @param $state
     * @return $this
     */
    protected function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @param $z
     * @return $this
     */
    protected function setZip($z)
    {
        $this->zip = $z;

        return $this;
    }

    /**
     * @param $a
     * @return $this
     */
    protected function setAddress($a)
    {
        $this->address = $a;

        return $this;
    }

    /**
     * @param $c
     * @return $this
     */
    protected function setCountry($c)
    {
        $this->country = $c;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return mixed
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param array $httpResponse
     * @return null|string
     */
    public function parseForCity(array $httpResponse)
    {
        foreach($httpResponse['results'][0]['address_components'] as $component) {
            if(in_array(GoogleMapsGeocoder::TYPE_LOCALITY, $component['types'])) {

                return $component['long_name'];
            }
        }

        return null;
    }

    /**
     * @param array $httpResponse
     * @return null|string
     */
    public function parseForState(array $httpResponse)
    {
        foreach($httpResponse['results'][0]['address_components'] as $component) {
            if(in_array(GoogleMapsGeocoder::TYPE_ADMIN_AREA_1, $component['types'])) {

                return $component['short_name']; // 2 char iso code?
            }
        }

        return null;
    }

    /**
     * @param array $httpResponse
     * @return null|string
     */
    public function parseForCountry(array $httpResponse)
    {
        foreach($httpResponse['results'][0]['address_components'] as $component) {
            if(in_array(GoogleMapsGeocoder::TYPE_COUNTRY, $component['types'])) {

                return $component['short_name']; // 2 char iso code?
            }
        }

        return null;
    }


    /**
     * @param array $httpResponse
     * @return null|string
     */
    public function parseForAddress(array $httpResponse)
    {
        $foundStreet = false;
        $foundRoute = false;
        $address = '';
        foreach($httpResponse['results'][0]['address_components'] as $component) {
            if(!$foundRoute
            && in_array(GoogleMapsGeocoder::TYPE_ROUTE, $component['types'])) {
                $foundRoute = true;
                $address = trim($address.' '.$component['long_name']); // append full street name
            }

            if(!$foundStreet
                && in_array(GoogleMapsGeocoder::TYPE_STREET_NUMBER, $component['types'])) {
                $foundStreet = true;
                $address = trim($component['long_name'].' '.$address); // prepend street number
            }
        }
        $address = trim($address);
        if($address) {
            return $address;
        } else {
            return null;
        }
    }

    /**
     * @param array $httpResponse
     * @return null|string
     */
    public function parseForZip(array $httpResponse)
    {
        foreach($httpResponse['results'][0]['address_components'] as $component) {
            if(in_array(GoogleMapsGeocoder::TYPE_POSTAL_CODE, $component['types'])) {

                return $component['short_name'];
            }
        }

        return null;
    }
}
