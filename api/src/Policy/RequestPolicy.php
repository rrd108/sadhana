<?php

namespace App\Policy;

use Authorization\Exception\ForbiddenException;
use Authorization\Policy\RequestPolicyInterface;
use Cake\Core\Configure;
use Cake\Http\ServerRequest;

class RequestPolicy implements RequestPolicyInterface
{
    /**
     * Method to check if the request can be accessed
     *
     * @param \Authorization\IdentityInterface|null $identity Identity
     * @param \Cake\Http\ServerRequest $request Server Request
     * @return bool
     */
    public function canAccess($identity, ServerRequest $request)
    {
        if (
            $request->getParam('controller') === 'Users'
            && ($request->getParam('action') === 'forgotpass' || $request->getParam('action') === 'register')
        ) {
            return true;
        }

        if ($identity) {
            if ($identity->getOriginalData()->role == 'admin') {
                return true;
            }

            Configure::load('permissions', 'default', false);
            $permissions = Configure::read('Permissions.' . $identity->getOriginalData()->role);

            if (
                isset($permissions[$request->getParam('controller')])
                && in_array($request->getParam('action'), $permissions[$request->getParam('controller')])
            ) {
                return true;
            }
        }

        throw new ForbiddenException(null, 'Authorization error');
        return false;
    }
}
