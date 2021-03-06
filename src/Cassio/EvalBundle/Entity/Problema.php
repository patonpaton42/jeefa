<?php
// src/Cassio/EvalBundle/Entity/Problema.php
namespace Cassio\EvalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Entity
 * @ORM\Table(name="Problema")
 */
class Problema //extends AbstractType
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
  protected $id_usuario;
 

  /**
   * @ORM\ManyToMany(cascade={"persist"}, targetEntity="Categoria")
   * @ORM\JoinTable(name="problemas_categorias",
                    joinColumns={@JoinColumn(name="problema_id", referencedColumnName="id")},
                    inverseJoinColumns={@JoinColumn(name="categoria_id", referencedColumnName="id")}
                   )
   */
  protected $id_categoria;
  //protected $categorias;


  /**
   * @ORM\Column(type="string", length=100)
   */
  protected $titulo;

  
  /**
   * @ORM\Column(type="string", length=1000)
   */
  protected $descripcion;

   /**
   * @ORM\Column(type="string", length=256)
   */
  protected $path_solucion;

  /**
   * @Assert\File(maxSize="2000000")
   */
  private $file;

  protected $autor;
  protected $categoria;

  /**
    * @ManyToOne(targetEntity="Autor", inversedBy="problemas", cascade={"persist"})
    **/
  private $autor_r;

  public function __construct()
  {
    $this->id_categoria = Array();
  }

  public function getCategoria()
  {
    return $this->categoria;
  }

  public function setCategoria($categoria)
  {
    $this->categoria = $categoria;
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
   * Set id_usuario
   *
   * @param integer $idUsuario
   * @return Problema
   */
  public function setIdUsuario($idUsuario)
  {
      $this->id_usuario = $idUsuario;
  
      return $this;
  }

  /**
   * Get id_usuario
   *
   * @return integer 
   */
  public function getIdUsuario()
  {
      return $this->id_usuario;
  }

  public function setAutor($autor){
      $this->autor = $autor;
      return $this;
  }

  public function getAutor(){
      return $this->autor;
  }

  public function setIdCategoria($idCategoria){
      $this->id_categoria = $idCategoria;
      return $this;
  }

  public function getIdCategoria(){
      return $this->id_categoria;
  }
  
  /**
   * Set descripcion
   *
   * @param string $descripcion
   * @return Problema
   */
  public function setDescripcion($descripcion)
  {
      $this->descripcion = $descripcion;
  
      return $this;
  }

  /**
   * Get descripcion
   *
   * @return string 
   */
  public function getDescripcion()
  {
      return $this->descripcion;
  }

  /**
   * Set titulo
   *
   * @param string $titulo
   * @return Problema
   */
  public function setTitulo($titulo)
  {
      $this->titulo = $titulo;
  
      return $this;
  }

  /**
   * Get titulo
   *
   * @return string 
   */
  public function getTitulo()
  {
      return $this->titulo;
  }

  //public function buildForm(FormBuilderInterface $builder, array $options)
  //{
  //  $builder->add('id_usuario','text')
  //    ->add('titulo', 'text')
  //    ->add('descripcion', 'textarea');
  //}
  //public function getName()
  //{
  //  return 'problema';
  //}

  public function upload()
  {
      // the file property can be empty if the field is not required
      //echo $this->getFile()."<br>";
      if (null === $this->getFile()) {
          $this->path_solucion = "ninguno";
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
      $this->path_solucion = $this->getFile()->getClientOriginalName();

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
  public function setFile($file)
  {
      $this->file = $file;
  }
  protected function getUploadDir()
  {
      // get rid of the __DIR__ so it doesn't screw up
      // when displaying uploaded doc/image in the view.
      return 'uploads/soluciones';
  }
  public function getAbsolutePath()
  {
    return null === $this->path
        ? null
        : $this->getUploadRootDir().'/'.$this->path;
  }


    /**
     * Set path_solucion
     *
     * @param string $pathSolucion
     * @return Problema
     */
    public function setPathSolucion($pathSolucion)
    {
        $this->path_solucion = $pathSolucion;
    
        return $this;
    }

    /**
     * Get path_solucion
     *
     * @return string 
     */
    public function getPathSolucion()
    {
        return $this->path_solucion;
    }

    public function addCategoria(Categoria $categoria)
    {
      array_push($this->id_categoria, $categoria);
    }

    /**
     * Add id_categoria
     *
     * @param \Cassio\EvalBundle\Entity\Categoria $idCategoria
     * @return Problema
     */
    public function addIdCategoria(\Cassio\EvalBundle\Entity\Categoria $idCategoria)
    {
        $this->id_categoria[] = $idCategoria;
    
        return $this;
    }

    /**
     * Remove id_categoria
     *
     * @param \Cassio\EvalBundle\Entity\Categoria $idCategoria
     */
    public function removeIdCategoria(\Cassio\EvalBundle\Entity\Categoria $idCategoria)
    {
        $this->id_categoria->removeElement($idCategoria);
    }

    /**
     * Set autor_r
     *
     * @param \Cassio\EvalBundle\Entity\Autor $autorR
     * @return Problema
     */
    public function setAutorR(\Cassio\EvalBundle\Entity\Autor $autorR = null)
    {
        $this->autor_r = $autorR;
    
        return $this;
    }

    /**
     * Get autor_r
     *
     * @return \Cassio\EvalBundle\Entity\Autor 
     */
    public function getAutorR()
    {
        return $this->autor_r;
    }
}
