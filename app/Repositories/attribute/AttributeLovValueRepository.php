<?php namespace attribute;
 
interface AttributeLovValueRepository{
   
  public function all();
 
  public function find($id);
}