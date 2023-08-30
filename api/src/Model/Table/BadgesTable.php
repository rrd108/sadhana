<?php

declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\BadgesUser;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Badges Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsToMany $Users
 *
 * @method \App\Model\Entity\Badge newEmptyEntity()
 * @method \App\Model\Entity\Badge newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Badge[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Badge get($primaryKey, $options = [])
 * @method \App\Model\Entity\Badge findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Badge patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Badge[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Badge|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Badge saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Badge[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Badge[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Badge[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Badge[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class BadgesTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('badges');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Users', [
            'foreignKey' => 'badge_id',
            'targetForeignKey' => 'user_id',
            'joinTable' => 'badges_users',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 24)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        $validator
            ->scalar('icon')
            ->maxLength('icon', 36)
            ->requirePresence('icon', 'create')
            ->notEmptyString('icon');

        $validator
            ->scalar('field')
            ->maxLength('field', 48)
            ->requirePresence('field', 'create')
            ->notEmptyString('field');

        $validator
            ->scalar('base')
            ->maxLength('base', 6)
            ->requirePresence('base', 'create')
            ->notEmptyString('base');

        $validator
            ->integer('goal')
            ->requirePresence('goal', 'create')
            ->notEmptyString('goal');

        return $validator;
    }

    public function getTopBadges(string $userId)
    {
        // TODO a hacky solution

        // get top level badge names and levels what the user has
        $topBadgesQuery = $this->find();
        $_topBadges = $topBadgesQuery->select([
            'Badges.name',
            'maxLevel' => $topBadgesQuery->func()->max('Badges.level'),
        ])
            ->innerJoinWith('Users', function ($q) use ($userId) {
                return $q->where(['Users.id' => $userId]);
            })
            ->group(['Badges.name']);

        $topBadges = [];
        foreach ($_topBadges as $topBadge) {
            $badge = $this->find()->select(['Badges.id'])->where(['Badges.name' => $topBadge->name, 'Badges.level' => $topBadge->maxLevel]);
            $topBadges[] = $badge->first()->id;
        }

        $query = $this->find();
        if (count($topBadges)) {
            $query->select([
                'badgesUsersId' => 'BadgesUsers.id',
                'gained' => 'BadgesUsers.created',
                'accepted' => 'BadgesUsers.accepted'
            ])
                ->enableAutoFields(true)
                ->where(['Badges.id IN' => $topBadges])
                ->innerJoinWith('Users', function ($q) use ($userId) {
                    return $q->where(['Users.id' => $userId]);
                })
                ->order(['gained' => 'DESC']);
        }
        return $query;
    }
}
