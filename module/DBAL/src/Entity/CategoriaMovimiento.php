<?php
namespace DBAL\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * This class represents a registered user.
 * @ORM\Entity()
 * @ORM\Table(name="CategoriaMovimiento")
 */
class CategoriaMovimiento
{
    /**
     * @ORM\Id
     * @ORM\Column(name="idCategoriaMovimiento", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(name="descripcion", type="string")
     */
    protected $descripcion;


    /**
     * @ORM\ManyToOne(targetEntity="TipoMovimiento")
     * @ORM\JoinColumn(name="idTipoMovimiento", referencedColumnName="idTipoMovimiento")
     */
    protected $TipoMovimiento;

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function setTipoMovimiento($TipoMovimiento)
    {
        $this->TipoMovimiento = $TipoMovimiento;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getTipoMovimiento()
    {
        return $this->TipoMovimiento;
    }


    public function getJSON(){
        $output = "";
        $output .= '"id": "' . $this->getId() .'", ';

        if ($this->getTipoMovimiento()){
            $output .= '"tipoMovimiento": ' . $this->getTipoMovimiento()->getJSON() .', ';
        }else{
            $output .= '"tipoMovimiento": "", ';
        }

        $output .= '"descripcion": "' . $this->getDescripcion() .'"';

        return '{' . $output . '}';
    }
}