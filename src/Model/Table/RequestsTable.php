<?php
/**
 * CakeManager (http://cakemanager.org)
 * Copyright (c) http://cakemanager.org
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) http://cakemanager.org
 * @link          http://cakemanager.org CakeManager Project
 * @since         1.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Analyzer\Model\Table;

use Analyzer\Model\Entity\Request;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use DateTime;

/**
 * Requests Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Visitors
 */
class RequestsTable extends Table
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

        $this->table('analyzer_requests');
        $this->displayField('id');
        $this->primaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Visitors', [
            'foreignKey' => 'visitor_id',
            'className' => 'Analyzer.Visitors'
        ]);
    }

    public function findUniqueVisitors(Query $query, array $options)
    {
        $query->group('Requests.visitor_id');
        return $query;
    }

    public function findBetween(Query $query, array $options)
    {
        if(array_key_exists('start', $options)) {
            $query->where([
                'Requests.created <=' => new DateTime($options['start'])
            ]);
        }

        if(array_key_exists('end', $options)) {
            $query->where([
                'Requests.created >=' => new DateTime($options['end'])
            ]);
        }

        return $query;
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
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->allowEmpty('url');

        $validator
            ->allowEmpty('plugin');

        $validator
            ->allowEmpty('controller');

        $validator
            ->allowEmpty('action');

        $validator
            ->allowEmpty('ext');

        $validator
            ->allowEmpty('prefix');

        $validator
            ->allowEmpty('pass');

        $validator
            ->allowEmpty('query');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['visitor_id'], 'Visitors'));
        return $rules;
    }
}
