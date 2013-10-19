<?php
// src/Cassio/EvalBundle/Entity/Usuario.php
namespace Cassio\EvalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="Usuario")
 */
class Usuario
{
   /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="string", length=100)
   */
  protected $nick;

  /**
   * @ORM\Column(type="string", length=100)
   */
  protected $nombre;

  /**
   * @ORM\Column(type="string", length=100)
   */
  protected $apellido;

  /**
   * Get id
   *
   * @return integer 
   */
  public function getId()
  {
      return $this->id;
  }

  /**
   * Set nick
   *
   * @param string $nick
   * @return Usuario
   */
  public function setNick($nick)
  {
      $this->nick = $nick;
  
      return $this;
  }

  /**
   * Get nick
   *
   * @return string 
   */
  public function getNick()
  {
      return $this->nick;
  }

  /**
   * Set nombre
   *
   * @param string $nombre
   * @return Usuario
   */
  public function setNombre($nombre)
  {
      $this->nombre = $nombre;
  
      return $this;
  }

  /**
   * Get nombre
   *
   * @return string 
   */
  public function getNombre()
  {
      return $this->nombre;
  }

  /**
   * Set apellido
   *
   * @param string $apellido
   * @return Usuario
   */
  public function setApellido($apellido)
  {
      $this->apellido = $apellido;
  
      return $this;
  }

  /**
   * Get apellido
   *
   * @return string 
   */
  public function getApellido()
  {
      return $this->apellido;
  }

  public function getNombreCompleto()
  {
      return $this->nombre." ".$this->apellido;
  }
}