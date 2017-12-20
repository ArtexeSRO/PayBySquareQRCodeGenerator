<?php

namespace Artexe\PayBySquareQRCodeGenerator;

/**
 * Description of QrCodeGenerator
 *
 * @author Vladimír Vráb <www.artexe.sk>
 */
class QrCodeGenerator {
    
    /** Username */
    private $username;
    
    /** Password */
    private $password;
    
    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }
    
    public function generateXmlString($invoiceID, $iban, $bic, $amount, $currency, $paymentDueDate, $vs, $ks, $ss) {
        
        $trimmedIban = str_replace(' ', '', $iban);
        
        $xml = '<BySquareXmlDocuments xmlns:xsd="http://www.w3.org/2001/XMLSchema"
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                    <Username>'.$this->username.'</Username>
                    <Password>'.$this->password.'</Password>
                    <Documents>
                        <Pay xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.bysquare.com/bysquare" xsi:type="Pay">
                            <InvoiceID>'.$invoiceID.'</InvoiceID>
                            <Payments>
                              <Payment>
                                <PaymentOptions>paymentorder</PaymentOptions>
                                <Amount>'.$amount.'</Amount>
                                <CurrencyCode>'.$currency.'</CurrencyCode>
                                <PaymentDueDate>'.$paymentDueDate.'</PaymentDueDate>
                                <VariableSymbol>'.$vs.'</VariableSymbol>
                                <ConstantSymbol>'.$ks.'</ConstantSymbol>
                                <SpecificSymbol>'.$ss.'</SpecificSymbol>
                                <BankAccounts>
                                  <BankAccount>
                                    <IBAN>'.$trimmedIban.'</IBAN>
                                    <BIC>'.$bic.'</BIC>
                                  </BankAccount>
                                </BankAccounts>
                              </Payment>
                            </Payments>
                        </Pay>
                    </Documents>
               </BySquareXmlDocuments>';
        
        return $xml;
    }
    
    /**
     * Returns QR code if given xml file
     * @param string $xml
     * @return \InvoiceModule\QrCode\QrCode
     */
    public function sendRequest($xml) {
        
        $curl = curl_init();
        
        curl_setopt($curl, CURLOPT_URL, "https://app.bysquare.com/api/generateQR");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
        $result = curl_exec($curl);
        
        $isOk = curl_errno($curl);
        if ($isOk == 0) {
            $isOk = true;
        } else {
            $isOk = false;
        }
        curl_close($curl);
        
        $returnedXml = simplexml_load_string($result);
        
        $qrCode = new QrCode($returnedXml->PayBySquare, $isOk);
        return $qrCode;
    }
    
}
