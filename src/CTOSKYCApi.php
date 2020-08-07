<?php
/**
 * API Library for CTOS KYC.
 * User: Mohd Nazrul Bin Mustaffa
 */

namespace MohdNazrul\CTOSKYCLaravel;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class CTOSKYCApi
{
    private $username;
    private $password;
    private $serviceURL;

    public function __construct($serviceUrl, $username, $password)
    {
        $this->username = $username;
        $this->password = $password;
        $this->serviceURL = $serviceUrl;
    }

    public function generateXMLFromArray($dataArray, $method, $XMLEscape = true)
    {

        $xmlString = '<batch no="1234" output="0" xmlns="http://ws.cmctos.com.my/ctosnet/kyc">';

        foreach ($dataArray as $key => $value) {
            if ($key == 'records') {
                $xmlString .= "<$key>";
                foreach ($value as $key2 => $innerValue) {
                    if (!empty($innerValue)) {
                        $xmlString .= "<$key2>$innerValue</$key2>";
                    } else {
                        $xmlString .= "<$key2/>";
                    }
                }
                $xmlString .= "</$key>";
            } else {
                if (!empty($value)) {
                    $xmlString .= "<$key>$value</$key>";
                } else {
                    $xmlString .= "<$key/>";
                }

            }
        }
        $xmlString .= '</batch>';
        
        if ($XMLEscape) {
            $escape = htmlspecialchars($xmlString, ENT_QUOTES, 'UTF-8');
        } else {
            $escape = $xmlString;
        }

        $str = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ws="http://ws.proxy.xml.ctos.com.my/">'
            . '<soapenv:Header/>'
            . '<soapenv:Body>'
            . '<ws:requestKyc>'
            . '<!--Optional:-->'
            . '<input>';
        $str .= $escape;
        $str .= '</input></ws:requestKyc></soapenv:Body></soapenv:Envelope>';

        return $str;

    }

    public function getReport($requestXML, $decryptedResponse = true)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_URL, $this->serviceURL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestXML); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->Headers($requestXML));

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return new \Exception(curl_error($ch));
        }

        curl_close($ch);

        if($decryptedResponse){
            return base64_decode($this->getResponseBody($response));
        } else {
            return $this->getResponseBody($response);
        }

    }

    private function Headers($requestXML){

        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: ",
            "Content-length: " . strlen($requestXML),
            "username:" . $this->username,
            "password:" . $this->password
        );

        return $headers;
    }

    private function getResponseBody($response)
    {
        $response1 = str_replace("<?xml version='1.0' encoding='UTF-8'?><S:Envelope xmlns:S=\"http://schemas.xmlsoap.org/soap/envelope/\"><S:Body><ns2:requestKycResponse xmlns:ns2=\"http://ws.proxy.xml.ctos.com.my/\"><return>", "", $response);
        $response2 = str_replace("</return></ns2:requestKycResponse></S:Body></S:Envelope>", "", $response1);
        return $response2;
    }

    public function convertToJSON($responseXML){
        $xml = simplexml_load_string($responseXML);
        $json = json_encode($xml);
        $array = json_decode($json, true);
        return $array;
    }

}