<?php
namespace Cassio\EvalBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SubirSol extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder->add('SubirSol');
    $builder->add('Path','text');
    $builder->add('Lenguaje','text');
    $builder->add('Subir','submit');
  }

  public function getName()
  {
    return 'subirsol';
  }
}
