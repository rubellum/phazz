<?php

namespace JobAccess\DataModel;

use JobAccess\Exception\JobAccessException;

readonly class UrlHost
{
    private function __construct(
        public string $urlHost,
    )
    {

    }

    /**
     * URLからURLホストを抽出する
     *
     * オブジェクトの比較によって一致判定をするため、
     * 同一URLホストの場合は同じインスタンスを返す。
     *
     * @param string $url
     * @return self
     * @throws JobAccessException
     */
    public static function parse(string $url): self
    {
        static $cache = [];

        $urlHost = parse_url($url, PHP_URL_HOST);

        if (!$urlHost) {
            throw new JobAccessException('Cannot parse URL: ' . $url);
        }

        if (!isset($cache[$urlHost])) {
            $cache[$urlHost] = new static($urlHost);
        }

        return $cache[$urlHost];
    }

    public function __toString(): string
    {
        return $this->urlHost;
    }
}
