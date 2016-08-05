<?php
/*
    This file is part of CyberGestionnaire.

    CyberGestionnaire is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    (at your option) any later version.

    CyberGestionnaire is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with CyberGestionnaire.  If not, see <http://www.gnu.org/licenses/>

*/

include_once("Mysql.class.php");


class Horaire
{
    private $_id;
    private $_idEspace;
    private $_idJour;
    private $_horaire1Debut;
    private $_horaire1Fin;
    private $_horaire2Debut;
    private $_horaire2Fin;
    private $_typeUniteHoraire;
    
    private $_jours = array("","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche") ;
    
    private function __construct($array) {
        $this->_id                  = $array["id_horaire"];
        $this->_idEspace            = $array["id_epn"];
        $this->_idJour              = $array["jour_horaire"];
        $this->_horaire1Debut       = $array["hor1_begin_horaire"];
        $this->_horaire1Fin         = $array["hor1_end_horaire"];
        $this->_horaire2Debut       = $array["hor2_begin_horaire"];
        $this->_horaire2Fin         = $array["hor2_end_horaire"];
        $this->_typeUniteHoraire    = $array["unit_horaire"];
    }

    
    
    public function getHorairesById($id) {
        $horaire = null;
        
        if ($id != 0) {
            $db = Mysql::opendb();
            $id = mysqli_real_escape_string($db, $id);
            $sql = "SELECT * "
                 . "FROM `tab_horaire` "
                 . "WHERE `id_horaire` = " . $id . "";
            $result = mysqli_query($db,$sql);
            Mysql::closedb($db);
            
            if (mysqli_num_rows($result) == 1) {
                $horaire = new Horaire(mysqli_fetch_assoc($result));
            }
        }
        
        return $horaire;

        
    }
    
    public function getHoraire1Debut() {
        return $this->_horaire1Debut;
    }
    public function getHoraire1Fin() {
        return $this->_horaire1Fin;
    }
    public function getHoraire2Debut() {
        return $this->_horaire2Debut;
    }
    public function getHoraire2Fin() {
        return $this->_horaire2Fin;
    }
    
    public function getIdJour() {
        return $this->_idJour;
    }

    public function getJour() {
        return $this->_jours[$this->_idJour];
    }
    
    
    public static function getHorairesByIdEspace($idEspace) {
        $horaires = null;
        if ( is_int($idEspace)  && $idEspace != 0) {
            $db       = Mysql::opendb();
            $sql      = "SELECT * FROM `tab_horaire` WHERE `id_epn`=" . $idEspace. " ORDER BY jour_horaire" ;
            $result   = mysqli_query($db,$sql);
            Mysql::closedb($db);
        
            if ($result) {
                if (mysqli_num_rows($result) == 7) { // vérification de cohérence : on doit avoir 7 jours
                    $horaires = array();
                    while($row = mysqli_fetch_assoc($result)) {
                        $horaires[] = new Horaire($row);
                    }
                }
            }
        }
        
        return $horaires;  
    }
    
    public static function convertHoraire($temps) {
        $h    = substr($temps,0,2) ;
        $m    = substr($temps,3,2) ;
        $conv = (60*$h)+$m ;
        return $conv ;
    }
    
    
}