<?php

namespace PuzzleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Puzzle
 *
 * @ORM\Table(name="puzzle")
 * @ORM\Entity
 */
class Puzzle
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name = 'Puzzle';

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status = true;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="text", length=65535, nullable=false)
     */
    private $state;

    /**
     * @var integer
     *
     * @ORM\Column(name="steps", type="integer", nullable=false)
     */
    private $steps = '0';

    /**
     * @param boolean $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = json_encode($state);
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return json_decode($this->state, true);
    }

    /**
     * @param mixed $steps
     */
    public function setSteps($steps)
    {
        $this->steps = json_encode($steps);
    }

    /**
     * @return integer
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

}

