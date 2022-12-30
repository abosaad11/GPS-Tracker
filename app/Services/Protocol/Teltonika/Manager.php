<?php declare(strict_types=1);

namespace App\Services\Protocol\Teltonika;

use App\Services\Protocol\ProtocolAbstract;
use App\Services\Protocol\Resource\ResourceAbstract;
use App\Services\Protocol\Teltonika\Parser\Identification as IdentificationParser;
use App\Services\Protocol\Teltonika\Parser\Location as LocationParser;
use App\Services\Server\Socket\Server;

class Manager extends ProtocolAbstract
{
    /**
     * @const array
     */
    protected const PARSERS = [
        IdentificationParser::class,
        LocationParser::class,
    ];

    /**
     * @return string
     */
    public function code(): string
    {
        return 'teltonika';
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return 'Teltonika';
    }

    /**
     * @param int $port
     *
     * @return \App\Services\Server\Socket\Server
     */
    public function server(int $port): Server
    {
        return Server::new($port)
            ->socketType('stream')
            ->socketProtocol('ip');
    }

    /**
     * @param string $body
     *
     * @return array
     */
    public function resources(string $body): array
    {
        return array_filter([$this->resource($body)]);
    }

    /**
     * @param string $body
     *
     * @return ?\App\Services\Protocol\Resource\ResourceAbstract
     */
    public function resource(string $body): ?ResourceAbstract
    {
        $body = $this->body($body);

        foreach (static::PARSERS as $parser) {
            if (($resource = $parser::new($body)->resource()) && $resource->isValid()) {
                return $resource;
            }
        }

        return null;
    }

    /**
     * @param string $body
     *
     * @return string
     */
    protected function body(string $body): string
    {
        return trim(explode("\n", $body)[0] ?? '');
    }
}
