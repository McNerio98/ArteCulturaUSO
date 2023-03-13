<div class="_cl1" style="background-color: #f2f2f2;padding: 10px;font-family: Arial, Helvetica, sans-serif;">
    <div class="_cl2" style="width: 100%;max-width: 500px;margin: auto;border-left: 2px solid #31333f;border-right: 2px solid #31333f; background-color: white;">
        <div class="_cl5" style="background-color: #31333f;padding: 10px 0px; ">
            <img class="_cl4"" style="width: 100%;max-width: 200px;display: block;margin-right: auto;" src=" https://conexos.trekinggroup.com/images/ACBrand.png" alt="">
        </div>
        <div class="_cl6" style="padding: 10px 25px;">
            <img class="_cl11" style="width: 100%;max-width: 100px;display: block;margin: auto;" src="https://conexos.trekinggroup.com/images/well-done.png" alt="">
            <h3 class="_cl3" style="color: #00B05C;text-align: center;font-size: 160%;padding: 10px 0px;">Aprobación de cuenta</h3>

            <p class="_cl10" style="font-size: 110%;color: #8b8484;margin: 10px 0px;">Hola, <span style="color: #31333f;">{{$user->name}}</span>, tu cuenta de acceso a nuestra plataforma ha sido aprobada. Tú nombre de usuario y contraseña son: </p>
            <div class="_cl7" style="background-color: #eee;padding: 10px;border-radius: 15px;">
                <table style="width: 100%;">
                    <tr>
                        <th style="text-align: right;width: 50%;padding-right: 10px;">Usuario</th>
                        <td>{{$user->username}}</td>
                    </tr>
                    <tr>
                        <th style="text-align: right;width: 50%;padding-right: 10px;">Email</th>
                        <td>{{$user->email}}</td>
                    </tr>
                    <tr>
                        <th style="text-align: right;width: 50%;padding-right: 10px;">Contraseña</th>
                        <td>{{$user->password}}</td>
                    </tr>
                </table>
            </div>
            <a href="{{ route('page.login') }}" target="_blank"  style="display: block;margin: auto;margin-top: auto;margin-bottom: auto;width: 150px;margin-top: 20px;margin-bottom: 20px;padding: 20px 10px;border: 0px;background-color: #ffc500;color: black;border-radius: 15px;font-size: 110%;text-align: center;text-decoration: none;">Iniciar sesión</a>
        </div>
    </div>
</div>