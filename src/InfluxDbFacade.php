<?php
/**
 * src/InfluxDbFacade.php.
 *
 * @author      Austin Heap <me@elemenx.com>
 * @version     v0.1.7
 */
declare(strict_types=1);

namespace ElemenX\Database\InfluxDb;

use Illuminate\Support\Facades\Facade;
use Illuminate\Queue\InteractsWithQueue;
use ElemenX\Database\InfluxDb\Jobs\Write;
use ElemenX\Database\InfluxDb\Jobs\WritePoints;
use ElemenX\Database\InfluxDb\Jobs\WritePayload;

/**
 * Class InfluxDbFacade.
 */
class InfluxDbFacade extends Facade
{
    use InteractsWithQueue;

    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'InfluxDb';
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public static function __callStatic($method, $arguments)
    {
        switch ($method) {
            case 'write':
            case 'writePoints':
            case 'writePayload':
                return static::$method(...$arguments);
            default:
                return static::getFacadeRoot()
                             ->$method(...$arguments);
        }
    }

    /**
     * @param array $parameters
     * @param string|array $payload
     *
     * @return bool
     */
    public static function write(array $parameters, $payload): bool
    {
        if (config('influxdb.queue.enable', false) === true) {
            Write::dispatch($parameters, $payload)
                 ->onQueue(config('influxdb.queue.name', 'default'));
        } else {
            return static::getFacadeRoot()
                         ->write($parameters, $payload);
        }

        return true;
    }

    /**
     * @param  string|array $payload
     * @param  string       $precision
     * @param  string|null  $retentionPolicy
     *
     * @return bool
     */
    public static function writePayload(
        $payload,
        $precision = WritePayload::PRECISION_SECONDS,
        $retentionPolicy = null
    ): bool {
        if (config('influxdb.queue.enable', false) === true) {
            WritePayload::dispatch($payload, $precision, $retentionPolicy)
                        ->onQueue(config('influxdb.queue.name', 'default'));
        } else {
            return static::getFacadeRoot()
                         ->writePayload($payload, $precision, $retentionPolicy);
        }

        return true;
    }

    /**
     * @param  \InfluxDB\Point[] $points
     * @param  string            $precision
     * @param  string|null       $retentionPolicy
     *
     * @return bool
     */
    public static function writePoints(
        array $points,
        $precision = WritePoints::PRECISION_SECONDS,
        $retentionPolicy = null
    ): bool {
        if (config('influxdb.queue.enable', false) === true) {
            WritePoints::dispatch($points, $precision, $retentionPolicy)
                       ->onQueue(config('influxdb.queue.name', 'default'));
        } else {
            return static::getFacadeRoot()
                         ->writePoints($points, $precision, $retentionPolicy);
        }

        return true;
    }
}
