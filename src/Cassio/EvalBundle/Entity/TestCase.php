<?php
// src/Cassio/EvalBundle/Entity/TestCase.php
namespace Cassio\EvalBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
//use Doctrine\Common\Collections\ArrayCollection;
//use Symfony\Component\Form\Extension\Core\Type\PercentType;


/**
 * @ORM\Entity
 * @ORM\Table(name="TestCase")
 * @ORM\HasLifecycleCallbacks
 */
class TestCase
{
//  public function __construct() {
//      $this->entradas = new ArrayCollection();
//      $this->salidas = new ArrayCollection();
//  }
  /**
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected $id;

  /**
   * @ORM\Column(type="integer")
   */
  protected $id_problema;

//  /**
//   * @ORM\Column(type="string", length=100)
//   */
//  protected $entradas;
//  /**
//   * @ORM\Column(type="string", length=100)
//   */
//  protected $salidas;

  /**
   * @ORM\Column(type="string", length=100)
   */
  protected $titulo;

  /**
  * @ORM\Column(type="integer")
  */
  protected $puntaje;
   /**
   * @ORM\Column(type="string", length=256)
   */
  protected $path_entrada;
   /**
   * @ORM\Column(type="string", length=256)
   */
  protected $path_salida;

  /**
   * @Assert\File(maxSize="6000000")
   */
  public $entrada;
  /**
   * @Assert\File(maxSize="6000000")
   */
  public $salida;

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
   * Set id_problema
   *Fin de la conversación

   * @param integer $idProblema
   * @return TestCase
   */
  public function setIdProblema($idProblema)
  {
      $this->id_problema = $idProblema;

      return $this;
  }

  /**
   * Get id_problema
   *
   * @return integer
   */
  public function getIdProblema()
  {
      return $this->id_problema;
  }

//   /**
//   * Set id_in
//   *
//   * @param integer $idIn
//   * @return TestCase
//   */
//  public function setEntradas(ArrayCollection $entradas)
//  {
//      $this->entradas = $entradas;
//      return $this;
//  }
//
//  /**
//   * Get id_in
//   *
//   * @return integer
//   */
  public function getEntrada()
  {
      return $this->entrada;
  }

  public function getSalida()
  {
      return $this->salida;
  }


//   /**
//   * Set salidas
//   *
//   * @param integer $Salidas
//   * @return TestCase
//   */
//  public function setSalidas(ArrayCollection $Salidas)
//  {
//      $this->salidas = $Salidas;
//
//      return $this;
//  }
//
//  /**
//   * Get Salidas
//   *
//   * @return integer
//   */
//  public function getSalidas()
//  {
//      return $this->salidas;
//  }

  /**
   * Set titulo
   *
   * @param string $titulo
   * @return TestCase
   */
  public function setTitulo($titulo)
  {
      $this->titulo = $titulo;

      return $this;
  }

  /**
   * Get puntaje
   *
   * @return string
   */
  public function getTitulo()
  {
      return $this->titulo;
  }
  public function setpuntaje($puntaje)
  {
      $this->puntaje= $puntaje;

      return $this;
  }

  /**
   * Get puntaje
   *
   * @return string
   */
  public function getpuntaje()
  {
      return $this->puntaje;
  }

  public function upload() {
      $files = $this->getFiles();
        if (null === $files) {
            return;
        }

        foreach($files as $file) {
            $file->move(
                $this->getUploadRootDir(),
                $file->getClientOriginalName());
        }

        // set the path property to the filename where you've saved the file
        $this->path_entrada = $files[0]->getClientOriginalName();
        $this->path_salida = $files[1]->getClientOriginalName();
    }

  protected function getUploadRootDir()
  {
      // the absolute directory path where uploaded
      // documents should be saved
      return __DIR__.'/../../../../web/'.$this->getUploadDir();
  }

    public function getFiles() {
        $ret = array();
        $ret[] = $this->entrada;
        $ret[] = $this->salida;
        return $ret;
    }

    protected function getUploadDir() {
      return 'uploads/tc';
  }
  public function getAbsolutePath()
  {
      return $this->salida;
  }

  public function getPathEntrada()
  {
    return $this->path_entrada;
  }

  public function getPathSalida()
  {
    return $this->path_salida;
  }
}
