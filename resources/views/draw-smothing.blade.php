<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>

<div class="jumbotron">
  <h1 class="display-4">Brandlive!</h1>
  <p class="lead">Formulario de prueba de programación.</p>
  <hr class="my-4">    
</div>

<div class="container">
    <div class="card-group">
    <div class="card">    
    <div class="card-body">
      <h3 class="card-title">DrawSomething!</h3>
      <p class="crd-text">Envía todas las letras y el número de caracteres que conforman la palabra máximo 7 caracteres!</p>
      
        <form action="{{route('combinations.combinate')}}" method="POST">

        @csrf

        <div class="mb-3">
            <label for="Letters" class="form-label">Carecteres</label>
            <input type="text" class="form-control" name="letters" aria-describedby="letters">
            <div id="letters" class="form-text">Envía todas las letras sin espacios como si fueran una palabra!</div>
        </div>
        <div class="mb-3">
            <label for="charNumber" class="form-label">Número de Caracteres</label>
            <input type="number" min="3" max="7" class="form-control" name="charNumber">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" name="justWords">
            <label class="form-check-label" for="justWords">Solo Palabras Reales!</label>
        </div>
        <button type="submit" class="btn btn-primary">Listar Opciones</button>
        </form>

    </div>
  </div>
  <div class="card">    
    <div class="card-body">
        <h3 class="card-title">Posibles Combinaciones:</h3>
        <p class="card-text">Estan son todas las combinaciones posibles, si quieres palabras reales únicamente selecciona la opción: <small>Solo Palabras Reales!</small></p>
        <!-- <ul class="list-group list-group-horizontal-sm"> -->
        @foreach($combinations ?? array() as $combination)
            <!-- <li class="list-group-item">{{ $combination }}</li> -->
            <span class="badge bg-light text-dark">{{ $combination }}</span>
        @endforeach
        @foreach($errors ?? array() as $error)
        <div class="alert alert-danger" role="alert">
            {{ $error }}
        </div>
        @endforeach
        <!-- </ul> -->
    </div>
  </div>
    </div>
</div>
</body>
</html>