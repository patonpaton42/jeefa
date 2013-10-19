<?php

namespace Cassio\EvalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Categoria")
 */
class Categoria
{
    /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
    private $id;

    /**
   * @ORM\Column(type="string", length=100)
   */
    private $nombre;

    public function getId()
    {
        return $this->id;
    }
    public function setNombre( $nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
}