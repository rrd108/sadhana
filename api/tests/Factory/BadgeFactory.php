<?php

declare(strict_types=1);

namespace App\Test\Factory;

use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;

/**
 * BadgeFactory
 *
 * @method \App\Model\Entity\Badge getEntity()
 * @method \App\Model\Entity\Badge[] getEntities()
 * @method \App\Model\Entity\Badge|\App\Model\Entity\Badge[] persist()
 * @method static \App\Model\Entity\Badge get(mixed $primaryKey, array $options = [])
 */
class BadgeFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Badges';
    }

    /**
     * Defines the factory's default values. This is useful for
     * not nullable fields. You may use methods of the present factory here too.
     *
     * @return void
     */
    protected function setDefaultTemplate(): void
    {
        $this->setDefaultData(function (Generator $faker) {
            return [
                'name' => $faker->word,
                'description' => $faker->text,
                'icon' => $faker->word,
                'field' => $faker->word,
                'base' => 'point',
                'goal' => 10,
                'level' => $faker->numberBetween(1, 10),
            ];
        });
    }

    /**
     * @param array|callable|null|int|\Cake\Datasource\EntityInterface|string $parameter
     * @param int $n
     * @return BadgeFactory
     */
    public function withUsers($parameter = null, int $n = 1): BadgeFactory
    {
        return $this->with(
            'Users',
            \App\Test\Factory\UserFactory::make($parameter, $n)->without('Badges')
        );
    }
}
