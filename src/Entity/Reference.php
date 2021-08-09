<?php
namespace Reference\Entity;

use Omeka\Entity\AbstractEntity;

/**
 * @Entity
 */
class Reference extends AbstractEntity
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @ManyToOne(targetEntity="Omeka\Entity\Item")
     * @JoinColumn(onDelete="SET NULL")
     */
    protected $item;    

    /**
     * @ManyToOne(targetEntity="Omeka\Entity\Item")
     * @JoinColumn(onDelete="SET NULL")
     */
    protected $bibl;    

    /**
     * @Column(length=190, nullable=true)
     */
    protected $ref;    

    public function getId() {
        return $this->id;
    }

    public function setItem($item = null) {
        $this->item = $item;
    }

    public function getItem() {
        return $this->item;
    }

    public function setBibl($bibl = null) {
        $this->bibl = $bibl;
    }

    public function getBibl() {
        return $this->bibl;
    }

    public function setRef($ref) {
        $this->ref = $ref;
    }

    public function getRef() {
        return $this->ref;
    }
}