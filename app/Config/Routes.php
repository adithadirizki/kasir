<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

// Session
$routes->get('/', 'Home::index');
$routes->add('/logout', 'Home::logout');
$routes->get('/login', 'Login::index');
$routes->post('/login', 'Login::check_login');
$routes->post('/pesananbaru', 'Struk::newOrders');

// Produk
$routes->get('/produk', 'Produk::index');
$routes->get('/produk/(:num)', 'Produk::detailProduct/$1');
$routes->post('/allproduk', 'Produk::allProducts');
$routes->get('/tambahproduk', 'Produk::preCreateProducts');
$routes->post('/tambahproduk', 'Produk::addProducts');
$routes->post('/produk/(:num)', 'Produk::updateProduct/$1');
$routes->post('/hapusproduk', 'Produk::deleteProducts');
$routes->delete('/produk/(:num)', 'Produk::deleteProduct/$1');
$routes->post('/hapusproduk', 'Produk::deleteProduct');

// Struk
$routes->get('/buatpesanan', 'Produk::index');
$routes->post('/buatpesanan', 'Produk::preCreateOrders');
$routes->post('/simpanstruk', 'Struk::saveStruk');
$routes->get('/riwayatpesanan', 'Struk::index');
$routes->get('/struk/(:num)', 'Struk::strukDetail/$1');
$routes->post('/riwayatpesanan', 'Struk::historyOrders');
$routes->delete('/hapuspesanan/(:num)', 'Struk::deleteOrder/$1');

$routes->get('/kategori', 'Kategori::index');
$routes->get('/kategori/(:any)', 'Kategori::detailCategory/$1');
$routes->post('/kategori/(:any)', 'Kategori::updateCategory/$1');
$routes->post('/tambahkategori', 'Kategori::addCategory');
$routes->delete('/kategori/(:any)', 'Kategori::deleteCategory/$1');

$routes->get('/barang', 'Barang::index');
$routes->post('/tambahbarangmasuk', 'Barang::addInGoods');
$routes->post('/barang/(:alpha)', 'Barang::getGoods/$1');
$routes->delete('/barang/(:alpha)/(:num)', 'Barang::deleteGoods/$1/$2');

$routes->get('/laporan/(:alpha)', 'Laporan::reports/$1');

// $routes->post('/newOrders', 'Home::newOrders');

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
