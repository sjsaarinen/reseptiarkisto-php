<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

class Raakaaine {
    
    private $id;
    private $nimi;
    private $kalorit;
    private $hiilarit;
    private $proteiinit;
    private $rasvat;
    private $hinta;
    private $tiheys;
    private $kpl_paino;
    private $virheet = array();
    
    public function __construct($id, $nimi, $kalorit, $hiilarit, $proteiinit, $rasvat, $hinta, $tiheys, $kpl_paino){
        $this->id = $id;
        $this->nimi = $nimi;
        $this->kalorit = $kalorit;
        $this->hiilarit = $hiilarit;
        $this->proteiinit = $proteiinit;
        $this->rasvat = $rasvat;
        $this->hinta = $hinta;
        $this->tiheys = $tiheys;
        $this->kpl_paino = $kpl_paino;
    }
    
    //READ
    /**
     * Hakee kaikki raaka-aineet kannasta, aakkosjärjestyksessä nimen mukaan
     * 
     * @return \Raakaaine
     */
    public static function haeKaikki(){
        $sql = "SELECT id, nimi, kalorit, hiilarit, proteiinit, rasvat, hinta, tiheys, kpl_paino from raakaaineet order by nimi";
        $kysely = getTietokantayhteys()->prepare($sql); $kysely->execute();
    
        $tulokset = array();
            foreach($kysely->fetchAll(PDO::FETCH_OBJ) as $tulos) {
                $raakaaine = new Raakaaine($tulos->id, $tulos->nimi, $tulos->kalorit, $tulos->hiilarit, $tulos->proteiinit, $tulos->rasvat, $tulos->hinta, $tulos->tiheys, $tulos->kpl_paino); 
                $tulokset[] = $raakaaine;
            }
        return $tulokset;
        
    }
    
    /**
     * Hakee raaka-aineen kannasta
     * 
     * @param type $id
     * @return \Raakaaine|null
     */
    public static function hae($id){
        $sql = "SELECT id, nimi, kalorit, hiilarit, proteiinit, rasvat, hinta, tiheys, kpl_paino from raakaaineet where id=?";
        $kysely = getTietokantayhteys()->prepare($sql); $kysely->execute(array($id));
        $tulos = $kysely->fetchObject();
        if (!$tulos == null){
            return new Raakaaine($tulos->id, $tulos->nimi, $tulos->kalorit, $tulos->hiilarit, $tulos->proteiinit, $tulos->rasvat, $tulos->hinta, $tulos->tiheys, $tulos->kpl_paino);
        } else {
            return null;
        }
    }
    
    public static function haeNimi($id){
        $sql = "SELECT nimi from raakaaineet where id=?";
        $kysely = getTietokantayhteys()->prepare($sql); $kysely->execute(array($id));
        $tulos = $kysely->fetchObject();
        if (!$tulos == null){
            return $tulos->nimi;
        } else {
            return null;
        }
    }
    
    /**
     * Hakee yhden sivun raaka-aineita kannasta
     * 
     * @param type $sivu
     * @param type $montako
     * @return \Raakaaine
     */
    public static function haeSivu($sivu, $montako){
        $sql = "SELECT * from raakaaineet ORDER by nimi LIMIT ? OFFSET ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kohta = $montako*$sivu;
        $kysely->execute(array($montako, $kohta));
        //$kysely->execute();
        $tulokset = array();
            foreach($kysely->fetchAll(PDO::FETCH_OBJ) as $tulos) {
                $raakaaine = new Raakaaine($tulos->id, $tulos->nimi, $tulos->kalorit, $tulos->hiilarit, $tulos->proteiinit, $tulos->rasvat, $tulos->hinta, $tulos->tiheys, $tulos->kpl_paino); 
                $tulokset[] = $raakaaine;
            }
        return $tulokset;    
    }
    
    /**
     * Hakee parametrinä annettua nimeä vastaavat raaka-aineet kannasta
     * 
     * @param type $nimi
     * @return \Raakaaine
     */
    public static function haeNimella($nimi, $sivu, $montako){
        $sql = "SELECT * from raakaaineet WHERE nimi ILIKE ? ORDER by nimi LIMIT ? OFFSET ?";
        $kysely = getTietokantayhteys()->prepare($sql);
        $kohta = $montako*$sivu;
        $kysely->execute(array("$nimi%", $montako, $kohta));
        $tulokset = array();
            foreach($kysely->fetchAll(PDO::FETCH_OBJ) as $tulos) {
                $raakaaine = new Raakaaine($tulos->id, $tulos->nimi, $tulos->kalorit, $tulos->hiilarit, $tulos->proteiinit, $tulos->rasvat, $tulos->hinta, $tulos->tiheys, $tulos->kpl_paino); 
                $tulokset[] = $raakaaine;
            }
        return $tulokset;
    }
    
    public static function poistaRaakaaineKannasta($id){
        $sql = "DELETE from raakaaineet where id=?";
        $kysely = getTietokantayhteys()->prepare($sql); 
        try { 
            $kysely->execute(array($id));
        } catch (PDOException $e) { 
            return false; 
        } 
        return true;
    }
    
