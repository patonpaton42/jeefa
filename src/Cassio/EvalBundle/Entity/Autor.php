<?php

namespace Cassio\EvalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OneToMany;

/**
 * @ORM\Entity
 * @ORM\Table(name="Autor")
 */
class Autor
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

    /**
     * @ORM\OneToMany(targetEntity="Problema", mappedBy="autor_r")
     */
    private $problemas;

   
    public function getId()
    {
        return $this->id;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->problemas = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add problemas
     *
     * @param \Cassio\EvalBundle\Entity\Problema $problemas
     * @return Autor
     */
    public function addProblema(\Cassio\EvalBundle\Entity\Problema $problemas)
    {
        $this->problemas[] = $problemas;
    
        return $this;
    }

    /**
     * Remove problemas
     *
     * @param \Cassio\EvalBundle\Entity\Problema $problemas
     */
    public function removeProblema(\Cassio\EvalBundle\Entity\Problema $problemas)
    {
        $this->problemas->removeElement($problemas);
    }

    /**
     * Get problemas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProblemas()
    {
        return $this->problemas;
    }
}
