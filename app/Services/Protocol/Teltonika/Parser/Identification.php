<?php declare(strict_types=1);

namespace App\Services\Protocol\Teltonika\Parser;

use App\Services\Protocol\Resource\Command as CommandResource;

class Identification extends ParserAbstract
{
    /**
     * @return ?\App\Services\Protocol\Resource\Command
     */
    public function resource(): ?CommandResource
    {
        if ($this->bodyIsValid() === false) {
            return null;
        }

        return new CommandResource([
            'body' => $this->body,
            'maker' => $this->maker(),
            'serial' => $this->serial(),
            'type' => $this->type(),
            'payload' => $this->payload(),
            'response' => $this->response(),
        ]);
    }

    /**
     * @return bool
     */
    public function bodyIsValid(): bool
    {
        return hexdec(substr($this->body, 0, 4)) === strlen($this->serial());
    }

    /**
     * @return string
     */
    protected function maker(): string
    {
        return 'Teltonika';
    }

    /**
     * @return string
     */
    protected function serial(): string
    {
        return $this->cache[__FUNCTION__] ??= hex2bin(substr($this->body, 4));
    }

    /**
     * @return string
     */
    protected function type(): string
    {
        return 'identification';
    }

    /**
     * @return array
     */
    protected function payload(): array
    {
        return [];
    }

    /**
     * @return string
     */
    protected function response(): string
    {
        return '01';
    }
}