    /**
     * Palauttaa nimeä vastaavien raaka-aineden lukumääärän kannassa
     * 
     * @return int
     */
    public static function raakaaineidenLkm($nimi){
        $sql = "SELECT COUNT(*) from raakaaineet where nimi ILIKE ?";
        $kysely = getTietokantayhteys()->prepare($sql); $kysely->execute(array("$nimi%"));
        return $kysely->fetchColumn(0);
    }
    
    //CREATE
    /**
     * Lisää raaka-aineen kantaan
     * 
     * @return type
     */
    public function lisaaKantaan(){
        $sql = "INSERT INTO raakaaineet(nimi, kalorit, hiilarit, proteiinit, rasvat, hinta, tiheys, kpl_paino) VALUES(?,?,?,?,?,?,?,?) RETURNING id";
        $kysely = getTietokantayhteys()->prepare($sql);

        $ok = $kysely->execute(array($this->getNimi(), $this->getKalorit(), $this->getHiilarit(), $this->getProteiinit(), $this->getRasvat(), $this->getHinta(), $this->getTiheys(), $this->getKplPaino()));
        if ($ok) {
            $this->id = $kysely->fetchColumn();
        }
        return $ok;
    }
    
    //UPDATE
    /**
     * Päivittää raaka-aineen kantaan
     * 
     * @return type
     */
    public function paivitaKantaan(){
        $sql = "UPDATE raakaaineet SET nimi=?, kalorit=?, hiilarit=?, proteiinit=?, rasvat=?, hinta=?, tiheys=?, kpl_paino=? WHERE id=? RETURNING id";
        $kysely = getTietokantayhteys()->prepare($sql);

        $ok = $kysely->execute(array($this->getNimi(), $this->getKalorit(), $this->getHiilarit(), $this->getProteiinit(), $this->getRasvat(), $this->getHinta(), $this->getTiheys(), $this->getKplPaino(), $this->getId()));
        if ($ok) {
            $this->id = $kysely->fetchColumn();
        }
        return $ok;
    }
    
    //DELETE
    /**
     * Poistaa raaka-aineen kannasta
     * 
     * @return boolean
     */
    public function poistaKannasta(){
        $sql = "DELETE from raakaaineet where id=?";
        $kysely = getTietokantayhteys()->prepare($sql); 
        try { 
            $kysely->execute(array($this->getId()));
        } catch (PDOException $e) { 
            return false; 
        } 
        return true;
    }
    
    
    /**
     * Tarkistaa onko Raakaaine kelvollinen
     * 
     * @return boolean
     */
    public function onkoKelvollinen(){
        return empty($this->virheet);
    }
    
    //getters 
    public function getId(){
        return $this->id;
    }
    
    public function getNimi(){
        return $this->nimi;
    }
    
    public function getKalorit(){
        return $this->kalorit;
    }

    public function getHiilarit() {
        return $this->hiilarit;
    }

    public function getProteiinit() {
        return $this->proteiinit;
    }

    public function getRasvat() {
        return $this->rasvat;
    }

    public function getHinta() {
        return $this->hinta;
    }
    
    public function getTiheys() {
        return $this->tiheys;
    }
    
    public function getKplPaino() {
        return $this->kpl_paino;
    }
    
    public function getVirheet(){
        return $this->virheet;
    }
    
    //setters
    public function setId($id){
        $this->id = $id;
    }
    
    public function setNimi($nimi){
        $this->nimi = $nimi;
        
        if (trim($this->nimi) == ''){
            $this->virheet['nimi'] = "Nimi ei saa olla tyhjä";
        } else{
            unset($this->virheet['nimi']);
        }
    }

    public function setHiilarit($hiilarit) {
        $this->hiilarit = $hiilarit;
        $this->tarkistaAtribuutti('hiilarit', $hiilarit);
    }

    public function setProteiinit($proteiinit) {
        $this->proteiinit = $proteiinit;
        $this->tarkistaAtribuutti('proteiinit', $proteiinit);
    }

    public function setRasvat($rasvat) {
        $this->rasvat = $rasvat;
        $this->tarkistaAtribuutti('rasvat', $rasvat);
    }
    
    public function setKalorit($kalorit) {
        $this->kalorit = $kalorit;
        $this->tarkistaAtribuutti('kalorit', $kalorit);
    }

    public function setTiheys($tiheys){
        $this->tiheys = $tiheys;
        $this->tarkistaAtribuutti('tiheys', $tiheys);
    }
    
    public function setKplPaino($kpl_paino){
        $this->kpl_paino = $kpl_paino;
        $this->tarkistaAtribuutti('kpl_paino', $kpl_paino);
    }
    
    public function setHinta($hinta){
        $this->hinta = $hinta;
        $this->tarkistaAtribuutti('hinta', $hinta);
    }
    
    //apufunktoita
    
    //tarkistaa onko luku kelvollinen (eli väliltä 0.0-10000.0)
    private function onkoOkLuku($syote) {
        $ok = is_numeric($syote) && $syote >= 0 && $syote < 10000; 
        return $ok;
    }
    
    //tarkistaa onko attribuutti kelvollinen, jos ei ole talletta virheilmoituksen attribuuttia vastaavaan kenttään virheet taulukkoon
    private function tarkistaAtribuutti($nimi, $arvo){
        if (!$this->onkoOkLuku($arvo)){
            $this->virheet[$nimi] = "Syötteen tulee olla luku väliltä 0.00 - 9999.99";
        } else{
            unset($this->virheet[$nimi]);
        }
    }

}

