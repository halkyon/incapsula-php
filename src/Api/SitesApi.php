<?php

declare(strict_types=1);

namespace Incapsula\Api;

class SitesApi extends AbstractApi
{
    /**
     * @var string
     */
    private $apiUri = 'https://my.incapsula.com/api/prov/v1/sites';

    public function list(int $pageSize = 50, int $pageNum = 0): array
    {
        return $this->client->send(sprintf('%s/list', $this->apiUri), [
            'page_size' => $pageSize,
            'page_num' => $pageNum,
        ]);
    }

    public function add(array $params = []): array
    {
        return $this->client->send(sprintf('%s/add', $this->apiUri), $params);
    }

    public function status(string $siteId): array
    {
        return $this->client->send(sprintf('%s/status', $this->apiUri), [
            'site_id' => $siteId,
        ]);
    }

    public function delete(string $siteId): array
    {
        return $this->client->send(sprintf('%s/delete', $this->apiUri), [
            'site_id' => $siteId,
        ]);
    }

    public function uploadCustomCertificate(string $siteId, string $certificate, string $privateKey): array
    {
        return $this->client->send(sprintf('%s/customCertificate/upload', $this->apiUri), [
            'site_id' => $siteId,
            'certificate' => base64_encode($certificate),
            'private_key' => base64_encode($privateKey),
        ]);
    }

    public function removeCustomCertificate(string $siteId): array
    {
        return $this->client->send(sprintf('%s/removeCustomCertificate', $this->apiUri), [
            'site_id' => $siteId,
        ]);
    }

    public function purgeCache(string $siteId, string $purgePattern = ''): array
    {
        return $this->client->send(sprintf('%s/cache/purge', $this->apiUri), [
            'site_id' => $siteId,
            'purge_pattern' => $purgePattern,
        ]);
    }

    public function moveSite(string $siteId, string $destAccountId): array
    {
        return $this->client->send(sprintf('%s/moveSite', $this->apiUri), [
            'site_id' => $siteId,
            'destination_account_id' => $destAccountId,
        ]);
    }

    public function listCacheRules(string $siteId, int $pageSize = 50, int $pageNum = 0): array
    {
        return $this->client->sendRaw(sprintf('%s/performance/caching-rules/list', $this->apiUri), [
            'site_id' => $siteId,
            'page_size' => $pageSize,
            'page_num' => $pageNum,
        ]);
    }

    public function setStaticCacheMode(string $siteId): array
    {
        return $this->client->send(sprintf('%s/performance/cache-mode', $this->apiUri), [
            'site_id' => $siteId,
            'cache_mode' => 'static_only',
        ]);
    }
}
