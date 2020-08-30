<?php

use Illuminate\Database\Seeder;
use App\Tag;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// Estos deben ocultarse en el admin panel para evitar eliminacion 
    	Tag::create(['name'=>'Artistas']);
    	Tag::create(['name'=>'Promotores']);
    	Tag::create(['name'=>'Expresiones']);
        Tag::create(['name'=>'Escuelas']);
        Tag::create(['name'=>'Recursos']);
        
    	Tag::create(['name'=>'Baile Moderno']);	
        Tag::create(['name'=>'Musico']);
        Tag::create(['name'=>'Banda Musical']);
        Tag::create(['name'=>'Pintor']);
        Tag::create(['name'=>'Artista Independiente']);
        Tag::create(['name'=>'Cantante']);
        Tag::create(['name'=>'Orquesta']);
    }
}
