<?php

/**
 * Class Personnage
 */
abstract class Personnage
{
    /**
     * @var
     */
    /**
     * @var
     */
    /**
     * @var
     */
    /**
     * @var
     */
    /**
     * @var
     */
    const CEST_MOI = 1;
    const PERSONNAGE_TUE = 2; // Constante renvoyée par la méthode `frapper` si on se frappe soit-même.
    const PERSONNAGE_FRAPPE = 3; // Constante renvoyée par la méthode `frapper` si on a tué le personnage en le frappant.
    const PERSONNAGE_ENSORCELE = 4; // Constante renvoyée par la méthode `frapper` si on a bien frappé le personnage.
    const PAS_DE_MAGIE = 5; // Constante renvoyée par la méthode `lancerUnSort` (voir classe Magicien) si on a bien ensorcelé un personnage.
    const PERSO_ENDORMI = 6; // Constante renvoyée par la méthode `lancerUnSort` (voir classe Magicien) si on veut jeter un sort alors que la magie du magicien est à 0.
    /**
     * @var string
     */
    protected $atout,
        $degats,
        $id,
        $nom,
        $timeEndormi,
        $type; // Constante renvoyée par la méthode `frapper` si le personnage qui veut frapper est endormi.

    /**
     * Personnage constructor.
     * @param array $donnees
     */
    public function __construct(array $donnees)
    {
        $this->hydrate($donnees);
        $this->type = strtolower(static::class);
    }

    /**
     * @param array $donnees
     */
    public function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value) {
            $method = 'set' . ucfirst($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * @param Personnage $perso
     * @return int
     */
    public function frapper(Personnage $perso)
    {
        if ($perso->id == $this->id) {
            return self::CEST_MOI;
        }

        if ($this->estEndormi()) {
            return self::PERSO_ENDORMI;
        }

        // On indique au personnage qu'il doit recevoir des dégâts.
        // Puis on retourne la valeur renvoyée par la méthode : self::PERSONNAGE_TUE ou self::PERSONNAGE_FRAPPE.
        return $perso->recevoirDegats();
    }

    /**
     * @return bool
     */
    public function estEndormi()
    {
        return $this->timeEndormi > time();
    }

    /**
     * @return int
     */
    public function recevoirDegats()
    {
        $this->degats += 5;

        // Si on a 100 de dégâts ou plus, on supprime le personnage de la BDD.
        if ($this->degats >= 100) {
            return self::PERSONNAGE_TUE;
        }

        // Sinon, on se contente de mettre à jour les dégâts du personnage.
        return self::PERSONNAGE_FRAPPE;
    }

    /**
     * @return bool
     */
    public function nomValide()
    {
        return !empty($this->nom);
    }

    /**
     * @return string
     */
    public function reveil()
    {
        $secondes = $this->timeEndormi;
        $secondes -= time();

        $heures = floor($secondes / 3600);
        $secondes -= $heures * 3600;
        $minutes = floor($secondes / 60);
        $secondes -= $minutes * 60;

        $heures .= $heures <= 1 ? ' heure' : ' heures';
        $minutes .= $minutes <= 1 ? ' minute' : ' minutes';
        $secondes .= $secondes <= 1 ? ' seconde' : ' secondes';

        return $heures . ', ' . $minutes . ' et ' . $secondes;
    }

    /**
     * @return string
     */
    public function atout()
    {
        return $this->atout;
    }

    /**
     * @return string
     */
    public function degats()
    {
        return $this->degats;
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function nom()
    {
        return $this->nom;
    }

    /**
     * @return string
     */
    public function timeEndormi()
    {
        return $this->timeEndormi;
    }

    /**
     * @return string
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * @param $atout
     */
    public function setAtout($atout)
    {
        $atout = (int)$atout;

        if ($atout >= 0 && $atout <= 100) {
            $this->atout = $atout;
        }
    }

    /**
     * @param $degats
     */
    public function setDegats($degats)
    {
        $degats = (int)$degats;

        if ($degats >= 0 && $degats <= 100) {
            $this->degats = $degats;
        }
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $id = (int)$id;

        if ($id > 0) {
            $this->id = $id;
        }
    }

    /**
     * @param $nom
     */
    public function setNom($nom)
    {
        if (is_string($nom)) {
            $this->nom = $nom;
        }
    }

    /**
     * @param $time
     */
    public function setTimeEndormi($time)
    {
        $this->timeEndormi = (int)$time;
    }
}