<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	In this momment you have an account, but your admin has disabled or still not accep this count 
	<h3>Please, Contact with your Administrator for more information</h3>

                    <a href="{{ route('logout') }}" class="nav-link text-hpolis" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-guitar"></i>
                        <p>Cerrar Session</p>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>                        
                    </a>	
</body>
</html>