@extends('layouts.admin')
@section('contenido')
<div class="row">
  <div class="col-lg-6 col-xs-12">
    {{--<h3>Editar Evento: {{$eventos->Nombre}}</h3>--}}

    @if (count($errors)>0)
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{$error}}</li>
        @endforeach
      </ul>
    </div>
    @endif

    {!!Form::model($eventos,['method'=>'PATCH','route'=>['eventos.update',$eventos->id]])!!}
    {{Form::token()}}
    <h3 class="header smaller lighter blue">Editar Evento</h3>

    <div class="form-group">
      <label for="nombre">Nombre</label>
      <input type="text" name="nombre" class="form-control" value="{{$eventos->nombre}}">
    </div>

    <div class="form-group">
      <button class="btn btn-primary" type="submit">Guardar<i class="fa fa-check"></i> </button>
      <button class="btn btn-danger" type="reset">Cancelar<i class="fa fa-times"></i></button>
    </div>

    {!!Form::close()!!}
  </div>
</div>
@stop
