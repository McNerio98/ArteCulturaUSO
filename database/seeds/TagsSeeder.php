<?php

use Illuminate\Database\Seeder;
use App\Tag;
use App\Category;

class TagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $cat_musicos = Category::create(['name'=>'Músicos']);
        $cat_cine = Category::create(['name'=>'Cine']);
        $cat_circenses = Category::create(['name'=>'Circenses']);
        $cat_danza = Category::create(['name'=>'Danza']);
        $cat_escenicas = Category::create(['name'=>'Escénicas']);
        $cat_escultural = Category::create(['name'=>'Escultural']);
        $cat_literatura = Category::create(['name'=>'Literatura']);
        $cat_visuales = Category::create(['name'=>'Visuales']);
        $cat_promotores = Category::create(['name'=>'Promotores']);
        $cat_aprendizaje = Category::create(['name'=>'Centros de aprendizaje']);
        $cat_default = Category::create(['name'=>'Predeterminado']);
        
        
        // ::::::::::::::::::::::::::::::::::::::::
        
        //Esto esta segun el documento 


    	// Estos deben ocultarse en el admin panel para evitar eliminacion 
    	Tag::create(['name'=>'Actor','category_id' => $cat_cine->id]);
    	Tag::create(['name'=>'Productor','category_id' => $cat_cine->id]);

    	Tag::create(['name'=>'Acróbata','category_id' => $cat_circenses->id]);
        Tag::create(['name'=>'Contorsionista','category_id' => $cat_circenses->id]);
        Tag::create(['name'=>'Equilibrista','category_id' => $cat_circenses->id]);
    	Tag::create(['name'=>'Malabarista','category_id' => $cat_circenses->id]);	
        Tag::create(['name'=>'Mimo','category_id' => $cat_circenses->id]);
        Tag::create(['name'=>'Payaso','category_id' => $cat_circenses->id]);

        Tag::create(['name'=>'Grupo de danza','category_id' => $cat_danza->id]);

        Tag::create(['name'=>'Grupo de teatro','category_id' => $cat_escenicas->id]);
        Tag::create(['name'=>'Payaso (Clown)','category_id' => $cat_escenicas->id]);

        Tag::create(['name'=>'Escultor','category_id' => $cat_escenicas->id]);

        Tag::create(['name'=>'Escritor','category_id' => $cat_literatura->id]);
        Tag::create(['name'=>'Declamador','category_id' => $cat_literatura->id]);

        Tag::create(['name'=>'Grupos de música','category_id' => $cat_musicos->id]);
        Tag::create(['name'=>'Solista','category_id' => $cat_musicos->id]);

        Tag::create(['name'=>'Fotógrafo','category_id' => $cat_visuales->id]);
        Tag::create(['name'=>'Grafitero','category_id' => $cat_visuales->id]);
        Tag::create(['name'=>'Muralista','category_id' => $cat_visuales->id]);
        Tag::create(['name'=>'Dibujante','category_id' => $cat_visuales->id]);
        Tag::create(['name'=>'Pintor','category_id' => $cat_visuales->id]);

        Tag::create(['name'=>'Casas de la cultura','category_id' => $cat_promotores->id]);
        Tag::create(['name'=>'Asociaciones ONG’s','category_id' => $cat_promotores->id]);
        Tag::create(['name'=>'Exposiciones permanentes','category_id' => $cat_promotores->id]);
        Tag::create(['name'=>'Museo','category_id' => $cat_promotores->id]);
        Tag::create(['name'=>'Cafeterías o bares culturales','category_id' => $cat_promotores->id]);
        Tag::create(['name'=>'Colectivo artísticos','category_id' => $cat_promotores->id]);
        Tag::create(['name'=>'Asociaciones de artistas','category_id' => $cat_promotores->id]);


        Tag::create(['name'=>'Escuela de Cine','category_id' => $cat_aprendizaje->id]);
        Tag::create(['name'=>'Escuela Circense','category_id' => $cat_aprendizaje->id]);
        Tag::create(['name'=>'Escuela de Danza','category_id' => $cat_aprendizaje->id]);
        Tag::create(['name'=>'Escuela Escénica','category_id' => $cat_aprendizaje->id]);
        Tag::create(['name'=>'Escuela Esculturales ','category_id' => $cat_aprendizaje->id]);
        Tag::create(['name'=>'Escuela de Literatura','category_id' => $cat_aprendizaje->id]);
        Tag::create(['name'=>'Escuela de música','category_id' => $cat_aprendizaje->id]);
        Tag::create(['name'=>'Escuela Visual','category_id' => $cat_aprendizaje->id]);

        Tag::create(['name'=>'Otros','category_id' => $cat_default->id]);
    }
}
