<?php
require_once __DIR__.'/includes/auth.php';
requerirAdmin();
require_once __DIR__.'/includes/admin_gestion.php';
if(!in_array($_SERVER['REQUEST_METHOD'],['GET','POST'],true)){http_response_code(405);header('Allow: GET, POST');exit('Método no permitido.');}
$id=ca_id($_GET['id']??0);$receta=ca_recipe($id);
if(!$receta){http_response_code(404);$titulo='Receta no encontrada';$estilosPagina=['public/css/admin.css'];$claseBody='cookly-admin-body';require __DIR__.'/includes/header.php';echo '<section class="ca-edit-card"><h1>La receta no está disponible</h1><p>Pudo haber sido eliminada mientras la revisabas.</p><a class="ca-button" href="admin.php">Volver a administrar</a></section>';require __DIR__.'/includes/footer.php';exit;}
$ingredientes=ca_ingredients($id);$form=$receta;$formIngs=$ingredientes;$version=ca_signature($receta,$ingredientes);$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    foreach(['titulo','descripcion','instrucciones','dificultad','estado','imagen_url','id_categoria','tiempo_preparacion','porciones'] as $k)$form[$k]=ca_string($_POST[$k]??'');
    $version=ca_string($_POST['version']??'');
    if(is_array($_POST['ing_nombre']??null)&&count($_POST['ing_nombre'])<=100){$formIngs=[];foreach($_POST['ing_nombre'] as $k=>$name)$formIngs[]=['nombre'=>ca_string($name),'cantidad'=>ca_string($_POST['ing_cantidad'][$k]??''),'unidad_medida'=>ca_string($_POST['ing_unidad'][$k]??'gramos')];}
    try{ca_check_token($_POST['_csrf']??null);$data=ca_validate_recipe($_POST);ca_save_recipe($id,$data,$version,$_FILES);ca_flash('La receta se actualizó correctamente. Se conservó su autor y se aplicó el estado seleccionado.');header('Location: admin.php',true,303);exit;}
    catch(DomainException $e){http_response_code(403);$error=$e->getMessage();}
    catch(InvalidArgumentException $e){http_response_code(400);$error=$e->getMessage();}
    catch(Throwable $e){http_response_code(500);error_log('[Cookly Admin Edit] '.$e->getMessage());$error='No se pudo guardar la receta. Revisa los datos o vuelve a intentarlo. Los cambios no se han confirmado.';}
}
$catalogos=ca_catalogs();$titulo='Editar receta como administrador';$estilosPagina=['public/css/admin.css'];$claseBody='cookly-admin-body';
require __DIR__.'/includes/header.php';
?>
<div class="ca-root">
<div class="ca-breadcrumb">COOKLY <span>/</span> ADMINISTRACIÓN <span>/</span> EDITAR RECETA</div>
<header class="ca-hero"><div><p class="ca-eyebrow">CONTROL DE CONTENIDO</p><h1>Editar receta<span>.</span></h1><p>Corrige la preparación y decide en qué estado debe quedar.</p></div><a class="ca-button" href="admin.php">← Volver al panel</a></header>
<?php if($error): ?><div class="ca-alert ca-alert-error" role="alert"><?= ca_e($error) ?> <a href="admin_receta_editar.php?id=<?= $id ?>">Recargar la versión guardada</a></div><?php endif; ?>
<section class="ca-edit-card"><div class="ca-edit-owner"><strong>Autor original:</strong> <?= ca_e($receta['autor']) ?> · Receta #<?= $id ?>. El autor se conserva al guardar.</div>
<form method="post" enctype="multipart/form-data" action="admin_receta_editar.php?id=<?= $id ?>">
<input type="hidden" name="_csrf" value="<?= ca_e(ca_token()) ?>"><input type="hidden" name="version" value="<?= ca_e($version) ?>">
<div class="form-grid">
<label class="full-width">Título<input type="text" name="titulo" value="<?= ca_e($form['titulo']) ?>" maxlength="120" required></label>
<label>Categoría<select name="id_categoria" required><?php foreach($catalogos['categorias'] as $c): ?><option value="<?= (int)$c['id_categoria'] ?>" <?= (string)$c['id_categoria']===(string)$form['id_categoria']?'selected':'' ?>><?= ca_e($c['nombre']) ?></option><?php endforeach; ?></select></label>
<label>Estado<select name="estado" required><?php foreach(ca_states() as $state): ?><option value="<?= ca_e($state) ?>" <?= $state===$form['estado']?'selected':'' ?>><?= ca_e($state) ?></option><?php endforeach; ?></select></label>
<label>Tiempo de preparación (minutos)<input type="number" name="tiempo_preparacion" value="<?= ca_e($form['tiempo_preparacion']) ?>" min="1" max="2147483647" step="1" required></label>
<label>Porciones<input type="number" name="porciones" value="<?= ca_e($form['porciones']) ?>" min="1" max="2147483647" step="1" required></label>
<label>Dificultad<select name="dificultad"><?php foreach(['Fácil','Media','Difícil'] as $d): ?><option value="<?= ca_e($d) ?>" <?= $d===$form['dificultad']?'selected':'' ?>><?= ca_e($d) ?></option><?php endforeach; ?></select></label>
<label class="full-width">Descripción<textarea name="descripcion" rows="3" maxlength="20000"><?= ca_e($form['descripcion']??'') ?></textarea></label>
<label class="full-width">Imagen (URL o ruta existente)<input type="text" name="imagen_url" value="<?= ca_e($form['imagen_url']??'') ?>" maxlength="255" placeholder="https://… o public/IMG/…"></label>
<label class="full-width">Reemplazar la imagen con un archivo<input type="file" name="imagen_file" accept="image/jpeg,image/png,image/webp,image/gif"><span class="ca-edit-help">JPG, PNG, WEBP o GIF, hasta 5 MB. Si eliges un archivo válido, se usará en lugar de la dirección anterior. Para quitar la imagen, deja la dirección vacía y no adjuntes un archivo.</span></label>
<?php if($receta['imagen_url']): ?><div class="full-width"><p class="ca-edit-help">Imagen guardada actualmente</p><img class="ca-edit-image" src="<?= ca_e($receta['imagen_url']) ?>" alt="Imagen actual de la receta"></div><?php endif; ?>
<label class="full-width">Preparación paso a paso<textarea name="instrucciones" rows="7" maxlength="20000" required><?= ca_e($form['instrucciones']) ?></textarea></label>
</div>
<h2>Ingredientes</h2><p class="ca-edit-help">Revisa nombre, cantidad y unidad. Usa una sola fila por ingrediente.</p>
<div id="ings"><?php foreach($formIngs?:[['nombre'=>'','cantidad'=>'','unidad_medida'=>'gramos']] as $ing): ?><div class="ing-row"><input type="text" name="ing_nombre[]" value="<?= ca_e($ing['nombre']) ?>" maxlength="80" placeholder="Ingrediente" aria-label="Nombre del ingrediente"><input type="number" name="ing_cantidad[]" value="<?= ca_e($ing['cantidad']) ?>" min="0.01" max="99999999.99" step="0.01" placeholder="Cantidad" aria-label="Cantidad"><select name="ing_unidad[]" aria-label="Unidad de medida"><?php foreach(ca_units() as $unit): ?><option value="<?= ca_e($unit) ?>" <?= $unit===$ing['unidad_medida']?'selected':'' ?>><?= ca_e($unit) ?></option><?php endforeach; ?></select></div><?php endforeach; ?></div>
<button class="ca-button" type="button" id="add-ing">+ Ingrediente</button>
<p class="ca-edit-help">Una receta «Aceptado» se mantiene visible en Explorar. Puedes dejarla «Pendiente» o «Rechazado» si debe retirarse de la lista pública.</p>
<div class="ca-edit-actions"><a class="ca-button" href="admin.php">Cancelar</a><button type="submit" class="ca-button ca-primary">Guardar cambios de la receta</button></div>
</form>
</section></div>
<?php require __DIR__.'/includes/footer.php'; ?>
