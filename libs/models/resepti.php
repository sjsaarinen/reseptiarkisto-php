<?php

class Resepti {
    private $id;
    private $nimi;
    private $kategoria;
    private $omistaja;
    private $lahde;
    private $juomasuositus;
    private $valmistusohje;
    
    function __construct($id, $nimi, $kategoria, $omistaja, $lahde, $juomasuositus, $valmistusohje) {
        $this->id = $id;
        $this->nimi = $nimi;
        $this->kategoria = $kategoria;
        $this->omistaja = $omistaja;
        $this->lahde = $lahde;
        $this->juomasuositus = $juomasuositus;
        $this->valmistusohje = $valmistusohje;
    }
    
    public function getId() {
        return $this->id;
    }

    public function getNimi() {
        return $this->nimi;
    }

    public function getKategoria() {
        return $this->kategoria;
    }

    public function getOmistaja() {
        return $this->omistaja;
    }

    public function getLahde() {
        return $this->lahde;
    }

    public function getJuomasuositus() {
        return $this->juomasuositus;
    }

    public function getValmistusohje() {
        return $this->valmistusohje;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNimi($nimi) {
        $this->nimi = $nimi;
    }

    public function setKategoria($kategoria) {
        $this->kategoria = $kategoria;
    }

    public function setOmistaja($omistaja) {
        $this->omistaja = $omistaja;
    }

    public function setLahde($lahde) {
        $this->lahde = $lahde;
    }

    public function setJuomasuositus($juomasuositus) {
        $this->juomasuositus = $juomasuositus;
    }

    public function setValmistusohje($valmistusohje) {
        $this->valmistusohje = $valmistusohje;
    }



}

?>