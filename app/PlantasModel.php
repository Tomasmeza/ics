<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlantasModel extends Model
{
  protected $table='plantas';
  protected $fillable= ['nombre'];
  //protected $primaryKey= "idCategoria";

  public$timestamps=false;

}
