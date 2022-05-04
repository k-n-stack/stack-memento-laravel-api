<?php

namespace Utils\Utils;

function generateANID($length) {
  $alphaNumerics = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
  $random = "";
  for ($i = 0; $i < $length; $i++) {
      $random .= $alphaNumerics[rand(0, strlen($alphaNumerics) - 1)];
  }
  return $random;
}