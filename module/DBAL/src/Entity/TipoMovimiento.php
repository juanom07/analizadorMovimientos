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

    public function setdescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }


    public function getJSON(){
        $output = "";
        $output .= '"id": "' . $this->getId() .'", ';
        $output .= '"descripcion": "' . $this->getDescripcion() .'"';
        
        return '{' . $output . '}';
    }
}