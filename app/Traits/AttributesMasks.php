<?php

namespace App\Traits;


trait AttributesMasks
{
    /**
     * @param $zipCode
     * @return string
     */
    public function removeMaskZipCode($zipCode): string
    {
        return $this->removeMask($zipCode);
    }


    /**
     * @param $string
     * @param array $itens
     * @return string
     */
    public function removeMask( $string, array $itens = ['-', '.', '%', '$', ',', '/', '(', ')', ' ']): string
    {
        if(empty($string)){
            return '';
        }
        return str_replace($itens, '', $string);
    }

    /**
     * @param $zipCode
     * @return string
     */
    public function makeMaskZipCode($zipCode): string
    {
        return $this->makeMask($zipCode, '##.###-###');
    }

    /**
     * @param $value
     * @param $mask
     * @return string
     */
    public function makeMask($value, $mask): string
    {

        if(empty($value) || empty($mask)){
            return (!empty($value))? $value : '';
        }

        $value = str_replace(" ", "", $this->removeMask($value));

        for ($i = 0; $i < strlen($value); $i++) {

            if( $value == ""){
                continue;
            }
            $mask[strpos($mask, "#")] = $value[$i];
        }


        return $mask;
    }
}
