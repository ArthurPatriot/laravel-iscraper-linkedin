<?php

namespace Iscraper\Services;

use Iscraper\Exceptions\IscraperRequestError;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

class LinkedInSearchService
{
    private array $baseHeaders;

    public function __construct()
    {
        $this->baseHeaders = [
            'X-API-KEY' => config('iscraper.api_key'),
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     * @throws \Illuminate\Http\Client\RequestException
     * @throws \Throwable
     */
    public function getAccountDetails()
    {
        $response = Http::withHeaders($this->baseHeaders)->get($this->getApiUrl('/my-account'));

        $this->validateResponse($response);

        return $response->collect();
    }

    /**
     * @param  string  $keyword
     * @param  string|null  $location
     * @param  string|null  $size
     * @param  int  $perPage
     * @param  int  $offset
     * @return \Illuminate\Support\Collection
     * @throws \Illuminate\Http\Client\RequestException
     * @throws \Throwable
     */
    public function searchCompanies(string $keyword, ?string $location = null, ?string $size = null, int $perPage = 10, int $offset = 0)
    {
        $data = [
            'search_type' => 'companies',
            'keyword' => $keyword,
            'location' => $location,
            'size' => $size,
            'per_page' => $perPage,
            'offset' => $offset,
        ];

        return $this->linkedInSearch(array_filter($data));
    }

    /**
     * @param  string  $keyword
     * @param  string|null  $location
     * @param  int  $perPage
     * @param  int  $offset
     * @return \Illuminate\Support\Collection
     * @throws \Illuminate\Http\Client\RequestException
     * @throws \Throwable
     */
    public function searchPeople(string $keyword, ?string $location = null, int $perPage = 10, int $offset = 0)
    {
        $data = [
            'search_type' => 'people',
            'keyword' => $keyword,
            'location' => $location,
            'per_page' => $perPage,
            'offset' => $offset,
        ];

        return $this->linkedInSearch(array_filter($data));
    }

    /**
     * TODO: Need deep refactor
     *
     * @param  array  $data
     * @param  bool  $detailed
     * @return \Illuminate\Support\Collection
     * @throws \Illuminate\Http\Client\RequestException
     * @throws \Throwable
     */
    public function linkedInSearch(array $data, bool $detailed = true)
    {
        $response = Http::withHeaders($this->baseHeaders)->post($this->getApiUrl('/v1/linkedin-search'), $data);

        $this->validateResponse($response);

        $responseData = $response->collect();
        $responseData[ 'results' ] = collect($responseData[ 'results' ]);

        if (!$detailed) {
            return $responseData;
        }


        $detailedResponses = Http::pool(function (Pool $pool) use ($responseData, $data) {

            $pools = [];

            foreach ($responseData[ 'results' ] as $item) {
                $pools[] = $pool->withHeaders($this->baseHeaders)
                    ->post($this->getApiUrl('/v1/profile-details'), [
                        'profile_id' => $item[ 'universal_id' ],
                        'profile_type' => $data[ 'search_type' ] === 'people' ? 'personal' : 'company',
                        'contact_info' => true,
                    ]);
            }

            return $pools;

        });

        $detailedData = [];

        foreach ($detailedResponses as $detailedResponse) {

            // TODO: Skip it?
            try {
                $this->validateResponse($detailedResponse);
            } catch (\Illuminate\Http\Client\RequestException $e) {
                continue;
            }

            if ($data[ 'search_type' ] === 'people') {
                $detailedData[] = $detailedResponse->json();
            } else {
                $detailedData[] = $detailedResponse->json('details');
            }

        }

        $responseData[ 'results' ] = $detailedData;

        return $responseData;
    }

    /**
     * @param  array  $data
     * @return array|mixed
     * @throws \Illuminate\Http\Client\RequestException
     * @throws \Throwable
     */
    public function getProfileDetails(array $data)
    {
        $response = Http::withHeaders($this->baseHeaders)->post($this->getApiUrl('/v1/profile-details'), $data);

        $this->validateResponse($response);

        return $response->json();
    }

    /**
     * @param  string  $path
     * @return string
     */
    private function getApiUrl(string $path)
    {
        return config('iscraper.base_api_url').$path;
    }

    /**
     * @throws \Illuminate\Http\Client\RequestException
     * @throws \Throwable
     * @var \Illuminate\Http\Client\Response $response
     */
    private function validateResponse(\Illuminate\Http\Client\Response $response)
    {
        if ($response->successful()) {
            return;
        }

        $errorMessage = $response->json('detail');

        if (is_array($errorMessage)) {
            $errorMessage = $errorMessage[ 'msg' ];
        }

        throw_if($errorMessage, new IscraperRequestError("Search API Error: $errorMessage"));

        $response->throw();
    }

}