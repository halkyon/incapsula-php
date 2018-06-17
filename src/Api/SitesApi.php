<?php

namespace Incapsula\Api;

class SitesApi extends AbstractApi
{
    private $api_uri = 'https://my.incapsula.com/api/prov/v1/sites';

    /**
     * @return array
     */
    public function list()
    {
        return $this->client->send(sprintf('%s/list', $this->api_uri));
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function add($params = [])
    {
        return $this->client->send(sprintf('%s/add', $this->api_uri), $params);
    }

    /**
     * @param string $siteId
     *
     * @return array
     */
    public function delete($siteId)
    {
        return $this->client->send(sprintf('%s/delete', $this->api_uri), [
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
        return $this->client->send(sprintf('%s/uploadCustomCertificate', $this->api_uri), [
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
        return $this->client->send(sprintf('%s/removeCustomCertificate', $this->api_uri), [
            'site_id' => $siteId,
        ]);
    }
}
