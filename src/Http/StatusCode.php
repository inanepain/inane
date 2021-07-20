<?php

declare(strict_types=1);

namespace Inane\Http;

use Inane\Type\Enum;

/**
 * StatusCode
 * 
 * @method static self CONTINUE()
 * @method static self SWITCHING_PROTOCOLS()
 * @method static self PROCESSING()
 * @method static self OK()
 * @method static self CREATED()
 * @method static self ACCEPTED()
 * @method static self NON_AUTHORITATIVE_INFORMATION()
 * @method static self NO_CONTENT()
 * @method static self RESET_CONTENT()
 * @method static self PARTIAL_CONTENT()
 * @method static self MULTI_STATUS()
 * @method static self ALREADY_REPORTED()
 * @method static self IM_USED()
 * @method static self MULTIPLE_CHOICES()
 * @method static self MOVED_PERMANENTLY()
 * @method static self FOUND()
 * @method static self SEE_OTHER()
 * @method static self NOT_MODIFIED()
 * @method static self USE_PROXY()
 * @method static self SWITCH_PROXY()
 * @method static self TEMPORARY_REDIRECT()
 * @method static self PERMANENT_REDIRECT()
 * @method static self RESUME_INCOMPLETE()
 * @method static self BAD_REQUEST()
 * @method static self UNAUTHORIZED()
 * @method static self PAYMENT_REQUIRED()
 * @method static self FORBIDDEN()
 * @method static self NOT_FOUND()
 * @method static self METHOD_NOT_ALLOWED()
 * @method static self NOT_ACCEPTABLE()
 * @method static self PROXY_AUTHENTICATION_REQUIRED()
 * @method static self REQUEST_TIMEOUT()
 * @method static self CONFLICT()
 * @method static self GONE()
 * @method static self LENGTH_REQUIRED()
 * @method static self PRECONDITION_FAILED()
 * @method static self REQUEST_ENTITY_TOO_LARGE()
 * @method static self REQUEST_URI_TOO_LONG()
 * @method static self UNSUPPORTED_MEDIA_TYPE()
 * @method static self REQUESTED_RANGE_NOT_SATISFIABLE()
 * @method static self EXPECTATION_FAILED()
 * @method static self IM_A_TEAPOT()
 * @method static self MISDIRECTED_REQUEST()
 * @method static self UNPROCESSABLE_ENTITY()
 * @method static self LOCKED()
 * @method static self FAILED_DEPENDENCY()
 * @method static self UPGRADE_REQUIRED()
 * @method static self PRECONDITION_REQUIRED()
 * @method static self TOO_MANY_REQUESTS()
 * @method static self REQUEST_HEADER_FIELDS_TOO_LARGE()
 * @method static self LOGIN_TIMEOUT()
 * @method static self NO_RESPONSE()
 * @method static self RETRY_WITH()
 * @method static self BLOCKED_BY_WINDOWS_PARENTAL_CONTROLS()
 * @method static self UNAVAILABLE_FOR_LEGAL_REASONS()
 * @method static self REDIRECT()
 * @method static self REQUEST_HEADER_TOO_LARGE()
 * @method static self CERT_ERROR()
 * @method static self NO_CERT()
 * @method static self HTTP_TO_HTTPS()
 * @method static self TOKEN_EXPIRED_INVALID()
 * @method static self CLIENT_CLOSED_REQUEST()
 * @method static self TOKEN_REQUIRED()
 * @method static self INTERNAL_SERVER_ERROR()
 * @method static self NOT_IMPLEMENTED()
 * @method static self BAD_GATEWAY()
 * @method static self SERVICE_UNAVAILABLE()
 * @method static self GATEWAY_TIMEOUT()
 * @method static self HTTP_VERSION_NOT_SUPPORTED()
 * @method static self VARIANT_ALSO_NEGOTIATES()
 * @method static self INSUFFICIENT_STORAGE()
 * @method static self LOOP_DETECTED()
 * @method static self BANDWIDTH_LIMIT_EXCEEDED()
 * @method static self NOT_EXTENDED()
 * @method static self NETWORK_AUTHENTICATION_REQUIRED()
 * @method static self UNKNOWN_ERROR()
 * @method static self WEB_SERVER_IS_DOWN()
 * @method static self CONNECTION_TIMED_OUT()
 * @method static self ORIGIN_IS_UNREACHABLE()
 * @method static self A_TIMEOUT_OCCURRED()
 * @method static self SSL_HANDSHAKE_FAILED()
 * @method static self INVALID_SSL_CERTIFICATE()
 * @method static self RAILGUN_ERROR()
 * 
 * @version 0.9.0
 * 
 * @package Http
 */
