<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../models/Auditorio.php';

class CatalogoController {
    
    public function index() {
        // Obtenemos los auditorios desde el modelo
        $auditorioModel = new Auditorio();
        $auditorios = $auditorioModel->obtenerTodos();
        
        // Cargamos la vista del catálogo
        require_once __DIR__ . '/../views/catalogo/index.php';
    }
}
?>
