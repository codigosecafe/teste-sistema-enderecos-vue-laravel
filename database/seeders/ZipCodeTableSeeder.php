<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ZipCodeTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('zip_code')->delete();

        \DB::table('zip_code')->insert(array (
            0 =>
            array (
                'id' => 1,
                'zip_code' => '81260360',
                'street' => 'RUA TELÊMACO DA SILVA QUADROS',
                'complement' => '',
                'neighborhood' => 'CIDADE INDUSTRIAL',
                'city' => 'CURITIBA',
                'state' => 'PR',
                'ibge' => '4106902',
                'ddd' => '41',
                'created_at' => '2021-12-02 19:49:00',
                'updated_at' => '2021-12-02 19:49:00',
                'deleted_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'zip_code' => '83704325',
                'street' => 'RUA MARIA EDITH DE FRANÇA TRAUCZYNSKI',
                'complement' => '',
                'neighborhood' => 'BOQUEIRÃO',
                'city' => 'ARAUCÁRIA',
                'state' => 'PR',
                'ibge' => '4101804',
                'ddd' => '41',
                'created_at' => '2021-12-02 21:04:35',
                'updated_at' => '2021-12-02 21:04:35',
                'deleted_at' => NULL,
            ),
        ));


    }
}
