<?php

namespace Artexe\PayBySquareQRCodeGenerator;

/**
 * Description of QrCode
 *
 * @author Vladimír Vráb <www.artexe.sk>
 */
class QrCode {
    
    private $data;
    
    private $niceData;
    
    private $isOk = false;
    
    public function __construct($data, $result) {
        $this->data = $data;
        $this->niceData = "data:image/png;base64," . $data;
        $this->isOk = $result; 
        $this->checkOk();
    }
    
    public function checkOk() {
        if ($this->data == "") {
            $this->isOk = FALSE;
        }
    }
    
    /**
     * Return data
     * @return strings
     */
    public function getData() {
        return $this->data;
    }
    
    /**
     * Return base64data with data:image/png prefix
     * @return strings
     */
    public function getNiceData() {
        return $this->niceData;
    }

    /**
     * 
     * @return boolean
     */
    public function getIsOk() {
        return $this->isOk;
    }


    
}
