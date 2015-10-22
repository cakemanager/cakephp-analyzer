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
namespace Analyzer\Controller\Admin;

use CakeAdmin\Controller\AppController;

class AnalyzerController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Analyzer.Analyzer');

        $this->loadModel('Analyzer.Requests');
    }

    public function index() {

        // GOALS

        // unique visitors today

        // total visits today

        // unique visitors this month

        // total visits this month

        // list of requests (today)

        $result = $this->Requests->find()
            ->find('between', [
                'end' => '-3 days'
            ]);

        debug($result->count());
        debug($result);


    }

}