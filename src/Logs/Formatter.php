<?php
/**
 * src/Logs/Formatter.php.
 *
 * @author      Austin Heap <me@elemenx.com>
 * @version     v0.1.7
 */
declare(strict_types=1);

namespace ElemenX\Database\InfluxDb\Logs;

use Monolog\Formatter\NormalizerFormatter;

/**
 * Class Formatter.
 */
class Formatter extends NormalizerFormatter
{
    /**
     * {@inheritdoc}
     */
    public function format(array $record)
    {
        $record = parent::format($record);

        return $this->prepareMessage($record);
    }

    /**
     * @param array $record
     *
     * @return array
     */
    protected function prepareTags(array $record): array
    {
        $tags = [];

        if (isset($_SERVER['REMOTE_ADDR'])) {
            $tags['serverName'] = $_SERVER['REMOTE_ADDR'];
        }

        if (isset($record['level'])) {
            $tags['severity'] = $this->rfc5424ToSeverity($record['level']);
        }

        if (isset($_SERVER['REQUEST_URI'])) {
            $tags['endpoint_url'] = $_SERVER['REQUEST_URI'];
        }

        if (isset($_SERVER['REQUEST_METHOD'])) {
            $tags['method'] = $_SERVER['REQUEST_METHOD'];
        }

        if (isset($record['context']['user_id'])) {
            $tags['user_id'] = $record['context']['user_id'];
        }

        if (isset($record['context']['project_id'])) {
            $tags['project_id'] = $record['context']['project_id'];
        }

        if (isset($record['context']['file'])) {
            $tags['file'] = $this->replaceDigitData($record['context']['file']);
        }

        if (isset($record['context']['event']['api_stats'][0])) {
            foreach ($record['context']['event']['api_stats'][0] as $key => $value) {
                if (is_string($value) || is_int($value)) {
                    $tags[$key] = $value;
                }
            }
        }

        return $tags;
    }

    /**
     * @param array $record
     *
     * @return array
     */
    protected function prepareMessage(array $record): array
    {
        $tags = $this->prepareTags($record);
        $message = [
            'name'      => 'Error',
            'value'     => 1,
            'timestamp' => round(microtime(true) * 1000),
        ];

        if (count($tags)) {
            foreach ($tags as $key => $value) {
                if (is_numeric($value)) {
                    $message['fields'][$key] = (int) $value;
                }
            }

            $message['tags'] = $tags;
        }

        if (isset($message['fields']['Debug']['message'])) {
            $message['fields']['Debug']['message'] = $this->trimLines($message['fields']['Debug']['message']);
        }

        return $message;
    }

    /**
     * @param int $level
     *
     * @return mixed
     */
    private function rfc5424ToSeverity(int $level)
    {
        $levels = [
            100 => 'Debugging',
            200 => 'Informational',
            250 => 'Notice',
            300 => 'Warning',
            400 => 'Error',
            500 => 'Critical',
            550 => 'Alert',
            600 => 'Emergency',
        ];

        $result = isset($levels[$level]) ? $levels[$level] : $levels[600];

        return $result;
    }

    /**
     * @param string $string
     *
     * @return string
     */
    private function replaceDigitData(string $string): string
    {
        $string = preg_replace('~\/[0-9]+~', '/*', $string);
        $string = preg_replace('~\=[0-9]+~', '=*', $string);

        return $string;
    }

    /**
     * @param $message
     *
     * @return string
     */
    private function trimLines($message): string
    {
        $limit = config('influxdb.log.limit', 5);

        if (is_int($limit)) {
            $message_array = explode(PHP_EOL, $message);

            if ($limit < count($message_array)) {
                $message = implode(PHP_EOL, array_slice($message_array, 0, $limit));
            }
        }

        return $message;
    }
}
