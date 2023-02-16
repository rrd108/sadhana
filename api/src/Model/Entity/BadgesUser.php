<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * BadgesUser Entity
 *
 * @property int $id
 * @property int $badge_id
 * @property string $user_id
 * @property \Cake\I18n\FrozenDate $created
 * @property bool $accepted
 *
 * @property \App\Model\Entity\Badge $badge
 * @property \App\Model\Entity\User $user
 */
class BadgesUser extends Entity
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
        'badge_id' => true,
        'user_id' => true,
        'created' => true,
        'accepted' => true,
        'badge' => true,
        'user' => true,
    ];
}
