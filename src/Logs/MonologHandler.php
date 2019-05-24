<?php
/**
 * src/Logs/MonologHandler.php.
 *
 * @author      Austin Heap <me@elemenx.com>
 * @version     v0.1.7
 */
declare(strict_types=1);

namespace ElemenX\Database\InfluxDb\Logs;

use Monolog\Logger;
use Monolog\Handler\AbstractProcessingHandler;

/**
 * Class MonologHandler.
 */
class MonologHandler extends AbstractProcessingHandler
{
    /**
     * InfluxDbMonologHandler constructor.
     *
     * @param int  $level
     * @param bool $bubble
     */
    public function __construct($level = Logger::DEBUG, $bubble = true)
    {
        parent::__construct($level, $bubble);
    }

    /**
     * @param array $record
     *
     * @return void
     */
    protected function write(array $record): void
    {
        parent::write($record);
    }
}
