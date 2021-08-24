@extends('layouts.app')

@section('content')
<div class="container mt-5 bg-white shadow-sm rounded px-5 pb-4">
  <div class="row col-12 pt-4 text-center mx-auto ">
    <h3 class="mx-auto col-12">Struktura drzewiasta</h3>
  </div>
  <hr>
  <div class="h5 ml-4">
    @if($wynik!='empty')
    <script type="text/javascript" src="{{ URL::asset('js/modelDane.js') }}"></script>
    @foreach($wynik as $sciezka)
    <script>
      var a = "{{$sciezka['path']}}";
      var b = "{{$sciezka['name']}}";
      var c = parseInt("{{$sciezka['id']}}");
      var d = parseInt("{{$sciezka['level']}}");
      wynik[i] = new Dane(a, b, c, d);
      i++;
    </script>
    @endforeach
    <script type="text/javascript" src="{{ URL::asset('js/tree.js') }}"></script>
    @else
    Brak kategori w strukturze
    @endif
    
  </div>
  @guest
  <hr>
  <div class="row col-12 pt-4 text-center mx-auto ">
    <h5 class="mx-auto col-12 mb-3"><a class="" href="{{ route('login') }}">Zaloguj się</a>, aby móc edytować strukturę!</h5>
  </div>
  @endguest
  @auth
    @if(Auth()->user()->role == 'admin')
  <hr>
  <div class="row col-12 pt-4 text-center mx-auto ">
    <h4 class="mx-auto col-12 mb-3">Edycja struktury</h4>
  </div>
  <div class="row justify-content-center">
    <form class="col-lg-5 col-md-5 mb-3" action="{{route('createTree')}}" method="GET">
      <div class="card">
        <div class="card-body">
          <h4>Dodaj</h4>
          <div class="form-group">
            <label for="name">Nazwa:</label>
            <input type="text" name="name" value="" id="name" class="form-control">
            @if($errors->has('name'))
            <span class="text-danger"> {{ $errors->first('name') }}</span>
            @endif
          </div>
          <div class="form-group">
            <label for="parent">Wybierz miejsce:</label>
            <select name="parent" id="parent" class="form-control">
              @foreach($categories as $category)
              <option value="{{$category->id}}">{{$category->name}}</option>
              @endforeach
              <option value="top" selected>Dodaj jako gałąź główną</option>
            </select>
            @if($errors->has('parent'))
            <span class="text-danger"> {{ $errors->first('parent') }}</span>
            @endif
          </div>
          <button type="submit" class="btn btn-primary">Dodaj</button>
        </div>
      </div>
    </form>
    @if($wynik!='empty')
    <form class=" col-lg-5 col-md-5  mb-3" action="{{route('editTree')}}" method="POST">
      @method('PATCH')
      @csrf
      <div class="card">
        <div class="card-body">
          <h4>Edytuj</h4>
          <div class="form-group">
            <label for="change_id">Wybierz gałąź, którą chcesz edytować:</label>
            <select name="change_id" id="change_id" class="form-control">
              @foreach($categories as $category)
              <option value="{{$category->id}}">{{$category->name}}</option>
              @endforeach
            </select>
            @if($errors->has('change_id'))
            <span class="text-danger"> {{ $errors->first('change_id') }}</span>
            @endif
          </div>
          <div class="form-group">
            <label for="name1">Nowa nazwa:</label>
            <input type="text" name="name1" value="" id="name1" class="form-control">
            @if($errors->has('name1'))
            <span class="text-danger"> {{ $errors->first('name1') }}</span>
            @endif
          </div>

          <button type="submit" class="btn btn-primary">Zmień</button>
        </div>
      </div>
    </form>
  </div>
  <div class="row mt-3  justify-content-center">
    

    <form class="col-lg-5 col-md-5" action="{{route('moveTree')}}" method="POST" onsubmit="return confirm('Czy jesteś pewien, że chcesz przenieść tą kategorię oraz wszystkie jej podkategorie?')">
      @method('PATCH')
      @csrf
      <div class="card">
        <div class="card-body">
          <h4>Przenieś</h4>
          <div class="form-group">
            <label for="who">Wybierz gałąź, którą chcesz przenieść:</label>
            <select name="who" id="who" class="form-control">
              @foreach($categories as $category)
              <option value="{{$category->id}}">{{$category->name}}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="where">Wybierz miejsce:</label>
            <select name="where" id="where" class="form-control">
            <option value="top" selected>Ustaw jako gałąź główną</option>
              @foreach($categories as $category)
              <option value="{{$category->id}}">{{$category->name}}</option>
              @endforeach
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Przenieś</button>
        </div>
      </div>
    </form>
    <form class="col-lg-5 col-md-5" action="{{route('deleteTree')}}" method="POST" onsubmit="return confirm('Czy jesteś pewien, że chcesz usunąć tą kategorię oraz wszystkie jej podkategorie?')">
      <input type="hidden" name="_method" value="DELETE">
      @csrf
      <div class="card">
        <div class="card-body">
          <h4>Usuń</h4>
          <div class="form-group">
            <label for="deleted">Wybierz gałąź, którą chcesz usunąć:</label>
            <select name="deleted" id="deleted" class="form-control">
              @foreach($categories as $category)
              <option value="{{$category->id}}">{{$category->name}}</option>
              @endforeach
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Usuń</button>
        </div>
      </div>
    </form>
  </div>
  @endif
  @else
  <hr>
  <div class="row col-12 pt-4 text-center mx-auto ">
    <h5 class="mx-auto col-12 mb-3">Potrzebujesz uprawnień administratora, aby móc edytować strukturę!</h5>
  </div>
  @endif
  @endauth
</div>

@endsection