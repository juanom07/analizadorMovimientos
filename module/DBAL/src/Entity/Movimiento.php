<?php
namespace DBAL\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * This class represents a registered user.
 * @ORM\Entity()
 * @ORM\Table(name="Movimiento")
 */
class Movimiento
{
    /**
     * @ORM\Id
     * @ORM\Column(name="idMovimiento", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(name="descripcion")
     */
    protected $descripcion;


    /**
     * @ORM\Column(name="fecha")
     */
    protected $fecha;


    /**
     * @ORM\Column(name="valor")
     */
    protected $valor;

    /**
     * @ORM\ManyToOne(targetEntity="MotivoMovimiento")
     * @ORM\JoinColumn(name="idMotivoMovimiento", referencedColumnName="idMotivoMovimiento")
     */
    protected $MotivoMovimiento;

    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function setMotivoMovimiento($MotivoMovimiento)
    {
        $this->MotivoMovimiento = $MotivoMovimiento;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function getMotivoMovimiento()
    {
        return $this->MotivoMovimiento;
    }

    public function getJSON(){
        $output = "";
        $output .= '"id": "' . $this->getId() .'", ';
        $output .= '"descripcion": "' . $this->getDescripcion() .'"';
        $output .= '"fecha": "' . $this->getFecha() .'"';
        $output .= '"valor": "' . $this->getValor() .'"';
        $output .= '"motivoMovimiento": ' . $this->getMotivoMovimiento()->getJSON() .', ';

        return '{' . $output . '}';
    }
}