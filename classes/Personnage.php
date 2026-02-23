<?php
class Personnage
{
    private $id;
    private $name;
    private $pv;
    private $atk;
    private $img;
    private $specialName;
    private $specialMulti;
    private $specialType;
    private $isProtected = false;
    private $specialCount = 2;

    //Constante
    const MAX_PV = 100;

    //Contructeur
    public function __construct(array $donnees)
    {
        $this->hydrate($donnees);
    }

    //Getters
    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPv()
    {
        return $this->pv;
    }

    public function getAtk()
    {
        return $this->atk;
    }

    public function getImg()
    {
        return $this->img;
    }

    public function getSpecialName()
    {
        return $this->specialName;
    }

    public function getSpecialMulti()
    {
        return $this->specialMulti;
    }
    public function getSpecialType()
    {
        return $this->specialType;
    }
    
    public function getSpecialCount()
{
    return $this->specialCount;
}

    //Setters
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setPv($pv)
    {
        $this->pv = $pv;
        if ($pv < 0)
            $this->pv = 0;
    }

    public function setAtk($atk)
    {
        $this->atk = $atk;
    }

    public function setImg($img)
    {
        $this->img = $img;
    }

    public function setSpecialName($specialName)
    {
        $this->specialName = $specialName;
    }

    public function setSpecialMulti($specialMulti)
    {
        $this->specialMulti = $specialMulti;
    }
    public function setSpecialType($specialType)
    {
        $this->specialType = $specialType;
    }

    //Hydratation
    private function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value) {
            // On récupère le nom du setter correspondant à l'attribut. 
            $method = 'set' . ucfirst($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
                $this->$key = $value;
            }
        }
    }

    //Méthodes de jeu

    public function is_alive()
    {
        return $this->pv > 0;
    }

    public function attaquer(Personnage $cible)
    {
        $cible->recevoirDegat($this->getAtk());
    }

    public function regenerer($x = null)
    {
        if (is_null($x)) {
            return $this->pv = self::MAX_PV;
        } else {
            return $this->setPv($this->pv + $x);
        }
    }

    public function recevoirDegat($degat)
    {
        if ($this->isProtected) {
            $degatConsomme = $degat / $this->specialMulti;
            $this->setPv($this->getPv() - $degatConsomme);
            $this->isProtected = false;
        } else {
            $this->setPv($this->getPv() - $degat);
        }
    }

    public function coupSpecial(Personnage $cible)
    {
        if ($this->specialCount > 0) {
            $this->specialCount--;

            switch ($this->specialType) {
                case 'attaque':
                    $specialAtk = $this->atk * $this->specialMulti;
                    $cible->recevoirDegat($specialAtk);
                    break;
                case 'soin':
                    $specialSoin = 20 * $this->specialMulti;
                    $this->regenerer($specialSoin);
                    break;
                case 'défense':
                    $this->isProtected = true;
                    break;
            }
        }
    }
}
?>