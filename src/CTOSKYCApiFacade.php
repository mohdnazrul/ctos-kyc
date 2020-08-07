<?php
/**
 * API Library for CTOS KYC.
 * User: Mohd Nazrul Bin Mustaffa
 */
namespace MohdNazrul\CTOSKYCLaravel;

use Illuminate\Support\Facades\Facade;


class CTOSKYCApiFacade extends Facade
{
    protected static function getFacadeAccessor() { return 'CTOSKYCApi'; }
}