<?php

namespace Tiagof2\IntegrationCallback;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Http;

class Feedback
{
    protected $collection_data;
    protected bool $is_fake = false;
    protected bool $assert_value = false;
    protected array $callback_data;

    public function __construct(array $callback_data, bool $is_fake = false, bool $assert_value = false)
    {
        $this->callback_data = $callback_data;

        if($is_fake)
        {
            $this->fake();
            $this->assertValue($assert_value);
            $this->collect();
        }else{
            $this->validateAndPrepareData();
        }

    }

    protected function validateAndPrepareData()
    {
        if(!$this->isValid())
            return null;

        $this->collect();
    }

    /**
     * fake function
     *
     * To use in tests
     *
     * @return bool
     */
    public function fake()
    {
        $this->is_fake = true;
    }

    /**
     * isFake function
     *
     * check if is fake
     *
     * @return bool
     */
    public function isFake()
    {
        return !! $this->is_fake;
    }

    /**
     * fake function
     *
     * To use in tests
     *
     * @return bool
     */
    public function assertValue(bool $assert_value)
    {
        if($this->is_fake)
        {
            $this->assert_value = $assert_value;
        }
    }

    /**
     * isValid function
     * @return bool
     */
    public function isValid()
    {
        $validator = Validator::make($this->callback_data,
            [
                'protocol'  => 'required',
                'url'       => 'required|url|min:5',
                'method'    => [
                    'required',
                    Rule::in([
                        'get',   'GET',
                        'post',  'POST',
                        'put',   'PUT',
                        'patch', 'PATCH',
                    ]),
                ],
            ],
        );

        return !! ( (!$validator->fails()) ?? false );
    }

    /**
     * return instance of Illuminate\Support\Collection
     *
     */
    public function collect()
    {
        if(!$this->collection_data)
            $this->collection_data = new Collection($this->callback_data ?? []);

        return $this->collection_data ?? (new Collection([]));
    }

    /**
     * array function
     *
     * @return array
     */
    public function array()
    {
        return $this->collect()->all();
    }

    public function makeRequest(bool $success, string $message = null)
    {
        if($this->is_fake)
            return $this->assert_value;

        if(!$this->isValid())
            return null;

        $protocol   = $this->array()['protocol'] ?? null;
        $http_verb  = $this->array()['method'] ?? null;
        $url        = $this->array()['url'] ?? null;

        if (!$http_verb || !$url)
            return false;

        $payload = [
            'success' => !! $success,
        ];

        if($protocol)
            $payload['protocol'] = $protocol;

        if($message)
            $payload['message'] = $message;

        $response = Http::{$http_verb}($url, $payload);

        return $response->successful() ?? false;
    }
}
