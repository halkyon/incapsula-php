<?php

declare(strict_types=1);

namespace Incapsula\Api;

class AccountsApi extends AbstractApi
{
    /**
     * @var string
     */
    private $apiUri = 'https://my.incapsula.com/api/prov/v1';

    public function delete(string $subAccountId): array
    {
        return $this->client->send(sprintf('%s/subaccounts/delete', $this->apiUri), [
            'sub_account_id' => $subAccountId,
        ]);
    }

    public function add(string $subAccountName): array
    {
        return $this->client->send(sprintf('%s/subaccounts/add', $this->apiUri), [
            'sub_account_name' => $subAccountName,
        ]);
    }

    public function list(int $pageSize = 50, int $pageNum = 0): array
    {
        return $this->client->send(sprintf('%s/accounts/listSubAccounts', $this->apiUri), [
            'page_size' => $pageSize,
            'page_num' => $pageNum,
        ]);
    }

    public function getLoginToken(string $accountId): array
    {
        return $this->client->send(sprintf('%s/accounts/gettoken', $this->apiUri), [
            'account_id' => $accountId,
        ]);
    }
}
