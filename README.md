# ScaleEngine API Client
A [ScaleEngine API][scaleengine-api] Client.

[![Build Status](http://img.shields.io/travis/flocasts/scaleengine-api-client.svg?style=flat)](https://travis-ci.org/flocasts/scaleengine-api-client)
[![Scrutinizer Code Quality](http://img.shields.io/scrutinizer/g/flocasts/scaleengine-api-client.svg?style=flat)](https://scrutinizer-ci.com/g/flocasts/scaleengine-api-client/)
[![Code Coverage](http://img.shields.io/coveralls/flocasts/scaleengine-api-client.svg?style=flat)](https://coveralls.io/r/flocasts/scaleengine-api-client)

[![Latest Stable Version](http://img.shields.io/packagist/v/flosports/scaleengine-api-client.svg?style=flat)](https://packagist.org/packages/flosports/scaleengine-api-client)
[![Total Downloads](http://img.shields.io/packagist/dt/flosports/scaleengine-api-client.svg?style=flat)](https://packagist.org/packages/flosports/scaleengine-api-client)
[![License](http://img.shields.io/packagist/l/flosports/scaleengine-api-client.svg?style=flat)](https://packagist.org/packages/flosports/scaleengine-api-client)

## Requirements
This library requires PHP 5.6, or newer.

## Installation
This package uses [composer](https://getcomposer.org) so you can just add
`flocasts/scaleengine-api-client` as a dependency to your `composer.json` file
or execute the following command:

```bash
composer require flocasts/scaleengine-api-client
```

## Usage
The ScaleEngine API client is a typical [Guzzle 3 service
client][guzzle-service-client] and can be used as such.  The API secret (used
to sign requests) has to be provided on creation using the factory, and that
is also a good time to specify other parameters that are used on all requests:
the API key and the CDN, for instance.

Once the client is created, executing commands is as simple as calling the
command on the client.  Any errors (missing parameters, connectivity, API
error responses, etc.) will cause an exception to be thrown.  Only successful
responses will return back the result.  For API calls that have a result, that
result will be converted to a model class specific for that result (e.g., a
call to [`requestTicket`](#request-ticket) will return a
[`ScaleEngineTicket`][scaleengine-ticket] model).

```php
<?php
$service = ScaleEngineClient::factory([
    'apiSecret' => 'YOUR_API_SECRET',
    'command.params' => [
        'apiKey' => 'YOUR_API_KEY',
        'cdn' => 1234,
    ]
]);

$result = $service->getTicketStatus(['key' => 'some SEVU ticket']);
var_dump($result->toArray());
```

## Implemented Commands
Currently, not all of the commands that ScaleEngine offers via their API are
implemented in this client.  The below commands are the only ones currently
implemented.  You can view the full definitions of the commands implemented in
the [service defintion][service-definition].

### Get Ticket Status
> [Get Ticket Status][scaleengine-sevu-status]: This API finds the status of a
> SEVU Ticket. This system allows you to lookup the status of any SEVU Ticket
> created by your account using the SEVU Ticket key that was returned by
> calling sevu.request. The ticket will show the criteria it was created with
> as well as information on last used date, # of uses remaining, and active
> status.

The `getTicketStatus` request takes a ticket key as a parameter and returns a
[`ScaleEngineTicket`][scaleengine-ticket] as a response.

```php
<?php
$result = $service->getTicketStatus(['key' => 'some SEVU ticket']);
var_dump($result->toArray());
```

### Request Ticket
> [Request Ticket][scaleengine-sevu-request] This API creates a SEVU ticket.
> This ticket allows a user to view a protected video. This system allows you
> to restrict access to a specific video or subset of videos, to a specific IP
> address, timeframe and usage count.

The `requestTicket` request takes the parameters `app`, `expires`, `ip`,
`uses`, `pass`, and `video` and returns a newly created
[`ScaleEngineTicket`][scaleengine-ticket] as a response.  Note that all of the
fields have to be specified according to ScaleEngine's API.  For instance, you
can't specify a `DateTime` object for expires, but have to use a UTC time
string like `2016-01-01 08:35:00`.

```php
<?php
$result = $service->requestTicket(
    [
        'app' => 'app string',
        'expires' => '2016-01-01 08:35:00',
        'ip' => 'auto/24',
        'uses' => 5,
        'pass' => 'random pass',
        'video' => 'video string',
    ]
);
var_dump($result->toArray());
```

### Revoke Ticket
> [Revoke Ticket][scaleengine-sevu-revoke]: This API revokes a SEVU ticket.
> Once revoked the ticket can no longer be used, and a new ticket will be
> required for the user to continue viewing protected videos.

The `revokeTicket` request takes a ticket key as a parameter and has no
response (other than throwing an error on failure).

```php
<?php
$service->revokeTicket(['key' => 'some SEVU ticket']);
```

## License
scaleengine-api-client is licensed under the MIT license.  See
[LICENSE](LICENSE) for the full license text.

[guzzle-service-client]: http://guzzle3.readthedocs.org/webservice-client/webservice-client.html
[scaleengine-api]: https://cp.scaleengine.net/docs/api/
[scaleengine-sevu-request]: https://cp.scaleengine.net/docs/api/sevu/request
[scaleengine-sevu-revoke]: https://cp.scaleengine.net/docs/api/sevu/revoke
[scaleengine-sevu-status]: https://cp.scaleengine.net/docs/api/sevu/status
[scaleengine-ticket]: src/Model/ScaleEngineTicket.php
[service-definition]: src/service/main.json
