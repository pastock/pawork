<?php

namespace App\Commands;

use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use MilesChou\Codegener\Traits\Path;

use function collect;
use function mb_convert_encoding;
use function mb_detect_encoding;

class Update extends Command
{
    use Path;

    /**
     * @var string
     */
    private $template = <<< 'EOF'
<?php

namespace Pastock\Pawork;

/**
 * NOTE: THIS SOURCE CODE IS GENERATED VIA "App\Commands\Twnic" COMMAND
 *
 * PLEASE DO NOT EDIT IT DIRECTLY.
 */
class Database
{
    public const UPDATED_AT = '%s';

    /**
     * @array
     */
    private static $raw = [
%s
    ];

    public static function all(): array
    {
        return static::$raw;
    }
}

EOF;

    /**
     * @var string
     */
    private $templateLine = "        '%s',";

    protected $signature = 'update {target}';

    protected $description = 'parse working day';

    public function handle(): int
    {
        $target = $this->formatPath($this->argument('target'));

        $content = file_get_contents($target);

        if ('BIG-5' === mb_detect_encoding($content, 'BIG-5', true)) {
            $content = mb_convert_encoding($content, 'UTF-8', 'BIG-5');
        }

        $data = collect(explode("\n", $content))
            ->map(function ($v) {
                return explode(',', trim($v));
            })
            ->filter(function ($v) {
                return isset($v[2]) && $v[2] === '2';
            })
            ->map(function ($v) {
                $v[0] = sprintf('%s-%s-%s',
                    Str::substr($v[0], 0, 4),
                    Str::substr($v[0], 4, 2),
                    Str::substr($v[0], 6, 2)
                );

                return $v[0];
            })
            ->values();

        $this->generateCode($data->toArray());

        return 0;
    }

    private function generateCode(array $data): void
    {
        $templateCode = '';

        foreach ($data as $item) {
            $code = sprintf(
                $this->templateLine,
                $item
            );

            $templateCode .= $code . PHP_EOL;
        }

        $newContent = sprintf(
            $this->template,
            (new DateTime())->format('Y-m-d H:i:s'),
            rtrim($templateCode)
        );

        file_put_contents(__DIR__ . '/../../src/Pawork/Database.php', $newContent);
    }
}