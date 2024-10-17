<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use GuzzleHttp\Client;

class ScanFile implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Create a new GuzzleHttp client instance
        $client = new Client();

        // Make a POST request to the ClamAV API to scan the file
        $response = $client->request('POST', 'https://www.virustotal.com/vtapi/v2/file/scan', [
            'multipart' => [
                [
                    'name'     => 'apikey',
                    'contents' => env('CLAMAV_API_KEY'), // Set your ClamAV API key in your .env file
                ],
                [
                    'name'     => 'file',
                    'contents' => fopen($value, 'r'),
                ],
            ],
        ]);

        // Get the response body
        $responseBody = json_decode($response->getBody(), true);

        // Get the resource URL for the scanned file
        $resourceUrl = $responseBody['resource'];

        // Make a GET request to the ClamAV API to retrieve the scan report
        $response = $client->request('GET', 'https://www.virustotal.com/vtapi/v2/file/report', [
            'query' => [
                'apikey' => env('CLAMAV_API_KEY'), // Set your ClamAV API key in your .env file
                'resource' => $resourceUrl,
            ],
        ]);

        // Get the response body
        $responseBody = json_decode($response->getBody(), true);

        // Check if the file is infected
        if ($responseBody['positives'] > 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The uploaded file is infected with a virus!';
    }
}
