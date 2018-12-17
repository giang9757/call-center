<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Services Model
 *
 * @method \App\Model\Entity\Service get($primaryKey, $options = [])
 * @method \App\Model\Entity\Service newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Service[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Service|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Service patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Service[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Service findOrCreate($search, callable $callback = null, $options = [])
 */
class ServicesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('services');
        $this->setDisplayField('accountSid');
        $this->setPrimaryKey('accountSid');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->scalar('accountSid')
            ->allowEmpty('accountSid', 'create');

        $validator
            ->scalar('monday')
            ->requirePresence('monday', 'create')
            ->notEmpty('monday');

        $validator
            ->scalar('tuesday')
            ->requirePresence('tuesday', 'create')
            ->notEmpty('tuesday');

        $validator
            ->scalar('wednesday')
            ->requirePresence('wednesday', 'create')
            ->notEmpty('wednesday');

        $validator
            ->scalar('thursday')
            ->requirePresence('thursday', 'create')
            ->notEmpty('thursday');

        $validator
            ->scalar('friday')
            ->requirePresence('friday', 'create')
            ->notEmpty('friday');

        $validator
            ->scalar('saturday')
            ->requirePresence('saturday', 'create')
            ->notEmpty('saturday');

        $validator
            ->scalar('sunday')
            ->requirePresence('sunday', 'create')
            ->notEmpty('sunday');

        $validator
            ->scalar('holiday')
            ->requirePresence('holiday', 'create')
            ->notEmpty('holiday');

        $validator
            ->scalar('timeoutRec')
            ->requirePresence('timeoutRec', 'create')
            ->notEmpty('timeoutRec');

        $validator
            ->scalar('timeinRec')
            ->requirePresence('timeinRec', 'create')
            ->notEmpty('timeinRec');
            
        $validator
            ->scalar('busyRec')
            ->requirePresence('busyRec', 'create')
            ->notEmpty('busyRec');

        return $validator;
    }
}
