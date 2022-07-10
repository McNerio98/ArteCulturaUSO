<?php

use Illuminate\Database\Seeder;
use App\Municipios;
class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Municipios::create(
            ["municipio" => "Acajutla"],
            ["municipio" => "Armenia"],
            ["municipio" => "Caluco"],
            ["municipio" => "Cuisnahuat"],
            ["municipio" => "Izalco"],
            ["municipio" => "Juayúa"],
            ["municipio" => "Nahuizalco"],
            ["municipio" => "Nahulingo"],
            ["municipio" => "Salcoatitán"],
            ["municipio" => "San Antonio del Monte"],
            ["municipio" => "San Julián"],
            ["municipio" => "Santa Catarina Masahuat"],
            ["municipio" => "Santa Isabel Ishuatán"],
            ["municipio" => "Santo Domingo de Guzmán"],
            ["municipio" => "Sonsonate"],
            ["municipio" => "Sonzacate"]
        );        
    }
}
