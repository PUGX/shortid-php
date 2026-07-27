<?php

namespace PUGX\Shortid;

use Random\Randomizer;

final class Factory
{
    private int $length = 7;
    private string $alphabet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_-';
    private bool $readable = false;

    private const AMBIGUOUS_CHARS = 'B8G6I1l|0OQDS5Z2()[]{}:;,.';

    /**
     * @throws InvalidShortidException
     */
    public function generate(?int $length = null, ?string $alphabet = null, ?bool $readable = null): Shortid
    {
        $length ??= $this->length;
        $readable ??= $this->readable;
        if (null === $alphabet && $readable) {
            $alphabet = \str_replace(\str_split(self::AMBIGUOUS_CHARS), '', $this->alphabet);
            $alphabet .= \str_repeat('_', \strlen(self::AMBIGUOUS_CHARS) / 2);
            $alphabet .= \str_repeat('-', \strlen(self::AMBIGUOUS_CHARS) / 2);
        }
        $id = $this->getBytesFromString($alphabet ?? $this->alphabet, $length);

        return new Shortid($id, $length, $alphabet);
    }

    public function setAlphabet(string $alphabet): void
    {
        $this->checkAlphabet($alphabet, true);
        $this->alphabet = $alphabet;
    }

    public function setLength(int $length): void
    {
        $this->checkLength($length);
        $this->length = $length;
    }

    public function getAlphabet(): string
    {
        return $this->alphabet;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function checkLength(?int $length = null, bool $strict = false): void
    {
        if (null === $length && !$strict) {
            return;
        }
        if ($length < 2 || $length > 20) {
            throw new \InvalidArgumentException('Invalid length.');
        }
    }

    public function checkAlphabet(?string $alphabet = null, bool $strict = false): void
    {
        if (null === $alphabet && !$strict) {
            return;
        }
        $alphaLength = null === $alphabet ? 0 : \mb_strlen($alphabet, 'UTF-8');
        if (64 !== $alphaLength) {
            throw new \InvalidArgumentException(\sprintf('Invalid alphabet: %s (length: %u)', $alphabet, $alphaLength));
        }
    }

    public function setReadable(bool $readable): void
    {
        $this->readable = $readable;
    }

    // See https://www.php.net/releases/8.3/en.php#randomizer_get_bytes_from_string
    private function getBytesFromString(string $string, int $length): string
    {
        if (\PHP_VERSION_ID >= 80300) {
            return (new Randomizer())->getBytesFromString($string, $length);
        }

        $stringLength = \mb_strlen($string, 'UTF-8');
        $result = '';
        for ($i = 0; $i < $length; ++$i) {
            $result .= $string[\random_int(0, $stringLength - 1)];
        }

        return $result;
    }
}
