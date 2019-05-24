<?php
/**
 * src/helpers.php.
 *
 * @author      Austin Heap <me@elemenx.com>
 * @version     v0.1.7
 */
declare(strict_types=1);

if (! function_exists('influxdb')) {
    /**
     * @return \InfluxDB\Client|\InfluxDB\Database
     */
    function influxdb()
    {
        return \ElemenX\Database\InfluxDb\InfluxDbServiceProvider::getInstance();
    }
}
