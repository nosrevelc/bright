<?php

namespace WPFEPP\Data_Mappers;

if (!defined('WPINC')) die;

abstract class Mapper
{
	abstract function map();
}