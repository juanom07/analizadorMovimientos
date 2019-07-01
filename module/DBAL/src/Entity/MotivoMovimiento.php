<?php
namespace DBAL\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * This class represents a registered user.
 * @ORM\Entity()
 * @ORM\Table(name="MotivoMovimiento")
 */
class MotivoMovimiento
{
    /**
     * @ORM\Id
     * @ORM\Column(name="idMotivoMovimiento", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(name="descripcion")
     */
    protected $descripcion;


    /**
     * @ORM\ManyToOne(targetEntity="CategoriaMovimiento")
     * @ORM\JoinColumn(name="idCategoriaMovimiento", referencedColumnName="idCategoriaMovimiento")
     */
    protected $CategoriaMovimiento;

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function setCategoriaMovimiento($CategoriaMovimiento)
    {
        $this->CategoriaMovimiento = $CategoriaMovimiento;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getCategoriaMovimiento()
    {
        return $this->CategoriaMovimiento;
    }


    public function getJSON(){
        $output = "";
        $output .= '"id": "' . $this->getId() .'", ';
        $output .= '"descripcion": "' . $this->getDescripcion() .'"';
        $output .= '"categoriaMovimiento": ' . $this->getCategoriaMovimiento()->getJSON() .', ';

        return '{' . $output . '}';
    }
}