<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Sadhana Entity
 *
 * @property int $id
 * @property string $user_id
 * @property \Cake\I18n\FrozenDate $date
 * @property int $japaEarly
 * @property int $japaMorning
 * @property int $japaAfternoon
 * @property int $japaNight
 * @property bool $mangala
 * @property bool $japa
 * @property bool $kirtana
 * @property bool $class
 * @property bool $gauraarati
 * @property int $reading
 * @property int $study
 * @property int $murtiseva
 * @property int $gayatri
 *
 * @property \App\Model\Entity\User $user
 */
class Sadhana extends Entity
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
        'user_id' => true,
        'date' => true,
        'japaEarly' => true,
        'japaMorning' => true,
        'japaAfternoon' => true,
        'japaNight' => true,
        'mangala' => true,
        'japa' => true,
        'kirtana' => true,
        'class' => true,
        'gauraarati' => true,
        'reading' => true,
        'study' => true,
        'murtiseva' => true,
        'gayatri' => true,
        'user' => true,
    ];
}
