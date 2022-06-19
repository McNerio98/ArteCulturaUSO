<div class="_cl1" style="background-color: #f2f2f2;padding: 10px;font-family: Arial, Helvetica, sans-serif;">
    <div class="_cl2" style="width: 100%;max-width: 500px;margin: auto;border-left: 2px solid #31333f;border-right: 2px solid #31333f; background-color: white;">
        <div class="_cl5" style="background-color: #31333f;padding: 10px 0px; ">
            <img class="_cl4"" style="width: 100%;max-width: 200px;display: block;margin-right: auto;" src=" https://conexos.trekinggroup.com/images/ACBrand.png" alt="">
        </div>
        <div class="_cl6" style="padding: 10px 25px;">
            <h3 class="_cl3" style="color: #00B05C;text-align: center;font-size: 160%;padding: 10px 0px;">Verificación de cuenta </h3>

            <p class="_cl10" style="font-size: 110%;color: #8b8484;margin: 10px 0px;">Hola, <span style="color: #31333f;">{{$data->name}}</span>, verifica tu cuenta para ayudarnos a saber que eres el propietario/propietaria de la cuenta.</p>
            <p class="_cl10" style="font-size: 110%;color: #8b8484;margin: 10px 0px;">¡Haz clic en el siguiente botón para verificar tu cuenta! </p>
            
            <a href="{{ route('user.veriry', $data->token) }}" target="_blank"  style="display: block;margin: auto;margin-top: auto;margin-bottom: auto;width: 150px;margin-top: 20px;margin-bottom: 20px;padding: 20px 10px;border: 0px;background-color: #ffc500;color: black;border-radius: 15px;font-size: 110%;text-align: center;text-decoration: none;">CONFIRMAR CORREO</a>
        </div>
    </div>
</div>