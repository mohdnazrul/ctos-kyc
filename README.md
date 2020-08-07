# CTOS API Package (Version 2)

This Library allows to query the CTOS  - B2B API for registered users. 

You need the access details that were provided to you to make any calls to the API.
For exact parameters in the data array/XML, refer to your offline documentation.

If you do not know what all this is about, then you probably do not need or want this library.

# Configuration

## .env file

Configuration via the .env file currently allows the following variables to be set:

- CTOS\_KYC\_URL='http://api.endpoint/url/'
- CTOS\_KYC\_USERNAME=demouser 
- CTOS\_KYC\_PASSWORD=demoPassword

## Available functions

```php
CTOSV2::generateXMLFromArray($data, $XMLEscape = true);
```
This function takes an array of options for the CTOS API and generates the XML code
that can be submitted via the API Call. XMLEscape auto set true because data return generete XML 
must be in html special chars in 'UTF-8'. 
Example:
```php
// This is for Company Format
[
        'company_code' => 'XXXXXX', // Required
        'account_no' => 'AAAAAA', // Required
        'user_id' => 'AAAAAA', // Required
        'records' => [
            'type' => 'C', // Required and type is C for Company
            'name' => 'COMPANY_NAME',
            'ic_lc' => 'COMPANY_REGISTRATION_NO', // Conditional if type is individual (old IC), if type is Company is registration no
            'nic_br' => '', // Conditional if type is individual
            'country>' => 'MALAYSIA' // Required
        ],
]

// This is for Individual Format
[
        'company_code' => 'XXXXXX', // Required
        'account_no' => 'AAAAAA', // Required
        'user_id' => 'AAAAAA', // Required
        'records' => [
            'type' => 'I', // Required and type is I for Individual
            'name' => 'INDIVIDUAL NAME',
            'ic_lc' => '', // Conditional if type is individual (old IC), if type is Company is registration no
            'nic_br' => 'PASSPORT NO/NICR', // Conditional if type is individual
            'country>' => 'MALAYSIA' // Required
        ],
]

// This is for Business Format
[
        'company_code' => 'XXXXXX', // Required
        'account_no' => 'AAAAAA', // Required
        'user_id' => 'AAAAAA', // Required
        'records' => [
            'type' => 'B', // Required and type is B for Individual
            'name' => 'BUSINESS NAME', 
            'ic_lc' => 'BUSINESS_REGISTRATION_NO',  // Conditional if type is individual (old IC), if type is business is business no
            'nic_br' => '', // Conditional if type is individual
            'country>' => 'MALAYSIA' // Required
        ],
]


``` 

will generate
**// This is for Company Format**
```xml
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ws="http://ws.proxy.xml.ctos.com.my/">
   <soapenv:Header/>
   <soapenv:Body>
      <ws:request>
         <!--Optional:-->
         <input>
		&lt;batch output=&quot;0&quot; no=&quot;1234&quot; xmlns=&quot;http://ws.cmctos.com.my/ctosnet/kyc&quot;&gt;
			&lt;company_code&gt;XXXXXX&lt;/company_code&gt;
			&lt;account_no&gt;AAAAAA&lt;/account_no&gt;
			&lt;user_id&gt;AAAAAA&lt;/user_id&gt;
			&lt;records&gt;
				&lt;type&gt;C&lt;/type&gt;
            &lt;name&gt;COMPANY_NAME&lt;/name&gt;
				&lt;ic_lc&gt;COMPANY_REGISTRATION_NO&lt;/ic_lc&gt;
				&lt;nic_br/&gt;
				&lt;country/&gt;
			&lt;/records&gt;
			&lt;/batch&gt;
		</input>
      </ws:request>
   </soapenv:Body>
</soapenv:Envelope>

```
**// This is for Individual Format**
```xml
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ws="http://ws.proxy.xml.ctos.com.my/">
   <soapenv:Header/>
   <soapenv:Body>
      <ws:request>
         <!--Optional:-->
         <input>
		&lt;batch output=&quot;0&quot; no=&quot;1234&quot; xmlns=&quot;http://ws.cmctos.com.my/ctosnet/kyc&quot;&gt;
			&lt;company_code&gt;XXXXXX&lt;/company_code&gt;
			&lt;account_no&gt;AAAAAA&lt;/account_no&gt;
			&lt;user_id&gt;AAAAAA&lt;/user_id&gt;
			&lt;records&gt;
				&lt;type&gt;C&lt;/type&gt;
            &lt;name&gt;INDIVIDUAL NAME&lt;/name&gt;
				&lt;ic_lc/&gt;
            &lt;nic_br&gt;NRIC&lt;/nic_br&gt;
				&lt;country/&gt;
			&lt;/records&gt;
			&lt;/batch&gt;
		</input>
      </ws:request>
   </soapenv:Body>
</soapenv:Envelope>
```

**// This is for Business Format**
```xml
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ws="http://ws.proxy.xml.ctos.com.my/">
   <soapenv:Header/>
   <soapenv:Body>
      <ws:request>
         <!--Optional:-->
         <input>
		&lt;batch output=&quot;0&quot; no=&quot;1234&quot; xmlns=&quot;http://ws.cmctos.com.my/ctosnet/kyc&quot;&gt;
			&lt;company_code&gt;XXXXXX&lt;/company_code&gt;
			&lt;account_no&gt;AAAAAA&lt;/account_no&gt;
			&lt;user_id&gt;AAAAAA&lt;/user_id&gt;
			&lt;records&gt;
				&lt;type&gt;C&lt;/type&gt;
            &lt;name&gt;BUSINESS NAME&lt;/name&gt;
				&lt;ic_lc&gt;BUSINESS REGISTRATION NO&lt;/ic_lc&gt;
				&lt;nic_br/&gt;
				&lt;country/&gt;
			&lt;/records&gt;
			&lt;/batch&gt;
		</input>
      </ws:request>
   </soapenv:Body>
</soapenv:Envelope>
```

**FOR LARAVEL SETUP CONFIGURATION:-**

- Do composer require mohdnazrul/laravel-ctos-kyc
```php
   composer require mohdnazrul/laravel-ctos-kyc
```
- Add this syntax inside config/app.php
```php
   ....
   'providers'=> [
     .
     MohdNazrul\CTOSKYCLaravel\CTOSKYCServiceProvider::class,
     .
   ],
   'aliases' => [
      .
      'CTOSKYC' => MohdNazrul\CTOSKYCLaravel\CTOSKYCApiFacade::class,
      '
    ],
``` 
- Do publish as below
```php
php artisan vendor:publish --tag=ctoskyc 
```
- You can edit the default configuration CTOS KYC inside config/ctoskyc.php based your account as below
```php
return [
    'serviceUrl'    =>  env('CTOS_KYC_URL','http://localhost'),
    'username'      =>  env('CTOS_KYC_USERNAME','username'),
    'password'      =>  env('CTOS_KYC_PASSWORD','password')
];
``` 







     
