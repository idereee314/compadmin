<?php namespace attribute;
 
interface AttributeRepository{
   
  public function all();
 
  public function find($id);
}