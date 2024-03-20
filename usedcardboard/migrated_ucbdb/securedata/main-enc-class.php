<?php
class cipher
{
    private mixed $securekey;
    private mixed $iv_size;

    //Old function 
    /*function __construct($textkey)
    {
        $this->iv_size = mcrypt_get_iv_size(
            MCRYPT_RIJNDAEL_128,
            MCRYPT_MODE_CBC
        );
        $this->securekey = hash(
            'sha256',
            $textkey,
            TRUE
        );
    }
    */
    function __construct(string $textkey)
    {
        $this->iv_size = openssl_cipher_iv_length('AES-256-CBC');
        $this->securekey = hash('sha256', $textkey, TRUE);
    }

    //old function
    /*function encrypt($input)
    {
        $iv = mcrypt_create_iv($this->iv_size,MCRYPT_DEV_URANDOM);
        return base64_encode(
            $iv . mcrypt_encrypt(
                MCRYPT_RIJNDAEL_128,
                $this->securekey,
                $input,
                MCRYPT_MODE_CBC,
                $iv
            )
        );
    }*/
    function encrypt(string $input):string
    {
        $iv = openssl_random_pseudo_bytes($this->iv_size);
        $encrypted = openssl_encrypt($input, 'AES-256-CBC', $this->securekey, OPENSSL_RAW_DATA, $iv);
        return base64_encode($iv . $encrypted);
    }
    
    //old function
  /* 
    function decrypt($input)
    {
            $input = base64_decode($input);
            $iv = substr(
                $input,
                0,
                $this->iv_size
            );
            $cipher = substr(
                $input,
                $this->iv_size
            );
            return trim(
                mcrypt_decrypt(
                    MCRYPT_RIJNDAEL_128,
                    $this->securekey,
                    $cipher,
                    MCRYPT_MODE_CBC,
                    $iv
                )
            );
        }
    }
    */
    function decrypt(string $input): string
    {
        $input = base64_decode($input);
        $iv = substr($input, 0, $this->iv_size);
        $cipher = substr($input, $this->iv_size);
        return trim(openssl_decrypt($cipher, 'AES-256-CBC', $this->securekey, OPENSSL_RAW_DATA, $iv));
    }
    /*function encryptstr($stxt){
        $maink = "505241405938343140646f3435342352614d3d2d4348";
        $cipher = new cipher($maink);

        $encrypted_text = $cipher->encrypt($stxt);  
        return $encrypted_text;
    }*/

    /*function decryptstr($stxt){
        $maink = "505241405938343140646f3435342352614d3d2d4348";
        $cipher = new cipher($maink);

        $decrypted_text = $cipher->decrypt($stxt);
        return $decrypted_text;
    }*/
}