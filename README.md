
### Using

```php
use Tiagof2\IntegrationCallback\Feedback;

$request_url = 'http://fake-url.com/notify_status';

$callback_data = new Feedback($callback_data_values = [
    'protocol'          => 'my-protocol-id', //'required',
    'url'               => $request_url,     //'required|url|min:5',
    'method'            => 'post',           //HTTP verbs (get/post/put/patch)
]);

$success            = true; //bool
$optional_message   = 'The account has been created successfuly';

if($callback_data->isValid())
{
    $callback_data->makeRequest($success, $optional_message);
}
```

## Tests

> To use ins test, just pass to second parameter `true` or call the `fake()` method


```php
use Tiagof2\IntegrationCallback\Feedback;

$fake_url = 'http://fake-url.com/notify_status';
$is_fake  = true;//default false

$callback_data = new Feedback($callback_data_values = [
    'protocol'          => 'my-protocol-id', //'required',
    'url'               => $fake_url,        //'required|url|min:5',
    'method'            => 'post',           //HTTP verbs (get/post/put/patch)
], $is_fake);

//Using the fake() method
$callback_data->fake();

// Check if is a test
$this->assertTrue($callback_data->isFake());

//Setting assert return value to true
$callback_data->assertValue(true);//bool

$success            = true; //bool
$optional_message   = 'The account has been created successfuly';
$this->assertTrue($callback_data->makeRequest($success, $optional_message));//returns true (will not make the realy request)

//Setting assert return value to false
$callback_data->assertValue(false);//bool
$this->assertFalse($callback_data->makeRequest($success, $optional_message));//returns false (will not make the realy request)

```
