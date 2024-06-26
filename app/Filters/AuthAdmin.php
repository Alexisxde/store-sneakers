<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use \CodeIgniter\Exceptions\PageNotFoundException;

class AuthAdmin implements FilterInterface {
  public function before(RequestInterface $req, $args = null) {
    if (!session('logged_in') || session('rol') !== 'admin') {
      throw new PageNotFoundException("Error, permiso denegado");
    }
  }

  public function after(RequestInterface $req, ResponseInterface $res, $args = null) {}
}
