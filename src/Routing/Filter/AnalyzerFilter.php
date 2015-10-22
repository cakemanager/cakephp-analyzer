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
namespace Analyzer\Routing\Filter;

use Analyzer\Model\Entity\Visitor;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\DispatcherFilter;

class AnalyzerFilter extends DispatcherFilter
{
    public $Visitors;

    public $Requests;

    protected $Request;

    /**
     * construct
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $this->Visitors = TableRegistry::get('Analyzer.Visitors');
        $this->Requests = TableRegistry::get('Analyzer.Requests');

        parent::__construct($config);
    }

    protected function _setRequest($request)
    {
        $this->Request = $request;
    }

    public function beforeDispatch(Event $event)
    {
        $this->_setRequest($event->data['request']);

        $visitor = $this->getVisitor();

        $this->registerRequest($visitor);
    }

    public function getVisitor()
    {
        $clientIp = $this->Request->clientIp();

        $query = $this->Visitors->find()->where(['Visitors.client_ip' => $clientIp]);

        $exists = (bool)$query->count();

        if (!$exists) {
            $data = [
                'client_ip' => $clientIp
            ];
            $entity = $this->Visitors->newEntity($data);
            $this->Visitors->save($entity);
        }

        return $query->first();
    }

    public function registerRequest(Visitor $visitor)
    {
        $request = $this->Request;

        if ($this->shouldBeIgnored()) {
            return false;
        }

        $data = [
            'visitor_id' => $visitor->get('id'),
            'url' => $request->url,
            'plugin' => $request->param('plugin'),
            'controller' => $request->param('controller'),
            'action' => $request->param('action'),
            'ext' => $request->param('ext'),
            'prefix' => $request->param('prefix'),
            'pass' => $request->param('pass'),
            'query' => $request->query
        ];

        $entity = $this->Requests->newEntity($data);

        return $this->Requests->save($entity);
    }

    public function shouldBeIgnored()
    {
        $request = $this->Request;

        $list = Configure::read('Analyzer.Ignore');

        $ruleExample = [
            'plugin' => 'DebugKit',
            'controller' => 'Requests',
        ];

        $_rule = [
            'plugin' => '*',
            'controller' => '*',
            'action' => '*',
            'prefix' => '*'
        ];

        foreach ($list as $key => $rule) {
            $rule = array_merge($_rule, $rule);

            if ($this->__paramIgnored($rule, 'plugin') &&
                $this->__paramIgnored($rule, 'controller') &&
                $this->__paramIgnored($rule, 'action') &&
                $this->__paramIgnored($rule, 'prefix')
            ) {
                return true;
            }
        }

        return false;
    }

    private function __paramIgnored($rule, $key)
    {
        $request = $this->Request;

        if ($rule[$key] === '*') {
            return true;
        }
        if (is_array($rule[$key])) {
            if (in_array($request->param($key), $rule[$key])) {
                return true;
            }
        }
        if ($request->param($key) === $rule[$key]) {
            return true;
        }

        return false;
    }

}