class StatusCode extends Enum {
    // 1xx Informational
    const CONTINUE = 100;
    const SWITCHING_PROTOCOLS = 101;
    const PROCESSING = 102;

    // 2xx Success
    const OK = 200;
    const CREATED = 201;
    const ACCEPTED = 202;
    const NON_AUTHORITATIVE_INFORMATION = 203;
    const NO_CONTENT = 204;
    const RESET_CONTENT = 205;
    const PARTIAL_CONTENT = 206;
    const MULTI_STATUS = 207;
    const ALREADY_REPORTED = 208;
    const IM_USED = 226;

    // 3xx Redirection
    const MULTIPLE_CHOICES = 300;
    const MOVED_PERMANENTLY = 301;
    const FOUND = 302;
    const SEE_OTHER = 303;
    const NOT_MODIFIED = 304;
    const USE_PROXY = 305;
    const SWITCH_PROXY = 306;
    const TEMPORARY_REDIRECT = 307;
    const PERMANENT_REDIRECT = 308;
    const RESUME_INCOMPLETE = 308;

    // 4xx Client Error
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const PAYMENT_REQUIRED = 402;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const NOT_ACCEPTABLE = 406;
    const PROXY_AUTHENTICATION_REQUIRED = 407;
    const REQUEST_TIMEOUT = 408;
    const CONFLICT = 409;
    const GONE = 410;
    const LENGTH_REQUIRED = 411;
    const PRECONDITION_FAILED = 412;
    const REQUEST_ENTITY_TOO_LARGE = 413;
    const REQUEST_URI_TOO_LONG = 414;
    const UNSUPPORTED_MEDIA_TYPE = 415;
    const REQUESTED_RANGE_NOT_SATISFIABLE = 416;
    const EXPECTATION_FAILED = 417;
    const IM_A_TEAPOT = 418;
    const MISDIRECTED_REQUEST = 421;
    const UNPROCESSABLE_ENTITY = 422;
    const LOCKED = 423;
    const FAILED_DEPENDENCY = 424;
    const UPGRADE_REQUIRED = 426;
    const PRECONDITION_REQUIRED = 428;
    const TOO_MANY_REQUESTS = 429;
    const REQUEST_HEADER_FIELDS_TOO_LARGE = 431;
    const LOGIN_TIMEOUT = 440;
    const NO_RESPONSE = 444;
    const RETRY_WITH = 449;
    const BLOCKED_BY_WINDOWS_PARENTAL_CONTROLS = 450;
    const UNAVAILABLE_FOR_LEGAL_REASONS = 451;
    const REDIRECT = 451;
    const REQUEST_HEADER_TOO_LARGE = 494;
    const CERT_ERROR = 495;
    const NO_CERT = 496;
    const HTTP_TO_HTTPS = 497;
    const TOKEN_EXPIRED_INVALID = 498;
    const CLIENT_CLOSED_REQUEST = 499;
    const TOKEN_REQUIRED = 499;

    // 5xx Server Error
    const INTERNAL_SERVER_ERROR = 500;
    const NOT_IMPLEMENTED = 501;
    const BAD_GATEWAY = 502;
    const SERVICE_UNAVAILABLE = 503;
    const GATEWAY_TIMEOUT = 504;
    const HTTP_VERSION_NOT_SUPPORTED = 505;
    const VARIANT_ALSO_NEGOTIATES = 506;
    const INSUFFICIENT_STORAGE = 507;
    const LOOP_DETECTED = 508;
    const BANDWIDTH_LIMIT_EXCEEDED = 509;
    const NOT_EXTENDED = 510;
    const NETWORK_AUTHENTICATION_REQUIRED = 511;
    const UNKNOWN_ERROR = 520;
    const WEB_SERVER_IS_DOWN = 521;
    const CONNECTION_TIMED_OUT = 522;
    const ORIGIN_IS_UNREACHABLE = 523;
    const A_TIMEOUT_OCCURRED = 524;
    const SSL_HANDSHAKE_FAILED = 525;
    const INVALID_SSL_CERTIFICATE = 526;
    const RAILGUN_ERROR = 527;

