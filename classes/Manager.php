<?php
class personnageManager
{
    private $db;

    public function __construct($db)
    {
        $this->setDb($db);
    }

    public function setDb(PDO $db)
    {
        $this->db = $db;
    }

    //Récupère tout les persos
    public function getAll()
    {
        $query = $this->db->query('SELECT * FROM anifruit ORDER BY name');

        while ($donnees = $query->fetch(PDO::FETCH_ASSOC)) {
            $anifruits[] = new Personnage($donnees);
        }

        return $anifruits;
    }

    //Récupère 1 perso
    public function getAnifruitById($id)
    {
        $query = $this->db->prepare('SELECT * FROM anifruit WHERE id = :id');
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $donnees = $query->fetch(PDO::FETCH_ASSOC);

        $anifruit = new Personnage($donnees);
        return $anifruit;
    }

    //Ajout anifruit
    public function add($name, $pv, $atk, $specialName, $specialMulti, $specialType, $img)
    {
        $query = $this->db->prepare("INSERT INTO anifruit(name, pv, atk, specialName, specialMulti, specialType, img ) VALUES (:name, :pv, :atk, :specialName, :specialMulti, :specialType, :img)");
        $query->execute([
            ':name' => $name,
            ':pv' => $pv,
            ':atk' => $atk,
            ':specialName' => $specialName,
            ':specialMulti' => $specialMulti,
            ':specialType' => $specialType,
            ':img' => $img
        ]);
    }

    //Sup anifruit
    public function sup($id)
    {
        $stmt = $this->db->prepare("DELETE FROM anifruit WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    //update anifruit
    public function update(Personnage $a)
    {
        $sql = "UPDATE anifruit SET 
            name = :name, 
            pv = :pv, 
            atk = :atk, 
            specialName = :specialName, 
            specialMulti = :specialMulti, 
            specialType = :specialType 
            WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->execute(
            array(
                ':id' => $a->getId(),
                ':name' => $a->getName(),
                ':pv' => $a->getPv(),
                ':atk' => $a->getAtk(),
                ':specialName' => $a->getSpecialName(),
                ':specialMulti' => $a->getSpecialMulti(),
                ':specialType' => $a->getSpecialType()
            )
        );
    }

}
?>