<?php

namespace Tests\Feature;

use Tests\TestCase;
use Tiagof2\IntegrationCallback\Feedback;

class FeedbackTest extends TestCase
{
    protected $callback_data;

    public function setUp(): void {
        parent::setUp();

        $fake_url = 'http://fake-url.com/notify_status';
        $is_fake  = true;

        $this->callback_data = new Feedback($callback_data_values = [
            'protocol'          => 'my-protocol-id',   //'required',
            'url'               => $fake_url,          //'required|url|min:5',
            'method'            => 'post',             //HTTP verbs (get/post/put/patch)
        ], $is_fake);
    }

    /**
     *
     *  @test
     * @return void
     */
    public function checkIfCallbackDataIsFake()
    {
        $this->assertTrue($this->callback_data->isFake());
    }

    /**
     *
     *  @test
     * @return void
     */
    public function checkIfCallbackDataIsValid()
    {
        $this->assertTrue($this->callback_data->isValid());
    }

    /**
     *
     *  @test
     * @return void
     */
    public function checkIfMakeRequestReturnsTrue()
    {
        $this->callback_data->assertValue(true);

        $this->assertTrue($this->callback_data->makeRequest(true));
    }

    /**
     *
     *  @test
     * @return void
     */
    public function checkIfMakeRequestReturnsFalse()
    {
        $this->callback_data->assertValue(false);

        $this->assertFalse($this->callback_data->makeRequest(true));
    }
}