    /**
     * @var string[] the descriptions
     */
    protected static $descriptions = [
        self::CONTINUE => "The server has received the request headers, and that the client should proceed to send the request body.",
        self::SWITCHING_PROTOCOLS => "The requester has asked the server to switch protocols and the server is acknowledging that it will do so.",
        self::PROCESSING => "The server has received and is processing the request, but no response is available yet.",
        self::OK => "The standard response for successful HTTP requests.",
        self::CREATED => "The request has been fulfilled and a new resource has been created.",
        self::ACCEPTED => "The request has been accepted but has not been processed yet. This code does not guarantee that the request will process successfully.",
        self::NON_AUTHORITATIVE_INFORMATION => "HTTP 1.1. The server successfully processed the request but is returning information from another source.",
        self::NO_CONTENT => "The server accepted the request but is not returning any content. This is often used as a response to a DELETE request.",
        self::RESET_CONTENT => "Similar to a 204 No Content response but this response requires the requester to reset the document view.",
        self::PARTIAL_CONTENT => "The server is delivering only a portion of the content, as requested by the client via a range header.",
        self::MULTI_STATUS => "The message body that follows is an XML message and can contain a number of separate response codes, depending on how many sub-requests were made.",
        self::ALREADY_REPORTED => "The members of a DAV binding have already been enumerated in a previous reply to this request, and are not being included again.",
        self::IM_USED => "The server has fulfilled a GET request for the resource, and the response is a representation of the result of one or more instance-manipulations applied to the current instance.",
        self::MULTIPLE_CHOICES => "There are multiple options that the client may follow.",
        self::MOVED_PERMANENTLY => "The resource has been moved and all further requests should reference its new URI.",
        self::FOUND => "The HTTP 1.0 specification described this status as \"Moved Temporarily\", but popular browsers respond to this status similar to behavior intended for 303. The resource can be retrieved by referencing the returned URI.",
        self::SEE_OTHER => "The resource can be retrieved by following other URI using the GET method. When received in response to a POST, PUT, or DELETE, it can usually be assumed that the server processed the request successfully and is sending the client to an informational endpoint.",
        self::NOT_MODIFIED => "The resource has not been modified since the version specified in If-Modified-Since or If-Match headers. The resource will not be returned in response body.",
        self::USE_PROXY => "HTTP 1.1. The resource is only available through a proxy and the address is provided in the response.",
        self::SWITCH_PROXY => "Deprecated in HTTP 1.1. Used to mean that subsequent requests should be sent using the specified proxy.",
        self::TEMPORARY_REDIRECT => "HTTP 1.1. The request should be repeated with the URI provided in the response, but future requests should still call the original URI.",
        self::PERMANENT_REDIRECT => "Experimental. The request and all future requests should be repeated with the URI provided in the response. The HTTP method is not allowed to be changed in the subsequent request.",
        self::RESUME_INCOMPLETE => "This code is used in the Resumable HTTP Requests Proposal to resume aborted PUT or POST requests",
        self::BAD_REQUEST => "The request could not be fulfilled due to the incorrect syntax of the request.",
        self::UNAUTHORIZED => "The requester is not authorized to access the resource. This is similar to 403 but is used in cases where authentication is expected but has failed or has not been provided.",
        self::PAYMENT_REQUIRED => "Reserved for future use. Some web services use this as an indication that the client has sent an excessive number of requests.",
        self::FORBIDDEN => "The request was formatted correctly but the server is refusing to supply the requested resource. Unlike 401, authenticating will not make a difference in the server's response.",
        self::NOT_FOUND => "The resource could not be found. This is often used as a catch-all for all invalid URIs requested of the server.",
        self::METHOD_NOT_ALLOWED => "The resource was requested using a method that is not allowed. For example, requesting a resource via a POST method when the resource only supports the GET method.",
        self::NOT_ACCEPTABLE => "The resource is valid, but cannot be provided in a format specified in the Accept headers in the request.",
        self::PROXY_AUTHENTICATION_REQUIRED => "Authentication is required with the proxy before requests can be fulfilled.",
        self::REQUEST_TIMEOUT => "The server timed out waiting for a request from the client. The client is allowed to repeat the request.",
        self::CONFLICT => "The request cannot be completed due to a conflict in the request parameters.",
        self::GONE => "The resource is no longer available at the requested URI and no redirection will be given.",
        self::LENGTH_REQUIRED => "The request did not specify the length of its content as required by the resource.",
        self::PRECONDITION_FAILED => "The server does not meet one of the preconditions specified by the client.",
        self::REQUEST_ENTITY_TOO_LARGE => "The request is larger than what the server is able to process.",
        self::REQUEST_URI_TOO_LONG => "The URI provided in the request is too long for the server to process. This is often used when too much data has been encoded into the URI of a GET request and a POST request should be used instead.",
        self::UNSUPPORTED_MEDIA_TYPE => "The client provided data with a media type that the server does not support.",
        self::REQUESTED_RANGE_NOT_SATISFIABLE => "The client has asked for a portion of the resource but the server cannot supply that portion.",
        self::EXPECTATION_FAILED => "The server cannot meet the requirements of the Expect request-header field.",
        self::IM_A_TEAPOT => "Any attempt to brew coffee with a teapot should result in the error code \"418 I'm a teapot\". The resulting entity body MAY be short and stout.",
        self::MISDIRECTED_REQUEST => "The request was directed at a server that is not able to produce a response. This can be sent by a server that is not configured to produce responses for the combination of scheme and authority that are included in the request URI.",
        self::UNPROCESSABLE_ENTITY => "The request was formatted correctly but cannot be processed in its current form. Often used when the specified parameters fail validation errors.",
        self::LOCKED => "The requested resource was found but has been locked and will not be returned.",
        self::FAILED_DEPENDENCY => "The request failed due to a failure of a previous request.",
        self::UPGRADE_REQUIRED => "The client should repeat the request using an upgraded protocol such as TLS 1.0.",
        self::PRECONDITION_REQUIRED => "The origin server requires the request to be conditional.",
        self::TOO_MANY_REQUESTS => "The user has sent too many requests in a given amount of time (\"rate limiting\").",
        self::REQUEST_HEADER_FIELDS_TOO_LARGE => "The server is unwilling to process the request because its header fields are too large.",
        self::LOGIN_TIMEOUT => "A Microsoft extension. Indicates that your session has expired.",
        self::NO_RESPONSE => "Used in Nginx logs to indicate that the server has returned no information to the client and closed the connection (useful as a deterrent for malware).",
        self::RETRY_WITH => "A Microsoft extension. The request should be retried after performing the appropriate action.",
        self::BLOCKED_BY_WINDOWS_PARENTAL_CONTROLS => "A Microsoft extension. This error is given when Windows Parental Controls are turned on and are blocking access to the given webpage.",
        self::UNAVAILABLE_FOR_LEGAL_REASONS => "A server operator has received a legal demand to deny access to a resource or to a set of resources that includes the requested resource.",
        self::REDIRECT => "Used in Exchange ActiveSync if there either is a more efficient server to use or the server cannot access the users' mailbox.",
        self::REQUEST_HEADER_TOO_LARGE => "Nginx internal code similar to 431 but it was introduced earlier in version 0.9.4 (on January 21, 2011).",
        self::CERT_ERROR => "Nginx internal code used when SSL client certificate error occurred to distinguish it from 4XX in a log and an error page redirection.",
        self::NO_CERT => "Nginx internal code used when client didn't provide certificate to distinguish it from 4XX in a log and an error page redirection.",
        self::HTTP_TO_HTTPS => "Nginx internal code used for the plain HTTP requests that are sent to HTTPS port to distinguish it from 4XX in a log and an error page redirection.",
        self::TOKEN_EXPIRED_INVALID => "Returned by ArcGIS for Server. A code of 498 indicates an expired or otherwise invalid token.",
        self::CLIENT_CLOSED_REQUEST => "Used in Nginx logs to indicate when the connection has been closed by client while the server is still processing its request, making server unable to send a status code back.",
        self::TOKEN_REQUIRED => "Returned by ArcGIS for Server. A code of 499 indicates that a token is required (if no token was submitted).",
        self::INTERNAL_SERVER_ERROR => "A generic status for an error in the server itself.",
        self::NOT_IMPLEMENTED => "The server cannot respond to the request. This usually implies that the server could possibly support the request in the future — otherwise a 4xx status may be more appropriate.",
        self::BAD_GATEWAY => "The server is acting as a proxy and did not receive an acceptable response from the upstream server.",
        self::SERVICE_UNAVAILABLE => "The server is down and is not accepting requests.",
        self::GATEWAY_TIMEOUT => "The server is acting as a proxy and did not receive a response from the upstream server.",
        self::HTTP_VERSION_NOT_SUPPORTED => "The server does not support the HTTP protocol version specified in the request.",
        self::VARIANT_ALSO_NEGOTIATES => "Transparent content negotiation for the request results in a circular reference.",
        self::INSUFFICIENT_STORAGE => "The user or server does not have sufficient storage quota to fulfill the request.",
        self::LOOP_DETECTED => "The server detected an infinite loop in the request.",
        self::BANDWIDTH_LIMIT_EXCEEDED => "This status code is not specified in any RFCs. Its use is unknown.",
        self::NOT_EXTENDED => "Further extensions to the request are necessary for it to be fulfilled.",
        self::NETWORK_AUTHENTICATION_REQUIRED => "The client must authenticate with the network before sending requests.",
        self::UNKNOWN_ERROR => "This status code is not specified in any RFC and is returned by certain services, for instance Microsoft Azure and CloudFlare servers: \"The 520 error is essentially a \"catch-all\" response for when the origin server returns something unexpected or something that is not tolerated/interpreted (protocol violation or empty response).\"",
        self::WEB_SERVER_IS_DOWN => "The origin server has refused the connection from CloudFlare.",
        self::CONNECTION_TIMED_OUT => "CloudFlare could not negotiate a TCP handshake with the origin server.",
        self::ORIGIN_IS_UNREACHABLE => "CloudFlare could not reach the origin server; for example, if the DNS records for the origin server are incorrect.",
        self::A_TIMEOUT_OCCURRED => "CloudFlare was able to complete a TCP connection to the origin server, but did not receive a timely HTTP response.",
        self::SSL_HANDSHAKE_FAILED => "CloudFlare could not negotiate a SSL/TLS handshake with the origin server.",
        self::INVALID_SSL_CERTIFICATE => "CloudFlare could not validate the SSL/TLS certificate that the origin server presented.",
        self::RAILGUN_ERROR => "The request timed out or failed after the WAN connection has been established.",
    ];

