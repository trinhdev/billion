<?php

namespace Modules\Support\Http\Controllers;

use stdClass;
use GuzzleHttp\Client;
use Modules\Support\State;
use Illuminate\Http\Response;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ConnectException;
use FleetCart\Exceptions\InvalidLicenseException;

class CountryStateController
{
    private string $endpoint = 'https://partner.viettelpost.vn';
    /**
     * Display a listing of the resource.
     *
     * @param string $countryCode
     *
     * @return Response
     */
    public function index($countryCode)
    {
        $states = State::get($countryCode);

        return response()->json($states);
    }

    /**
     * @throws InvalidLicenseException|GuzzleException
     */
    public function city()
    {
        $client = new Client(['base_uri' => $this->endpoint]);
        try {
            $data = $client->get('/v2/categories/listProvinceById?provinceId=-1');
            return json_decode($data->getBody()->getContents());
        } catch (ClientException $e) {
            throw new InvalidLicenseException($e->getMessage());
        }
    }

    /**
     * @throws InvalidLicenseException|GuzzleException
     */
    public function district($cityId)
    {
        $client = new Client(['base_uri' => $this->endpoint]);
        try {
            $data = $client->get('/v2/categories/listDistrict?provinceId='.$cityId);
            return json_decode($data->getBody()->getContents());
        } catch (ClientException $e) {
            throw new InvalidLicenseException($e->getMessage());
        }
    }

    /**
     * @throws InvalidLicenseException|GuzzleException
     */
    public function ward($districtId)
    {
        $client = new Client(['base_uri' => $this->endpoint]);
        try {
            $data = $client->get('/v2/categories/listWards?districtId='.$districtId);
            return json_decode($data->getBody()->getContents());
        } catch (ClientException $e) {
            throw new InvalidLicenseException($e->getMessage());
        }
    }
}
