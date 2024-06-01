<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\SneakerModel;
use App\Models\SizesModel;

use CodeIgniter\HTTP\RedirectResponse;
use \CodeIgniter\Exceptions\PageNotFoundException;

class Sneaker extends BaseController {
  private SneakerModel $modelSneaker;
  private SizesModel $modelSizes;
  protected $helpers = ["form", "rules"];

  public function __construct() {
    $this->modelSneaker = new SneakerModel();
    $this->modelSizes = new SizesModel();
  }

  public function all_sneakers(): string {
    $items_page = 10;
    $data = [
      'products' => $this->modelSneaker->paginate($items_page),
      'pager' => $this->modelSneaker->pager,
    ];
    return view('pages/Products', $data);
  }

  public function one_sneaker(string $id): string {
    [$sneaker] = $this->modelSneaker->one_sneaker($id);
    $sizes = $this->modelSizes->where('id_sneaker', $id)->findAll();
    if ($sneaker == null) {
      throw new PageNotFoundException("Sneaker no encontrado.");
    }
    $data = [
      "product" => $sneaker,
      "sizes" => $sizes
    ];
    return view('pages/Product', $data);
  }

  public function form_add_sneaker(): string {
    return view("pages/FormAddSneaker");
  }

  public function form_edit_sneaker(string $id_sneaker): string {
    [$sneaker] = $this->modelSneaker->one_sneaker($id_sneaker);
    return view("pages/FormEditSneaker", ["sneaker" => $sneaker]);
  }

  public function edit_sneaker(): RedirectResponse {
    // $validationRules = getValidationRules('add_sneaker');
    // if (!$this->validate($validationRules)) {
    //   return redirect()->back()->withInput();
    // }
    extract($this->request->getPost([
      'sneaker_id',
      'sneaker_brand',
      'sneaker_model',
      'sneaker_price',
      'sneaker_discount',
      'sneaker_stars',
      'sneaker_description',
      'sneaker_active',
    ]));
    // $sneaker_img = $this->request->getFile('sneaker_img');
    // if (!$sneaker_img->isValid()) {
    // echo $sneaker_img->getErrorString();
    // exit;
    // }
    // if (!$sneaker_img->hasMoved()) {
    //   $path = ROOTPATH . '/assets/img/sneakers';
    //   $originalName = $sneaker_img->getClientName();
    //   sscanf($originalName, '%[^.].%s', $name, $extension);
    //   $nameFile = $id_sneaker . "." . $extension;
    //   $sneaker_img->move($path, $nameFile);
    // }
    $data = [
      'id_sneaker' => $sneaker_id,
      'brand' => $sneaker_brand,
      'price' => $sneaker_price,
      'discount' => $sneaker_discount,
      'model' => $sneaker_model,
      'stars' => $sneaker_stars,
      'description' => $sneaker_description,
      'is_active' => $sneaker_active == 'on' ? '1' : '0',
      // 'img' => $nameFile
    ];
    $this->modelSneaker->edit_sneaker($sneaker_id, $data);
    return redirect()->to(base_url('sneakers'))->with('msg', [
      'type' => 'success',
      'body' => "$sneaker_brand $sneaker_model guardado correctamente..."
    ]);
  }

  public function add_sneaker(): RedirectResponse {
    $validationRules = getValidationRules('add_sneaker');
    if (!$this->validate($validationRules)) {
      return redirect()->back()->withInput();
    }
    $id_sneaker = uniqid();
    extract($this->request->getPost([
      'sneaker_brand',
      'sneaker_model',
      'sneaker_price',
      'sneaker_discount',
      'sneaker_stars',
      'sneaker_description',
    ]));
    $sneaker_img = $this->request->getFile('sneaker_img');
    if (!$sneaker_img->isValid()) {
      return redirect()->to(base_url('add_sneaker'))->with('msg', [
        'type' => 'error',
        'body' => $sneaker_img->getErrorString()
      ]);
    }
    if ($sneaker_img->hasMoved()) {
      return redirect()->to(base_url('add_sneaker'))->with('msg', [
        'type' => 'error',
        'body' => "La imagen se movió y ocurrió un error."
      ]);
    }
    $path = ROOTPATH . '/assets/img/sneakers';
    $originalName = $sneaker_img->getClientName();
    sscanf($originalName, '%[^.].%s', $name, $extension);
    $nameFile = $id_sneaker . '.' . $extension;
    $sneaker_img->move($path, $nameFile);
    $data = [
      'id_sneaker' => $id_sneaker,
      'brand' => $sneaker_brand,
      'price' => $sneaker_price,
      'discount' => $sneaker_discount,
      'model' => $sneaker_model,
      'stars' => $sneaker_stars,
      'description' => $sneaker_description,
      'img' => $nameFile
    ];
    $this->modelSneaker->add_sneaker($data);
    return redirect()->to(base_url('add_sneaker'))->with('msg', [
      'type' => 'success',
      'body' => "$sneaker_brand $sneaker_model subido correctamente..."
    ]);
  }

  public function status(string $id): RedirectResponse {
    $sneaker = $this->modelSneaker->one_sneaker($id);
    if ($sneaker !== null) {
      extract($sneaker[0]);
      $sneaker[0]["is_active"] = ($sneaker[0]["is_active"] == 0) ? 1 : 0;
      $this->modelSneaker->edit_sneaker($id, $sneaker[0]);
      return redirect()->to(base_url("sneakers"))->with("msg", [
        "type" => $sneaker[0]["is_active"] == 0 ? "error" : "success",
        "body" => $sneaker[0]["is_active"] == 0 ? "$brand $model Desactivado" : "$brand $model Activado",
      ]);
    }
    throw new PageNotFoundException("Sneaker no encontrado.");
  }

  public function featured(): ?array {
    $featured = $this->modelSneaker->featured(2, 10);
    return $featured;
  }
}