    /**
     * @var string[] the descriptions
     */
    protected static array $defaults = [
        self::CONTINUE => 'Continue',
        self::SWITCHING_PROTOCOLS => 'Switching protocols',
        self::PROCESSING => 'Processing',
        self::OK => 'HTTP/1.1 200 OK', // FINAL
        self::CREATED => 'Created',
        self::ACCEPTED => 'Accepted',
        self::NON_AUTHORITATIVE_INFORMATION => 'Non authoritative information',
        self::NO_CONTENT => 'No content',
        self::RESET_CONTENT => 'Reset content',
        self::PARTIAL_CONTENT => 'HTTP/1.1 206 Patial Content', // FINAL
        self::MULTI_STATUS => 'MULTI Status',
        self::ALREADY_REPORTED => 'Already reported',
        self::IM_USED => 'Im used',
        self::MULTIPLE_CHOICES => 'Multiple choices',
        self::MOVED_PERMANENTLY => 'Moved permanently',
        self::FOUND => 'Found',
        self::SEE_OTHER => 'See other',
        self::NOT_MODIFIED => 'Not modified',
        self::USE_PROXY => 'Use proxy',
        self::SWITCH_PROXY => 'Switch proxy',
        self::TEMPORARY_REDIRECT => 'Temporary redirect',
        self::PERMANENT_REDIRECT => 'Permanent redirect',
        self::RESUME_INCOMPLETE => 'Resume incomplete',
        self::BAD_REQUEST => 'Bad request',
        self::UNAUTHORIZED => 'Unauthorized',
        self::PAYMENT_REQUIRED => 'Payment required',
        self::FORBIDDEN => 'Forbidden',
        self::NOT_FOUND => 'Not found',
        self::METHOD_NOT_ALLOWED => 'Method not allowed',
        self::NOT_ACCEPTABLE => 'Not acceptable',
        self::PROXY_AUTHENTICATION_REQUIRED => 'Proxy authentication required',
        self::REQUEST_TIMEOUT => 'Request timeout',
        self::CONFLICT => 'Conflict',
        self::GONE => 'Gone',
        self::LENGTH_REQUIRED => 'Length required',
        self::PRECONDITION_FAILED => 'Precondition failed',
        self::REQUEST_ENTITY_TOO_LARGE => 'Request entity too large',
        self::REQUEST_URI_TOO_LONG => 'REQUEST-URI TOO Long',
        self::UNSUPPORTED_MEDIA_TYPE => 'Unsupported media type',
        self::REQUESTED_RANGE_NOT_SATISFIABLE => 'Requested range not satisfiable',
        self::EXPECTATION_FAILED => 'Expectation failed',
        self::IM_A_TEAPOT => 'I\'m a teapot',
        self::MISDIRECTED_REQUEST => 'Misdirected request',
        self::UNPROCESSABLE_ENTITY => 'Unprocessable entity',
        self::LOCKED => 'Locked',
        self::FAILED_DEPENDENCY => 'Failed dependency',
        self::UPGRADE_REQUIRED => 'Upgrade required',
        self::PRECONDITION_REQUIRED => 'Precondition required',
        self::TOO_MANY_REQUESTS => 'Too many requests',
        self::REQUEST_HEADER_FIELDS_TOO_LARGE => 'Request header fields too large',
        self::LOGIN_TIMEOUT => 'Login timeout',
        self::NO_RESPONSE => 'No response',
        self::RETRY_WITH => 'Retry with',
        self::BLOCKED_BY_WINDOWS_PARENTAL_CONTROLS => 'Blocked by windows parental controls',
        self::UNAVAILABLE_FOR_LEGAL_REASONS => 'Unavailable for legal reasons',
        self::REDIRECT => 'Redirect',
        self::REQUEST_HEADER_TOO_LARGE => 'Request header too large',
        self::CERT_ERROR => 'Cert error',
        self::NO_CERT => 'No cert',
        self::HTTP_TO_HTTPS => 'Http to https',
        self::TOKEN_EXPIRED_INVALID => 'Token expired invalid',
        self::CLIENT_CLOSED_REQUEST => 'Client closed request',
        self::TOKEN_REQUIRED => 'Token required',
        self::INTERNAL_SERVER_ERROR => 'Internal server error',
        self::NOT_IMPLEMENTED => 'Not implemented',
        self::BAD_GATEWAY => 'Bad gateway',
        self::SERVICE_UNAVAILABLE => 'Service unavailable',
        self::GATEWAY_TIMEOUT => 'Gateway timeout',
        self::HTTP_VERSION_NOT_SUPPORTED => 'Http version not supported',
        self::VARIANT_ALSO_NEGOTIATES => 'Variant also negotiates',
        self::INSUFFICIENT_STORAGE => 'Insufficient storage',
        self::LOOP_DETECTED => 'Loop detected',
        self::BANDWIDTH_LIMIT_EXCEEDED => 'Bandwidth limit exceeded',
        self::NOT_EXTENDED => 'Not extended',
        self::NETWORK_AUTHENTICATION_REQUIRED => 'Network authentication required',
        self::UNKNOWN_ERROR => 'Unknown error',
        self::WEB_SERVER_IS_DOWN => 'Web server is down',
        self::CONNECTION_TIMED_OUT => 'Connection timed out',
        self::ORIGIN_IS_UNREACHABLE => 'Origin is unreachable',
        self::A_TIMEOUT_OCCURRED => 'A timeout occurred',
        self::SSL_HANDSHAKE_FAILED => 'Ssl handshake failed',
        self::INVALID_SSL_CERTIFICATE => 'Invalid ssl certificate',
        self::RAILGUN_ERROR => 'Railgun error',
    ];
}
