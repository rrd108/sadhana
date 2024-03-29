<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\ORM\Entity;

/**
 * User Entity
 *
 * @property string $id
 * @property string $email
 * @property string $password
 * @property string|null $token
 * @property \Cake\I18n\FrozenTime|null $token_expiration
 * @property \Cake\I18n\FrozenTime|null $last_login
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property string $role
 * @property string|null $forgotPass
 * @property string|null $firebaseUserToken
 * @property string|null $notificationTime
 * @property string|null $name
 *
 * @property \App\Model\Entity\Badge[] $badges
 * @property \App\Model\Entity\User[] $counsulees
 * @property \App\Model\Entity\User[] $counsellors
 * @property \App\Model\Entity\CounsellorsCounsulee[] $counsellors_counsulees
 */
class User extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected $_accessible = [
        'email' => true,
        'password' => true,
        'token' => true,
        'token_expiration' => true,
        'last_login' => true,
        'created' => true,
        'modified' => true,
        'role' => true,
        'forgotPass' => true,
        'firebaseUserToken' => true,
        'notificationTime' => true,
        'name' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array<string>
     */
    protected $_hidden = ['password', 'forgotpass'];

    protected function _setPassword(string $password)
    {
        $hasher = new DefaultPasswordHasher();
        return $hasher->hash($password);
    }
}
