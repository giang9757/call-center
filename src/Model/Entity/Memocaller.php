<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Memocaller Entity
 *
 * @property string $callId
 * @property string $memo
 * @property \Cake\I18n\FrozenDate $date
 * @property int $orderId
 */
class Memocaller extends Entity
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
        'callId' => false
    ];
}
