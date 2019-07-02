<?php
namespace DBAL\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * This class represents a registered user.
 * @ORM\Entity()
 * @ORM\Table(name="TipoMovimiento")
 */
class TipoMovimiento
{
    /**
     * @ORM\Id
     * @ORM\Column(name="idTipoMovimiento", type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(name="descripcion")
     */
    protected $descripcion;

    /**
     * @ORM\Column(name="color")
     */
    protected $color;

    public function setdescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function setColor($color)
    {
        $this->color = $color;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getColor()
    {
        return $this->color;
    }


    public function getJSON(){
        $output = "";
        $output .= '"id": "' . $this->getId() .'", ';
        $output .= '"descripcion": "' . $this->getDescripcion() .'", ';
        $output .= '"color": "' . $this->getColor() .'"';
        
        return '{' . $output . '}';
    }
}