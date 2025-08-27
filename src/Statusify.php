<?php

namespace UthayakumarDinesh\Statusify;

class Statusify
{
  // 1xx Informational
  public const CONTINUE = 100;
  public const SWITCHING_PROTOCOLS = 101;

  // 2xx Success
  public const OK = 200;
  public const CREATED = 201;
  public const ACCEPTED = 202;
  public const NO_CONTENT = 204;

  // 3xx Redirection
  public const MOVED_PERMANENTLY = 301;
  public const FOUND = 302;
  public const NOT_MODIFIED = 304;

  // 4xx Client Errors
  public const BAD_REQUEST = 400;
  public const UNAUTHORIZED = 401;
  public const FORBIDDEN = 403;
  public const NOT_FOUND = 404;
  public const METHOD_NOT_ALLOWED = 405;

  // 5xx Server Errors
  public const INTERNAL_SERVER_ERROR = 500;
  public const NOT_IMPLEMENTED = 501;
  public const BAD_GATEWAY = 502;
  public const SERVICE_UNAVAILABLE = 503;

  // Reverse lookup: get status name by code
  public static function getStatusName(int $code): string
  {
    $map = [
      100 => 'CONTINUE',
      101 => 'SWITCHING_PROTOCOLS',
      200 => 'OK',
      201 => 'CREATED',
      202 => 'ACCEPTED',
      204 => 'NO_CONTENT',
      301 => 'MOVED_PERMANENTLY',
      302 => 'FOUND',
      304 => 'NOT_MODIFIED',
      400 => 'BAD_REQUEST',
      401 => 'UNAUTHORIZED',
      403 => 'FORBIDDEN',
      404 => 'NOT_FOUND',
      405 => 'METHOD_NOT_ALLOWED',
      500 => 'INTERNAL_SERVER_ERROR',
      501 => 'NOT_IMPLEMENTED',
      502 => 'BAD_GATEWAY',
      503 => 'SERVICE_UNAVAILABLE'
    ];

    return $map[$code] ?? 'UNKNOWN_STATUS';
  }
}
