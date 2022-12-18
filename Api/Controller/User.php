<?php

namespace PBY\EApi\Api\Controller;

use XF\Mvc\ParameterBag;

class User extends AbstractController
{
    protected function preDispatchController($action, ParameterBag $params)
    {
        $this->assertApiScopeByRequestMethod('user');
    }

    public function actionGetConnectedAccounts(ParameterBag $params)
    {
        $user = $this->assertViewableUser($params->user_id);

        $accounts = $user->ConnectedAccounts;

        $result = [];
        foreach ($accounts as $account)
        {
            $result += [
                $account->provider => $account->provider_key
            ];
        }

        return $this->apiResult($result);
    }

    /**
     * @param int $id
     * @param mixed $with
     * @param bool $basicProfileOnly
     *
     * @return \XF\Entity\User
     *
     * @throws \XF\Mvc\Reply\Exception
     */
    protected function assertViewableUser($id, $with = 'api', $basicProfileOnly = true)
    {
        /** @var \XF\Entity\User $user */
        $user = $this->assertRecordExists('XF:User', $id, $with);

        if (\XF::isApiCheckingPermissions())
        {
            $canView = $basicProfileOnly ? $user->canViewBasicProfile($error) : $user->canViewFullProfile($error);
            if (!$canView)
            {
                throw $this->exception($this->noPermission($error));
            }
        }

        return $user;
    }
}