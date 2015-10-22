<?php
namespace Analyzer\Model\Entity;

use Cake\ORM\Entity;

/**
 * Request Entity.
 *
 * @property int $id
 * @property int $visitor_id
 * @property \Analyzer\Model\Entity\Visitor $visitor
 * @property string $url
 * @property string $plugin
 * @property string $controller
 * @property string $action
 * @property string $ext
 * @property string $prefix
 * @property string $pass
 * @property string $query
 * @property \Cake\I18n\Time $created
 */
class Request extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}
