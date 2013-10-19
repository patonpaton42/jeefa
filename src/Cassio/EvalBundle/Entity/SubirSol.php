<?php
// src/Cassio/EvalBundle/Entity/Task.php

namespace Cassio\EvalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity
 * @ORM\Table(name="SubirSol")
 */
class SubirSol
{
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;
  
  /**
   * @ORM\Column(type="integer")
   */
  protected $id_lenguaje;
  
  protected $puntaje;

  /**
   * @ORM\Column(type="integer")
   */
  protected $problema;
    
  /**
   * @Assert\File(maxSize="2000000")
   */
  private $file;

  /**
   * @ORM\Column(type="string", length=512)
   */
  public $path;

  public function getPath()
  {
      return $this->path;
  }
  public function setPath($path)
  {
      $this->path = $path;
  }

  public function getLenguaje()
  {
      return $this->id_lenguaje;
  }
  public function setLenguaje($id_lenguaje)
  {
      $this->id_lenguaje = $id_lenguaje;
  }

  public function getPuntaje()
  {
      return $this->puntaje;
  }
  public function setPuntaje($puntaje)
  {
      $this->puntaje = $puntaje;
  }

  public function getProblema()
  {
      return $this->problema;
  }
  public function setProblema($problema)
  {
      $this->problema = $problema;
  }

  public function upload()
  {
      // the file property can be empty if the field is not required
      //echo "|".$this->getFile()."|<br>";
      //var_dump($this->getFile());
      if ($this->getFile() === NULL) {
          echo "return";
          return;
      }

      // use the original file name here but you should
      // sanitize it at least to avoid any security issues

      // move takes the target directory and then the
      // target filename to move to
      $this->getFile()->move(
          $this->getUploadRootDir(),
          $this->getFile()->getClientOriginalName()
      );

      // set the path property to the filename where you've saved the file
      $this->path = $this->getFile()->getClientOriginalName();

      // clean up the file property as you won't need it anymore
      $this->file = null;
  }
  protected function getUploadRootDir()
  {
      // the absolute directory path where uploaded
      // documents should be saved
      return __DIR__.'/../../../../web/'.$this->getUploadDir();
  }
  public function getFile()
  {
      return $this->file;
  }

  /**
   * Sets file.
   *
   * @param UploadedFile $file
   */
  public function setFile(File $file = null)
//  public function setFile(UploadedFile $file = null)
  {
      $this->file = $file;
  }

  protected function getUploadDir()
  {
      // get rid of the __DIR__ so it doesn't screw up
      // when displaying uploaded doc/image in the view.
      return 'uploads/documents';
  }
  public function getAbsolutePath()
  {
    return null === $this->path
        ? null
        : $this->getUploadRootDir().'/'.$this->path;
  }


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
     * Set id_lenguaje
     *
     * @param integer $idLenguaje
     * @return SubirSol
     */
    public function setIdLenguaje($idLenguaje)
    {
        $this->id_lenguaje = $idLenguaje;
    
        return $this;
    }

    /**
     * Get id_lenguaje
     *
     * @return integer 
     */
    public function getIdLenguaje()
    {
        return $this->id_lenguaje;
    }
}