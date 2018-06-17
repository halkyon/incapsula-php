<?php

namespace Incapsula\Api;

class SitesApi extends AbstractApi
{
    private $apiUri = 'https://my.incapsula.com/api/prov/v1/sites';

    /**
     * @param int $pageSize
     * @param int $pageNum
     *
     * @return array
     */
    public function list($pageSize = 50, $pageNum = 0)
    {
        return $this->client->send(sprintf('%s/list', $this->apiUri), [
            'page_size' => $pageSize,
            'page_num' => $pageNum,
        ]);
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function add($params = [])
    {
        return $this->client->send(sprintf('%s/add', $this->apiUri), $params);
    }

    /**
     * @param string $siteId
     *
     * @return array
     */
    public function delete($siteId)
    {
        return $this->client->send(sprintf('%s/delete', $this->apiUri), [
            'site_id' => $siteId,
        ]);
    }

    /**
     * @param string $siteId
     * @param string $certificate
     * @param string $privateKey
     *
     * @return array
     */
    public function uploadCustomCertificate($siteId, $certificate, $privateKey)
    {
        return $this->client->send(sprintf('%s/uploadCustomCertificate', $this->apiUri), [
            'site_id' => $siteId,
            'certificate' => base64_encode($certificate),
            'private_key' => base64_encode($privateKey),
        ]);
    }

    /**
     * @param string $siteId
     *
     * @return array
     */
    public function removeCustomCertificate($siteId)
    {
        return $this->client->send(sprintf('%s/removeCustomCertificate', $this->apiUri), [
            'site_id' => $siteId,
        ]);
    }
}
