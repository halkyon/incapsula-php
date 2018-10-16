<?php

namespace Incapsula\Api;

class AccountsApi extends AbstractApi
{
    private $apiUri = 'https://my.incapsula.com/api/prov/v1';

    /**
     *   sub_account_id
     *	Numeric identifier of the sub account to operate on.
     *
     * @param mixed $subAccountId
     */
    public function delete($subAccountId)
    {
        return $this->client->send(sprintf('%s/subaccounts/delete', $this->apiUri), [
            'sub_account_id' => $subAccountId,
        ]);
    }

    /**
     * Parameters:
     *api_id
     *   API authentication identifier.
     *api_key
     *	API authentication identifier.
     *sub_account_name
     *	The name of the sub account.
     *
     *parent_id
     *	The newly created account's parent id. If not specified, the invoking account will be assigned as the parent account. 	Optional:Yes
     *ref_id
     *   Customer specific identifier for this operation. 	Optional:Yes
     *
     * @param mixed $subAccountName
     */
    public function add($subAccountName)
    {
        return $this->client->send(sprintf('%s/subaccounts/add', $this->apiUri), [
            'sub_account_name' => $subAccountName,
        ]);
    }

    /**
     * @param int $pageSize
     * @param int $pageNum
     *
     * @return array
     */
    public function list($pageSize = 50, $pageNum = 0)
    {
        return $this->client->send(sprintf('%s/accounts/listSubAccounts', $this->apiUri), [
            'page_size' => $pageSize,
            'page_num' => $pageNum,
        ]);
    }

    /**
     * Get account login token.
     *
     *Tokens are used instead of user/password based authentication to log in to the Incapsula management console.
     *
     *Use this operation to generate a token for an account. The token is valid for 15 minutes.
     *
     * /api/prov/v1/accounts/gettoken
     *
     *In order to use the token, the user must use the following link:
     *
     *https://my.incapsula.com/?token={generated_token}
     *
     * @param mixed $accountId
     */
    public function getLoginToken($accountId)
    {
        return $this->client->send(sprintf('%s/accounts/gettoken', $this->apiUri), [
            'account_id' => $accountId,
        ]);
    }
}
