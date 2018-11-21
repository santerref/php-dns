<?php
namespace RemotelyLiving\PHPDNS\Entities;

class Hostname extends EntityAbstract
{
    /**
     * @var string
     */
    private $hostname;

    public function __construct(string $hostname)
    {
        $hostname = self::punyCode(mb_strtolower(trim($hostname)));

        if ((bool)filter_var($hostname, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) === false) {
            throw new \InvalidArgumentException("{$hostname} is not a valid hostname");
        }

        $this->hostname = $hostname;
    }

    public function __toString(): string
    {
        return $this->hostname;
    }

    public function equals(Hostname $hostname): bool
    {
        return $this->hostname === (string) $hostname;
    }

    public static function createFromString(string $hostname): Hostname
    {
        return new static($hostname);
    }

    public function getHostName(): string
    {
        return $this->hostname;
    }

    public function isPunycoded(): bool
    {
        return $this->toUTF8() !== $this->hostname;
    }

    public function toUTF8(): string
    {
        return (string)idn_to_utf8($this->hostname, IDNA_ERROR_PUNYCODE, INTL_IDNA_VARIANT_UTS46);
    }

    private static function punyCode(string $hostname): string
    {
        return (string)idn_to_ascii($hostname, IDNA_ERROR_PUNYCODE, INTL_IDNA_VARIANT_UTS46);
    }
}